<!DOCTYPE html>
<html>
<head>
  @include ('layouts.head')
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('plugins/iCheck/square/blue.css') }}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="{{ url('/') }}"><b>Portal GTK</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your session</p>
    @include('layouts._flash')
    {!! Form::open(['url' => route('login'), 'method' => 'post']) !!}
      <div class="form-group has-feedback{{ $errors->has('login') ? ' has-error' : '' }}">
        {!! Form::email('login', null, ['class'=>'form-control', 'placeholder'=>'Masukan Email', 'onchange' => 'autoRemember()', 'id' => 'login', 'autocomplete' => 'off', 'required']) !!}
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        {!! $errors->first('login', '<p class="help-block">:message</p>') !!}
      </div>
      <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
        {!! Form::password('password', ['class'=>'form-control', 'placeholder'=>'Masukan Password', 'required']) !!}
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
      </div>
      @php
        $request = request();
        $ip = $request->ip();
        $key_recaptcha = "recaptcha|".$ip;
        $show_recaptcha = false;
        if(\Cache::has($key_recaptcha)) {
          $show_recaptcha = true;
          \Cache::forget($key_recaptcha);
        }
      @endphp
      @if (config('app.env', 'local') === 'production')
        @if($show_recaptcha)
          <div class="form-group has-feedback{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
            {!! app('captcha')->display() !!}
            {!! $errors->first('g-recaptcha-response', '<p class="help-block">:message</p>') !!}
          </div>
          {!! Form::hidden('recaptcha', "T", ['class'=>'form-control', 'id' => 'recaptcha', 'readonly' => 'readonly']) !!}
        @else
          {!! Form::hidden('recaptcha', "F", ['class'=>'form-control', 'id' => 'recaptcha', 'readonly' => 'readonly']) !!}
        @endif
      @endif
      <div class="row" style="margin-top:30px;">
        <div class="col-xs-4">
          <button type="button" class="btn btn-primary btn-block btn-flat" onclick="register()">Register</button>
        </div>
        <div class="col-xs-4"></div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    {{-- <div class="social-auth-links text-left"> --}}
      {{-- <p>
        <a href="{{ url('/password/reset') }}">
          @if(config('app.locale', 'en') === 'en') I forgot my password
          @else Lupa Kata Sandi? Klik di sini
          @endif
        </a>
      </p> --}}
      {{-- @if(config('app.url', 'http://localhost') === 'https://iaess.igp-astra.co.id' || config('app.url', 'http://localhost') === 'https://mfg.igp-astra.co.id' || config('app.url', 'http://localhost') === 'http://localhost')
        <p>
          <a href="{{ url('/register') }}">
            @if(config('app.locale', 'en') === 'en') Don't have an account? Sign up
            @else Tidak punya akun? Daftar
            @endif
          </a>
        </p> --}}
        {{-- <p>
          <a href="{{ url('/changeemail') }}">
            @if(config('app.locale', 'en') === 'en') Change Email? Click here
            @else Ubah Email / No. HP? Klik di sini
            @endif
          </a>
        </p> --}}
      {{-- @else 
        <p>
          <a href="{{ url('/kunjungan') }}">
            @if(config('app.locale', 'en') === 'en') Want to visit the PT. {{ config('app.kd_pt', 'XXX') }}?
            @else Ingin berkunjung ke PT. {{ config('app.kd_pt', 'XXX') }}?
            @endif
          </a>
        </p>
      @endif --}}
    {{-- </div> --}}
    
    <hr>

    <p style="text-align:center;">
      <img src="{{ asset('images/login-gtk.png') }}" height="130" width="240"/>
    </p>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="{{ asset('plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
<!-- iCheck -->
<script src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
<!-- Sweet Alert 2-->
<script src="{{ asset('js/sweetalert2.min.js') }}"></script>
<script>
  document.getElementById("login").focus();
  
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });

  function register() {
    window.location.href = "/register";
  }

  function autoRemember() {
    var login = document.getElementById("login").value.trim();
    if(login === 'ian' || login === 'ian.septian22@gmail.com' || login === '14438' || login === 'septian@igp-astra.co.id') {
      $('#remember').iCheck('check');
    } else {
      if(login.length == 4 && login.substring(0, 1) != 'r') {
        document.getElementById("login").value = "0" + login;
      }
    }
  }

  @if (config('app.env', 'local') === 'production')
    @if($show_recaptcha)
      $("form").submit(function(event) {
        var recaptcha = $("#g-recaptcha-response").val();
        if (recaptcha === "") {
          event.preventDefault();
          swal('Warning', 'reCAPTCHA harus dicentang!', 'error');
        }
      });
    @endif
  @endif
</script>
</body>
</html>