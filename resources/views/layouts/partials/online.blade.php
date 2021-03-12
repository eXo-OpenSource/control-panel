@php
    $usersOnline = \Illuminate\Support\Facades\Cache::get('users-online');

    $count = 0;

    try {
        $count = count($usersOnline)
    } catch (Exception $e) {

    }
@endphp
<react-users-online data-children="{{ __(':online Benutzer online', ['online' => $count]) }}"></react-users-online>
