<x-guest-layout>
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Session Status -->
        @if (session('status'))
            <div class="alert alert-info py-2 px-3 mb-3 small" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label small fw-semibold text-muted text-uppercase">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="form-control @error('email') is-invalid @enderror" placeholder="nama@kashin.com">
            @error('email')
                <div class="invalid-feedback small">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label small fw-semibold text-muted text-uppercase">Password</label>
            <input id="password" type="password" name="password" required class="form-control @error('password') is-invalid @enderror" placeholder="••••••••">
            @error('password')
                <div class="invalid-feedback small">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
            <label class="form-check-label small text-secondary" for="remember_me">
                Ingat saya
            </label>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
            Masuk
        </button>

        {{-- Quick Demo Login Helper --}}
        <div class="mt-4 pt-3 border-top border-secondary-subtle">
            <div class="text-center text-muted small mb-2" style="font-size: 11px; letter-spacing: 0.5px;">DEMO LOGIN:</div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary btn-sm flex-grow-1" onclick="quickLogin('bendahara@kashin.com', 'bendahara')">
                    <i class="bi bi-shield-lock me-1"></i> Bendahara
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm flex-grow-1" onclick="quickLogin('andi_firmansyah@siswa.dev', 'siswa123')">
                    <i class="bi bi-person me-1"></i> Siswa
                </button>
            </div>
        </div>
    </form>

    <script>
        function quickLogin(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
            document.querySelector('form').submit();
        }
    </script>
</x-guest-layout>
