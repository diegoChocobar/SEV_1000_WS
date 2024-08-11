import VES1D_proc
import json
import sys


def flatten(l):
    return [item for sublist in l for item in sublist]

# input data from database via grafico_ajuste.php
filepath = 'data.json'
with open(filepath, 'r') as f:
	data = json.load(f)
data["nlayers"] = int(data["nlayers"])
if len(data["rho0"]) == 1: 
    data["rho0"] = flatten(data["rho0"])
if len(data["thick0"]) == 1: 
    data["thick0"] = flatten(data["thick0"])
data["rho0"] = [float(v) for v in data["rho0"]]
data["thick0"] = [float(v) for v in data["thick0"]]
data["checkR"] = True if data["checkR"] == "true" else False
data["checkP"] = True if data["checkP"] == "true" else False
print(data)

optimize = True
if optimize:
    data["OA"], data["R"] = VES1D_proc.process_experimental_data(data["OA"], data["R"])

# VARIAR TODOS LOS PARAMETROS (ancho de capas y resistividades)
if not data["checkR"] and not data["checkP"]:

    rho, thick = VES1D_proc.vary_all_parameters(data)

# VARIAR UN SET DE PARAMETROS (resistividades)
elif not data["checkR"] and data["checkP"]:

    rho, thick = VES1D_proc.vary_resistivity(data)

# VARIAR ANCHOS DE CAPA (thick)
elif data["checkR"] and not data["checkP"]:

    rho, thick = VES1D_proc.vary_thickness(data)

lam_dict = VES1D_proc.process_output_data(rho, thick, data)
print(json.dumps(lam_dict))
