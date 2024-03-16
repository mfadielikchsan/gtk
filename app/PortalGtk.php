<?php

namespace App;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class PortalGtk extends Model
{
    # Pelanggan
    public function getProduk()
    {
        return DB::connection('pgsql')
            ->table("master_produk")
            ->select("*")
            ->get();
    }

    public function insertPesanan($request)
    {
        try {
            DB::beginTransaction();
            DB::connection('pgsql')
                ->table('pesanan')
                ->insert([
                    'id_pelanggan' => $request->idpelanggan,
                    'tgl_pesanan' => Carbon::createFromFormat('d-m-Y', $request->tglpesanan)->format('Y-m-d'),
                    'kode_produk' => $request->produk,
                    'jml_barang' => $request->jumlah,
                    'alamat' => $request->alamat,
                    'dtcrea' => Carbon::now(new \DateTimeZone('Asia/Jakarta'))
                ]);
            DB::commit();
            Session::flash("flash_notification", [
                "level" => "success",
                "message" => "Pesanan Berhasil Disubmit."
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('flash_notification', [
                'level' => 'danger',
                'message' => "Gagal! $th"
            ]);
        }
    }

    public function dttableBelumProses($request)
    {
        return DB::connection('pgsql')
            ->table("pesanan")
            ->selectRaw("pesanan.*, master_produk.*, users.name nama_pelanggan")
            ->Join('master_produk', 'pesanan.kode_produk', '=', 'master_produk.kode_produk')
            ->Join('users', 'pesanan.id_pelanggan', '=', 'users.id')
            ->whereRaw("status_approve is null AND
                            EXTRACT(MONTH FROM tgl_pesanan) = $request->bulan 
                            and EXTRACT(YEAR FROM tgl_pesanan) = $request->tahun 
                            and id_pelanggan = '$request->id_pelanggan'
                        ")
            ->orderByRaw("dtcrea asc")
            ->get();
    }

    public function dttableSudahProses($request)
    {
        return DB::connection('pgsql')
            ->table("pesanan")
            ->selectRaw("pesanan.*, master_produk.*, users.name nama_pelanggan")
            ->Join('master_produk', 'pesanan.kode_produk', '=', 'master_produk.kode_produk')
            ->Join('users', 'pesanan.id_pelanggan', '=', 'users.id')
            ->whereRaw("status_approve = 'Y' AND
                            EXTRACT(MONTH FROM tgl_pesanan) = $request->bulan 
                            and EXTRACT(YEAR FROM tgl_pesanan) = $request->tahun 
                            and id_pelanggan = '$request->id_pelanggan'
                        ")
            ->orderByRaw("dtcrea asc")
            ->get();
    }

    public function dttableSelesai($request)
    {
        return DB::connection('pgsql')
            ->table("pesanan")
            ->selectRaw("pesanan.*, master_produk.*, users.name nama_pelanggan")
            ->Join('master_produk', 'pesanan.kode_produk', '=', 'master_produk.kode_produk')
            ->Join('users', 'pesanan.id_pelanggan', '=', 'users.id')
            ->whereRaw("status_approve = 'S' AND
                            EXTRACT(MONTH FROM tgl_pesanan) = $request->bulan 
                            and EXTRACT(YEAR FROM tgl_pesanan) = $request->tahun 
                            and id_pelanggan = '$request->id_pelanggan'
                        ")
            ->orderByRaw("dtcrea asc")
            ->get();
    }

    public function dttableDibatalkan($request)
    {
        return DB::connection('pgsql')
            ->table("pesanan")
            ->selectRaw("pesanan.*, master_produk.*, users.name nama_pelanggan")
            ->Join('master_produk', 'pesanan.kode_produk', '=', 'master_produk.kode_produk')
            ->Join('users', 'pesanan.id_pelanggan', '=', 'users.id')
            ->whereRaw("status_approve = 'N' AND
                            EXTRACT(MONTH FROM tgl_pesanan) = $request->bulan 
                            and EXTRACT(YEAR FROM tgl_pesanan) = $request->tahun 
                            and id_pelanggan = '$request->id_pelanggan'
                        ")
            ->orderByRaw("dtcrea asc")
            ->get();
    }

    public function hapusPesanan($id)
    {
        try {
            DB::beginTransaction();
            DB::connection('pgsql')
                ->table('pesanan')
                ->where('id_pesanan', '=', $id)
                ->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
        }
    }

    #============================================================================================================
    # Admin

    public function dttableBelumProsesAdmin($request)
    {
        return DB::connection('pgsql')
            ->table("pesanan")
            ->selectRaw("pesanan.*, master_produk.*, users.name nama_pelanggan")
            ->Join('master_produk', 'pesanan.kode_produk', '=', 'master_produk.kode_produk')
            ->Join('users', 'pesanan.id_pelanggan', '=', 'users.id')
            ->whereRaw("status_approve is null AND
                            EXTRACT(MONTH FROM tgl_pesanan) = $request->bulan 
                            and EXTRACT(YEAR FROM tgl_pesanan) = $request->tahun 
                        ")
            ->orderByRaw("dtcrea asc")
            ->get();
    }

    public function dttableSudahProsesAdmin($request)
    {
        return DB::connection('pgsql')
            ->table("pesanan")
            ->selectRaw("pesanan.*, master_produk.*, users.name nama_pelanggan")
            ->Join('master_produk', 'pesanan.kode_produk', '=', 'master_produk.kode_produk')
            ->Join('users', 'pesanan.id_pelanggan', '=', 'users.id')
            ->whereRaw("status_approve = 'Y' AND
                            EXTRACT(MONTH FROM tgl_pesanan) = $request->bulan 
                            and EXTRACT(YEAR FROM tgl_pesanan) = $request->tahun 
                        ")
            ->orderByRaw("dtcrea asc")
            ->get();
    }

    public function dttableSelesaiAdmin($request)
    {
        return DB::connection('pgsql')
            ->table("pesanan")
            ->selectRaw("pesanan.*, master_produk.*, users.name nama_pelanggan")
            ->Join('master_produk', 'pesanan.kode_produk', '=', 'master_produk.kode_produk')
            ->Join('users', 'pesanan.id_pelanggan', '=', 'users.id')
            ->whereRaw("status_approve = 'S' AND
                            EXTRACT(MONTH FROM tgl_pesanan) = $request->bulan 
                            and EXTRACT(YEAR FROM tgl_pesanan) = $request->tahun 
                        ")
            ->orderByRaw("dtcrea asc")
            ->get();
    }

    public function dttableDibatalkanAdmin($request)
    {
        return DB::connection('pgsql')
            ->table("pesanan")
            ->selectRaw("pesanan.*, master_produk.*, users.name nama_pelanggan")
            ->Join('master_produk', 'pesanan.kode_produk', '=', 'master_produk.kode_produk')
            ->Join('users', 'pesanan.id_pelanggan', '=', 'users.id')
            ->whereRaw("status_approve = 'N' AND
                            EXTRACT(MONTH FROM tgl_pesanan) = $request->bulan 
                            and EXTRACT(YEAR FROM tgl_pesanan) = $request->tahun 
                        ")
            ->orderByRaw("dtcrea asc")
            ->get();
    }

    public function dttableSemuaAdmin($request)
    {
        return DB::connection('pgsql')
            ->table("pesanan")
            ->selectRaw("pesanan.*, master_produk.*, users.name nama_pelanggan")
            ->Join('master_produk', 'pesanan.kode_produk', '=', 'master_produk.kode_produk')
            ->Join('users', 'pesanan.id_pelanggan', '=', 'users.id')
            ->whereRaw("EXTRACT(MONTH FROM tgl_pesanan) = $request->bulan 
                        and EXTRACT(YEAR FROM tgl_pesanan) = $request->tahun 
                        ")
            ->orderByRaw("dtcrea asc")
            ->get();
    }

    public function submitApprovePesanan($request)
    {
        try {
            DB::beginTransaction();
            DB::connection('pgsql')
                ->table('pesanan')
                ->whereRaw("id_pesanan = '$request->id'")
                ->update([
                    'status_approve' => $request->status == "" ? null : $request->status
                ]);
            DB::commit();
            Session::flash("flash_notification", [
                "level" => "success",
                "message" => "Status Pesanan Berhasil Disubmit."
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('flash_notification', [
                'level' => 'danger',
                'message' => "Gagal! $th"
            ]);
        }
    }

    public function printApprovalPesan($tahun, $bulan)
    {
        return DB::connection('pgsql')
        ->table("pesanan")
        ->select("*")
        ->Join('master_produk', 'pesanan.kode_produk', '=', 'master_produk.kode_produk')
        ->Join('users', 'pesanan.id_pelanggan', '=', 'users.id')
        ->whereRaw("EXTRACT(MONTH FROM tgl_pesanan) = $bulan 
                    and EXTRACT(YEAR FROM tgl_pesanan) = $tahun 
                    ")
        ->orderByRaw("dtcrea asc")
        ->get();
    }

    public function dttableHakAkses($request)
    {
        return DB::connection('pgsql')
            ->table("users")
            ->select("*")
            ->whereRaw("EXTRACT(MONTH FROM created_at) = $request->bulan 
                        and EXTRACT(YEAR FROM created_at) = $request->tahun 
                        ")
            ->orderByRaw("created_at desc")
            ->get();
    }

    #============================================================================================================
    # Operator

    public function insertStockMaster($request)
    { 
        return DB::connection('pgsql')
            ->table('master_stock_produk')
            ->insert([
                'creaby' => $request->nama,
                'dtcrea' => Carbon::now(new \DateTimeZone('Asia/Jakarta'))
            ]);     
    }

    public function getStockProdukAwal()
    {
        return DB::connection('pgsql')
        ->table(DB::raw("master_produk mp"))
        ->selectRaw("mp.*, 
                    case
                        when detail_stock_produk.stock_total  = null then '0'
                    else
                        '0'
                    end as stock_total")
        ->leftJoin("detail_stock_produk", "mp.kode_produk", "=", "detail_stock_produk.kode_produk")
        ->orderByRaw("kode_produk asc")
        ->get();
    }

    public function getStockProdukIsi()
    {
        return DB::connection('pgsql')
        ->table(DB::raw("master_produk mp"))
        ->selectRaw("mp.*, detail_stock_produk.stock_total ")
        ->leftJoin("detail_stock_produk", "mp.kode_produk", "=", "detail_stock_produk.kode_produk")
        ->whereRaw("detail_stock_produk.id_stock = (select max(id_stock) from detail_stock_produk)")
        ->orderByRaw("kode_produk asc")
        ->get();
    }

    public function getIdMasterStock()
    {
        return DB::connection('pgsql')
            ->table("master_stock_produk")
            ->selectRaw("max(id_stock) as id")
            ->value("id");
    }

    public function dttableStock($request)
    {
        return DB::connection('pgsql')
            ->table("master_stock_produk")
            ->select("*")
            ->whereRaw("EXTRACT(MONTH FROM dtcrea) = $request->bulan 
                        and EXTRACT(YEAR FROM dtcrea) = $request->tahun 
                        ")
            ->orderByRaw("dtcrea desc")
            ->get();
    }

    public function getDetaiStock($d)
    {
        return DB::connection('pgsql')
            ->table("detail_stock_produk")
            ->selectRaw("detail_stock_produk.*, master_produk.nama_produk")
            ->Join("master_produk", "detail_stock_produk.kode_produk", "=", "master_produk.kode_produk")
            ->whereRaw("id_stock = '$d->id_stock'")
            ->get();
    }

}
