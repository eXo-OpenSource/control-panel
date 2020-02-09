@auth
    @if(auth()->user()->Rank >= 3)
        @php
            $users = \Illuminate\Support\Facades\Cache::get('users-online');
        @endphp
        @foreach($users as $user)
            {{ $user->Name }} <i>({{ $user->Time->diffForHumans() }})</i></span>
        @endforeach
    @endif
@endauth
