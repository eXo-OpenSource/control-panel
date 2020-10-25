<div class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show" id="sidebar">
    <div class="c-sidebar-brand">
        <a href="{{ '/' }}">
            <img class="c-sidebar-brand-full" src="/images/logo.png" width="118" height="46" alt="{{ config('app.name', 'Laravel') }}">
            <img class="c-sidebar-brand-minimized" src="/images/logo_small.png" width="46" height="46" alt="{{ config('app.name', 'Laravel') }}">
        </a>
    </div>
    <ul class="c-sidebar-nav" data-drodpown-accordion="true">
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="https://forum.exo-reallife.de">
                <i class="c-sidebar-nav-icon fas fa-comments"></i>{{ __('Forum') }}
            </a>
        </li>
        @auth
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{ route('users.search') }}">
                <i class="c-sidebar-nav-icon fas fa-user"></i>{{ __('Benutzersuche') }}
            </a>
        </li>
        @endauth
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{ route('factions.index') }}">
                <i class="c-sidebar-nav-icon fas fa-user-friends"></i>{{ __('Fraktionen') }}
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{ route('companies.index') }}">
                <i class="c-sidebar-nav-icon fas fa-user-friends"></i>{{ __('Unternehmen') }}
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{ route('groups.index') }}">
                <i class="c-sidebar-nav-icon fas fa-user-friends"></i>{{ __('Gruppen') }}
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{ route('vehicles.index') }}"><span class="c-sidebar-nav-icon fas fa-car"></span>{{ __('Fahrzeuge') }}</a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{ route('tickets.index') }}">
                <i class="c-sidebar-nav-icon fas fa-headset"></i>{{ __('Tickets') }}
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{ route('who.is.online') }}">
                <i class="c-sidebar-nav-icon fas fa-globe-europe"></i>{{ __('Wer ist online') }}
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{ route('statistics') }}">
                <i class="c-sidebar-nav-icon fas fa-list-ol"></i>{{ __('Statistiken') }}
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{ route('commits') }}">
                <i class="c-sidebar-nav-icon fas fa-code-branch"></i>{{ __('Commits') }}
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{ route('achievements') }}">
                <i class="c-sidebar-nav-icon fas fa-trophy"></i>{{ __('Achievements') }}
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{ route('textures.index') }}">
                <i class="c-sidebar-nav-icon fas fa-images"></i>{{ __('Texturen') }}
            </a>
        </li>
        @auth
            @if(auth()->user()->Rank >= 5 || count(auth()->user()->character->getTrainingTargets()) > 0)
        <li class="c-sidebar-nav-dropdown">
            <a class="c-sidebar-nav-dropdown-toggle" href="#">
                <i class="c-sidebar-nav-icon fas fa-toolbox"></i>{{ __('Schulungen') }}
            </a>
            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link" href="{{ route('trainings.overview.index') }}"><span class="c-sidebar-nav-icon fas fa-table"></span>{{ __('Übersicht') }}</a>
                </li>
            </ul>
            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link" href="{{ route('trainings.index') }}"><span class="c-sidebar-nav-icon fas fa-chalkboard"></span>{{ __('Schulungen') }}</a>
                </li>
            </ul>
            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link" href="{{ route('trainings.templates.index') }}"><span class="c-sidebar-nav-icon fas fa-list"></span>{{ __('Vorlagen') }}</a>
                </li>
            </ul>
            @if((auth()->user()->character->FactionId <> 0 && auth()->user()->character->FactionRank >= 5) ||
                (auth()->user()->character->CompanyId <> 0 && auth()->user()->character->CompanyRank >= 4))
            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link" href="{{ route('trainings.permissions.index') }}"><span class="c-sidebar-nav-icon fas fa-shield-alt"></span>{{ __('Rechte') }}</a>
                </li>
            </ul>
            @endif
        </li>
            @endif
        @endauth
        @auth
            @if(auth()->user()->Rank >= 3)
                <li class="c-sidebar-nav-dropdown">
                    <a class="c-sidebar-nav-dropdown-toggle" href="#">
                        <i class="c-sidebar-nav-icon fas fa-user-shield"></i>{{ __('Admin') }}
                    </a>
                    <ul class="c-sidebar-nav-dropdown-items">
                        <li class="c-sidebar-nav-item">
                            <a class="c-sidebar-nav-link" href="{{ route('admin.dashboard.index') }}"><span class="c-sidebar-nav-icon fas fa-tachometer-alt"></span>Dashboard</a>
                        </li>
                        <li class="c-sidebar-nav-item">
                            <a class="c-sidebar-nav-link" href="{{ route('admin.user.multiaccounts') }}"><span class="c-sidebar-nav-icon fas fa-people-arrows"></span>Multiaccounts</a>
                        </li>
                        <li class="c-sidebar-nav-item">
                            <a class="c-sidebar-nav-link" href="{{ route('admin.texture') }}"><span class="c-sidebar-nav-icon fas fa-images"></span>Texturen</a>
                        </li>
                        <li class="c-sidebar-nav-item">
                            <a class="c-sidebar-nav-link" href="{{ route('admin.teamspeak.index') }}"><span class="c-sidebar-nav-icon fab fa-teamspeak"></span>Teamspeak</a>
                        </li>
                        <li class="c-sidebar-nav-item">
                            <a class="c-sidebar-nav-link" href="{{ route('admin.logs.show') }}"><span class="c-sidebar-nav-icon fas fa-file-alt"></span>Logs</a>
                        </li>
                        <li class="c-sidebar-nav-item">
                            <a class="c-sidebar-nav-link" href="{{ route('admin.bans.index') }}"><span class="c-sidebar-nav-icon fas fa-ban"></span>{{ __('Bans') }}</a>
                        </li>
                        <li class="c-sidebar-nav-item">
                            <a class="c-sidebar-nav-link" href="{{ route('admin.houses.index') }}"><span class="c-sidebar-nav-icon fas fa-home"></span>{{ __('Häuser') }}</a>
                        </li>
                        <li class="c-sidebar-nav-item">
                            <a class="c-sidebar-nav-link" href="{{ route('admin.server.show') }}"><span class="c-sidebar-nav-icon fas fa-server"></span>Server</a>
                        </li>
                        <li class="c-sidebar-nav-item">
                            <a class="c-sidebar-nav-link" href="{{ route('admin.polls.index') }}"><span class="c-sidebar-nav-icon fas fa-poll"></span>{{ __('Abstimmung') }}</a>
                        </li>
                        <li class="c-sidebar-nav-item">
                            <a class="c-sidebar-nav-link" href="{{ route('admin.tickets.index') }}"><span class="c-sidebar-nav-icon fas fa-list-ol"></span>{{ __('Tickets') }}</a>
                        </li>
                        @if(auth()->user()->Rank >= 7)
                            <li class="c-sidebar-nav-item">
                                <a class="c-sidebar-nav-link" href="{{ route('admin.maps.index') }}"><span class="c-sidebar-nav-icon fas fa-map"></span>Maps</a>
                            </li>
                        @endif
                        @if(auth()->user()->Rank >= 7)
                            <li class="c-sidebar-nav-item">
                                <a class="c-sidebar-nav-link" href="https://pma.exo.cool"><span class="c-sidebar-nav-icon fas fa-database"></span>phpMyAdmin</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        @endauth
    </ul>
    <button class="c-sidebar-minimizer c-class-toggler" type="button" data-target="_parent" data-class="c-sidebar-minimized"></button>
</div>
