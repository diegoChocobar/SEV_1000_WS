<div class="app-header white box-shadow">
  <div class="navbar navbar-toggleable-sm flex-row align-items-center">
    <!-- Open side - Naviation on mobile -->
    <a data-toggle="modal" data-target="#aside" class="hidden-lg-up mr-3">
      <i class="material-icons">&#xe5d2;</i>
    </a>
    <!-- / -->

    <!-- Page title - Bind to $state's title -->
    <div class="mb-0 h5 no-wrap" ng-bind="$state.current.data.title" id="pageTitle"></div>

    <!-- BARRA DE LA DERECHA -->
    <ul class="nav navbar-nav ml-auto flex-row">
      <li class="nav-item dropdown">
        <a class="nav-link p-0 clear" href="#" data-toggle="dropdown">
          <span class="avatar w-32">
            <img src="http://localhost/SEV_1000_WS/assets/images/a0.jpg" alt="...">
            <i class="on b-white bottom"></i>
          </span>
        </a>
        <div class="dropdown-menu dropdown-menu-overlay pull-right">
          <a class="dropdown-item" href="http://localhost/SEV_1000_WS/dashboard.php">Perfil</a>
          <a class="dropdown-item" href="http://localhost/SEV_1000_WS/info.php">Info Equipo</a>

          <div class="dropdown-divider"></div>

          <a class="dropdown-item" href="http://localhost/SEV_1000_WS/archivos/SEV_Manual_Usuario.pdf" target="_blank">Ayuda</a>
          <a class="dropdown-item" href="http://localhost/SEV_1000_WS/login.php">Salir</a>
        </div>
      </li>
      <li class="nav-item hidden-md-up">
        <a class="nav-link pl-2" data-toggle="collapse" data-target="#collapse">
          <i class="material-icons">&#xe5d4;</i>
        </a>
      </li>
    </ul>
    <!-- / navbar right -->
  </div>
</div>
