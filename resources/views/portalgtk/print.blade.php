@php
    $no=0;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>

    <table>
        <tr>
            <th colspan="6" style="background-color: #FFFFFF; border:1px solid;"><span style="vertical-align: middle; text-align: center;">Laporan Pemesanan Bulan {{ $bulan }} Tahun {{ $tahun }}</span></th>
        </tr>
    </table>
    
    <table id="detail-table">
        <thead>
            
            <tr>
                <th style="background-color: #f2f2f2; border:1px solid #000;"><span style="vertical-align: middle; text-align: center;">No</span></th>
                <th style="background-color: #f2f2f2; border:1px solid #000;"><span style="vertical-align: middle; text-align: center;">Tanggal Pesan</span></th>
                <th style="background-color: #f2f2f2; border:1px solid #000;"><span style="vertical-align: middle; text-align: center;">Nama Pelanggan</span></th>
                <th style="background-color: #f2f2f2; border:1px solid #000;"><span style="vertical-align: middle; text-align: center;">Nama Produk</span></th>
                <th style="background-color: #f2f2f2; border:1px solid #000;"><span style="vertical-align: middle; text-align: center;">Jumlah Barang</span></th>
                <th style="background-color: #f2f2f2; border:1px solid #000;"><span style="vertical-align: middle; text-align: center;">Status</span></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td  style="background-color: #FFFFFF; border:1px solid #000;"><span style="vertical-align: middle; text-align: center;">{{$no = $no + 1}}</span></th>
                    <td  style="background-color: #FFFFFF; border:1px solid #000;"><span style="vertical-align: middle; text-align: center;">{{$item->tglnew}}</span></th>
                    <td  style="background-color: #FFFFFF; border:1px solid #000;"><span style="vertical-align: middle; text-align: center;">{{$item->name}}</span></th>
                    <td  style="background-color: #FFFFFF; border:1px solid #000;"><span style="vertical-align: middle; text-align: center;">{{$item->nama_produk}}</span></th>
                    <td  style="background-color: #FFFFFF; border:1px solid #000;"><span style="vertical-align: middle; text-align: center;">{{$item->jml_barang}}</span></th>
                    @if($item->status_approve == null)
                        <td  style="background-color: #FFFFFF; border:1px solid #000;"><span style="vertical-align: middle; text-align: center; font-weight: bold; color:#A9A9A9;">Belum Proses</span></td>
                    @elseif($item->status_approve == "Y")
                        <td  style="background-color: #FFFFFF; border:1px solid #000;"><span style="vertical-align: middle; text-align: center; font-weight: bold; color:	#000000;">Sedang Proses</span></td>
                    @elseif($item->status_approve == "S")
                        <td  style="background-color: #FFFFFF; border:1px solid #000;"><span style="vertical-align: middle; text-align: center; font-weight: bold; color:#008000;">Selesai</span></td>
                    @else
                        <td  style="background-color: #FFFFFF; border:1px solid #000;"><span style="vertical-align: middle; text-align: center; font-weight: bold; color:#FF0000;">Dibatalkan</span></td>
                    @endif
                </tr>    
            @endforeach
        </tbody> 
    </table>
</body>

</html>
