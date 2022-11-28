#!/home/ale/anaconda3/bin/python
import sys
import numpy as np
import json
import VES1D
import pandas as pd


# input data from database via grafico_ajuste.php
try:
    data = json.loads(sys.argv[1])
    nlayers, x_exp, y_exp = data['nlayers'], data['OA'], data['R']
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

# compute initial data based on polynomial fit
try:
    if nlayers == 0:
        thick0, rho0 = VES1D.init_values(df['OA'], df['R'])
        nlayers0 = len(rho0)
        ini_data = {
            "nlayers": nlayers0, 
            "thick0": list(thick0),
            "rho0": list(rho0)
        }
        print(json.dumps(ini_data))
    else:
        rho0 = VES1D.initial_rho(nlayers, df['R'])
        thick0 = VES1D.initial_thick(nlayers, df['R'], df['OA'])
        ini_data = {
            "nlayers": nlayers, 
            "thick0": list(thick0),
            "rho0": list(rho0)
        }
        print(json.dumps(ini_data))

# compute initial data based on given nlayers
except:
    print('**')
    print("failed fit data")
    sys.exit(1)
