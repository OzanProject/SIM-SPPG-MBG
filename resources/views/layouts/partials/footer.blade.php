  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> {{ $appConfig->get('app_version', 'v1.0.0') }}
    </div>
    <strong>Copyright &copy; {{ $appConfig->get('copyright_year', date('Y')) }} 
    <a href="#">{{ $appConfig->get('company_name', config('app.name')) }}</a>.</strong> All rights reserved.
  </footer>
