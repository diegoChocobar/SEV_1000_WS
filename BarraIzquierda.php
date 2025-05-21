<!-- BARRA IZQUIERDA -->
<div id="aside" class="app-aside modal nav-dropdown" style="width: 150px;">
    <!-- fluid app aside -->
    <div class="left navside light dk" data-layout="column">
      <div class="navbar no-radius">
        <!-- brand -->
        <a class="image align-left" href="https://cdcelectronics.com" target="_blank">
            <img class="cropContainer" src="<?php echo $_SESSION['page_position']; ?>img/logo.png" height="110%" width="110%">
            <br>
            
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
              <a id="linkPrincipal" href="<?php echo $_SESSION['page_position']; ?>dashboard.php" onclick="">
                <span class="nav-icon">
                  <i class="fa fa-building-o"></i>
                </span>
                <span class="nav-text">Principal</span>
              </a>
            </li>

            <li>
              <a id="linkEnsayo" href="<?php echo $_SESSION['page_position']; ?>html/ensayo.php" onclick >
                <span class="nav-icon">
                  <i class='material-icons'></i>
                </span>
                <span class="nav-text">Ensayo</span>
              </a>
            </li>

            <li>
              <a id="linkManual" href="<?php echo $_SESSION['page_position']; ?>archivos/SEV_Manual_Usuario.pdf" target="_blank">
                <span class="nav-icon">
                  <i class='fa fa-building-o'></i>
                </span>
                <span class="nav-text">Manual</span>
              </a>
            </li>

            <li>
              <a id="linkInformacion" href="<?php echo $_SESSION['page_position']; ?>info.php" onclick >
                <span class="nav-icon">
                  <i class='material-icons'></i>
                </span>
                <span class="nav-text">Datos </span>
              </a>
            </li>

          </ul>
        </nav>
      </div>
      <!--  Exit Menu desplegable izquierdo -->

    </div>
  </div>
<!-- END BARRA IZQUIERDA -->
