@extends('layouts.frontend')

@section('content')

<!-- Menu Start -->
<div class="container-xxl py-5">
    <div class="container">

        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h5 class="section-title ff-secondary text-center text-primary fw-normal">
                Food & Drink Menu
            </h5>
            <h1 class="mb-5">Most Popular Items</h1>
        </div>

        <div class="tab-class text-center wow fadeInUp" data-wow-delay="0.1s">

            <!-- Category Tabs -->
            <ul class="nav nav-pills d-inline-flex justify-content-center border-bottom mb-5">
                @foreach($categories as $key => $category)
                <li class="nav-item">
                    <a class="d-flex align-items-center text-start mx-3 pb-3 
                        {{ $key == 0 ? 'active' : '' }}"
                        data-bs-toggle="pill"
                        href="#tab-{{ $category->id }}">

                        <i class="fa fa-utensils fa-2x text-primary"></i>

                        <div class="ps-3">
                            <small class="text-body">Category</small>
                            <h6 class="mt-n1 mb-0">{{ $category->name }}</h6>
                        </div>
                    </a>
                </li>
                @endforeach
            </ul>

            <!-- Tab Content -->
            <div class="tab-content">
                @foreach($categories as $key => $category)
                <div id="tab-{{ $category->id }}"
                    class="tab-pane fade show p-0 {{ $key == 0 ? 'active' : '' }}">

                    <div class="row g-4">

                        @forelse($category->makanans as $makanan)

                        <div class="col-lg-6 mb-4">
                            <div class="menu-item d-flex align-items-center justify-content-between"
                                data-id="{{ $makanan->id_makanan }}"
                                data-nama="{{ $makanan->nama }}"
                                data-harga="{{ $makanan->harga }}"
                                data-gambar="{{ asset('storage/'.$makanan->gambar) }}"
                                data-sub='@json($makanan->subMakanans)'>

                                <a href="{{ route('menu.detail', $makanan->id_makanan) }}"
                                   class="d-flex align-items-center text-decoration-none text-dark flex-grow-1">

                                    <img class="img-fluid rounded"
                                        src="{{ asset('storage/'.$makanan->gambar) }}"
                                        style="width:85px; height:85px; object-fit:cover">

                                    <div class="ms-3">
                                        <h5 class="mb-1 fw-bold">
                                            {{ $makanan->nama }}
                                        </h5>

                                        <small class="text-muted d-block mb-2" style="max-width:250px;">
                                            {{ $makanan->deskripsi }}
                                        </small>

                                        <span class="text-primary fw-bold fs-5">
                                            Rp {{ number_format($makanan->harga) }}
                                        </span>
                                    </div>

                                </a>

                                <!-- Button -->
                                <div class="text-end ms-3">
                                    <button type="button"
                                        class="btn btn-warning btn-sm rounded-circle shadow-sm"
                                        style="width:35px; height:35px;"
                                        onclick="openModal(this); event.stopPropagation();">
                                        <i class="bi bi-plus text-white"></i>
                                    </button>
                                </div>

                            </div>
                        </div>

                        @empty
                        <div class="col-12 text-center">
                            <p>Belum ada menu di kategori ini</p>
                        </div>
                        @endforelse

                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</div>
<!-- Menu End -->


<!-- Modal -->
<div class="modal fade" id="menuModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4">

            <div class="modal-header">
                <h5 class="modal-title">Tambahkan Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="text-center mb-3">
                    <img id="modalGambar" src="" class="img-fluid rounded"
                        style="max-height:150px; object-fit:cover;">
                </div>

                <h5 id="modalNama"></h5>
                <p class="text-primary fw-bold" id="modalHarga"></p>

                <hr>

                <h6 class="fw-bold">Varian (pilih 1)</h6>
                <div id="levelOptions"></div>

                <button class="btn btn-primary w-100 mt-4" onclick="addToCart()">
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
    let modal = new bootstrap.Modal(document.getElementById('menuModal'));

    function openModal(button) {
        let item = button.closest('.menu-item');
        currentItem = item;

        let nama = item.dataset.nama;
        let harga = item.dataset.harga;
        let gambar = item.dataset.gambar;
        let subs = JSON.parse(item.dataset.sub);

        document.getElementById('modalNama').innerText = nama;
        document.getElementById('modalHarga').innerText =
            "Rp " + Number(harga).toLocaleString();
        document.getElementById('modalGambar').src = gambar;

        let levelContainer = document.getElementById('levelOptions');
        levelContainer.innerHTML = "";

        if (subs.length > 0) {
            subs.forEach((sub, index) => {
                levelContainer.innerHTML += `
                    <div class="form-check mb-2">
                        <input class="form-check-input"
                            type="radio"
                            name="sub_makanan"
                            value="${sub.id_sub_makanan}"
                            ${index === 0 ? "checked" : ""}>
                        <label class="form-check-label">
                            ${sub.nama} - Rp ${Number(sub.tambahan_harga).toLocaleString()}
                        </label>
                    </div>
                `;
            });
        } else {
            levelContainer.innerHTML += `
                <div class="form-check mb-2">
                    <input class="form-check-input"
                        type="radio"
                        name="sub_makanan"
                        value="${sub.id}"
                        ${index === 0 ? "checked" : ""}>
                    <label class="form-check-label">
                        ${sub.nama} - Rp ${Number(sub.tambahan_harga).toLocaleString()}
                    </label>
                </div>
            `;
        }

        modal.show();
    }

    function addToCart() {

        let id = currentItem.dataset.id;
        let nama = currentItem.dataset.nama;
        let harga = parseInt(currentItem.dataset.harga);
        let gambar = currentItem.dataset.gambar;
        let subs = JSON.parse(currentItem.dataset.sub);

        let selectedSub = document.querySelector('input[name="sub_makanan"]:checked');

        let subValue = null;
        let subNama = null;
        let tambahanHarga = 0;

        if (selectedSub) {
            subValue = selectedSub.value;

            let subData = subs.find(s => s.id_sub_makanan == subValue);

            if (subData) {
                subNama = subData.nama;
                tambahanHarga = parseInt(subData.tambahan_harga);
            }
        }

        let finalHarga = harga + tambahanHarga;

        fetch("{{ route('cart.add') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                makanan_id: id,
                nama: nama,
                harga_asli: harga,              
                tambahan_harga: tambahanHarga,  
                harga: finalHarga,              
                gambar: gambar,
                qty: 1,
                sub_makanan: subValue,
                sub_nama: subNama
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                modal.hide();
                updateCartBadge(data.total_items);
            }
        });
    }

    function updateCartBadge(total) {
        let badge = document.getElementById('cart-badge');

        if (!badge) {
            const cartBtn = document.querySelector('.bi-cart').parentElement;
            cartBtn.style.position = "relative";

            badge = document.createElement('span');
            badge.id = "cart-badge";
            badge.className = "position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger";
            cartBtn.appendChild(badge);
        }

        badge.innerText = total;
    }
</script>
@endpush