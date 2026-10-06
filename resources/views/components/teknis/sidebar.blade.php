<aside class="sidebar">
    <div class="sidebar-header" style="padding: 1.5rem;">
        <div class="sidebar-logo">

        <a
            href="{{ route('teknis.profil.index') }}"
            class="nav-item {{ request()->routeIs('teknis.profil.index') ? 'active' : '' }}">
            <span class="nav-icon">⚙</span>
            <span>Profil</span>
        </a>

    </nav>


    <!-- BOTTOM -->
    <div class="sidebar-bottom">

        <div class="admin-mini">

            <div class="admin-avatar">
                T
            </div>

            <div class="admin-info">

                <strong>
                    {{ Auth::user()->name ?? 'Pengguna Teknis' }}
                </strong>

                <span>
                    Teknis
                </span>

            </div>

            <button
                type="button"
                class="more-btn">
                ⋮
            </button>

        </div>


        <a
            href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="logout-btn">
            <span>↪</span>
            <span>Keluar</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

    </div>

</aside>