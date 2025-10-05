<!-- Hero Section - Simplified -->
<section class="hero-modern">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <div class="animate-fade-in-up">
                  <h1 class="display-4 fw-bold text-white mb-2">
    Peminjaman Alat Media
</h1>
<h1 class="display-4 fw-bold text-warning mb-4">
    Mudah & Terpercaya
</h1>

                    <p class="lead text-light mb-4">
                        Platform penyewaan alat media terlengkap dengan {{ $totalBarang }}+ produk berkualitas untuk segala kebutuhan produksi Anda
                    </p>
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="input-group input-group-lg">
                                <input type="text" 
                                       class="form-control search-bar-modern" 
                                       placeholder="Cari produk yang ingin disewa..." 
                                       id="heroSearch" 
                                       value="{{ request('search') }}"
                                       autocomplete="off"
                                       spellcheck="false"
                                       autocorrect="off"
                                       autocapitalize="off"
                                       data-lpignore="true"
                                       data-form-type="other">
                                <button class="btn btn-warning fw-bold" type="button" onclick="performSearch()">
                                    <i class="fas fa-search me-2 text-white"></i>
                                    <span class="text-white">Cari Sekarang</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>