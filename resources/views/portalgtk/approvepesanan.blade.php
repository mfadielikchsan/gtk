@extends('layouts.app')
@section('content')
<style>
    table.dataTable #theadMaster {background-color:#3C8DBC; color: white}
</style>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Approve Pesanan
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li>Admin</li>
                <li class="active">Approve Pesanan</li>
            </ol>
        </section>
        <section class="content">
            @include('layouts._flash')
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <div class="box-header with-border">
                                <h3 class="box-title">Approval Pesanan</h3>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="col-md-12" style="margin-bottom:25px;">
                                <div class="col-sm-3 form-group">
                                    {!! Form::label('table-dropdown', 'Status') !!}
                                    <select name="table-dropdown" id="table-dropdown" class="form-control select2"
                                        style="width: 100%;margin: 0 0 10px 0;" onchange="tblMaster()">
                                        <option value="0">Belum Proses</option>
                                        <option value="1">Sedang Proses</option>
                                        <option value="2">Selesai</option>
                                        <option value="3">Dibatalkan</option>
                                        <option value="4">Semua</option>
                                    </select>
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
                                <div class="col-sm-2 form-group">
                                    <button type="button" class="btn btn-success" id="print" style="width:100%; margin-top:25px;">Export Excel</button>
                                </div>                           
                            </div>
                            <div>
                                <table id="tblMaster" class="table table-bordered table-striped" cellspacing="0" style="width:100%;table-layout:fixed">
                                    <thead id="theadMaster">
                                        <tr>
                                            <th class="all" style="width:05%;"> </th>
                                            <th class="all" style="width:15%;">Tanggal Pesanan</th>
                                            <th class="all" style="width:25%;">Nama Pelanggan</th>
                                            <th class="all" style="width:25%;">Nama Produk</th>
                                            <th class="all" style="width:10%;">Jumlah Barang</th>
                                            <th class="all" style="width:15%;">Status Approve</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
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
    <script>
        let tableMaster= null;
        $(document).ready(function() {
            tblMaster();
            $('#table-dropdown').select2({
                minimumResultsForSearch: Infinity
            });
            $('#filter_tahun').select2({
                minimumResultsForSearch: Infinity
            });
            $('#filter_bulan').select2({
                minimumResultsForSearch: Infinity
            });

            $('#print').hide();

            $('#print').on('click', function() {
                let tahun = $('#filter_tahun').val();
                let bulan = $('#filter_bulan').val();
                let url = '{{ route('portalgtk.printapprovalpesanan', ['param1', 'param2']) }}'
                url = url.replace('param1', btoa(tahun));
                url = url.replace('param2', btoa(bulan));
                window.open(url, '_blank');
            }); 
           
        });

        $('#table-dropdown').on('change', function() {
            if ($('#table-dropdown').val() == '0' || $('#table-dropdown').val() == '1' || $('#table-dropdown').val() == '2' || $('#table-dropdown').val() == '3') {
                $('#print').hide();
            } else if ($('#table-dropdown').val() == '4') {
                $('#print').show();
            }
        })

        function tblMaster() {
            tableMaster = $('#tblMaster')
                .on('preXhr.dt', function(e, settings, data) {
                    data.status = $('#table-dropdown').val()
                    data.tahun = Number($('#filter_tahun').val()) || null;
                    data.bulan = Number($('#filter_bulan').val()) || null;
                }).DataTable({
                    "iDisplayLength": 10,
                    responsive: false,
                    "order": [
                        [1, 'desc']
                    ],
                    "scrollX": true,
                    "scrollY": "410px",
                    scrollCollapse: true,
                    columnDefs: [{
                        className: 'control',
                        orderable: false,
                        targets: 0,
                    }, ],
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    searching: true,
                    "oLanguage": {
                        'sProcessing': '<div id="processing" style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgb(102, 102, 102); z-index: 30001; opacity: 0.8;"><p style="position: absolute; color: White; top: 50%; left: 45%;"><img src="{{ asset('images/ajax-loader.gif') }}"></p></div>Processing...'
                    },
                    ajax: "{{ route('portalgtk.dttableapprovepesanan') }}",
                    columns: [{
                            name: 'info',
                            data: 'info',
                            className: "dt-center"
                        },
                        {
                            name: 'tgl_pesanan',
                            data: 'tgl_pesanan',
                            className: "dt-center"
                        },
                        {
                            name: 'nama_pelanggan',
                            data: 'nama_pelanggan',
                            className: "dt-center"
                        },
                        {
                            name: 'nama_produk',
                            data: 'nama_produk',
                            className: "dt-center"
                        },
                        {
                            name: 'jml_barang',
                            data: 'jml_barang',
                            className: "dt-center"
                        },
                        {
                            name: 'status_approve',
                            data: 'status_approve',
                            className: "dt-center"
                        }
                    ],
                    initComplete:()=>{
                        $(".select2").select2({
                            minimumResultsForSearch: Infinity
                        })
                    }
                });
            return tableMaster;
        };
    </script>
@endsection
