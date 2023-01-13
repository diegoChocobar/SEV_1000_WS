import sys
import numpy as np
import json
import VES1D
import pandas as pd


# input data from database via grafico_ajuste.php
try:
    data = json.loads(sys.argv[1])
    nlayers, x_exp, y_exp = int(data['nlayers']), data['OA'], data['R']
except:
    print("failed input data")
    sys.exit(1)

# # reverse data if necessary
# if x_exp[0] > x_exp[-1]:
#     x_exp.reverse()
#     y_exp.reverse()

# arrange data into a dataframe
df = pd.DataFrame(data={'OA': x_exp, 'R': y_exp})
df = VES1D.preprocess_data(df)

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
    # appres_exp = list(df['R'])
    # istep_rho = int(len(appres_exp) / (nlayers - 1))
    # ilayers = [istep_rho * i for i in range(nlayers - 1)] + [-1]
    # [round(appres_exp[i], -1) for i in ilayers]
    print("failed fit data",x_exp[0],y_exp[0])
    sys.exit(1)
