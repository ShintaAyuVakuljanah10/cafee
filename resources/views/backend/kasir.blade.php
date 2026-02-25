@extends('layouts.backend')

@section('title','Kasir POS')

@section('content')
<div class="container-fluid">
    <div class="row">

        {{-- ===== MENU AREA ===== --}}
        <div class="col-md-8">
            <div class="row">
                @foreach($menus as $menu)
                <div class="col-md-3 mb-3">
                    <div class="card menu-card"
                            data-id="{{ $menu->id }}"
                            data-nama="{{ $menu->nama }}"
                            data-harga="{{ $menu->harga }}"
                            data-sub='@json($menu->sub_menus)'>
                         
                        <img src="{{ asset('storage/'.$menu->gambar) }}"
                             class="card-img-top"
                             style="height:150px; object-fit:cover;">

                        <div class="card-body text-center">
                            <h6>{{ $menu->nama }}</h6>
                            <small>Rp {{ number_format($menu->harga) }}</small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ===== KERANJANG ===== --}}
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header">
                    <h5>Keranjang</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('backend.transaksi.store') }}" method="POST">
                        @csrf

                        <div id="cart-list"></div>

                        <hr>

                        <div class="mb-2">
                            <label>Total</label>
                            <input type="text" id="total" name="total" class="form-control" readonly>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Simpan Transaksi
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- Modal Sub Menu -->
<div class="modal fade" id="subMenuModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Sub Menu</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="sub-menu-list">
                <!-- Sub menu muncul di sini -->
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    let cart = [];
    let selectedMenu = null;

    function renderCart(){
        let html = '';
        let total = 0;

        cart.forEach((item, index) => {
            let subtotal = item.qty * item.harga;
            total += subtotal;

            html += `
                <div class="border-bottom mb-2 pb-2">
                    <strong>${item.nama}</strong>
                    <div class="d-flex justify-content-between">
                        <input type="hidden" name="menu_id[]" value="${item.id}">
                        <input type="hidden" name="qty[]" value="${item.qty}">
                        
                        <span>${item.qty} x ${item.harga}</span>
                        <span>${subtotal}</span>
                    </div>
                    <button type="button" class="btn btn-sm btn-danger mt-1"
                        onclick="removeItem(${index})">Hapus</button>
                </div>
            `;
        });

        document.getElementById('cart-list').innerHTML = html;
        document.getElementById('total').value = total;
    }

    function removeItem(index){
        cart.splice(index,1);
        renderCart();
    }

    $(document).on('click','.menu-card', function(){

        selectedMenu = $(this);

        let subMenus = @json($menus);

        let menuId = $(this).data('id');

        let menuData = subMenus.find(m => m.id == menuId);

        let html = '';
        
        if(menuData.sub_menus && menuData.sub_menus.length > 0){

            menuData.sub_menus.forEach(sub => {
                html += `
                    <div class="border p-2 mb-2 sub-menu-item"
                        data-id="${sub.id}"
                        data-nama="${sub.nama}"
                        data-harga="${sub.harga}">
                        
                        <strong>${sub.nama}</strong><br>
                        <small>Rp ${sub.harga}</small>
                    </div>
                `;
            });

            $('#sub-menu-list').html(html);
            $('#subMenuModal').modal('show');

        } else {
            addToCart(
                $(this).data('id'),
                $(this).data('nama'),
                $(this).data('harga')
            );
        }

    });
    $(document).on('click','.sub-menu-item', function(){

        let id = $(this).data('id');
        let nama = $(this).data('nama');
        let harga = $(this).data('harga');

        addToCart(id,nama,harga);

        $('#subMenuModal').modal('hide');
    });
    function addToCart(id,nama,harga){

        let existing = cart.find(item => item.id == id);

        if(existing){
            existing.qty += 1;
        }else{
            cart.push({
                id:id,
                nama:nama,
                harga:harga,
                qty:1
            });
        }

        renderCart();
    }
</script>
@endsection