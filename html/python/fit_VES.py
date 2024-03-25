import sys
import numpy as np
import pandas as pd
import VES1D_misc, VES1D_models
from scipy.optimize import curve_fit
import matplotlib.pyplot as plt
import json

def flatten(l):
    return [item for sublist in l for item in sublist]


# input data from database via grafico_ajuste.php
filepath = 'data.json'
with open(filepath, 'r') as f:
	data = json.load(f)
#print(data)


# formatting input data
try:
    nlayers, rho0, thick0 = int(data['nlayers']), data['rho0'], data['thick0']
    if len(rho0) == 1: rho0 = flatten(rho0)
    if len(thick0) == 1: thick0 = flatten(thick0)
    x_exp, y_exp = data['OA'], data['R']

    # reverse data if necessary
    if x_exp[0] > x_exp[-1]:
        x_exp.reverse()
        y_exp.reverse()
except:
    print("failed input data format")
    sys.exit(1)


# define fit function
if data["model"] == 'schlumberger':
    f = VES1D_models.apparent_resistivity_Schlumberger
elif data["model"] == 'wenner':
    print('not implemented')
    sys.exit(1)
elif data["model"] == 'dipolo':
    print('not implemented')
    sys.exit()
# define bounds for parameter optimization
bounds = (tuple([0 for i in range(2*nlayers - 1)]), tuple([np.inf for i in range(2*nlayers - 1)]))


try:
# try fitting curve with input seeds
    lam0 = np.concatenate([rho0, thick0])
    lam, pcov = curve_fit(f, x_exp, y_exp, p0=lam0, bounds=bounds)
    rho, thick = VES1D_misc.extract_values(lam)
    lam_dict = VES1D_misc.construct_lambda(x_exp, rho, thick)
    print(data["model"],json.dumps(lam_dict))
except:
# if fitting fails, try initializing seeds differently
    thick0, rho0 = VES1D_misc.init_values(x_exp, y_exp)
    lam0 = np.concatenate([rho0, thick0[:-1]])
    lam, pcov = curve_fit(f, x_exp, y_exp, p0=lam0, bounds=bounds)
    rho, thick = VES1D_misc.extract_values(lam)
    lam_dict = VES1D_misc.construct_lambda(rho, thick)
    print(data["model"],json.dumps(lam_dict))
