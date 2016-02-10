Welcome {{ $user->name }} to {{ env('APP_NAME') }}!
@if($password)
	<br><br>
	Your password: {{ $password }}
@endif
