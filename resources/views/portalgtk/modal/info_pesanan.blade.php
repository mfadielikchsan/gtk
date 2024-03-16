<button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#modalInfo{{ $d->id_pelanggan }}" type="button"><i class="glyphicon glyphicon-info-sign"></i></button>

<div class="modal fade" id="modalInfo{{ $d->id_pelanggan }}" tabindex="-1" role="dialog" aria-labelledby="#modalInfo{{ $d->id_pelanggan }}Label" aria-hidden="true" >
    <div class="modal-dialog modal-md">
        <div class="modal-content" style="border-radius: 10px;">
            <div class="modal-header" style="background-color: #3C8DBC; color: white; text-align: center;border-radius: 10px 10px 0 0;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" style="font-size: 30px; color: white">&times;</span></button>
                <h4 class="modal-title" id="modalTambahPesananLabel">Info Pesanan</h4>
            </div>
            {{-- Form Laravel/Collective --}}
            {!! Form::open(['class' => 'form-horizontal', 'id' => 'form_id', 'enctype' => 'multipart/form-data']) !!}
            <div class="modal-body">
                {{-- Status --}}
                <div class="row">
                    <div class="col-sm-12 form-group">
                        <div class="col-sm-4">
                            {!! Form::label('nama', 'Status Pesanan', ['class' => 'control-label']) !!}
                        </div>
                        <div class="col-sm-8">
                            @if($d->status_approve == null)
                                <input type="text" style="width: 100%; color:darkgrey;" class="form-control" name="nama" value="Belum Proses" readonly>
                            @elseif ($d->status_approve == "Y")
                                <input type="text" style="width: 100%;" class="form-control" name="nama" value="Sudah Proses" readonly>
                            @elseif ($d->status_approve == "S")
                                <input type="text" style="width: 100%; color:green;" class="form-control" name="nama" value="Selesai" readonly>
                            @else
                                <input type="text" style="width: 100%; color:red;" class="form-control" name="nama" value="Dibatalkan" readonly>
                            @endif
                        </div>
                    </div>
                </div>
                {{-- Nama Pelanggan --}}
                <div class="row" style="margin-top: 20px;">
                    <div class="col-sm-12 form-group">
                        <div class="col-sm-4">
                            {!! Form::label('nama', 'Nama Pelanggan', ['class' => 'control-label']) !!}
                        </div>
                        <div class="col-sm-8">
                            <input type="text" style="width: 100%;" class="form-control" name="nama" value="{{ $d->nama_pelanggan }}" readonly>
                        </div>
                    </div>
                </div>
                {{-- Tanggal Pemesanan --}}
                <div class="row" style="margin-top: 20px;">
                    <div class="col-sm-12 form-group">
                        <div class="col-sm-4">
                            {!! Form::label('tglpesanan', 'Tanggal Pesanan', ['class' => 'control-label']) !!}
                        </div>
                        <div class="col-sm-8">
                            <div class="input-group date" style="width: 100%;">
                                <input type="text" id="tglpesanan" name="tglpesanan" class="form-control"
                                    value="{{ $tgl }}" readonly />
                                <div class="input-group-addon" style="background-color: lightgrey">
                                    <i class="fa fa-calendar" style="cursor: default;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Nama Produk --}}
                <div class="row" style="margin-top: 20px;">
                    <div class="col-sm-12 form-group">
                        <div class="col-sm-4">
                            {!! Form::label('produk', 'Nama Produk', ['class' => 'control-label']) !!}
                        </div>
                        <div class="col-sm-8">
                            <input type="text" style="width: 100%;" class="form-control" readonly value="{{ $d->nama_produk }}">
                        </div>
                    </div>
                </div>
                {{-- Jumlah Barang --}}
                <div class="row" style="margin-top: 20px;">
                    <div class="col-sm-12 form-group">
                        <div class="col-sm-4">
                            {!! Form::label('jumlah', 'Jumlah Barang', ['class' => 'control-label']) !!}
                        </div>
                        <div class="col-sm-8">
                            <input type="text" style="width: 100%;" class="form-control" readonly value="{{ $d->jml_barang }}">
                        </div>
                    </div>
                </div>  
                {{-- Alamat --}}
                <div class="row" style="margin-top: 20px;">
                    <div class="col-sm-12 form-group">
                        <div class="col-sm-4">
                            {!! Form::label('alamat', 'Alamat', ['class' => 'control-label']) !!}
                        </div>
                        <div class="col-sm-8">
                            <textarea readonly style="width: 100%;" name="alamat" class="form-control" id="alamat" rows="4" required maxlength="500">{{ $d->alamat }}</textarea>
                        </div>
                    </div>
                </div> 
            </div>
            <div class="modal-footer" style="text-align: right;margin-right:25px;" >
                <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>