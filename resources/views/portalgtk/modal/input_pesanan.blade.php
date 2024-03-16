<div class="modal fade" id="modalTambahPesanan" tabindex="-1" role="dialog" aria-labelledby="modalTambahPesananLabel" aria-hidden="true" >
    <div class="modal-dialog modal-md">
        <div class="modal-content" style="border-radius: 10px;">
            <div class="modal-header" style="background-color: #3C8DBC; color: white; text-align: center;border-radius: 10px 10px 0 0;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" style="font-size: 30px; color: white">&times;</span></button>
                <h4 class="modal-title" id="modalTambahPesananLabel">Input Pesanan</h4>
            </div>
            {{-- Form Laravel/Collective --}}
            {!! Form::open(['url' => route('portalgtk.storepesanan'), 'method' => 'post', 'class' => 'form-horizontal', 'id' => 'form_id', 'enctype' => 'multipart/form-data']) !!}
            <div class="modal-body">
                {{-- Nama Pelanggan --}}
                <div class="form-group">
                    {!! Form::label('nama', 'Nama Pelanggan', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="nama" value="{{ Auth::user()->name }}" readonly>
                        <input type="hidden" name="idpelanggan" value="{{ Auth::user()->id }}">
                    </div>
                </div>
                {{-- Tanggal Pemesanan --}}
                <div class="form-group">
                    {!! Form::label('tglpesanan', 'Tanggal Pesanan', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-6">
                        <div class="input-group date">
                            <input type="text" id="tglpesanan" name="tglpesanan" class="form-control"
                                value="<?php echo date('d-m-Y', strtotime('today')); ?>" readonly />
                            <div class="input-group-addon" style="background-color: lightgrey">
                                <i class="fa fa-calendar" style="cursor: default;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Nama Produk --}}
                <div class="form-group">
                    {!! Form::label('produk', 'Nama Produk', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-6">
                        <select name="produk" id="produk" class="form-control" required style="width:100%">
                            <option value=""></option>
                            @foreach ($produk as $key => $p)
                                <option value="{{ $p->kode_produk }}">{{ $p->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- Jumlah Barang --}}
                <div class="form-group">
                    {!! Form::label('jumlah', 'Jumlah Barang', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-6">
                        <input type="number" name="jumlah" class="form-control" required>
                    </div>
                </div>
                {{-- Alamat --}}
                <div class="form-group">
                    {!! Form::label('alamat', 'Alamat', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-6">
                        <textarea name="alamat" class="form-control" id="alamat" rows="4" required maxlength="500"></textarea>
                    </div>
                </div>           
            </div>
            <div class="modal-footer" style="text-align: right;" >
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>