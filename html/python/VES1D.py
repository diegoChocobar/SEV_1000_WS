import numpy as np
import matplotlib.pyplot as plt
import matplotlib.ticker as mticker
import pandas as pd

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


def forward(ab2, rho, thick):
    """
    Calculate forward VES response with half the current electrode spacing for a 1D layered earth.

    Parameters
    ----------
    ab2 : float
        Half the current (AB/2) electrode spacing (m)

    rho : np.array, (n,)
        Resistivity, last layer n is assumed to be 0

    thick : np.array, (n-1,)
        Thickness of n-1 layers (m), last layer n is assumed to be infinite and does not require a thickness


    Returns
    -------
    app_con : float
        Apparent half-space electrical conductivity (S/m)

    References
    ----------
    Ekinci, Y. L., Demirci, A., 2008. A Damped Least-Squares Inversion Program 
    for the Interpretation of Schlumberger Sounding Curves, Journal of Applied Sciences, 8, 4070-4078.
	
    Koefoed, O., 1970. A fast method for determining the layer distribution from the raised
    kernel function in geoelectrical soundings, Geophysical Prospection, 18, 564-570.

    Nyman, D. C., Landisman, M., 1977. VES Dipole-dipole filter coefficients,
    Geophysics, 42(5), 1037-1044.
    """

    # Conductivity to resistivity and number of layers
    lays = len(rho) - 1

    # Constants
    LOG = np.log(10)
    COUNTER = 1 + (2 * 13 - 2)
    UP = np.exp(0.5 * LOG / 4.438)

    # Filter integral variable
    up = ab2 * np.exp(-10 * LOG / 4.438)

    # Initialize array
    ti = np.zeros(COUNTER)

    for ii in range(COUNTER):

        # Set bottom layer equal to its resistivity
        ti1 = rho[lays]

        # Recursive formula (Koefoed, 1970)
        lay = lays
        while lay > 0:
            lay -= 1
            tan_h = np.tanh(thick[lay] / up)
            ti1 = (ti1 + rho[lay] * tan_h) / (1 + ti1 * tan_h / rho[lay])

        # Set overlaying layer to previous
        ti[ii] = ti1

        # Update filter integral variable
        up *= UP

    # Apply point-filter weights (Nyman and Landisman, 1977)
    res_a = 105 * ti[0] - 262 * ti[2] + 416 * ti[4] - 746 * ti[6] + 1605 * ti[8] - 4390 * ti[10] + 13396 * ti[12]
    res_a += - 27841 * ti[14] + 16448 * ti[16] + 8183 * ti[18] + 2525 * ti[20] + 336 * ti[22] + 225 * ti[24]
    res_a /= 1e4

    # Resistivity to conductivity
    return res_a

def apparent_resistivity(ab2s, *params):
    nparams = len(params)
    nlayers = int((nparams + 1)/ 2)

    # Input
    # Resistivity of n layers (S/m), last layer n is assumed to be zero
    rho = np.array(params[:nlayers])
    # res = np.array([100, 50, 20])

    # Thickness of n-1 layers (m), last layer n is assumed to be infinite and does not require a thickness
    thick = np.array(params[nlayers:])
    # thick = np.array([5, 10])  # m

    # Forward model
    # Calculate forward apparent electrical conductivities (ECa)
    app_res = []
    for ab2 in ab2s:
        app_res.append(forward(ab2, rho, thick))

    # Calculate apparent resistivity (Ohm.m)
    app_res = np.array(app_res)
    return app_res


def extract_values(lam):
    
    nlayers = int((len(lam) + 1) / 2)
    res, thick = lam[:nlayers], lam[nlayers:]
    
    return res, thick


def plot_results(ab2s_exp, appres_exp, res, thick):

    # Figure
    # fig = plt.figure()
    ax = plt.gca()

    plt.plot(ab2s_exp, appres_exp, 'o', color='tab:orange', label='data')
    plot_apparent_resistivity(ab2s_exp, res, thick)
    plot_layers(ab2s_exp, res, thick)
    
    plt.xscale('log')
    plt.yscale('log')
    plt.xlabel('AB/2 (m)')
    plt.ylabel('$\\rho_a$ ($\Omega$m)')
    ymin = min(appres_exp) * 0.8
    ymax = max(appres_exp) * 1.2
    plt.ylim(ymin, ymax)
    mape = compute_error(ab2s_exp, appres_exp, res, thick)
    plt.legend(title=f'error = {mape:.1e}')
    # xtext = max(ab2s_exp) * 0.8
    # ytext = max(appres_exp) * 0.8
    # plt.text(xtext, ytext, )

    ax.tick_params(direction='in',which='major',bottom=True,top=True,left=True,right=True)
    ax.tick_params(direction='in',which='minor',bottom=True,top=True,left=True,right=True)

    plt.show()


def compute_error(ab2s_exp, appres_exp, res, thick):
    y_exp = appres_exp
    y_fit = fitted_apparent_resistivity(ab2s_exp, res, thick)
    n = len(y_exp) if len(y_exp) == len(y_fit) else 0
    diff = abs(y_exp - y_fit)
    mape = 1 / n * sum(diff / y_exp)
    return mape * 100


def fitted_apparent_resistivity(ab2s_exp, res, thick):
    lam = np.concatenate([res, thick])
    return apparent_resistivity(ab2s_exp, *lam)


def plot_apparent_resistivity(ab2s_exp, res, thick):
    appres_fitted = fitted_apparent_resistivity(ab2s_exp, res, thick)
    plt.plot(ab2s_exp, appres_fitted, color='tab:red', linestyle='solid', label=f'ajuste')
    

def plot_layers(ab2s_exp, res, thick):
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
    plt.plot(thick_plot, res_plot, color='tab:green')
