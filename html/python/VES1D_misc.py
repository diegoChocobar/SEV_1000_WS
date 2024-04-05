import numpy as np
import pandas as pd
import VES1D_models


def preprocess_data(df):
    from collections import Counter
    x = df['OA'].tolist()
    ao_rep = [item for item, count in Counter(x).items() if count > 1]
    ao = np.array(list(set(x)))
    ao.sort()
    df_filtered = pd.DataFrame()
    for x in ao:
        row = df.loc[df['OA'] == x]
        if len(row) > 1:
            rval = np.mean(row['R'])
            row.iloc[0]['R'] = rval
            row = row.drop(index=row.index[-1])
        df_filtered = pd.concat([df_filtered, row])
    return df_filtered


def initial_rho(nlayers, appres_exp):
    appres_exp = list(appres_exp)
    istep_rho = int(len(appres_exp) / (nlayers - 1))
    ilayers = [istep_rho * i for i in range(nlayers - 1)] + [-1]
    return [round(appres_exp[i], -1) for i in ilayers]


def initial_thick(nlayers, appres_exp, ab2s_exp):
    appres_exp = list(appres_exp)
    ab2s_exp = list(ab2s_exp)
    istep_layers = int(len(appres_exp) / (nlayers))
    ilayers = [istep_layers * i for i in range(nlayers)]
    layers_tick = [ab2s_exp[ilayers[1]]] + [ab2s_exp[x] - ab2s_exp[x-1] for i, x in enumerate(ilayers) if i > 1]
    return [round(t, -1) for t in layers_tick]


def init_values(x, y):
    x, y = list(x), list(y)
    xlog, ylog = np.log(x), np.log(y)
    poly = np.polyfit(xlog, ylog, deg=9)
    ylog_fit = np.polyval(poly, xlog)
    y_fit = np.exp(ylog_fit)
    dy = np.gradient(y_fit, x)
    asign = np.sign(dy)
    signchange = ((np.roll(asign, 1) - asign) != 0).astype(int)
    x_init, y_init = [], []
    for i, sign in enumerate(signchange):
        if sign == 1:
            x_init.append((x[i]))
            y_init.append(round(y[i], -1))
    y_init.append(y[-1])
    return np.array(x_init), np.array(y_init)


def add_last_layer(x_exp, thick):

    # add layer to thick
    thick = list(thick)
    last_layer = max(x_exp) - sum(thick)
    if last_layer < 0: last_layer = -last_layer
    thick = thick + [last_layer]

    return thick


def extract_values(lam):
    
    nlayers = int((len(lam) + 1) / 2)
    res, thick = lam[:nlayers], lam[nlayers:]
    
    return res, thick


def construct_lambda(x_exp, rho, thick):
    rho, thick = list(rho), list(thick)
    
    # round to two significant figures
    rho = [round(r, 1) for r in rho]
    thick = [round(t, 1) for t in thick]
    
    return {'rho': rho, 'thick': thick}


def compute_total_thick(thick):
    thick_total = []
    suma = 0
    for i in range(len(thick)):
        suma += thick[i]
        thick_total.append(suma)

    # round to two significant figures
    thick_total = [round(t, 1) for t in thick_total]
    
    return thick_total


def compute_error(ab2s_exp, appres_exp, res, thick):
    y_exp = appres_exp
    y_fit = fitted_apparent_resistivity(ab2s_exp, res, thick)
    n = len(y_exp) if len(y_exp) == len(y_fit) else 0
    diff = abs(y_exp - y_fit)
    mape = 1 / n * sum(diff / y_exp)
    return round(mape * 100, 1)


def fitted_apparent_resistivity(ab2s_exp, res, thick):
    lam = np.concatenate([res, thick])
    return VES1D_models.apparent_resistivity_Schlumberger(ab2s_exp, *lam)


def export_layers_model(ab2s_exp, res, thick):
    nlayers = len(res)
    tdum = [0] + list(thick)
    tdum = np.array([sum(tdum[:i+1]) for i in range(nlayers)])
    thick_plot = []
    res_plot = []
    for i, (r, t) in enumerate(zip(res, tdum)):
        res_plot.append(r)
        thick_plot.append(t)
        if i < len(tdum) - 1:
            res_plot.append(r)
            thick_plot.append(tdum[i+1])
        else:
            res_plot.append(r)
            thick_plot.append(max(ab2s_exp))
    layermodel = []
    for xi, yi in zip(thick_plot, res_plot):
        layermodel.append({"x": round(xi, 1), "y": round(yi, 1)})
    return layermodel


def export_fitted_values(x_exp, rho, thick):
    y_fitted = fitted_apparent_resistivity(x_exp, rho, thick)
    fitvalues = []
    for xi, yi in zip(x_exp, y_fitted):
        fitvalues.append({"x": xi, "y": round(yi, 1)})
    return fitvalues
