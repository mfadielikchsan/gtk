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
                Form Pemesanan Produk
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li>Pelanggan</li>
                <li class="active">Form Pemesanan Produk</li>
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
                                    <h3 class="box-title">Input Pesanan</h3>
                                </div>
                                <br>
                                <button data-toggle="modal" data-target="#modalTambahPesanan" type="button" class="btn btn-primary"><i class="fa fa-plus"></i> Input Pesanan</button>
                                @include('portalgtk.modal.input_pesanan')    
                            </div>
                            <div class="col-sm-3 form-group">
                                {!! Form::label('table-dropdown', 'Status') !!}
                                <select name="table-dropdown" id="table-dropdown" class="form-control select2"
                                    style="width: 100%;margin: 0 0 10px 0;" onchange="tblMaster()">
                                    <option value="0">Belum Proses</option>
                                    <option value="1">Sedang Proses</option>
                                    <option value="2">Selesai</option>
                                    <option value="3">Dibatalkan</option>
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
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body"> 
                            <table id="tblMaster" class="table table-bordered table-striped" cellspacing="0" style="width:100%;table-layout:fixed">
                                <thead id="theadMaster">
                                <tr>
                                    <th class="center" style="width:05%;"> </th> 
                                    <th class="center" style="width:18%;">Tanggal</th> 
                                    <th class="center" style="width:30%;">Nama Produk</th> 
                                    <th class="center" style="width:18%;">Jumlah (pcs)</th> 
                                    <th class="center" style="width:20%">Status</th> 
                                    <th class="center" style="width:05%"> </th> 
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
            $('#table-dropdown').select2({
                minimumResultsForSearch: Infinity
            });
            $('#filter_tahun').select2({
                minimumResultsForSearch: Infinity
            });
            $('#filter_bulan').select2({
                minimumResultsForSearch: Infinity
                });
            $('#produk').select2({
                placeholder: "Pilih Produk",
                minimumResultsForSearch: Infinity
            });
            document.getElementById("form_id").addEventListener("submit", (e) => {
                e.preventDefault()
                validate()
            });
            tblMaster();
        });

        
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
                    ajax: "{{ route('portalgtk.dttablepelanggan') }}",
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
                        },
                        {
                            name: 'hapus',
                            data: 'hapus',
                            className: "dt-center"
                        }
                    ]
                });
            return tableMaster;
        }

        function validate() {
            swal({
                title: 'Submit Input Pesanan',
                text: 'Apakah anda yakin untuk mengsubmit?',
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                allowEnterKey: true,
                reverseButtons: false,
                focusCancel: false,
                allowOutsideClick: false,
            }).then(function() {
                $("#form_id").submit()
            }).catch(swal.noop)
        }

        function deleteData(id){
            swal({
                title: 'Hapus Pesananan',
                text: 'Apakah anda yakin untuk menghapus?',
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                allowEnterKey: true,
                reverseButtons: false,
                focusCancel: false,
                allowOutsideClick: false,
            }).then(function(){
                let url='{{ route('portalgtk.deletepesanan',['dummyId']) }}'
                url = url.replace('dummyId',id);
                var CSRF = $('meta[name="csrf-token"]').attr("content")
                $.ajax(
                    {
                        url: url,
                        type: 'delete',
                        dataType: "JSON",
                        data: {
                            "id": id,
                            "_method": 'DELETE',
                            "_token": CSRF,
                        },
                        success: function (data)
                        {
                            if(data.status === "OK"){
                                swal("Hapus Berhasil","", "success");
                            }else{
                                swal("Hapus Gagal","", "warning");
                            }
                            tableMaster.ajax.reload()
                        }
                    });
            }).catch(swal.noop)
        }

    </script>
    @endsection
@endsection