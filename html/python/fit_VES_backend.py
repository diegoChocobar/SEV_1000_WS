import sys
import numpy as np
import pandas as pd
import VES1D
from scipy.optimize import curve_fit
import matplotlib.pyplot as plt
import json

def flatten(l):
    return [item for sublist in l for item in sublist]

# input data from database via grafico_ajuste.php
inpdata = sys.argv[1]
# print(inpdata)
try:
    data = json.loads(inpdata)
    data["checkR"] = True if data["checkR"] == "true" else False
    data["checkP"] = True if data["checkP"] == "true" else False
except:
    procdata = inpdata.replace('{ nlayers :', '')
    procdata = procdata.replace(', OA :', ',,')
    procdata = procdata.replace(', R :', ',,')
    procdata = procdata.replace(', rho0 :[', ',,')
    procdata = procdata.replace('], thick0 :[', ',,')
    procdata = procdata.replace(']}', '')
    # FALTA CODEAR checkR y checkP
    procdata = procdata.split(',,')
    data = {
        "nlayers": int(procdata[0].strip()),
        # "checkR": procdata[1],
        # "checkP": procdata[2],
        "OA": json.loads(procdata[1]),
        'R': json.loads(procdata[2]),
        'rho0': json.loads(procdata[3]),
        'thick0': json.loads(procdata[4]),
    }
    # print(json.dumps(data))

try:
    checkR, checkP = data['checkR'], data['checkP']
    x_exp, y_exp = data['OA'], data['R']
    nlayers, rho0, thick0 = int(data['nlayers']), data['rho0'], data['thick0']
    if len(rho0) == 1: rho0 = flatten(rho0)
    if len(thick0) == 1: thick0 = flatten(thick0)

    # reverse data if necessary
    if x_exp[0] > x_exp[-1]:
        x_exp.reverse()
        y_exp.reverse()
except:
    print("failed python: input data")
    sys.exit(1)


# arrange data into a dataframe
df = pd.DataFrame(data={'OA': x_exp, 'R': y_exp})
df = VES1D.preprocess_data(df)

# VARIAR TODOS LOS PARAMETROS (ancho de capas y resistividades)
if not checkR and not checkP:

    # define fit function
    f = VES1D.apparent_resistivity

    # definir espacio de hiperparámetros y valores iniciales 
    # TOTAL HIPERPARAMETERS = 2 * nlayers - 1
    bounds = (tuple([0 for i in range(2*nlayers - 1)]), tuple([np.inf for i in range(2*nlayers - 1)]))
    lam0 = np.concatenate([rho0, thick0])

    try:
        lam, pcov = curve_fit(f, x_exp, y_exp, p0=lam0, bounds=bounds)
        rho, thick = VES1D.extract_values(lam)
        lam_dict = VES1D.construct_lambda(x_exp, rho, thick)
        lam_dict['thick_total'] = VES1D.compute_total_thick(lam_dict['thick'])
        print(json.dumps(lam_dict))
    except:
        # REVISAR!!!
        # si el ajuste fallar, se usan otros valores iniciales
        # (capas dadas por puntos de inflexion de ajuste polinomico)
        thick0, rho0 = VES1D.init_values(x_exp, y_exp)
        lam0 = np.concatenate([rho0, thick0[:-1]])
        lam, pcov = curve_fit(f, x_exp, y_exp, p0=lam0, bounds=bounds)
        rho, thick = VES1D.extract_values(lam)
        lam_dict = VES1D.construct_lambda(rho, thick)
        lam_dict['thick_total'] = VES1D.compute_total_thick(thick)
        print(json.dumps(lam_dict))


# VARIAR UN SET DE PARAMETROS (resistividades)
elif not checkR and checkP:

    # define fit function by wrapping general-fit-function
    def apparent_resistivity_with_fixed_thick(ab2s, *rho):
        global thick0

        rho = [float(r) for r in rho]
        thick0 = [float(r) for r in thick0]
        params = np.concatenate([rho, thick0])

        return VES1D.apparent_resistivity(ab2s, *params)

    f = apparent_resistivity_with_fixed_thick
    
    # definir espacio de hiperparámetros y valores iniciales 
    # TOTAL HIPERPARAMETERS = nlayers
    bounds_rho = (tuple([0 for i in range(nlayers)]), tuple([np.inf for i in range(nlayers)]))

    try:
        rho, pcov = curve_fit(f, x_exp, y_exp, p0=rho0, bounds=bounds_rho)
        lam_dict = VES1D.construct_lambda(x_exp, rho, thick0)
        lam_dict['thick_total'] = VES1D.compute_total_thick(lam_dict['thick'])
        print(json.dumps(lam_dict))
    except:
        print("failed python: fit failed (rho)")


# VARIAR ANCHOS DE CAPA (thick)
elif checkR and not checkP:

    # define fit function by wrapping general-fit-function
    def apparent_resistivity_with_fixed_rho(ab2s, *thick):
        global rho0

        rho0 = [float(r) for r in rho0]
        thick = [float(r) for r in thick]
        params = np.concatenate([rho0, thick])

        return VES1D.apparent_resistivity(ab2s, *params)

    f = apparent_resistivity_with_fixed_rho

    # definir espacio de hiperparámetros y valores iniciales 
    # TOTAL HIPERPARAMETERS = nlayers - 1
    bounds_thick = (tuple([0 for i in range(nlayers - 1)]), tuple([np.inf for i in range(nlayers - 1)]))

    try:
        thick, pcov = curve_fit(f, x_exp, y_exp, p0=thick0, bounds=bounds_thick)
        lam_dict = VES1D.construct_lambda(x_exp, rho0, thick)
        lam_dict['thick_total'] = VES1D.compute_total_thick(lam_dict['thick'])
        print(json.dumps(lam_dict))
    except:
        print("failed python: fit failed (thick)")
