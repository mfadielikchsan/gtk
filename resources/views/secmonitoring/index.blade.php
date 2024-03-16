@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Security Monitoring
                <small>Status Security Monitoring</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Dashboard</li>
            </ol>
        </section>
        <section class="content">
            @include('layouts._flash')
            <div class="row">
                <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <div class="box-header with-border">
                            <h3 class="box-title">Security Monitoring</h3><small> (Y1A1NRiz51)</small>
                        </div>
                        <br>
                        <a class="btn btn-primary" href="{{ route('secmonitoring.create') }}" id="buttonAdd"><span class="fa fa-plus"></span> Input</a>
                        {{-- <br><br>
                        <div class="alert alert-info" style="margin-bottom:0px;">
                            <strong>Perhatian!</strong>
                            <ul>
                                <li>Hubungi Atasan anda, infokan bahwa anda akan cuti.</li>
                                <li>Pastikan Cuti anda disetujui Atasan dengan melihat Status di Aplikasi ini. </li> 
                                <li>Setelah Disetujui, Cetak cuti ini agar Status tidak berubah lagi. </li>	
                            </ul>
                        </div> --}}
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        
                        <table id="tblMaster" class="table table-bordered table-striped" cellspacing="0" style="width: 100%;max-width: 100%">
                            <thead>
                            <tr>
                                <th class="all" style="width:05%;"> </th> 
                                <th class="all" style="width:30%;">Tanggal</th> 
                                <th class="all" style="width:20%;">Status Monitoring</th> 
                                <th class="all" style="width:20%">Approve</th> 
                                <th class="all" style="width:05%"> </th> 
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
            let tableMaster = $('#tblMaster').DataTable( {
                "iDisplayLength": 10,
                responsive: true,
                "order": [[1, 'desc']],
                "scrollX": true,
                columnDefs: [ 
                    {
                    className: 'control',
                    orderable: false,
                    targets:  []
                    },
                ],
                processing: true,
                serverSide:true, 
                destroy: true, 
                searching: false, 
                "oLanguage": {
                    'sProcessing': '<div id="processing" style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgb(102, 102, 102); z-index: 30001; opacity: 0.8;"><p style="position: absolute; color: White; top: 50%; left: 45%;"><img src="{{ asset('images/ajax-loader.gif') }}"></p></div>Processing...'
                }, 
                ajax: "{{ route('secmonitoring.dashboard') }}",
                columns: [
                    { name: 'info',data:'info', className: "dt-center"},
                    { name: 'tglmonitor',data:'tglmonitor', className: "dt-center"},
                    { name: 'statusmonitoring',data:'statusmonitoring', className: "dt-center"},
                    { name: 'tglapprdh',data:'tglapprdh', className: "dt-center"},
                    { name: 'action',data:'action', className: "dt-center"},
                ],
                // "initComplete": function(){
                //     $('#tblMaster tbody').on('click', 'tr', function() {
                //         if ($(this).hasClass('selected')) {
                //             $(this).removeClass('selected');
                //         } else {
                //             tableMaster.$('tr.selected').removeClass('selected');
                //             $(this).addClass('selected');
                //         }
                //     })
                //     $('#tblMaster tbody').on('dblclick', 'tr', function() {
                //         let data = tableMaster.row(this).data();
                //         // console.log(data);
                //         document.location = document.location.href+'/'+btoa(data.no_doc);
                //     })
                    // $('#table-dropdown').change(function(){
                    // if ($('#table-dropdown').val() == 0) {
                    //     tableMaster.ajax.url("{{ route('joborder.listjoborder') }}").load();
                    // }else if($('#table-dropdown').val() == 1){
                    //     tableMaster.ajax.url("{{ route('joborder.listjoborderDone') }}").load();
                    // }else{
                    //     tableMaster.ajax.url("{{ route('joborder.listjoborderCancel') }}").load();
                    // }
                    // })
                // }
            } );
        } );
        
        
    </script>
    @endsection
@endsection