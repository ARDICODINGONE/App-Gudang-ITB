<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login - Inventory</title>
	<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/dash.css') }}">
</head>
<body class="bg-light">
	<div class="container py-5">
		<div class="row justify-content-center">
			<div class="col-md-6 col-lg-5">
				<div class="card shadow-sm">
					<div class="card-body">
						<h4 class="card-title mb-4 text-center">Login</h4>

						@if(session('status'))
							<div class="alert alert-success">{{ session('status') }}</div>
						@endif

						@if($errors->any())
							<div class="alert alert-danger">
								<ul class="mb-0">
									@foreach($errors->all() as $error)
										<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
						@endif

						<form method="POST" action="{{ route('login') }}">
							@csrf

							<div class="mb-3">
								<label for="username" class="form-label">Username</label>
								<input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autofocus>
								@error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
							</div>

							<div class="mb-3">
								<label for="password" class="form-label">Password</label>
								<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
								@error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
							</div>

							<div class="mb-3 form-check">
								<input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
								<label class="form-check-label" for="remember">Remember me</label>
							</div>

							<div class="d-grid">
								<button type="submit" class="btn btn-primary">Login</button>
							</div>
						</form>

						<div class="mt-3 text-center">
							@if (Route::has('password.request'))
								<a href="{{ route('password.request') }}">Forgot your password?</a>
							@endif
							{{-- <div class="mt-2">Don't have an account? <a href="{{ route('register') }}">Register</a></div> --}}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="{{ asset('js/bootstrap.js') }}"></script>
</body>
</html>

