@extends('layouts.app')
@section('content')
<style>
    .center{
        text-align: center;
    }
    table.dataTable #theadMaster {background-color:#3C8DBC; color: white}
</style>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Hak Akses User
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li>Admin</li>
                <li class="active">Hak Akses User</li>
            </ol>
        </section>
        <section class="content">
            @include('layouts._flash')
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="col-md-12" style="margin-bottom:25px;">
                            <div class="box-header" style="margin-bottom:25px;">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Edit Hak Akses User</h3>
                                </div> 
                            </div>
                            <div class="col-sm-3 form-group">
                                {!! Form::label('filter_tahun', 'Tahun') !!}
                                <select id="filter_tahun" name="filter_tahun" aria-controls="filter_status"
                                    class="form-control select2" style="width: 100%;margin: 0 0 10px 0;" onchange="tblMaster()">
                                    @for ($i = \Carbon\Carbon::now()->format('Y'); $i > \Carbon\Carbon::now()->format('Y') - 5; $i--)
                                        @if ($i == \Carbon\Carbon::now()->format('Y'))
                                            <option value={{ $i }} selected="selected">{{ $i }}
                                            </option>
                                        @else
                                            <option value={{ $i }}>{{ $i }}</option>
                                        @endif
                                    @endfor
                                </select>
                            </div>
                            <div class="col-sm-3 form-group">
                                {!! Form::label('filter_bulan', 'Bulan') !!}
                                <select id="filter_bulan" name="filter_bulan" aria-controls="filter_bulan"
                                    class="form-control select2" style="width: 100%;margin: 0 0 10px 0;" onchange="tblMaster()">
                                    <option value="01" @if ('01' == \Carbon\Carbon::now()->format('m')) selected="selected" @endif>
                                        Januari</option>
                                    <option value="02" @if ('02' == \Carbon\Carbon::now()->format('m')) selected="selected" @endif>
                                        Februari</option>
                                    <option value="03" @if ('03' == \Carbon\Carbon::now()->format('m')) selected="selected" @endif>
                                        Maret</option>
                                    <option value="04" @if ('04' == \Carbon\Carbon::now()->format('m')) selected="selected" @endif>
                                        April</option>
                                    <option value="05" @if ('05' == \Carbon\Carbon::now()->format('m')) selected="selected" @endif>Mei
                                    </option>
                                    <option value="06" @if ('06' == \Carbon\Carbon::now()->format('m')) selected="selected" @endif>Juni
                                    </option>
                                    <option value="07" @if ('07' == \Carbon\Carbon::now()->format('m')) selected="selected" @endif>Juli
                                    </option>
                                    <option value="08" @if ('08' == \Carbon\Carbon::now()->format('m')) selected="selected" @endif>
                                        Agustus</option>
                                    <option value="09" @if ('09' == \Carbon\Carbon::now()->format('m')) selected="selected" @endif>
                                        September</option>
                                    <option value="10" @if ('10' == \Carbon\Carbon::now()->format('m')) selected="selected" @endif>
                                        Oktober</option>
                                    <option value="11" @if ('11' == \Carbon\Carbon::now()->format('m')) selected="selected" @endif>
                                        November</option>
                                    <option value="12" @if ('12' == \Carbon\Carbon::now()->format('m')) selected="selected" @endif>
                                        Desember</option>
                                </select>
                            </div>                          
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body"> 
                            <table id="tblMaster" class="table table-bordered table-striped" cellspacing="0">
                                <thead id="theadMaster">
                                <tr>
                                    <th class="center" style="width:10%;">No</th> 
                                    <th class="center" style="width:25%;">Nama</th> 
                                    <th class="center" style="width:20%;">Email</th> 
                                    <th class="center" style="width:20%;">Created At</th> 
                                    <th class="center" style="width:25%;">Role</th> 
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
        </section>
    </div>

    @section('scripts')
        
    <script>
        $(document).ready(function() {
            $('#filter_tahun').select2({
                minimumResultsForSearch: Infinity
            });
            $('#filter_bulan').select2({
                minimumResultsForSearch: Infinity
            });
            tblMaster();
        });
        
        function tblMaster() {
            tableMaster = $('#tblMaster')
                .on('preXhr.dt', function(e, settings, data) {
                    data.tahun = Number($('#filter_tahun').val()) || null;
                    data.bulan = Number($('#filter_bulan').val()) || null;
                }).DataTable({
                    "iDisplayLength": 10,
                    responsive: false,
                    "order": [
                        [2, 'desc']
                    ],
                    "scrollX": false,
                    "scrollY": "410px",
                    scrollCollapse: true,
                    columnDefs: [ 
                    {
                        className: 'control',
                        orderable: false,
                        targets: 0,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                        },
                    ],
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    searching: true,
                    "oLanguage": {
                        'sProcessing': '<div id="processing" style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgb(102, 102, 102); z-index: 30001; opacity: 0.8;"><p style="position: absolute; color: White; top: 50%; left: 45%;"><img src="{{ asset('images/ajax-loader.gif') }}"></p></div>Processing...'
                    },
                    ajax: "{{ route('portalgtk.dttablehakakses') }}",
                    columns: [
                        {   name: null, 
                            data:null, 
                            className: "dt-center"
                        },
                        {
                            name: 'name',
                            data: 'name',
                            className: "dt-center"
                        },
                        {
                            name: 'email',
                            data: 'email',
                            className: "dt-center"
                        },
                        {
                            name: 'createdat',
                            data: 'createdat',
                            className: "dt-center"
                        },
                        {
                            name: 'role',
                            data: 'role',
                            className: "dt-center"
                        }
                    ],
                    drawCallback: function( settings ) {
                        $('.selectrole').select2({
                            minimumResultsForSearch: Infinity
                        });
                        $(".selectrole").on("change", function(){
                            var thisbox = $(this);
                            var id = $(this).attr("id_users");
                            var role = $(this).val();
                            var url = "{{ route('portalgtk.changehakakses', ['param1', 'param2']) }}";
                            url = url.replace("param1", window.btoa(id));
                            url = url.replace("param2", window.btoa(role));
                            thisbox.prop("disabled", true);
                            $.get(url, function(response) {
                                thisbox.prop("disabled", false);
                                if(response.status == "1"){
                                    swal("Success", response.msg, "success");
                                } else {
                                    swal("Info", response.msg, "warning");
                                }
                            });
                        })
                    }
                });
            return tableMaster;
            
        }
    </script>
    @endsection
@endsection