<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title') - Dapur MBG</title>

  <!-- Google Font: Inter (Lebih elegan & korporat dibanding Source Sans Pro) -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/adminlte3/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style Bootstrap 4 & AdminLTE 3 -->
  <link rel="stylesheet" href="/adminlte3/dist/css/adminlte.min.css">
  <style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f4f6f9;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        margin: 0;
    }
    .auth-wrapper {
        width: 100%;
        max-width: 900px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.06);
        overflow: hidden;
        display: flex;
    }
    .auth-brand {
        background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
        color: #fff;
        padding: 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        flex: 1;
        position: relative;
    }
    .auth-brand h1 {
        font-weight: 700;
        font-size: 2.2rem;
        margin-bottom: 12px;
        letter-spacing: -0.5px;
    }
    .auth-brand p {
        font-size: 1.05rem;
        opacity: 0.85;
        margin-bottom: 0;
        line-height: 1.6;
    }
    .auth-form-container {
        padding: 50px 60px;
        flex: 1.1;
        max-width: 500px;
    }
    .auth-form-container h4 {
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 8px;
        letter-spacing: -0.3px;
    }
    .auth-form-container p.text-muted {
        font-size: 0.95rem;
        margin-bottom: 30px;
    }
    .form-control {
        border-radius: 6px;
        padding: 12px 15px;
        height: auto;
        font-size: 0.95rem;
        border-color: #ced4da;
    }
    .btn-primary {
        background-color: #203a43;
        border-color: #203a43;
        border-radius: 6px;
        padding: 12px 20px;
        font-weight: 600;
        letter-spacing: 0.3px;
        transition: all 0.2s ease-in-out;
    }
    .btn-primary:hover, .btn-primary:focus {
        background-color: #0f2027;
        border-color: #0f2027;
        box-shadow: 0 4px 12px rgba(15,32,39,0.2);
    }
    .input-group-text {
        background: #fff;
        border-left: 0;
        color: #adb5bd;
    }
    .form-control {
        border-right: 0;
    }
    .form-control:focus {
        border-color: #203a43;
        box-shadow: none;
    }
    .form-control:focus + .input-group-append .input-group-text {
        border-color: #203a43;
        color: #203a43;
    }
    a {
        color: #203a43;
        transition: color 0.15s ease-in-out;
    }
    a:hover {
        color: #0f2027;
        text-decoration: underline;
    }
    .custom-control-input:checked ~ .custom-control-label::before {
        border-color: #203a43;
        background-color: #203a43;
    }
    @media (max-width: 768px) {
        .auth-wrapper {
            flex-direction: column;
            margin: 20px;
            border-radius: 10px;
        }
        .auth-brand {
            padding: 40px 30px;
            text-align: center;
        }
        .auth-form-container {
            padding: 40px 30px;
            max-width: 100%;
        }
    }
  </style>
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-brand d-none d-md-flex">
        <div>
            <h1>@yield('logo_bold', 'Dapur') @yield('logo_regular', 'MBG')</h1>
            <p>Platform Pengelolaan SaaS untuk Modernisasi Operasional Usaha Kuliner Nusantara.</p>
        </div>
    </div>
    <div class="auth-form-container w-100">
        <h4>@yield('heading', 'Selamat Datang')</h4>
        <p class="text-muted">@yield('subheading', 'Silakan masuk untuk melanjutkan sesi.')</p>

        @yield('content')
    </div>
</div>

<!-- jQuery -->
<script src="/adminlte3/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/adminlte3/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="/adminlte3/dist/js/adminlte.min.js"></script>
@stack('scripts')
</body>
</html>
