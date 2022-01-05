Sayın {!! $user->name !!},<br>
İşte sizin için bir kurtarma linki

{!! env('FRONT_URL') . '/' . $token !!}
