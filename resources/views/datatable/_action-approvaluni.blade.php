<button class="btn btn-primary" data-toggle="modal" data-target="#detail{{ $row->nouni }}"><span class="fa fa-info"></span></button>

<!-- Modal -->
<div id="detail{{ $row->nouni }}" class="modal fade" role="dialog">
    <div class="modal-dialog">
      
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Detail Permintaan Uniform</h4>
        </div>
        <div class="modal-body">
          @foreach ($data_detail as $detail)
          @php
          $status = $detail->status;
          $nouni = $detail->nouni;
          $tgluni = $detail->tgluni;
          if ($status == "1"){
            $status = "Belum Approval Atasan";
          } else if ($status == "2") {
            $status = "Sudah Approval Atasan - Belum Approval GA";
          } else if ($status == "3") {
            $status = "Sudah Approval GA - Silakan Ambil Seragam di GA";
          } else if ($status == "4") {
            $status = "Sudah Approval Pengambilan";
          }
          @endphp
          @endforeach
          <div class="header">
            <table class="table">
              <tr>
                <td class="bold">No. Permintaan</td>
                <td>:</td>
                <td>{{ $nouni }}</td>
              </tr>
              <tr>
                <td class="bold">Nama Karyawan</td>
                <td>:</td>
                <td>{{ $namakaryawan }}</td>
              </tr>
              <tr>
                <td class="bold">Nama Atasan</td>
                <td>:</td>
                <td>{{ $namaatasan }}</td>
              </tr>
              <tr>
                <td class="bold">Tanggal Pengajuan</td>
                <td>:</td>
                <td>{{ date("d-M-Y", strtotime($tgluni)) }}</td>
              </tr>
              <tr>
                <td class="bold">Status</td>
                <td>:</td>
                <td>{{ $status }}</td>
              </tr>
            </table>
          </div>
          <div class="body">
            <table id="table-detail" class="table table-bordered table-striped">
              <tr>
                <th style="text-align: center;">No</th>
                <th>Item</th>
                <th style="text-align: center;">Qty</th>
                <th>Alasan</th>
              </tr>
              @foreach ($data_detail as $detail)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $detail->nm_uni }}</td>
                <td>{{ $detail->qty_keb }}</td>
                @if($detail->alasan == "1" || strtolower($detail->alasan) === "rusak")
                <td>Rusak</td>
                @elseif($detail->alasan == "2" || strtolower($detail->alasan) === "hilang")
                <td>Hilang</td>
                @else
                <td>Jatuh Tempo</td>
                @endif
              </tr>
              @endforeach
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button onclick="approve('{{$row->nouni}}')" class="btn btn-success" title="Approve Data">Approve</button>
          <button onclick="reject('{{$row->nouni}}')" class="btn btn-danger" title="Reject Data">Reject</button>
          {{-- <button id="reject({{$row->nouni}})" class="btn btn-danger" title="Approve Data">Approve</button> --}}
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>