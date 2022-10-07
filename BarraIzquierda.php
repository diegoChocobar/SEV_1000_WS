<!-- BARRA IZQUIERDA -->
<div id="aside" class="app-aside modal nav-dropdown">
    <!-- fluid app aside -->
    <div class="left navside light dk" data-layout="column">
      <div class="navbar no-radius">
        <!-- brand -->
        <a class="image align-left" href="https://geofisicainstrumentos.com" target="_blank">
            <img class="cropContainer" src="http://localhost/SEV_1000_WS/img/logo.png" height="75%" width="75%">
            <br>
            <h7 class="align-center"><strong>CDC ELECTRONICS</strong></h7>
        </a>
        <!-- / brand -->
      </div>
      <div class="hide-scroll" data-flex><!--  Menu desplegable izquierdo -->
        <nav class="scroll nav-light">
          <ul class="nav" ui-nav>

            <li class="nav-header hidden-folded">
              <small class="text-muted">Main</small>
            </li>

            <li>
              <a id="linkPrincipal" href="http://localhost/SEV_1000_WS/dashboard.php" onclick="">
                <span class="nav-icon">
                  <i class="fa fa-building-o"></i>
                </span>
                <span class="nav-text">Principal</span>
              </a>
            </li>

            <li>
              <a id="linkEnsayo" href="http://localhost/SEV_1000_WS/html/ensayo.php" onclick >
                <span class="nav-icon">
                  <i class='material-icons'></i>
                </span>
                <span class="nav-text">Ensayo</span>
              </a>
            </li>

            <li>
              <a id="linkManual" href="http://localhost/SEV_1000_WS/archivos/SEV_Manual_Usuario.pdf" target="_blank">
                <span class="nav-icon">
                  <i class='fa fa-building-o'></i>
                </span>
                <span class="nav-text">Manual</span>
              </a>
            </li>

            <li>
              <a id="linkInformacion" href="http://localhost/SEV_1000_WS/info.php" onclick >
                <span class="nav-icon">
                  <i class='material-icons'></i>
                </span>
                <span class="nav-text">Especificaciones Técnicas</span>
              </a>
            </li>

          </ul>
        </nav>
      </div>
      <!--  Exit Menu desplegable izquierdo -->

      <div class="b-t">
        <div class="nav-fold">
          <a href="http://localhost/SEV_1000_WS/dashboard.php">
            <span class="pull-left">
              <img src="http://localhost/SEV_1000_WS/assets/images/a0.jpg" alt="..." class="w-40 img-circle">
            </span>
            <span class="clear hidden-folded p-x">
              <span class="block _500"><?php echo $user_login_name ?></span>
              <small class="block text-muted"><i class="fa fa-circle text-success m-r-sm"></i>online</small>
            </span>
          </a>
        </div>
      </div>

    </div>
  </div>
<!-- END BARRA IZQUIERDA -->
