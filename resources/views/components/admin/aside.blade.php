<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link text-center">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                {{-- avatar optional --}}
            </div>
            <div class="info">
                @auth
                    <a href="#" class="d-block">{{ auth()->user()->name }}</a>
                @else
                    <a href="#" class="d-block">Admin</a>
                @endauth
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu"
                data-accordion="false">

                {{-- ===== MENU DARI VARIABLE $routes (AMAN) ===== --}}
                @isset($routes)
                    @foreach ($routes as $route)
                        @if (!empty($route['route_name']))
                            <li class="nav-item">
                                <a href="{{ route($route['route_name']) }}"
                                   class="nav-link {{ request()->routeIs($route['route_active'] ?? '') ? 'active' : '' }}">
                                    <i class="nav-icon {{ $route['icon'] ?? 'fas fa-circle' }}"></i>
                                    <p>
                                      {{ $route['label'] ?? '-' }}
                                    </p>
                                </a>
                            </li>
                            @else
                             {{-- ===== MASTER DATA ===== --}}
                            <li class="nav-item {{ request()->routeIs($route['route_active'] ?? '') ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ request()->routeIs('master-data.*') ? 'active' : '' }}">
                                    <i class="nav-icon {{$route['icon']    }}"></i>
                                    <p>
                                      {{ $route['label'] }}
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>

                                <ul class="nav nav-treeview">
                                    @foreach ($route['dropdown'] as $item)
                                    <li class="nav-item">
                                      <a href="{{ route($item['route_name']) }}" class="nav-link {{ request()->routeIs($item['route_active']) ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ $item['label'] }}</p>
                                      </a>
                                    </li>
                                    @endforeach
                                </ul>
                             </li>
                             @endif
                             @endforeach
                 @endisset     
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
