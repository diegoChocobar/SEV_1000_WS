import numpy as np


def forward_Schlumberger(ab2, rho, thick):
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

def apparent_resistivity_Schlumberger(ab2s, *params):
    nparams = len(params)
    nlayers = int((nparams + 1)/ 2)

    # Input
    # Resistivity of n layers (S/m), last layer n is assumed to be zero
    rho = np.array(params[:nlayers])

    # Thickness of n-1 layers (m), last layer n is assumed to be infinite and does not require a thickness
    thick = np.array(params[nlayers:])

    # Forward model
    # Calculate forward apparent electrical conductivities (ECa)
    app_res = []
    for ab2 in ab2s:
        app_res.append(forward_Schlumberger(ab2, rho, thick))

    # Calculate apparent resistivity (Ohm.m)
    app_res = np.array(app_res)
    return app_res


def forward_Wenner(ab2, rho, thick):
    return 0
    
def apparent_resistivity_Wenner(ab2s, *params):
    nparams = len(params)
    nlayers = int((nparams + 1)/ 2)

    # Input
    # Resistivity of n layers (S/m), last layer n is assumed to be zero
    rho = np.array(params[:nlayers])

    # Thickness of n-1 layers (m), last layer n is assumed to be infinite and does not require a thickness
    thick = np.array(params[nlayers:])

    # Forward model
    # Calculate forward apparent electrical conductivities (ECa)
    app_res = []
    for ab2 in ab2s:
        app_res.append(forward_Wenner(ab2, rho, thick))

    # Calculate apparent resistivity (Ohm.m)
    app_res = np.array(app_res)
    return app_res


def forward_Dipole(ab2, rho, thick):
    return 0

def apparent_resistivity_Dipole(ab2s, *params):
    nparams = len(params)
    nlayers = int((nparams + 1)/ 2)

    # Input
    # Resistivity of n layers (S/m), last layer n is assumed to be zero
    rho = np.array(params[:nlayers])

    # Thickness of n-1 layers (m), last layer n is assumed to be infinite and does not require a thickness
    thick = np.array(params[nlayers:])

    # Forward model
    # Calculate forward apparent electrical conductivities (ECa)
    app_res = []
    for ab2 in ab2s:
        app_res.append(forward_Dipole(ab2, rho, thick))

    # Calculate apparent resistivity (Ohm.m)
    app_res = np.array(app_res)
    return app_res
