@if(isset($user))
<center>
	<button class="btn btn-info btn-xs fa fa-info" data-toggle="modal" data-target="#modalShow_{{$id}}" data-toggle="tooltip" data-placement="bottom" title="Show Data"></button>
</center>
<div id="modalShow_{{$id}}" class="modal fade" role="dialog" data-backdrop="static">
	<div class="modal-dialog">   
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<center><h4 class="modal-title">INFO PERMINTAAN UNIFORM</h4></center>
			</div>
			<div class="modal-body">
		          @php
		          $status = $mobiles->status;;
		          if ($status == "1"){
		            $status = "Belum Approval Atasan";
		          } else if ($status == "2") {
		            $status = "Sudah Approval Atasan - Belum Approval GA";
		          } else if ($status == "3") {
		            $status = "Sudah Approval GA - Silakan Ambil Seragam di GA";
		          } else if ($status == "4") {
		            $status = "Sudah Approval Pengambilan";
		          }else if ($status == "5") {
		            $status = "Ditolak oleh Atasan!";
		          }
		          @endphp
				<table border="0" style="width: 100%">
					<tr>
						<th width="30%">No. Permintaan</th>
						<td width="1%">:</td>
						<td>{{$id}}</td>
					</tr>
					<tr>
						<th width="30%">Nama Karyawan</th>
						<td width="1%">:</td>
						<td>{{$v_mas_karyawan->where("npk", $mobiles->npk)->first()->nama}}</td>
					</tr>
					<tr>
						<th width="30%">Nama Atasan</th>
						<td width="1%">:</td>
						<td>{{$v_mas_atasan->where("npk", $mobiles->npkatasan)->first()->nama}}</td>
					</tr>
					<tr>
						<th width="30%">Tanggal Pengajuan</th>
						<td width="1%">:</td>
						<td>{{\Carbon\Carbon::parse($mobiles->tgluni)->format('d-M-Y')}}</td>
					</tr>
		            <tr>
		                <th>Status</th>
		                <td>:</td>
		                <td>{{ $status }}</td>
		            </tr>
				</table>
				<hr>
				<br>
				<table border="1" style="width:100%">
					<tr>
						<th rowspan="2" style="text-align: center;" width="2%">NO</th>
						<th rowspan="2" style="text-align: center;">ITEM</th>
						<th style="text-align: center;" colspan="3">QTY</th>
						<th rowspan="2" style="text-align: center;">TGL-ambil</th>
					</tr>
					<tr>
						<td style="text-align: center;" width="5%">MINTA</td>
						<td style="text-align: center;" width="5%">APPROVED</td>
						<td style="text-align: center;border-right: 1px solid grey;" width="5%" >AMBIL</td>
					</tr>
					@foreach($gat_tseragam2 as $data)
					<tr>
						<td>{{$loop->iteration}}</td>
						<td>{{$data->nm_uni}}</td>
						<td align="center">{{$data->qty_keb}}</td>
						@if($data->qty_approv == null)
						<td align="center">0</td>
						@else
						<td align="center">{{$data->qty_approv}}</td>
						@endif
						@if($data->qty_ambil == null)
						<td align="center">0</td>
						@else
						<td align="center">{{$data->qty_ambil}}</td>
						@endif
						@if($data->tglambil == null)
						<td align="center">-</td>
						@else
						<td align="center">{{date("d-M-Y", strtotime($data->tglambil))}}</td>
						@endif
					</tr>
					@endforeach
				</table>
			</div> 
			<div class="modal-footer">
				<center><button type="button" class="btn btn-default" data-dismiss="modal">TUTUP</button></center>
				<!-- </form> -->
			</div>
		</div>       
	</div>
</div>

@elseif(isset($ga))

<center>
	<button class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalEdit_{{$mobiles->kode_uni}}" data-toggle="tooltip" data-placement="bottom" title="Edit Data"><i class="fa fa-edit"></i></button>
	<button class="btn btn-sm btn-danger" onclick="hapus('{{$mobiles->kode_uni}}')" title="Hapus Data"><i class="fa fa-trash"></i></button>
</center>	


  <div id="modalEdit_{{$mobiles->kode_uni}}" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog">   
      <!-- Modal content-->
      <div class="modal-content">
        {!! Form::open(['url' => route('puniform.update', $mobiles->kode_uni), 'method' => 'put', 'class'=>'form-horizontal', 'id'=>'fedituniform_'.$mobiles->kode_uni]) !!}  
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <center><h4 class="modal-title">EDIT DATA UNIFORM</h4></center>
        </div>
        <div class="modal-body">
          <table border="0" style="width: 100%;">
            <tr>
              <th style="width: 30%; padding: 5px;">Kode Uniform</th>
              <td style="width: 2%; padding: 5px;">:</td>
              <td style="padding: 5px;"><input type="text" readonly style="width: 100%;" name="kode_uni_{{$mobiles->kode_uni}}" class="form-control select2" value="{{$mobiles->kode_uni}}" id="kode_uni_{{$mobiles->kode_uni}}" required></td>
            </tr>
            <tr>
              <th style="width: 30%; padding: 5px;">Jenis Uniform</th>
              <td style="width: 2%; padding: 5px;">:</td>
              <td style="padding: 5px;">
                  @foreach($mseragam1 as $data)
                  @if($data->kode == substr($mobiles->kode_uni,0,2))
	                <input type="text" value="{{$data->desc}}" readonly id="kode_{{$mobiles->kode_uni}}" name="kode_{{$mobiles->kode_uni}}" class="form-control select2" style="width:100%;" required>
                  @endif
                  @endforeach
              </td>
            </tr>
            <tr>
              <th style="width: 30%; padding: 5px;">Nama Uniform</th>
              <td style="width: 2%; padding: 5px;">:</td>
              <td style="padding: 5px;"><input type="text" style="width: 100%;" name="nm_uni_{{$mobiles->kode_uni}}" value="{{$mobiles->nm_uni}}" id="nm_uni_{{$mobiles->kode_uni}}" required></td>
            </tr>
            <tr>
              <th style="width: 30%; padding: 5px;">Penjelasan Uniform</th>
              <td style="width: 2%; padding: 5px;">:</td>
              <td style="padding: 5px;"><textarea name="desc_uni_{{$mobiles->kode_uni}}" style="width: 100%;" id="desc_uni_{{$mobiles->kode_uni}}" class="form-control" rows="4"  required>{{$mobiles->desc_uni}}</textarea></td>
            </tr>
          </table>
        </div> 
        {!! Form::close() !!}
        <div class="modal-footer">
          <button type="button" onclick="update('{{$mobiles->kode_uni}}')" class="btn btn-success"><i class="fa fa-paper-plane"></i> Update</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">TUTUP</button>
          <!-- </form> -->
        </div>
      </div>       
    </div>
  </div>

@endif