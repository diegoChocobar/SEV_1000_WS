
import json
import sys
import VES1D_models, VES1D_misc
import numpy as np
from scipy.optimize import curve_fit

def flatten(l):
    return [item for sublist in l for item in sublist]

def process_string_input_data(inpdata):
    try:
        data = json.loads(inpdata)
    except:
        procdata = inpdata.replace('{ nlayers :', '')
        procdata = procdata.replace(', OA :', ',,')
        procdata = procdata.replace(', checkR :', ',,')
        procdata = procdata.replace(', checkP :', ',,')
        procdata = procdata.replace(', R :', ',,')
        procdata = procdata.replace(', rho0 :[', ',,')
        procdata = procdata.replace('], thick0 :[', ',,')
        procdata = procdata.replace(']}', '')
        procdata = procdata.split(',,')
        data = {
            "nlayers": int(procdata[0].strip()),
            "checkR": procdata[1].strip(),
            "checkP": procdata[2].strip(),
            "OA": json.loads(procdata[3]),
            'R': json.loads(procdata[4]),
            'rho0': json.loads(procdata[5]),
            'thick0': json.loads(procdata[6]),
        }
        # print(json.dumps(data))

    try:
        data["nlayers"] = int(data["nlayers"])
        if len(data["rho0"]) == 1: 
            data["rho0"] = flatten(data["rho0"])
        if len(data["thick0"]) == 1: 
            data["thick0"] = flatten(data["thick0"])
        data["rho0"] = [float(v) for v in data["rho0"]]
        data["thick0"] = [float(v) for v in data["thick0"]]
        data["checkR"] = True if data["checkR"] == "true" else False
        data["checkP"] = True if data["checkP"] == "true" else False
        # reverse data if necessary
        x_exp, y_exp = data['OA'], data['R']
        if x_exp[0] > x_exp[-1]:
            x_exp.reverse()
            y_exp.reverse()
        data['OA'], data['R'] = x_exp, y_exp
    except:
        print("failed python: process_string_input_data")
        sys.exit(1)

    return data



def get_parameters(data):
    
    nlayers = data["nlayers"]
    rho0 = data["rho0"]
    thick0 = data["thick0"]
    x = data["OA"]
    y = data["R"]
    
    return nlayers, rho0, thick0, x, y

def vary_all_parameters(data):
    nlayers, rho0, thick0, x, y = get_parameters(data)

    # define fit function
    f = VES1D_models.apparent_resistivity_Schlumberger

    # definir espacio de hiperparámetros (2 * nlayers - 1) y valores iniciales 
    bounds = (tuple([0 for i in range(2*nlayers - 1)]), tuple([np.inf for i in range(2*nlayers - 1)]))
    lam0 = np.concatenate([rho0, thick0])

    try:
        lam, pcov = curve_fit(f, x, y, p0=lam0, bounds=bounds)
    except:
        # REVISAR!!! si el ajuste fallar, se usan otros valores iniciales
        # (capas dadas por puntos de inflexion de ajuste polinomico)
        thick0, rho0 = VES1D_misc.init_values(x, y)
        lam0 = np.concatenate([rho0, thick0[:-1]])
        lam, pcov = curve_fit(f, x, y, p0=lam0, bounds=bounds)

    rho, thick = VES1D_misc.extract_values(lam)
    
    return rho, thick

def vary_resistivity(data):
    nlayers, rho0, thick0, x, y = get_parameters(data)
    
    # define fit function by wrapping general-fit-function
    def apparent_resistivity_with_fixed_thick(ab2s, *rho):
        global thick0

        rho = [float(r) for r in rho]
        thick0 = [float(r) for r in thick0]
        params = np.concatenate([rho, thick0])

        return VES1D_models.apparent_resistivity_Schlumberger(ab2s, *params)

    f = apparent_resistivity_with_fixed_thick
    
    # definir espacio de hiperparámetros y valores iniciales 
    # TOTAL HIPERPARAMETERS = nlayers
    bounds_rho = (tuple([0 for i in range(nlayers)]), tuple([np.inf for i in range(nlayers)]))

    try:
        rho, pcov = curve_fit(f, x, y, p0=rho0, bounds=bounds_rho)
        thick = thick0
    except:
        print("failed python: fit failed (rho)")

    return rho, thick

def vary_thickness(data):
    nlayers, rho0, thick0, x, y = get_parameters(data)

    # define fit function by wrapping general-fit-function
    def apparent_resistivity_with_fixed_rho(ab2s, *thick):
        global rho0

        rho0 = [float(r) for r in rho0]
        thick = [float(r) for r in thick]
        params = np.concatenate([rho0, thick])

        return VES1D_models.apparent_resistivity_Schlumberger(ab2s, *params)

    f = apparent_resistivity_with_fixed_rho

    # definir espacio de hiperparámetros (nlayers - 1) y valores iniciales 
    bounds_thick = (tuple([0 for i in range(nlayers - 1)]), tuple([np.inf for i in range(nlayers - 1)]))

    try:
        thick, pcov = curve_fit(f, x, y, p0=thick0, bounds=bounds_thick)
        rho = rho0
    except:
        print("failed python: fit failed (thick)")

    return rho, thick

def process_output_data(rho, thick, data):
    
    x, y = data["OA"], data["R"]
    
    thick = VES1D_misc.add_last_layer(x, thick)
    lam_dict = VES1D_misc.construct_lambda(x, rho, thick)
    lam_dict['new_OA'] = list(data["OA"])
    lam_dict['new_R'] = list(data["R"])
    lam_dict['thick_total'] = VES1D_misc.compute_total_thick(lam_dict['thick'])
    lam_dict['error'] = VES1D_misc.compute_error(x, y, rho, thick)
    lam_dict['fit_plot'] = VES1D_misc.export_fitted_values(x, rho, thick)
    lam_dict['layer_model'] = VES1D_misc.export_layers_model(x, rho, thick)
    
    return lam_dict

def process_experimental_data(x, y):

    # Get unique x values and their indices
    unique_x, indices = np.unique(x, return_inverse=True)

    # Calculate mean y values for each unique x
    mean_y = np.bincount(indices, weights=y) / np.bincount(indices)

    # Create new arrays
    new_x = unique_x
    new_y = mean_y
    
    return new_x, new_y
