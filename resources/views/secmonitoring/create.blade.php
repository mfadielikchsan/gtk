@extends('layouts.app')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Security Monitoring
                <small>Input Security Monitoring</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('secmonitoring.index') }}"><i class="fa fa-dashboard"></i> Security Monitoring</a></li>
                <li class="active">input</li>
            </ol>
        </section>
        <section class="content">
            
            <div class="row">
                <div class="col-md-12">
                    @include('layouts._flash')
                    <div class="box box-primary">
                        <div class="box-header">
                            <div class="box-header with-border">
                                <h3 class="box-title">Input Security Monitoring</h3>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            {{-- Form Laravel/Collective --}}
                            {!! Form::open(['url' => route('secmonitoring.store'), 'method' => 'post', 'class'=>'form-horizontal', 'id'=>'form_pengajuan', 'enctype'=>'multipart/form-data']) !!}
                                {{-- input hidden --}}
                                <input type="hidden" name="id_monitoring" value="{{ $id_secmon }}">
                                <input type="hidden" name="npkpic" value="{{ $npkKar }}">
                            
                                {{-- Tanggal Monitoring --}}
                                <div class="form-group">
                                    {!! Form::label('tgl_monitoring', 'Tanggal Monitoring', ['class'=>'col-md-2 control-label']) !!}
                                    <div class="col-md-4">
                                        <div class="input-group date">
                                            <input type="text" id="tgl_monitoring" name="tgl_monitoring"  class="form-control"  value="<?php echo date('d/m/Y', strtotime("today"));?>" readonly/>
                                            <div class="input-group-addon" style="background-color: lightgrey">
                                            <i class="fa fa-calendar" style="cursor: default;"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Nama karyawan --}}
                                <div class="form-group">
                                    {!! Form::label('nama', 'NPK DH', ['class'=>'col-md-2 control-label']) !!}
                                    <div class="col-md-4">
                                    {!! Form::text('nama', $dephead->npk . ' - '.$dephead->nama, ['class'=>'form-control', 'placeholder' => '', 'id'=>'nama', 'required', 'readonly']) !!}
                                    </div>
                                </div>
                                {{-- Jam Monitoring --}}
                                <div class="form-group">
                                    {!! Form::label('jam_monitoring', 'Jam Monitoring', ['class'=>'col-md-2 control-label']) !!}
                                    <div class="col-md-2">
                                        <div class="bootstrap-timepicker input-group">
                                            {!! Form::text('jam_monitoring', '',['class' => 'form-control timepicker', 'id'=>'jam_monitoring', 'required'] ) !!}
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                        </div>
                                    </div>                                 
                                </div>
                                {{-- Status Monitoring --}}
                                <div class="form-group">
                                    {!! Form::label('status', 'Status Monitoring', ['class'=>'col-md-2 control-label']) !!}
                                    <div class="col-md-4">
                                        {{-- {!! Form::select('status', ['Ok' => 'Ok', 'NotOk' => 'Not Ok'], null, ['placeholder' => 'Pilih Status..', 'id'=>'status', 'required', 'class'=>'form-control']); !!} --}}
                                        <select name="status" id="status" class="form-control" required>
                                            <option value=""></option>
                                            <option value="Y">OK</option>
                                            <option value="N">Not OK</option>
                                        </select>
                                    </div>
                                </div>                                                              
                                {{-- Ket Not Ok --}}
                                <div class="form-group" id="ketNotOk">
                                        {!! Form::label('keterangan', 'Ket Not Ok', ['class'=>'col-md-2 control-label']) !!}
                                    <div class="col-md-4">
                                        {!! Form::textarea('keterangan', null, ['class'=>'form-control', 'id'=>'keterangan', 'rows' => '6']) !!}
                                    </div>
                                </div>
                                {{-- Tindakan --}}
                                <div class="form-group" id="tindakanDiv">
                                        {!! Form::label('tindakan', 'Tindakan', ['class'=>'col-md-2 control-label']) !!}
                                    <div class="col-md-4">
                                        {!! Form::textarea('tindakan', null, ['class'=>'form-control', 'id'=>'tindakan', 'rows' => '6']) !!}
                                    </div>
                                </div>
                                
                                {{-- Button Submit --}}
                                <div class="form-group" style="margin-top:30px; margin-bottom:50px;display:flex;justify-content:flex-end;">
                                    <div class="col-md-8">       
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a href="{{ route('secmonitoring.index') }}" class="btn btn-danger">Cancel</a>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                            <!-- /.Form -->
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            $('#status').select2({
                placeholder: "Pilih Status"
            });
            $('#jam_monitoring').timepicker({
                    showMeridian: false,
                    minuteStep: 1,
                    showInputs: false
                });
            $('#ketNotOk').hide();
            $('#tindakanDiv').hide();
            $('#keterangan').prop('required',false);
            $('#tindakan').prop('required',false);
        })
        $('#status').on('change', function(){
            if($('#status').val() == 'N'){
                $('#ketNotOk').show();
                $('#tindakanDiv').show();
                $('#keterangan').prop('required',true);
                $('#tindakan').prop('required',true);
            }else{
                $('#ketNotOk').hide();
                $('#tindakanDiv').hide();
                $('#keterangan').prop('required',false);
                $('#tindakan').prop('required',false);
            }
        })
    </script>
@endsection
