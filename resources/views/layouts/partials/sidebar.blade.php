<div class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show" id="sidebar">
    <div class="c-sidebar-brand">
        <a href="{{ '/' }}">
            <img class="c-sidebar-brand-full" src="/images/logo.png" width="118" height="46" alt="{{ config('app.name', 'Laravel') }}">
            <img class="c-sidebar-brand-minimized" src="/images/logo_small.png" width="118" height="46" alt="{{ config('app.name', 'Laravel') }}">
        </a>
    </div>
    <ul class="c-sidebar-nav">
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{ route('factions.index') }}">
                <i class="c-sidebar-nav-icon fas fa-chevron-right"></i>{{ __('Fraktionen') }}
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{ route('companies.index') }}">
                <i class="c-sidebar-nav-icon fas fa-chevron-right"></i>{{ __('Unternehmen') }}
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{ route('groups.index') }}">
                <i class="c-sidebar-nav-icon fas fa-chevron-right"></i>{{ __('Gruppen') }}
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{ route('who.is.online') }}">
                <i class="c-sidebar-nav-icon fas fa-chevron-right"></i>{{ __('Wer ist online') }}
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{ route('statistics') }}">
                <i class="c-sidebar-nav-icon fas fa-chevron-right"></i>{{ __('Statistiken') }}
            </a>
        </li>
        @auth
            @if(auth()->user()->Rank >= 3)
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link" href="{{ route('textures.index') }}">
                        <i class="c-sidebar-nav-icon fas fa-chevron-right"></i>{{ __('Texturen') }}
                    </a>
                </li>
                <li class="c-sidebar-nav-item c-sidebar-nav-dropdown">
                    <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
                        <i class="c-sidebar-nav-icon fas fa-chevron-right"></i>{{ __('Admin') }}
                    </a>
                    <ul class="c-sidebar-nav-dropdown-items">
                        <li class="c-sidebar-nav-item">
                            <a class="c-sidebar-nav-link" href="{{ route('admin.dashboard.index') }}"><span class="c-sidebar-nav-icon"></span>Dashboard</a>
                        </li>
                        <li class="c-sidebar-nav-item">
                            <a class="c-sidebar-nav-link" href="{{ route('admin.user.search') }}"><span class="c-sidebar-nav-icon"></span>Benutzersuche</a>
                        </li>
                        <li class="c-sidebar-nav-item">
                            <a class="c-sidebar-nav-link" href="{{ route('admin.texture') }}"><span class="c-sidebar-nav-icon"></span>Texturen</a>
                        </li>
                    </ul>
                </li>
            @endif
        @endauth
    </ul>
    <button class="c-sidebar-minimizer c-class-toggler" type="button" data-target="_parent" data-class="c-sidebar-minimized"></button>
</div>
