<?= $this->extend('admin/template/template'); ?>
<?= $this->section('content'); ?>

<div class="app-content pt-3 p-md-3 p-lg-4">
    <div class="container-xl">
        <h1 class="app-page-title">Tambahkan Artikel</h1>
        <hr class="mb-4">
        <div class="row g-4 settings-section">
            <div class="app-card app-card-settings shadow-sm p-4">
                <div class="card-body">
                    <?php if (!empty(session()->getFlashdata('error'))): ?>
                        <div class="alert alert-danger" role="alert">
                            <h4>Error</h4>
                            <p><?= esc(session()->getFlashdata('error')) ?></p>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('admin/artikel/proses_tambah') ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>
                        
                        <!-- Bagian Judul Artikel -->
                        <div class="mb-3">
                            <label class="form-label">Judul Artikel (ID)</label>
                            <input type="text" class="form-control <?= ($validation->hasError('judul_id')) ? 'is-invalid' : '' ?>" 
                                   name="judul_id" value="<?= old('judul_id') ?>" required>
                            <?php if ($validation->hasError('judul_id')): ?>
                                <div class="invalid-feedback"><?= $validation->getError('judul_id') ?></div>
                            <?php endif; ?>
                            <small class="text-muted">Hanya boleh mengandung huruf dan angka</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Judul Artikel (EN)</label>
                            <input type="text" class="form-control <?= ($validation->hasError('judul_en')) ? 'is-invalid' : '' ?>" 
                                   name="judul_en" value="<?= old('judul_en') ?>">
                            <?php if ($validation->hasError('judul_en')): ?>
                                <div class="invalid-feedback"><?= $validation->getError('judul_en') ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Bagian Deskripsi -->
                        <div class="mb-3">
                            <label class="form-label">Deskripsi (ID)</label>
                            <textarea class="form-control tiny <?= ($validation->hasError('konten_id')) ? 'is-invalid' : '' ?>" 
                                      name="konten_id"><?= old('konten_id') ?></textarea>
                            <?php if ($validation->hasError('konten_id')): ?>
                                <div class="invalid-feedback"><?= $validation->getError('konten_id') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi (EN)</label>
                            <textarea class="form-control tiny <?= ($validation->hasError('konten_en')) ? 'is-invalid' : '' ?>" 
                                      name="konten_en"><?= old('konten_en') ?></textarea>
                            <?php if ($validation->hasError('konten_en')): ?>
                                <div class="invalid-feedback"><?= $validation->getError('konten_en') ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Bagian Kategori -->
                        <div class="mb-3">
                            <label class="form-label">Kategori Artikel</label>
                            <select class="form-select <?= ($validation->hasError('kategori_id')) ? 'is-invalid' : '' ?>" 
                                    name="kategori_id" required>
                                <option value="" disabled selected>-- Pilih Kategori --</option>
                                <?php foreach ($all_data_kategori as $kategori): ?>
                                    <?php if ($kategori['is_approved'] == 1): ?>
                                        <option value="<?= esc($kategori['id_kategori_artikel']) ?>"
                                            <?= old('kategori_id') == $kategori['id_kategori_artikel'] ? 'selected' : '' ?>>
                                            <?= esc($kategori['nama_kategori_id']) ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($validation->hasError('kategori_id')): ?>
                                <div class="invalid-feedback"><?= $validation->getError('kategori_id') ?></div>
                            <?php endif; ?>
                            <?php if (empty(array_filter($all_data_kategori, fn($cat) => $cat['is_approved'] == 1))): ?>
                                <div class="alert alert-warning mt-2">
                                    Tidak ada kategori yang disetujui. Silakan hubungi administrator.
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Bagian Upload Gambar -->
                        <div class="mb-3">
                            <label class="form-label">Thumbnail Artikel</label>
                            <input type="file" class="form-control <?= ($validation->hasError('thumbnail')) ? 'is-invalid' : '' ?>" 
                                   name="thumbnail" required>
                            <?php if ($validation->hasError('thumbnail')): ?>
                                <div class="invalid-feedback"><?= $validation->getError('thumbnail') ?></div>
                            <?php endif; ?>
                            <small class="text-muted">
                                Ukuran maksimal 572x572 pixels, format: jpg/png/jpeg
                            </small>
                        </div>

                        <!-- Bagian SEO -->
                        <div class="mb-3">
                            <label class="form-label">Meta Title (ID)</label>
                            <input type="text" class="form-control" name="meta_title_id" 
                                   value="<?= old('meta_title_id') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Meta Description (ID)</label>
                            <textarea class="form-control" name="meta_description_id"><?= old('meta_description_id') ?></textarea>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Simpan Artikel</button>
                            <a href="<?= base_url('admin/artikel') ?>" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection('content'); ?>