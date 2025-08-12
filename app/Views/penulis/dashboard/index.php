<?= $this->extend('penulis/template/template'); ?>
<?= $this->section('content'); ?>

<div class="app-content pt-3 p-md-3 p-lg-4">
    <div class="container-xl">
        <h1 class="app-page-title mb-4">Dashboard</h1>

        <div class="row g-4 mb-5">
            <div class="col-6 col-lg-3">
                <div class="app-card app-card-stat shadow-sm h-100 text-center">
                    <div class="app-card-body p-3 p-lg-4">
                        <h4 class="stats-type mb-2">Jumlah Slider</h4>
                        <div class="stats-figure">—</div>
                    </div>
                    <a class="app-card-link-mask" href="<?= base_url('/penulis/slider/index') ?>"></a>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="app-card app-card-stat shadow-sm h-100 text-center">
                    <div class="app-card-body p-3 p-lg-4">
                        <h4 class="stats-type mb-2">Jumlah Produk</h4>
                        <div class="stats-figure">—</div>
                    </div>
                    <a class="app-card-link-mask" href="<?= base_url('/penulis/produk/index') ?>"></a>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="app-card app-card-stat shadow-sm h-100 text-center">
                    <div class="app-card-body p-3 p-lg-4">
                        <h4 class="stats-type mb-2">Jumlah Artikel</h4>
                        <div class="stats-figure"><?= esc($artikelCount) ?></div>
                    </div>
                    <a class="app-card-link-mask" href="<?= base_url('/penulis/berita/index') ?>"></a>
                </div>
            </div>
        </div>

        <!-- Rekap Tanggal Upload -->
        <div class="row g-4">
            <div class="col-md-6">
                <div class="app-card shadow-sm p-4 h-100">
                    <h5 class="app-card-title">Tanggal Sudah Upload</h5>
                    <ul class="list-unstyled mt-3">
                        <?php if (!empty($uploadedDates)): ?>
                            <?php foreach ($uploadedDates as $tgl): ?>
                                <li>📅 <?= date('d M Y', strtotime($tgl)) ?></li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="text-muted">Belum ada artikel di bulan ini.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <div class="col-md-6">
                <div class="app-card shadow-sm p-4 h-100">
                    <h5 class="app-card-title">Tanggal Belum Upload</h5>
                    <ul class="list-unstyled mt-3">
                        <?php foreach ($notUploadedDates as $tgl): ?>
                            <li>🕓 <?= date('d M Y', strtotime($tgl)) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection('content'); ?>
