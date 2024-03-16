<button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#modalInfo{{ $d->id_stock }}" type="button"><i class="glyphicon glyphicon-info-sign"></i></button>

<div class="modal fade" id="modalInfo{{ $d->id_stock }}" tabindex="-1" role="dialog" aria-labelledby="modalInfo{{ $d->id_stock }}Label" aria-hidden="true" >
    <div class="modal-dialog modal-md">
        <div class="modal-content" style="border-radius: 10px;">
            <div class="modal-header" style="background-color: #3C8DBC; color: white; text-align: center;border-radius: 10px 10px 0 0;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" style="font-size: 30px; color: white">&times;</span></button>
                <h4 class="modal-title" id="modalTambahStockLabel">Info Stock</h4>
            </div>
            {{-- Form Laravel/Collective --}}
            {!! Form::open(['class' => 'form-horizontal', 'id' => 'form_id', 'enctype' => 'multipart/form-data']) !!}
            <div class="modal-body">
                {{-- Nama Operator --}}
                <div class="row" style="margin-top: 20px;">
                    <div class="col-sm-12 form-group">
                        <div class="col-sm-4">
                            {!! Form::label('nama', 'Nama Operator', ['class' => 'control-label']) !!}
                        </div>
                        <div class="col-sm-8">
                            <input type="text" style="width: 100%;" class="form-control" name="nama" value="{{ $d->creaby }}" readonly>
                        </div>
                    </div>
                </div>
                {{-- Tanggal Input Stock --}}
                <div class="row" style="margin-top: 20px;">
                    <div class="col-sm-12 form-group">
                        <div class="col-sm-4">
                            {!! Form::label('tglstock', 'Tanggal Input Stock', ['class' => 'control-label']) !!}
                        </div>
                        <div class="col-sm-8">
                            <div class="input-group date" style="width: 100%;">
                                <input type="text" id="tglstock" name="tglstock" class="form-control"
                                    value="{{ $tgl }}" readonly />
                                <div class="input-group-addon" style="background-color: lightgrey">
                                    <i class="fa fa-calendar" style="cursor: default;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Tabel --}}
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
                            @foreach ($detail as $d)
                                <tr>
                                    <td style="vertical-align:middle;">{{ $d->nama_produk }}</td>
                                    <td><input class="form-control center" type="text" style="width: 100%" value="{{ $d->stock_awal }}" readonly></td>
                                    <td><input class="form-control center" type="number" style="width: 100%" value="{{ $d->stock_masuk }}" readonly></td>
                                    <td><input class="form-control center" type="number" style="width: 100%" value="{{ $d->stock_keluar }}" readonly></td>
                                    <td><input class="form-control center" type="text" style="width: 100%" value="{{ $d->stock_total }}" readonly></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div> 
            </div>
            <div class="modal-footer" style="text-align: right;margin-right:25px;" >
                <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>