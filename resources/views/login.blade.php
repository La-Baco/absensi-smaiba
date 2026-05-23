<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Absensi SMAIBA</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }} ">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }} ">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }} ">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/auth.css') }} ">
    <link rel="icon" href="{{ asset('assets/images/logo/iconsmaiba1.png') }}" sizes="any">

</head>

<body>
    <div id="auth">

        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <div class="auth-logo">
                        <a href="index.html"><img src="{{ asset('assets/images/logo/smaiba.png') }} "
                                alt="Logo"></a>
                    </div>
                    <h1 class="auth-title">Log in.</h1>
                    @if ($errors->any())
                        <div class="alert alert-denger">
                            <ul>
                                @foreach ($errors->all() as $item)
                                    <li> {{ $item }} </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="" method="POST">
                        @csrf
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input name="email" value="{{ old('email') }}" type="text"
                                class="form-control form-control-xl" placeholder="Username">
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input name="password" id="password" type="password" class="form-control form-control-xl"
                                placeholder="Password" style="padding-right: 50px;">

                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>

                            <div class="position-absolute end-0 top-50 translate-middle-y me-3"
                                style="cursor: pointer; z-index: 99; line-height: 0;">
                                <i class="bi bi-eye-slash fs-4 text-muted" id="togglePassword"></i>
                            </div>
                        </div>

                        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Log in</button>
                    </form>
                    <div class="text-center mt-5 text-lg fs-4">
                        <p class="text-gray-600">Hubungin Admin jika tidak memiliki Akun </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block ">
                <div id="auth-right">
                    <div class="d-flex flex-column align-items-center justify-content-center text-center"
                        style="height: 100vh;">
                        <img src="{{ asset('assets/images/logo/logo1.png') }}" alt="Logo Sekolah" height="300"
                            class="mb-4">
                        <h1 class="fw-bold display-5 text-white">SMA ISLAM BAITURRAHMAN</h1>
                    </div>

                </div>
            </div>
        </div>

    </div>
</body>
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function() {
        // Toggle tipe input
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);

        // Toggle ikon
        this.classList.toggle('bi-eye');
        this.classList.toggle('bi-eye-slash');
    });
</script>

</html>
