<!-- Late Fee Information Modal -->
<div class="modal fade" id="lateFeeModal" tabindex="-1" aria-labelledby="lateFeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title text-dark" id="lateFeeModalLabel">
                    <i class="bi bi-clock-history me-2"></i> Kebijakan Pengembalian
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-light border-start border-primary border-3">
                    <h6 class="fw-bold text-primary">Aturan Pengembalian:</h6>
                    <ul class="mb-0 text-muted">
                        <li>Barang harus dikembalikan sesuai jadwal yang disepakati</li>
                        <li>Keterlambatan 1 jam akan dikenakan biaya tambahan 1 hari penuh</li>
                        <li>Berlaku kelipatan untuk setiap periode 24 jam berikutnya</li>
                    </ul>
                </div>
                
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <h6 class="card-title text-dark">Contoh Perhitungan:</h6>
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <span class="text-muted">Sewa 1 hari, kembali tepat waktu</span>
                                    <span class="fw-bold text-success">1 hari billing</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <span class="text-muted">Sewa 1 hari, terlambat 1 jam</span>
                                    <span class="fw-bold text-warning">2 hari billing</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center py-2">
                                    <span class="text-muted">Sewa 2 hari, terlambat 1 jam</span>
                                    <span class="fw-bold text-warning">3 hari billing</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <small class="text-muted">
                        <i class="bi bi-shield-check me-1"></i>
                        Kebijakan ini berlaku untuk menjaga ketersediaan barang untuk pelanggan lain
                    </small>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Saya Mengerti</button>
            </div>
        </div>
    </div>
</div>
