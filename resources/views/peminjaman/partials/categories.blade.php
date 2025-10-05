<!-- Categories - Simplified -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h3 class="fw-bold mb-3">Kategori Produk</h3>
                <p class="text-muted">Pilih kategori sesuai kebutuhan Anda</p>
            </div>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row g-3">
                    <!-- Semua Kategori Button -->
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <div class="card border-0 shadow-sm h-100 category-card-simple active" data-category="all">
                            <div class="card-body text-center p-2 p-md-3">
                                <div class="mb-1 mb-md-2">
                                    <i class="fas fa-th-large text-primary" style="font-size: 1.2rem;"></i>
                                </div>
                                <h6 class="fw-bold mb-1" style="font-size: 0.8rem;">Semua</h6>
                                <small class="text-muted" style="font-size: 0.7rem;">{{ $totalBarang }} produk</small>
                            </div>
                        </div>
                    </div>
                    
                    @foreach($jenisBarang as $jenis)
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <div class="card border-0 shadow-sm h-100 category-card-simple" data-category="{{ $jenis->id }}">
                            <div class="card-body text-center p-2 p-md-3">
                                <div class="mb-1 mb-md-2">
                                    <i class="fas fa-layer-group text-primary" style="font-size: 1.2rem;"></i>
                                </div>
                                <h6 class="fw-bold mb-1" style="font-size: 0.8rem;">{{ Str::limit($jenis->nama, 8) }}</h6>
                                <small class="text-muted" style="font-size: 0.7rem;">{{ $jenis->barang_count ?? 0 }} produk</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>