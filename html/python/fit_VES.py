#!/home/ale/anaconda3/bin/python
import sys
import numpy as np
import pandas as pd
import VES1D
from scipy.optimize import curve_fit
import matplotlib.pyplot as plt
import json


# input data from database via grafico_ajuste.php
try:
    data = json.loads(sys.argv[1])
    nlayers, rho0, thick0 = data['nlayers'], data['rho0'], data['thick0']
    x_exp, y_exp = data['OA'], data['R']
    # nlayers = 3
    # x_exp = [250, 200, 160, 130, 100, 80, 80, 65, 50, 40, 40, 32, 25, 20, 16, 13, 10, 8, 6.5, 5, 4, 3.2, 2.5]
    # y_exp = [14, 14.39, 15.61, 17.28, 18.37, 21.94, 21.02, 27.46, 41.01, 63.42, 63.08, 87.15, 112.93, 129.21, 136.49, 138.84, 137.39, 124.77, 113.45, 100.99, 91.58, 78.3, 72.17]

    # reverse data if necessary
    if x_exp[0] > x_exp[-1]:
        x_exp.reverse()
        y_exp.reverse()

    # arrange data into a dataframe
    df = pd.DataFrame(data={'OA': x_exp, 'R': y_exp})
    df = VES1D.preprocess_data(df)
except:
    print("failed input data")
    sys.exit(1)

f = VES1D.apparent_resistivity
bounds = (tuple([0 for i in range(2*nlayers - 1)]), tuple([np.inf for i in range(2*nlayers - 1)]))

try:
    # rho0 = VES1D.initial_rho(nlayers, y_exp)
    # thick0 = VES1D.initial_thick(nlayers, y_exp, x_exp)

    lam0 = np.concatenate([rho0, thick0])
    lam, pcov = curve_fit(f, x_exp, y_exp, p0=lam0, bounds=bounds)
    rho, thick = VES1D.extract_values(lam)
    lam_dict = VES1D.construct_lambda(x_exp, rho, thick)
    print(json.dumps(lam_dict))
except:
    # LaPaz
    # rho0 = [100, 2.5, 60, 12]
    # thick0 = [1.5, 0.4, 17, 5]

    print('**')
    thick0, rho0 = VES1D.init_values(x_exp, y_exp)
    lam0 = np.concatenate([rho0, thick0[:-1]])
    lam, pcov = curve_fit(f, x_exp, y_exp, p0=lam0, bounds=bounds)
    rho, thick = VES1D.extract_values(lam)
    lam_dict = VES1D.construct_lambda(rho, thick)
    print(json.dumps(lam_dict))

# except:
#     rho0 = [100, 2.5, 60, 12]
#     thick0 = [1.5, 0.4, 17, 5]
#     print("plotted values: ", rho0, thick0)
#     VES1D.plot_results(ab2s_exp, appres_exp, rho0, thick0)

# TODO #1
# - problemas de ajuste con dataset LaPaz (otra funcion de más de 5 puntos?)
# - bug: nlayers = 5 (la paz)
#   initial guess:  [70.0, 30.0, 30.0, 10.0, 10.0] [10.0, 0.0, 10.0, 20.0]

# TODO #2
# - interface de input data
# - gráfico de initial data
# - gráfico de computed data