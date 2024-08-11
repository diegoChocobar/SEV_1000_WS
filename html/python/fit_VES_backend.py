import sys
import VES1D_proc
import json

# input data from database via grafico_ajuste.php
inpdata = sys.argv[1]
# print(inpdata)

data = VES1D_proc.process_string_input_data(inpdata)

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
