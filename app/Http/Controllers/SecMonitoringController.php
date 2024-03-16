<?php

namespace App\Http\Controllers;

use App\SecMonitoring;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\File;
class SecMonitoringController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('secmonitoring.index');
    }

    public function approval()
    {
        return view('secmonitoring.approval');
    }

    public function infosubmit($id_monitoring)
    {
        $r = new SecMonitoring();
        $id_monitoring = base64_decode($id_monitoring);
        
        //select data
        $secmon = $r->getDataSecmon($id_monitoring);
        $karyawan = $r->masKaryawan($secmon->npkdh);

        // dd($secmon8);
        return view('secmonitoring.submit', compact('karyawan', 'secmon'));
        
    }

    public function infodashboard($id_monitoring)
    {
        $r = new SecMonitoring();
        $id_monitoring = base64_decode($id_monitoring);
        
        //select data
        $secmon = $r->getDataSecmon($id_monitoring);
        $karyawan = $r->masKaryawan($secmon->npkdh);

        // dd($secmon);
        return view('secmonitoring.info', compact('karyawan', 'secmon'));
        
    }

    public function approvesubmit($id_monitoring)
    {
        $r = new SecMonitoring();
        $id_monitoring = base64_decode($id_monitoring);
        
        //select data
        $secmon = $r->getDataSecmon($id_monitoring);
        $karyawan = $r->masKaryawan($secmon->npkdh);

        // dd($secmon);
        return view('secmonitoring.approvesubmit', compact('karyawan', 'secmon'));
        
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $npkKar = "08268";
        $r = new SecMonitoring;
        $npkDephead = $r->masKaryawan($npkKar)->npk_dep_head;
        $dephead = $r->masKaryawan($npkDephead);
        $id_secmon = $r->getNewNoDoc();

        return view('secmonitoring.create', compact('dephead', 'id_secmon', 'npkKar'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $r = new SecMonitoring();
        $r->storeSecMonitoring($request);
        return redirect()->route('secmonitoring.infosubmit',[base64_encode($request->id_monitoring)]);
    }

    public function dashboard(Request $request)
    {
        $r = new SecMonitoring();
        $d = $r->getDataAllSecMon();

        return Datatables::of($d)
        ->editColumn('tglmonitor', function ($d){
            return Carbon::parse($d->tglmonitor)->format('d-m-Y');
        })
        ->editColumn('tglapprdh', function ($d){
            if ($d->tglapprdh != null) {
                return 'OK';
            }else{
                return 'Not Yet';
            }
        })
        ->editColumn('statusmonitoring', function ($d) {
            return $d->statusmonitoring==="Y"?"<b style='color:green;'>"."OK"."</b>":"<b style='color:red;'>"."Not OK"."</b>";
        })
        ->addColumn('info', function ($d) {
            return '<a href="'. route('secmonitoring.infodashboard', [base64_encode($d->id_monitoring)]) .'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-info-sign"></i></a>';
        })
        ->addColumn('action', function ($d) {
            if ($d->tglapprdh === null){
                $action = '<a href="'. route('secmonitoring.destroy', [base64_encode($d->id_monitoring)]) .'" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-remove"></i></a>';
                return $action;
            }
        })
        ->make(true);
    }

    public function dashboardapproval()
    {
        $r = new SecMonitoring();
        $d = $r->getDataAllSecMon();

        return Datatables::of($d)
        ->editColumn('tglmonitor', function ($d){
            return Carbon::parse($d->tglmonitor)->format('d-m-Y');
        })
        ->editColumn('tglapprdh', function ($d){
            if ($d->tglapprdh != null) {
                return Carbon::parse($d->tglmonitor)->format('d-m-Y');
            }else{
                return 'Belum Approve';
            }
        })
        ->editColumn('statusmonitoring', function ($d) {
            return $d->statusmonitoring==="Y"?"<b style='color:green;'>"."OK"."</b>":"<b style='color:red;'>"."Not OK"."</b>";
        })
        ->addColumn('info', function ($d) {
            return '<a href="'. route('secmonitoring.approvesubmit', [base64_encode($d->id_monitoring)]) .'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-info-sign"></i></a>';
        })
        ->make(true);
    }

    public function fetchbelumapprove()
    {
        $r = new SecMonitoring();
        $d = $r->fetchDataBelumApprove();

        return Datatables::of($d)
        ->editColumn('tglmonitor', function ($d){
            return Carbon::parse($d->tglmonitor)->format('d-m-Y');
        })
        ->editColumn('tglapprdh', function ($d){
            if ($d->tglapprdh != null) {
                return Carbon::parse($d->tglmonitor)->format('d-m-Y');
            }else{
                return 'Belum Approve';
            }
        })
        ->editColumn('statusmonitoring', function ($d) {
            return $d->statusmonitoring==="Y"?"<b style='color:green;'>"."OK"."</b>":"<b style='color:red;'>"."Not OK"."</b>";
        })
        ->addColumn('info', function ($d) {
            return '<a href="'. route('secmonitoring.approvesubmit', [base64_encode($d->id_monitoring)]) .'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-info-sign"></i></a>';
        })
        ->make(true);
    }

    public function fetchsudahapprove()
    {
        $r = new SecMonitoring();
        $d = $r->fetchDataSudahApprove();

        return Datatables::of($d)
        ->editColumn('tglmonitor', function ($d){
            return Carbon::parse($d->tglmonitor)->format('d-m-Y');
        })
        ->editColumn('tglapprdh', function ($d){
            if ($d->tglapprdh != null) {
                return Carbon::parse($d->tglmonitor)->format('d-m-Y');
            }else{
                return 'Belum Approve';
            }
        })
        ->editColumn('statusmonitoring', function ($d) {
            return $d->statusmonitoring==="Y"?"<b style='color:green;'>"."OK"."</b>":"<b style='color:red;'>"."Not OK"."</b>";
        })
        ->addColumn('info', function ($d) {
            return '<a href="'. route('secmonitoring.approvesubmit', [base64_encode($d->id_monitoring)]) .'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-info-sign"></i></a>';
        })
        ->make(true);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $r = new SecMonitoring();
        $hapus = $r->hapus(base64_decode($id));
        return redirect()->route('secmonitoring.index');

    }
}