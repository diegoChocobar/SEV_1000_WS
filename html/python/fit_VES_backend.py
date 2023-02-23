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
try:
    data = json.loads(sys.argv[1])
except:
    procdata = inpdata.replace('{ nlayers :', '')
    procdata = procdata.replace(', OA :', ',,')
    procdata = procdata.replace(', R :', ',,')
    procdata = procdata.replace(', rho0 :[', ',,')
    procdata = procdata.replace('], thick0 :[', ',,')
    procdata = procdata.replace(']}', '')
    procdata = procdata.split(',,')
    data = {
        "nlayers": int(procdata[0].strip()),
        "OA": json.loads(procdata[1]),
        'R': json.loads(procdata[2]),
        'rho0': json.loads(procdata[3]),
        'thick0': json.loads(procdata[4]),
    }
    # print(json.dumps(data))

try:
    x_exp, y_exp = data['OA'], data['R']
    nlayers, rho0, thick0 = int(data['nlayers']), data['rho0'], data['thick0']
    if len(rho0) == 1: rho0 = flatten(rho0)
    if len(thick0) == 1: thick0 = flatten(thick0)

    # reverse data if necessary
    if x_exp[0] > x_exp[-1]:
        x_exp.reverse()
        y_exp.reverse()
except:
    print("failed input data")
    sys.exit(1)


# arrange data into a dataframe
df = pd.DataFrame(data={'OA': x_exp, 'R': y_exp})
df = VES1D.preprocess_data(df)
    
# define bounds and fit function
f = VES1D.apparent_resistivity
bounds = (tuple([0 for i in range(2*nlayers - 1)]), tuple([np.inf for i in range(2*nlayers - 1)]))

try:
    lam0 = np.concatenate([rho0, thick0])
    lam, pcov = curve_fit(f, x_exp, y_exp, p0=lam0, bounds=bounds)
    rho, thick = VES1D.extract_values(lam)
    lam_dict = VES1D.construct_lambda(x_exp, rho, thick)
    lam_dict['thick_total'] = VES1D.compute_total_thick(lam_dict['thick'])
    print(json.dumps(lam_dict))
except:
    thick0, rho0 = VES1D.init_values(x_exp, y_exp)
    lam0 = np.concatenate([rho0, thick0[:-1]])
    lam, pcov = curve_fit(f, x_exp, y_exp, p0=lam0, bounds=bounds)
    rho, thick = VES1D.extract_values(lam)
    lam_dict = VES1D.construct_lambda(rho, thick)
    lam_dict['thick_total'] = VES1D.compute_total_thick(thick)
    print(json.dumps(lam_dict))
