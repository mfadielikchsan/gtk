@extends('layouts.app')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Security Monitoring
                <small>Info Security Monitoring</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('secmonitoring.index') }}"><i class="fa fa-dashboard"></i> Security Monitoring</a></li>
                <li class="active">Info</li>
            </ol>
        </section>
        <section class="content">
            @include('layouts._flash')
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <div class="box-header with-border">
                                <h3 class="box-title">Info Security Monitoring</h3>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            {{-- Form Laravel/Collective --}}
                            {!! Form::open(['url' => route('secmonitoring.store'), 'method' => 'post', 'class'=>'form-horizontal', 'id'=>'form_pengajuan', 'enctype'=>'multipart/form-data']) !!}
                                {{-- Tanggal Monitoring --}}
                                <div class="form-group">
                                    {!! Form::label('tgl_monitoring', 'Tanggal Monitoring', ['class'=>'col-md-2 control-label']) !!}
                                    <div class="col-md-4">
                                        <div class="input-group date">
                                            <input type="text" id="tgl_monitoring" name="tgl_monitoring"  class="form-control"  value="{{ Carbon\Carbon::parse($secmon->tglmonitor)->format('d/m/Y') }}" readonly/>
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
                                    {!! Form::text('nama', $karyawan->npk . ' - '.$karyawan->nama, ['class'=>'form-control', 'placeholder' => '', 'id'=>'nama', 'required', 'readonly']) !!}
                                    </div>
                                </div>
                                {{-- Jam Monitoring --}}
                                <div class="form-group">
                                    {!! Form::label('jam_monitoring', 'Jam Monitoring', ['class'=>'col-md-2 control-label']) !!}
                                    <div class="col-md-2">
                                        <div class="bootstrap-timepicker input-group">
                                            {!! Form::text('jam_monitoring', $secmon->jammonitoring,['class' => 'form-control timepicker', 'id'=>'jam_monitoring', 'readonly'] ) !!}
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
                                        {!! Form::text('status', $secmon->statusmonitoring==="Y"?"OK":"Not OK", ['class'=>'form-control', 'placeholder' => '', 'id'=>'status', 'required', 'readonly']) !!}
                                    </div>
                                </div>
                                {{-- Ket Not Ok --}}
                                @if ($secmon->statusmonitoring == 'N')
                                    <div class="form-group" id="ketNotOk">
                                            {!! Form::label('keterangan', 'Ket Not Ok', ['class'=>'col-md-2 control-label']) !!}
                                        <div class="col-md-4">
                                            {!! Form::textarea('keterangan', $secmon->ketproblem, ['class'=>'form-control', 'id'=>'keterangan', 'readonly', 'rows' => '6']) !!}
                                        </div>
                                    </div> 
                                                                                                 
                                    {{-- Tindakan --}}
                                    <div class="form-group" id="tindakanDiv">
                                            {!! Form::label('tindakan', 'Tindakan', ['class'=>'col-md-2 control-label']) !!}
                                        <div class="col-md-4">
                                            {!! Form::textarea('tindakan', $secmon->tindakan, ['class'=>'form-control', 'id'=>'tindakan', 'readonly', 'rows' => '6']) !!}
                                        </div>
                                    </div>
                                @endif
                                
                                {{-- Button Submit --}}
                                <div class="form-group" style="margin-top:30px; margin-bottom:50px;display:flex;justify-content:flex-end;">
                                    <div class="col-md-7">       
                                        <a href="{{ route('secmonitoring.index') }}" class="btn btn-primary">Close</a>
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
@endsection
