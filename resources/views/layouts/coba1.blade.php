@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                CRUD
                <small>Dashboard</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Coba </a></li>
                <li class="active">Satu</li>
            </ol>
        </section>
        <section class="content">
            @include('layouts._flash')
            <div class="row">
                <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                    <div class="box-header with-border">
                        <h3 class="box-title">Portal Local IGP</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    </div>
                    <!-- /.box-header -->   
                    <div class="box-body">
                        <p>Saya fadiel</p>
                    </div>
                    <!-- /.box-body -->
                </div>
                <div class="box box-primary">
                    <div class="box-header">
                    <div class="box-header with-border">
                        <h3 class="box-title">Portal Local IGP</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    </div>
                    <!-- /.box-header -->   
                    <div class="box-body">
                        <p>Saya ikchsan</p>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
        </section>
    </div>
@endsection

@section('scripts')
@endsection