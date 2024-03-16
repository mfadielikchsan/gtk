@if(Auth::user()->role == null)
<li class="header">Pelanggan</li>
<li><a href="{{ route('portalgtk.indexpelanggan') }}"><i class="fa fa-shopping-cart"></i>Buat Pesanan</a></li>
@endif

@if(Auth::user()->role == "admin")
<li class="header">Admin</li>
<li class="treeview">
    <a href="#">
        <i class="fa fa-user"></i> <span>Menu Admin</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        <li><a href="{{ route('portalgtk.approvepesanan') }}"><i class="fa fa-check-square-o"></i>Approve Pesanan</a></li>
        <li><a href="{{ route('portalgtk.monitoringstockbarang') }}"><i class="fa fa-desktop"></i>Monitoring Stock Barang</a></li>
        <li><a href="{{ route('portalgtk.hakaksesuser') }}"><i class="fa fa-user-plus"></i>Hak Akses User</a></li>
        {{-- <li><a href="{{ route('secmonitoring.approval') }}"><i class="fa fa-th-list"></i>Master Produk</a></li> --}}
    </ul>
</li>
@endif

@if(Auth::user()->role == "operator")
<li class="header">Produksi</li>
<li class="treeview">
    <a href="#">
        <i class="fa fa-product-hunt"></i> <span>Menu Produksi</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        <li><a href="{{ route('portalgtk.inputstockbarang') }}"><i class="fa fa-plus-circle"></i>Input Stock Barang</a></li>
    </ul>
</li>
@endif