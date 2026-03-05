@extends('layouts.backend')

@section('title','Kasir POS')

@section('content')

<div class="row">

    {{-- ================= MENU AREA ================= --}}
    <div class="col-md-8">
        <div class="row">

            @foreach($menus as $menu)
            <div class="col-md-3 mb-4">

                <div class="card shadow-sm h-100 menu-item" data-id="{{ $menu->id_makanan }}" data-nama="{{ $menu->nama }}"
                    data-harga="{{ (int) $menu->harga }}" data-gambar="{{ asset('storage/'.$menu->gambar) }}"
                    data-sub='@json($menu->subMakanans ?? [])'>

                    <img src="{{ asset('storage/'.$menu->gambar) }}" class="card-img-top"
                        style="height:140px; object-fit:cover;">

                    <div class="card-body text-center p-2">

                        <h6 class="font-weight-bold mb-1">
                            {{ $menu->nama }}
                        </h6>

                        <small class="text-primary font-weight-bold d-block mb-2">
                            Rp {{ number_format($menu->harga) }}
                        </small>

                        <button class="btn btn-warning btn-sm rounded-circle" style="width:35px; height:35px;"
                            onclick="openModal(this)">
                            <i class="fa fa-plus text-white"></i>
                        </button>

                    </div>
                </div>

            </div>
            @endforeach

        </div>
    </div>


    {{-- ================= KERANJANG ================= --}}
    <div class="col-md-4">
        <div class="card shadow">

            <div class="card-header">
                <h5 class="mb-0">Keranjang</h5>
            </div>

            <div class="card-body">
                <div class="form-group">
                    <input type="text" id="scan-barcode" class="form-control"
                        placeholder="Scan barcode pesanan..." autofocus>
                </div>

                <div id="cart-list"></div>
                @if(isset($transaksi))

                    @foreach($transaksi->detailTransaksi as $item)
                        <div class="cart-item">
                            {{ $item->makanan->nama }}
                            x {{ $item->qty }}
                            <span>Rp {{ number_format($item->harga) }}</span>
                        </div>
                    @endforeach

                @endif

                <hr>

                <div class="form-group">
                    <label>Total</label>
                    <input type="text" id="cart-total" class="form-control font-weight-bold text-primary" readonly>
                </div>

                <button type="button" class="btn btn-success btn-block" onclick="handleCheckout()">
                    Simpan Pesanan
                </button>

            </div>

        </div>
    </div>

</div>


{{-- ================= MODAL ================= --}}
<div class="modal fade" id="menuModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Tambah Menu</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="text-center mb-3">
                    <img id="modalGambar" src="" class="img-fluid rounded" style="max-height:120px;">
                </div>

                <h5 id="modalNama"></h5>
                <p class="text-primary font-weight-bold" id="modalHarga"></p>

                <hr>

                <h6 class="font-weight-bold">Varian</h6>
                <div id="levelOptions"></div>

                <button class="btn btn-primary btn-block mt-3" onclick="addToCart()">
                    Masukkan ke Keranjang
                </button>

            </div>

        </div>
    </div>
</div>

@endsection



