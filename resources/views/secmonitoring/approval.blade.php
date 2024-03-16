@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Security Monitoring
                <small>Approval Security Monitoring</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Dashboard</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                    <div class="box-header with-border">
                        <h3 class="box-title">Approval Security Monitoring</h3><small> (Y1A1NRiz51)</small>
                    </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div style="margin-bottom: 30px;">
                            <select autocomplete="off" class="form-control" style="width:200px;float:left;margin: 0px 12px 10px 0px;" id="table-dropdown">           
                                <option value="0">Belum Approve</option>
                                <option value="1">Sudah Approve</option>
                                <option value="2">Semua</option>
                            </select>
                        </div>
                        
                        <div>
                            <table id="tblMaster" class="table table-bordered table-striped" cellspacing="0" style="width:100%;max-width: 100%">
                                <thead>
                                <tr>
                                    <th class="all" style="width:05%;"> </th> 
                                    <th class="all" style="width:35%;">Tanggal Monitoring</th> 
                                    <th class="all" style="width:35%;">Tanggal Approve</th> 
                                    <th class="all" style="width:25%;">Status Monitoring</th> 
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
    $(document).ready(function() {
        tblMaster_BelumApproval();
    } );
    function tblMaster_BelumApproval() {
        let tableMaster = $('#tblMaster').DataTable( {
            "iDisplayLength": 10,
            responsive: true,
            "order": [[1, 'desc']],
            "scrollX": true,
            columnDefs: [ 
                {
                className: 'control',
                orderable: false,
                targets:  0,
                },
            ],
            processing: true,
            serverSide:true, 
            destroy: true, 
            searching: false, 
            "oLanguage": {
                'sProcessing': '<div id="processing" style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgb(102, 102, 102); z-index: 30001; opacity: 0.8;"><p style="position: absolute; color: White; top: 50%; left: 45%;"><img src="{{ asset('images/ajax-loader.gif') }}"></p></div>Processing...'
            }, 
            ajax: "{{ route('secmonitoring.fetchbelumaprove') }}",
            columns: [
                { name: 'info',data:'info', className: "dt-center"},
                { name: 'tglmonitor',data:'tglmonitor', className: "dt-center"},
                { name: 'tglapprdh',data:'tglapprdh', className: "dt-center"},
                { name: 'statusmonitoring',data:'statusmonitoring', className: "dt-center"}
            ],
            "initComplete": function(){
            //     $('#tblMaster tbody').on('click', 'tr', function() {
            //         if ($(this).hasClass('selected')) {
            //             $(this).removeClass('selected');
            //         } else {
            //             tableMaster.$('tr.selected').removeClass('selected');
            //             $(this).addClass('selected');
            //         }
            //     });
            //     $('#tblMaster tbody').on('dblclick', 'tr', function() {
            //         let data = tableMaster.row(this).data();
            //         // console.log(data);
            //         document.location = document.location.href+'/'+btoa(data.no_doc);
            //     });
                $('#table-dropdown').change(function(){
                    if ($('#table-dropdown').val() == 0) {
                        tableMaster.ajax.url("{{ route('secmonitoring.fetchbelumaprove') }}").load();
                    }else if($('#table-dropdown').val() == 1){
                        tableMaster.ajax.url("{{ route('secmonitoring.fetchsudahapprove') }}").load();
                    }else{
                        tableMaster.ajax.url("{{ route('secmonitoring.dashboardapproval') }}").load();
                    }
                })
            }
     } );
    }
    
    
</script>
@endsection