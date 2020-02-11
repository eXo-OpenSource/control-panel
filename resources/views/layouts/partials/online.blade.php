@auth
    @if(auth()->user()->Rank >= 3)
        @php
            $users = \Illuminate\Support\Facades\Cache::get('users-online');
        @endphp
        @foreach($users as $user)
            <span data-toggle="tooltip" data-placement="top" title="{{ $user->Time->diffForHumans() }}">{{ $user->Name }}</span>@if(!$loop->last){{ ',' }}@endif
        @endforeach
    @endif
@endauth
