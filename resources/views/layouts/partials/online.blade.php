@auth
    @if(auth()->user()->Rank >= 3)
        @php
            $users = \Illuminate\Support\Facades\Cache::get('users-online');
            $diff = \Carbon\Carbon::now()->subMinutes(30);
        @endphp
        @foreach($users as $user)
            @if($user->Time >= $diff)
                {{ $user->Name }} ({{ $user->Time->diffForHumans() }})</span>
            @endif
        @endforeach
    @endif
@endauth
