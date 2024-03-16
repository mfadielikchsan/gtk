<button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#modalApprove{{ $d->id_pesanan }}" type="button"><i class="glyphicon glyphicon-info-sign"></i></button>

<div class="modal fade" id="modalApprove{{ $d->id_pesanan }}" tabindex="-1" role="dialog" aria-labelledby="modalApprove{{ $d->id_pesanan }}Label" aria-hidden="true" >
    <div class="modal-dialog modal-md">
        <div class="modal-content" style="border-radius: 10px;">
            <div class="modal-header" style="background-color: #3C8DBC; color: white; text-align: center;border-radius: 10px 10px 0 0;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" style="font-size: 30px; color: white">&times;</span></button>
                <h4 class="modal-title" id="modalTambahPesananLabel">Approve Pesanan</h4>
            </div>
            {{-- Form Laravel/Collective --}}
            {!! Form::open(['url' => route('portalgtk.submitapprovepesanan'), 'method' => 'post', 'class' => 'form-horizontal', 'id' => 'form_id', 'enctype' => 'multipart/form-data']) !!}
                <div class="modal-body">
                    {{-- Nama Pelanggan --}}
                    <div class="row" style="margin-top: 20px;">
                        <div class="col-sm-12 form-group">
                            <div class="col-sm-4">
                                {!! Form::label('nama', 'Nama Pelanggan', ['class' => 'control-label']) !!}
                            </div>
                            <div class="col-sm-8">
                                <input type="text" style="width: 100%;" class="form-control" value="{{ $d->nama_pelanggan }}" readonly>
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
                                    <input type="text" id="tglpesanan" class="form-control"
                                        value="<?php echo date('d-m-Y', strtotime('today')); ?>" readonly />
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
                    {{-- Keterangan Karton --}}
                    <div class="row" style="margin-top: 20px;">
                        <div class="col-sm-12 form-group">
                            <div class="col-sm-4">
                                {!! Form::label('Ket', 'Jumlah Karton', ['class' => 'control-label']) !!}
                            </div>
                            <div class="col-sm-8">
                                <input type="text" style="width: 100%;" class="form-control" readonly value="{{ $ket }}">
                            </div>
                        </div>
                    </div>  
                    {{-- Jenis Kendaraan --}}
                    <div class="row" style="margin-top: 20px;">
                        <div class="col-sm-12 form-group">
                            <div class="col-sm-4">
                                {!! Form::label('jenis', 'Jenis Kendaraan', ['class' => 'control-label']) !!}
                            </div>
                            <div class="col-sm-8">
                                <input type="text" style="width: 100%;" class="form-control" readonly value="{{ $mobil }}">
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
                                <textarea readonly style="width: 100%;" class="form-control" id="alamat" rows="4" required maxlength="500">{{ $d->alamat }}</textarea>
                            </div>
                        </div>
                    </div> 
                    {{-- Status --}}
                    <div class="row" style="margin-top: 20px;">
                        <div class="col-sm-12 form-group">
                            <div class="col-sm-4">
                                {!! Form::label('status', 'Status', ['class' => 'control-label']) !!}
                            </div>
                            <div class="col-sm-8">
                                <select name="status" id="status" class="form-control select2"
                                    style="width: 100%;margin: 0 0 10px 0;">
                                    <option value="" @if($d->status_approve == null) selected @endif>Belum Proses</option>
                                    <option value="Y" @if($d->status_approve == "Y") selected @endif>Sedang Proses</option>
                                    <option value="S" @if($d->status_approve == "S") selected @endif>Selesai</option>
                                    <option value="N" @if($d->status_approve == "N") selected @endif>Dibatalkan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="id" value="{{ $d->id_pesanan }}">
                </div>
                <div class="modal-footer" style="text-align: right; margin-right:25px;">
                    <button type="submit" class="btn btn-success">Submit</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>