@push('scripts')
<script>
    let currentItem = null;
    let cart = [];

    function openModal(button) {

        let item = button.closest('.menu-item');
        currentItem = item;

        let nama = item.dataset.nama;
        let harga = parseInt(item.dataset.harga);
        let gambar = item.dataset.gambar;

        let subs = [];

        try {
            subs = JSON.parse(item.dataset.sub);
        } catch (e) {
            subs = [];
        }

        $('#modalNama').text(nama);
        $('#modalHarga').text("Rp " + harga.toLocaleString());
        $('#modalGambar').attr('src', gambar);

        let container = $('#levelOptions');
        container.html("");

        if (subs.length > 0) {

            subs.forEach(function (sub, index) {

                let tambahanHarga = parseInt(sub.tambahan_harga || 0);

                container.append(`
                    <div class="form-check mb-2">
                        <input class="form-check-input"
                            type="radio"
                            name="sub_makanan"
                            value="${sub.id}"
                            data-nama="${sub.nama}"
                            data-harga="${tambahanHarga}"
                            ${index === 0 ? 'checked' : ''}>
                        <label class="form-check-label">
                            ${sub.nama} (+Rp ${tambahanHarga.toLocaleString()})
                        </label>
                    </div>
                `);

            });

        } else {

            container.html(`
            <div class="alert alert-light text-center mb-0">
                Tidak ada varian untuk menu ini
            </div>
        `);

        }

        $('#menuModal').modal('show');
    }


    function addToCart(){

    let id = currentItem.dataset.id;
    let nama = currentItem.dataset.nama;
    let harga = parseInt(currentItem.dataset.harga);

    let sub = $('#menuModal input[name="sub_makanan"]:checked');

    let subId = sub.val() || 0;
    let subNama = sub.data('nama') || '';
    let tambahanHarga = parseInt(sub.data('harga') || 0);

    let finalHarga = harga + tambahanHarga;

    // KEY UNIK
    let key = id + "-" + subId + "-" + tambahanHarga;

    let item = cart.find(i => i.key == key);

    if(item){
        item.qty++;
    }else{
        cart.push({
            key:key,
            id:id,
            nama:nama,
            sub:subNama,
            harga:finalHarga,
            qty:1
        });
    }

    renderCart();
    $('#menuModal').modal('hide');
}


    function renderCart(){
        let html = "";
        let total = 0;

        cart.forEach((item,index)=>{
            let subtotal = item.qty * item.harga;
            total += subtotal;

            html += `
                <div class="mb-2 border-bottom pb-2">
                    <div>
                        <strong>${item.nama}</strong> x ${item.qty}
                        <span class="float-right">
                            Rp ${subtotal.toLocaleString()}
                        </span>
                    </div>
                    ${item.sub ? `<small class="text-muted">Varian: ${item.sub}</small>` : ''}
                </div>
            `;
        });

        $('#cart-list').html(html);
        $('#cart-total').val("Rp " + total.toLocaleString());
    }


    function increaseQty(index) {
        cart[index].qty += 1;
        renderCart();
    }

    function decreaseQty(index) {
        if (cart[index].qty > 1) {
            cart[index].qty -= 1;
        } else {
            cart.splice(index, 1);
        }
        renderCart();
    }

    function removeItem(index) {
        cart.splice(index, 1);
        renderCart();
    }
    function checkout(e){

        if(e) e.preventDefault();

        if(cart.length == 0){
            alert("Keranjang kosong");
            return;
        }

        let btn = $('.btn-success');
        btn.prop('disabled', true).text('Memproses...');

        $.ajax({
            url:"/backend/transaksi/kasir/checkout",
            type:"POST",
            data:{
                _token:"{{ csrf_token() }}",
                cart:cart
            },
            success:function(res){
                console.log(res);
                window.location = "/backend/transaksi/pembayaran/" + res.kode;
            },
            error: function() {
                // Jika gagal, aktifkan kembali tombolnya
                btn.prop('disabled', false).text('Simpan Pesanan');
                alert("Terjadi kesalahan.");
            }
        });
    }

    function handleCheckout() {
        let kode = $('#scan-barcode').val();

        // 1. Jika ada input Barcode, prioritaskan cari berdasarkan Barcode
        if (kode !== "") {
            window.location.href = "/backend/transaksi/kasir/barcode/" + kode;
        } 
        // 2. Jika input Barcode kosong, maka proses checkout keranjang (AJAX)
        else {
            // Panggil fungsi checkout() yang sudah Anda buat sebelumnya
            checkout(); 
        }
    }

    // Event saat menekan ENTER di input barcode
    $('#scan-barcode').on('keyup', function(e) {
        if (e.which == 13) {
            handleCheckout();
        }
    });
    // Otomatis fokus ke kotak scan saat halaman dimuat
    $(document).ready(function() {
        $('#scan-barcode').focus();
    });

    // Jika user klik di mana saja, kembalikan fokus ke kotak scan (opsional)
    $(document).click(function() {
        $('#scan-barcode').focus();
    });
</script>
@endpush
