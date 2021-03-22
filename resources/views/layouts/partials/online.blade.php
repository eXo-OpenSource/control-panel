@php
    $usersOnline = \Illuminate\Support\Facades\Cache::get('users-online');

    $count = 0;

    if (is_countable($usersOnline)) {
      $count = count($usersOnline);
    }
@endphp
<react-users-online data-children="{{ __(':online Benutzer online', ['online' => $count]) }}"></react-users-online>
