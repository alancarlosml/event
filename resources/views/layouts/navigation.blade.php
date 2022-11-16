<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="user-panel p-2 mr-3 ml-2 mt-1 d-flex" data-toggle="dropdown" href="#">
            <i class="fa-solid fa-user p-1 mr-1"></i> <b>{{ Auth::user()->name }}</b>
        </a>
        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
            <a href="#" class="dropdown-item">
                <i class="fa-solid fa-id-card"></i> Perfil
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="{{ route('admin.logout') }}"
                onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-right-from-bracket"></i> Sair
            </a>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('dashboard')}}" class="brand-link">
      <img src="{{ asset('dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a href="{{route('dashboard')}}" class="nav-link">
                    <i class="nav-icon fas fa-tachometer-alt ml-1"></i>
                    <p> Dashboard </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('event.index')}}" class="nav-link">
                  <i class="fa-solid fa-radio ml-2"></i>
                    <p class="ml-2"> Eventos </p>
                </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="fa-solid fa-user-check ml-2"></i>
                      <p class="ml-1">Participantes
                      <i class="fas fa-angle-left right"></i>
                  </p>
              </a>
              <ul class="nav nav-treeview">
                  <li class="nav-item">
                      <a href="{{route('participante.create')}}" class="nav-link">
                          <i class="fa-solid fa-minus"></i>
                          <p>Adicionar</p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="{{route('participante.index')}}" class="nav-link">
                          <i class="fa-solid fa-minus"></i>
                          <p>Listar</p>
                      </a>
                  </li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-user"></i>
                      <p style="margin-left: 4px">Organizadores
                      <i class="fas fa-angle-left right"></i>
                  </p>
              </a>
              <ul class="nav nav-treeview">
                  <li class="nav-item">
                      <a href="{{route('owner.create')}}" class="nav-link">
                          <i class="fa-solid fa-minus"></i>
                          <p>Adicionar</p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="{{route('owner.index')}}" class="nav-link">
                          <i class="fa-solid fa-minus"></i>
                          <p>Listar</p>
                      </a>
                  </li>
              </ul>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fa-solid fa-location-dot ml-2"></i>
                    <p style="margin-left: 10px"> Locais
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{route('place.create')}}" class="nav-link">
                            <i class="fa-solid fa-minus"></i>
                            <p>Adicionar</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('place.index')}}" class="nav-link">
                            <i class="fa-solid fa-minus"></i>
                            <p>Listar</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-copy"></i>
                        <p>Categorias
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{route('category.create')}}" class="nav-link">
                            <i class="fa-solid fa-minus"></i>
                            <p>Adicionar</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('category.index')}}" class="nav-link">
                            <i class="fa-solid fa-minus"></i>
                            <p>Listar</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="{{route('contact.index')}}" class="nav-link ml-2">
                    <i class="fa-solid fa-envelope"></i>
                    <p style="margin-left: 6px"> Contatos </p>
                </a>
            </li>
            <li class="nav-header">ADMINISTRAÇÃO</li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-users"></i>
                      <p>Usuários
                      <i class="fas fa-angle-left right"></i>
                  </p>
              </a>
              <ul class="nav nav-treeview">
                  <li class="nav-item">
                      <a href="{{route('user.create')}}" class="nav-link">
                          <i class="fa-solid fa-minus"></i>
                          <p>Adicionar</p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="{{route('user.index')}}" class="nav-link">
                          <i class="fa-solid fa-minus"></i>
                          <p>Listar</p>
                      </a>
                  </li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="{{route('configuration.edit')}}" class="nav-link">
                  <i class="nav-icon fas fa-gear"></i>
                  <p> Configurações </p>
              </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
