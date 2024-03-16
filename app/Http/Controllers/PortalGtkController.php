<?php

namespace App\Http\Controllers;

use App\PortalGtk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Facades\Datatables;

class PortalGtkController extends Controller
{
    #=================================================== Pelanggan ===========================================#
    
    public function indexpelanggan()
    {
        $r = new PortalGtk();
        $produk = $r->getProduk();
        if(Auth::user()->role != 'admin' && Auth::user()->role != 'operator') {
            return view('portalgtk.indexpelanggan', compact('produk'));
        }else{
            return view('errors.403');
        }
    }

    public function storepesanan(Request $request)
    {
        $r = new PortalGtk();
        $r->insertPesanan($request);
        return redirect()->route('portalgtk.indexpelanggan');
    }

    public function dttablepelanggan(Request $request)
    {
        $r = new PortalGtk();
        $st = $request->status;
        $request->id_pelanggan = Auth::user()->id;
        if($st == "0") {
            $d = $r->dttableBelumProses($request);
        }else if($st == "1") {
            $d = $r->dttableSudahProses($request);
        }else if($st == "2") {
            $d = $r->dttableSelesai($request);
        }else if($st == "3") {
            $d = $r->dttableDibatalkan($request);
        }
        
        return Datatables::of($d)
        ->editColumn('info', function ($d){
            $tgl = Carbon::parse($d->tgl_pesanan)->format('d-m-Y');
            return view('portalgtk.modal.info_pesanan', compact('d', 'tgl'));
        })
        ->editColumn('tgl_pesanan', function ($d){
            return Carbon::parse($d->tgl_pesanan)->format('d-m-Y');
        })
        ->editColumn('status_approve', function ($d){
            if($d->status_approve == null) {
                return "<b style='color:darkgray;'>" . "Belum Proses" . "</b>";
            }else if($d->status_approve == "Y") {
                return "<b style='color:dark;'>" . "Sedang Proses" . "</b>";
            }else if($d->status_approve == "N") {
                return "<b style='color:red;'>" . "Dibatalkan" . "</b>";
            }else{
                return "<b style='color:green;'>" . "Selesai" . "</b>";
            }
        })
        ->editColumn('hapus', function ($d){
            if ($d->status_approve === null){
                $parseId=base64_encode($d->id_pesanan);
                return "<button class='btn btn-xs btn-danger' onclick=deleteData('$parseId')><i class='glyphicon glyphicon-remove'></i></button>";
            }else{
                return "";
            }
        })
        ->make(true);
    }

    public function deletepesanan($id)
    {
        $id = base64_decode($id);
        $r = new PortalGtk();
        $r->hapusPesanan($id);
        return response()->json(['id' => $id, 'status' => 'OK', 'message' => "berhasil dihapus"]);
    }

    #=================================================== Admin ===============================================#

    public function approvepesanan()
    {
        if(Auth::user()->role == 'admin') {
            return view('portalgtk.approvepesanan');
        }else{
            return view('errors.403');
        } 
    }

    public function dttableapprovepesanan(Request $request)
    {
        $r = new PortalGtk();
        $st = $request->status;
        if($st == "0") {
            $d = $r->dttableBelumProsesAdmin($request);
        }else if($st == "1") {
            $d = $r->dttableSudahProsesAdmin($request);
        }else if($st == "2") {
            $d = $r->dttableSelesaiAdmin($request);
        }else if($st == "3") {
            $d = $r->dttableDibatalkanAdmin($request);
        }else{
            $d = $r->dttableSemuaAdmin($request);
        }
        
        return Datatables::of($d)
        ->editColumn('info', function ($d){
            $jml = $d->jml_barang;
            $ket = "";
            if($jml <= 24) {
                $ket = "1 Karton A";
                $c = 1;
                $b = 0;
            }else if($jml < 48) {
                $ket = "2 Karton A";
                $c = 2;
                $b = 0;
            }else if($jml >= 48) {
                $b = floor($jml/48);
                $mod = $jml % 24;
                if($mod > 0 && $mod <= 24 ) {
                    $a = "1 Karton A";
                    $c = 1;
                }else if($mod < 48) {
                    $a = "2 Karton A";
                    $c = 2;
                }else {
                    $a = null;
                    $c = 0;
                }

                if($a == null) {
                    $ket = $b." Karton B";
                }else{
                    $ket = $b." Karton B dan ".$a;
                }
            }

            $berat = ($c * 3.36) + ($b * 6.72) + ($c * 0.065) + ($b * 0.12);
            
            if($berat > 5000) {
                $mobil = "Fuso"; 
            }else if($berat > 2200) {
                $mobil = "CDD";
            }else{
                $mobil = "CDE";
            }
            return view('portalgtk.modal.approve_pesanan', compact('d', 'ket', 'mobil'));
        })
        ->editColumn('tgl_pesanan', function ($d){
            return Carbon::parse($d->tgl_pesanan)->format('d-m-Y');
        })
        ->editColumn('status_approve', function ($d){
            if($d->status_approve == null) {
                return "<b style='color:darkgray;'>" . "Belum Proses" . "</b>";
            }else if($d->status_approve == "Y") {
                return "<b style='color:dark;'>" . "Sedang Proses" . "</b>";
            }else if($d->status_approve == "N") {
                return "<b style='color:red;'>" . "Dibatalkan" . "</b>";
            }else{
                return "<b style='color:green;'>" . "Selesai" . "</b>";
            }
        })
        ->make(true);
    }

    public function submitapprovepesanan(Request $request)
    {
        $r = new PortalGtk();
        $r->submitApprovePesanan($request);
        return redirect()->route('portalgtk.approvepesanan');
    }

