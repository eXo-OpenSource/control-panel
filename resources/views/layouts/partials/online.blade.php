@php
    $usersOnline = \Illuminate\Support\Facades\Cache::get('users-online');
@endphp
<react-users-online data-children="{{ __(':online Benutzer online', ['online' => count($usersOnline)]) }}"></react-users-online>
