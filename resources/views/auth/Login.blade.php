<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login - Inventory</title>
	<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
	<style>
		:root {
			--login-primary: #1f4eb4;
			--login-primary-soft: #2e67dd;
			--login-surface: #ffffff;
			--login-text: #0f172a;
			--login-muted: #64748b;
			--login-border: #dbe4f3;
		}

		body.login-page {
			min-height: 100vh;
			margin: 0;
			background:
				radial-gradient(circle at 10% 20%, rgba(46, 103, 221, 0.28), transparent 42%),
				radial-gradient(circle at 88% 16%, rgba(16, 185, 129, 0.2), transparent 36%),
				linear-gradient(135deg, #f0f5ff 0%, #e8f4ff 45%, #f8fbff 100%);
			color: var(--login-text);
		}

		.login-wrap {
			min-height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 1.5rem;
		}

		.login-shell {
			width: 100%;
			max-width: 980px;
			display: grid;
			grid-template-columns: 1fr 1fr;
			background: rgba(255, 255, 255, 0.8);
			border: 1px solid rgba(255, 255, 255, 0.9);
			border-radius: 24px;
			backdrop-filter: blur(8px);
			overflow: hidden;
			box-shadow: 0 20px 55px rgba(15, 23, 42, 0.12);
		}

		.brand-panel {
			background: linear-gradient(160deg, #153d96 0%, #2b6ddc 100%);
			color: #fff;
			padding: 2.5rem 2.25rem;
			display: flex;
			flex-direction: column;
			justify-content: center;
		}

		.brand-badge {
			display: inline-flex;
			align-self: flex-start;
			padding: 0.35rem 0.75rem;
			border-radius: 999px;
			font-size: 0.78rem;
			letter-spacing: 0.06em;
			text-transform: uppercase;
			background: rgba(255, 255, 255, 0.16);
		}

		.brand-title {
			font-size: clamp(1.65rem, 3.2vw, 2.2rem);
			font-weight: 700;
			line-height: 1.2;
			margin-top: 1rem;
			margin-bottom: 0.8rem;
		}

		.brand-text {
			margin: 0;
			opacity: 0.92;
			max-width: 28ch;
			line-height: 1.65;
		}

		.form-panel {
			background: var(--login-surface);
			padding: 2.4rem 2.2rem;
		}

		.form-title {
			font-size: 1.45rem;
			font-weight: 700;
			margin: 0;
		}

		.form-subtitle {
			color: var(--login-muted);
			margin: 0.4rem 0 1.6rem;
		}

		.form-label {
			font-size: 0.92rem;
			font-weight: 600;
			color: #1e293b;
		}

		.form-control {
			height: 46px;
			border: 1px solid var(--login-border);
			border-radius: 12px;
			background: #f8fbff;
			padding: 0 0.9rem;
		}

		.form-control:focus {
			border-color: #8ab1ff;
			box-shadow: 0 0 0 0.2rem rgba(31, 78, 180, 0.15);
			background: #fff;
		}

		.form-check-label {
			color: #334155;
			font-size: 0.92rem;
		}

		.btn-login {
			height: 46px;
			border: 0;
			border-radius: 12px;
			font-weight: 600;
			background: linear-gradient(135deg, var(--login-primary) 0%, var(--login-primary-soft) 100%);
			box-shadow: 0 12px 22px rgba(31, 78, 180, 0.25);
			transition: transform 0.18s ease, box-shadow 0.2s ease, opacity 0.2s ease;
		}

		.btn-login:hover {
			opacity: 0.96;
			transform: translateY(-1px);
			box-shadow: 0 14px 24px rgba(31, 78, 180, 0.3);
		}

		.login-link {
			color: var(--login-primary);
			text-decoration: none;
			font-weight: 600;
		}

		.login-link:hover {
			text-decoration: underline;
		}

		@media (max-width: 991.98px) {
			.login-shell {
				grid-template-columns: 1fr;
				max-width: 520px;
			}

			.brand-panel {
				padding-bottom: 2rem;
			}

			.brand-text {
				max-width: none;
			}
		}

		@media (max-width: 575.98px) {
			.login-wrap {
				padding: 1rem;
			}

			.form-panel,
			.brand-panel {
				padding: 1.5rem;
			}
		}
	</style>
</head>
<body class="login-page">
	<div class="login-wrap">
		<div class="login-shell">
			<div class="brand-panel">
				<span class="brand-badge">Inventory System</span>
				<h1 class="brand-title">Kelola stok barang lebih cepat dan terstruktur</h1>
				<p class="brand-text">Masuk ke dashboard untuk memantau barang masuk, barang keluar, dan laporan inventaris secara terpusat.</p>
			</div>

			<div class="form-panel">
				<h2 class="form-title">Masuk Akun</h2>
				<p class="form-subtitle">Silakan login menggunakan username dan password Anda.</p>

				@if(session('status'))
					<div class="alert alert-success">{{ session('status') }}</div>
				@endif

				@if(session('error'))
					<div class="alert alert-danger">{{ session('error') }}</div>
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

					<div class="mb-4 form-check">
						<input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
						<label class="form-check-label" for="remember">Remember me</label>
					</div>

					<div class="d-grid">
						<button type="submit" class="btn btn-primary btn-login">Login</button>
					</div>
				</form>

				<div class="mt-3 text-center">
					@if (Route::has('password.request'))
						<a class="login-link" href="{{ route('password.request') }}">Forgot your password?</a>
					@endif
					{{-- <div class="mt-2">Don't have an account? <a href="{{ route('register') }}">Register</a></div> --}}
				</div>
			</div>
		</div>
	</div>

	<script src="{{ asset('js/bootstrap.js') }}"></script>
</body>
</html>
