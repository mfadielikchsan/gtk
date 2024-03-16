<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SecMonitoring extends Model
{
    public function masKaryawan($npk)
    {
        $mas_karyawan = DB::connection('pgsql')
            ->table("v_mas_karyawan")
            ->select(DB::raw("npk, nama, kd_pt, desc_dep,kd_jab, desc_div, email, kode_dep, kode_div, no_hp, kode_sie, npk_atasan, kode_gol, desc_jab, tgl_lahir, tgl_masuk_gkd, coalesce(substr(foto,18),'-.jpg') foto, npk_sec_head, npk_dep_head, npk_div_head, tgl_keluar, kode_site, kelamin"))
            ->where("npk", "=", $npk)
            ->first();

        return $mas_karyawan;
    }

    public function getDataSecMon($id)
    {
        $data_secmon = DB::connection('pgsql')
            ->table("ite_secmonitor")
            ->select(DB::raw("*"))
            ->where("id_monitoring", "=", $id)
            ->first();

        return $data_secmon;
    }

    public function getDataAllSecMon()
    {
        $data_secmon = DB::connection('pgsql')
            ->table("ite_secmonitor")
            ->select(DB::raw("*"))
            ->get();

        return $data_secmon;
    }

    public function storeSecMonitoring(Request $request)
    {
        // dd($request->tgl_monitoring, $request->all());
        try {
            DB::beginTransaction();
            DB::connection('pgsql')
                ->table('ite_secmonitor')
                ->insert([
                    'id_monitoring' => $request->id_monitoring,
                    'npkpic' => $request->npkpic,
                    'tglmonitor' => Carbon::now('Asia/Jakarta'),
                    'dtcrea' => Carbon::now(),
                    'npkdh' => substr($request->nama,0,5),
                    'jammonitoring' => $request->jam_monitoring,
                    'statusmonitoring' => $request->status,
                    'ketproblem' => $request->keterangan,
                    'tindakan' => $request->tindakan
                    
                ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            Session::flash('flash_notification', [
                'level'=>'danger',
                'message'=>"Gagal! $th"
            ]);
        }
    }

    public function infosubmit(Request $request)
    {

    }

    public function fetchDataBelumApprove()
    {
        $data_secmon = DB::connection('pgsql')
            ->table("ite_secmonitor")
            ->select(DB::raw("*"))
            ->where("tglapprdh", "=", null)
            ->get();

        return $data_secmon;
    }

    public function fetchDataSudahApprove()
    {
        $data_secmon = DB::connection('pgsql')
            ->table("ite_secmonitor")
            ->select(DB::raw("*"))
            ->where("tglapprdh", "!=", null)
            ->get();

        return $data_secmon;
    }

    // get last data insert
    public static function getLastData()
    {
        $data = DB::connection('pgsql')
            ->table('ite_secmonitor')
            ->select(DB::raw("*"))
            ->orderBy('id_monitoring', 'desc')
            ->limit('1')
            ->first();
        if ($data) {
            return $data;
        } else {
            return null;
        }
    }
    // Generate nomor dokumen baru
    public static function getNewNoDoc()
    {
        $secmon = static::getLastData();
        $year = date("y");
        if ($secmon != null) {
            $kode_doc = substr($secmon->id_monitoring, 4, 6);
            $kode_doc_int = (int)$kode_doc;
            $yearLast = substr($secmon->id_monitoring, 2, 2);
            if ($year == $yearLast) {
                $kode_doc_int = $kode_doc_int + 1;
            } else {
                $kode_doc_int = 1;
            }
        } else {
            $kode_doc_int = 1;
        }

        $kode_doc_new = str_pad((string)$kode_doc_int, 6, "0", STR_PAD_LEFT);

        $no_doc_new = "SM" . $year . $kode_doc_new;
        // dd($no_doc_new);
        // $no_doc = $data[0]->no_doc;
        return $no_doc_new;
    }
   
    public function hapus($id_monitoring)
    {
        try {
            DB::beginTransaction();
            DB::connection('pgsql')
                ->table('ite_secmonitor')
                ->where('id_monitoring', '=', $id_monitoring)
                ->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('flash_notification', [
                'level'=>'danger',
                'message'=>"Gagal! $th"
            ]);
        }
    }

}
