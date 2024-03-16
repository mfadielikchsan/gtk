@php
    $no = 0;
@endphp
<div class="modal fade" id="modalTambahStock" tabindex="-1" role="dialog" aria-labelledby="modalTambahStockLabel" aria-hidden="true" >
    <div class="modal-dialog modal-md">
        <div class="modal-content" style="border-radius: 10px;">
            <div class="modal-header" style="background-color: #3C8DBC; color: white; text-align: center;border-radius: 10px 10px 0 0;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" style="font-size: 30px; color: white">&times;</span></button>
                <h4 class="modal-title" id="modalTambahStockLabel">Input Stock</h4>
            </div>
            {{-- Form Laravel/Collective --}}
            {!! Form::open(['url' => route('portalgtk.storestock'), 'method' => 'post', 'class' => 'form-horizontal', 'id' => 'form_id', 'enctype' => 'multipart/form-data']) !!}
            <div class="modal-body">
                {{-- Nama Operator --}}
                <div class="form-group">
                    {!! Form::label('nama', 'Nama Operator', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="nama" value="{{ Auth::user()->name }}" readonly>
                    </div>
                </div>
                {{-- Tanggal Input --}}
                <div class="form-group">
                    {!! Form::label('tglinput', 'Tanggal Input', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-6">
                        <div class="input-group date">
                            <input type="text" id="tglinput" name="tglinput" class="form-control"
                                value="<?php echo date('d-m-Y', strtotime('today')); ?>" readonly />
                            <div class="input-group-addon" style="background-color: lightgrey">
                                <i class="fa fa-calendar" style="cursor: default;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Produk --}}
                <div style="margin-top:30px; max-height:350px; overflow-x: auto;">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="center" style="position: sticky;top: 0; background-color: white; vertical-align:middle;">Nama Produk</th>
                                <th class="center" style="width: 17%; position: sticky;top: 0; background-color: white; vertical-align:middle;">Stock Awal</th>
                                <th class="center" style="width: 17%; position: sticky;top: 0; background-color: white; vertical-align:middle;">Stock Masuk</th>
                                <th class="center" style="width: 17%; position: sticky;top: 0; background-color: white; vertical-align:middle;">Stock Keluar</th>
                                <th class="center" style="width: 17%; position: sticky;top: 0; background-color: white; vertical-align:middle;">Total Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($produk as $p)
                                <input type="hidden" value="{{ $no = $no+1 }}">
                                <tr>
                                    <td style="vertical-align:middle;">{{ $p->nama_produk }}</td>
                                    <input type="hidden" name="kodeproduk[]" value="{{ $p->kode_produk }}">
                                    <td><input name="awal[]" id="awal{{ $no }}" class="form-control center" type="text" style="width: 100%" value="{{ $p->stock_total }}" readonly></td>
                                    <td><input name="masuk[]" id="masuk{{ $no }}" class="form-control center" type="number" style="width: 100%" onchange="getTotalStock({{ $no }})" autocomplete="off" required></td>
                                    <td><input name="keluar[]" id="keluar{{ $no }}" class="form-control center" type="number" style="width: 100%" onchange="getTotalStock({{ $no }})" autocomplete="off" required></td>
                                    <td><input name="total[]" id="total{{ $no }}" class="form-control center" type="text" style="width: 100%" readonly></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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