    public function printapprovalpesanan($tahun, $bulan)
    {
        $tahun = base64_decode($tahun);
        $bulan = base64_decode($bulan);
        $r = new PortalGtk();
        $data = $r->printApprovalPesan($tahun, $bulan);
        foreach($data as $key => $d) {
            $d->tglnew = Carbon::parse($d->tgl_pesanan)->format('d-m-Y');
        }
        $format = "xls";
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
        ob_end_clean();
        ob_start();
        Excel::create('Laporan Pesanan Bulan '.$bulan." Tahun ".$tahun,function($excel)use($data, $bulan, $tahun) {
            $excel->sheet('Main',function($sheet) use($data, $bulan, $tahun){
                $sheet->loadView("portalgtk.print",[
                    "data"=>$data,
                    "bulan"=>$bulan,
                    "tahun"=>$tahun
                ]);
            });
        })->export($format); 
    }

    public function monitoringstockbarang()
    {
        if(Auth::user()->role == 'admin') {
            return view('portalgtk.monitoringstock'); 
        }else{
            return view('errors.403');
        } 
    }

    public function hakaksesuser()
    {
        if(Auth::user()->role == 'admin') {
            return view('portalgtk.hakakses'); 
        }else{
            return view('errors.403');
        } 
    }

    public function dttablehakakses(Request $request)
    {
        $r = new PortalGtk();
        $d = $r->dttableHakAkses($request);
        foreach($d as $key => $val) {
            if($val->role == null) {
                $val->role = 'pelanggan';
            }
        }
        return Datatables::of($d)
        ->editColumn('createdat', function ($d){
            return Carbon::parse($d->created_at)->format('d-m-Y H:i:s');
        })
        ->editColumn('role', function ($d){
            if($d->role == "admin") {
                return "<select name='role' id='role' id_users='$d->id' class='selectrole form-control' style='width:100%'>
                    <option value='pelanggan'>Pelanggan</option>    
                    <option value='admin' selected>Admin</option>    
                    <option value='operator'>Operator</option>    
                </select>";
            }else if($d->role == "operator"){
                return "<select name='role' id='role' id_users='$d->id' class='selectrole form-control' style='width:100%'>
                    <option value='pelanggan'>Pelanggan</option>    
                    <option value='admin'>Admin</option>    
                    <option value='operator' selected>Operator</option>    
                </select>";
            }else{
                return "<select name='role' id='role' id_users='$d->id' class='selectrole form-control' style='width:100%'>
                    <option value='pelanggan' selected>Pelanggan</option>    
                    <option value='admin'>Admin</option>    
                    <option value='operator'>Operator</option>    
                </select>"; 
            }
            
        })
        ->make(true);
    }
    
    public function changehakakses($id, $val)
    {
        $id = base64_decode($id);
        $val = base64_decode($val);
        if($val == "pelanggan") {
            $val = null;
        }
        try {
            DB::beginTransaction();
            DB::connection('pgsql')
                ->table('users')
                ->where("id", $id)
                ->update([
                    'role' => $val
                ]);
            DB::commit();
            return response()->json(['status' => '1', 'msg' => 'Hak Akses Berhasil Diganti']);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => '0', 'msg' => 'Hak Akses Gagal Diganti']);
        } 
    }


    #================================================ Produksi =================================================#

    public function inputstockbarang()
    {
        $r = new PortalGtk();
        $produkAwal = $r->getStockProdukAwal();
        $produkIsi =  $r->getStockProdukIsi();
        if(count($produkIsi) > 0) {
            $produk = $produkIsi;
        }else{
            $produk = $produkAwal;
        }
        if(Auth::user()->role == 'operator') {
            return view('portalgtk.inputstock', compact('produk'));
        }else{
            return view('errors.403');
        } 
        
    }

    public function storestock(Request $request)
    {
        $r = new PortalGtk();
        try {
            DB::beginTransaction();

            $r->insertStockMaster($request);

            $id = $r->getIdMasterStock();

            $list=[];
            for($i=0;$i<count($request->kodeproduk); $i++) {
                $array = [ 
                    "id_stock" => $id,
                    "kode_produk" => $request->kodeproduk[$i] == ""? null : $request->kodeproduk[$i],
                    "stock_awal" => $request->awal[$i] == ""? null : $request->awal[$i],
                    "stock_masuk" => $request->masuk[$i] == ""? null : $request->masuk[$i],
                    "stock_keluar" => $request->keluar[$i] == ""? null : $request->keluar[$i],
                    "stock_total" => $request->total[$i] == ""? null : $request->total[$i]
                ];
                array_push($list, $array);
            }

            DB::connection("pgsql")->table('detail_stock_produk')->insert($list);

            DB::commit();
            Session::flash("flash_notification", [
                "level" => "success",
                "message" => "Stock Berhasil Disubmit."
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('flash_notification', [
                'level' => 'danger',
                'message' => "Gagal! $th"
            ]);
        }
        return redirect()->route('portalgtk.inputstockbarang');
    }

    public function dttablestock(Request $request)
    {
        $r = new PortalGtk();
        $d = $r->dttableStock($request);
        
        return Datatables::of($d)
        ->editColumn('info', function ($d){
            $r = new PortalGtk();
            $detail = $r->getDetaiStock($d);
            $tgl = Carbon::parse($d->dtcrea)->format('d-m-Y H:i:s');
            return view('portalgtk.modal.info_stock', compact('d', 'detail', 'tgl'));
        })
        ->editColumn('dtcrea', function ($d){
            return Carbon::parse($d->dtcrea)->format('d-m-Y H:i:s');
        })
        ->make(true);
    }
}
