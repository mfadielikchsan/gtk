@extends('layouts.app2')
@section('content')
<div class="container3">
    <div class="row">
        <div class="login-logo">
            <a href="{{ url('/') }}"><b>Portal GTK</b></a>
        </div>
        <div class="col-md-8 col-md-offset-2">
            @include('layouts._flash')
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>
                <div class="panel-body">
                    {!! Form::open(['url'=>'/registercreate', 'class'=>'form-horizontal', 'id'=>'form_id']) !!}
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            {!! Form::label('name', 'Nama (*)', ['class'=>'col-md-4 control-label']) !!}
                            <div class="col-md-6">
                                {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Nama', 'required', 'autocomplete' => 'off']) !!}
                                {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            {!! Form::label('email', 'Email (*)', ['class'=>'col-md-4 control-label']) !!}
                            <div class="col-md-6">
                                {!! Form::email('email', null, ['class'=>'form-control', 'placeholder' => 'Email', 'maxlength' => 255, 'required', 'style' => 'text-transform:lowercase', 'onchange' => 'autoLowerCase(this)', 'autocomplete' => 'off']) !!}
                                {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            {!! Form::label('password', 'Password (*)', ['class'=>'col-md-4 control-label']) !!}
                            <div class="col-md-6">
                                {!! Form::password('password', ['class'=>'form-control', 'placeholder'=>'Password', 'required']) !!}
                                {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-user"></i> Daftar
                                </button>
                                &nbsp;&nbsp;
                                <a class="btn btn-primary" href="{{ url('/') }}">
                                    <i class="fa fa-btn fa-remove"></i> Cancel
                                </a>
                                &nbsp;&nbsp;
                                <p class="help-block pull-right has-error">
                                    {!! Form::label('info', '(*) tidak boleh kosong', ['style'=>'color:red']) !!}
                                </p>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">

    document.getElementById("username").focus();

    function autoUpperCase(a){
        a.value = a.value.toUpperCase();
    }

    function autoLowerCase(a){
        a.value = a.value.toLowerCase();
    }

    $('#form_id').submit(function (e, params) {
        var localParams = params || {};
        if (!localParams.send) {
            e.preventDefault();
            //additional input validations can be done hear
            swal({
                title: 'Apakah data yang Anda masukkan sudah benar?',
                text: 'Pastikan email yang Anda masukkan aktif',
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> Ya!',
                cancelButtonText: '<i class="fa fa-thumbs-down"></i> Tidak!',
                allowOutsideClick: true,
                allowEscapeKey: true,
                allowEnterKey: true,
                reverseButtons: false,
                focusCancel: false,
            }).then(function () {
              $(e.currentTarget).trigger(e.type, { 'send': true });
            }, function (dismiss) {
              // dismiss can be 'cancel', 'overlay',
              // 'close', and 'timer'
              if (dismiss === 'cancel') {
                // swal(
                //   'Cancelled',
                //   'Your imaginary file is safe :)',
                //   'error'
                // )
              }
            })
        }
    });
</script>
@endsection