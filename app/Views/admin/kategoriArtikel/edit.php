<?= $this->extend('admin/template/template'); ?>
<?= $this->Section('content'); ?>

<div class="app-content pt-3 p-md-3 p-lg-4">
    <div class="container-xl">
        <h1 class="app-page-title">Edit Kategori Artikel</h1>
        <hr class="mb-4">
        <div class="row g-4 settings-section">
            <div class="app-card app-card-settings shadow-sm p-4">
                <div class="card-body">
                    <?php if (!empty(session()->getFlashdata('error'))): ?>
                        <div class="alert alert-danger" role="alert">
                            <h4>Error</h4>
                            <p><?php echo session()->getFlashdata('error'); ?></p>
                        </div>
                    <?php endif; ?>

                    <form
                        action="<?= base_url('admin/kategoriArtikel/proses_edit/' . ($all_data_artikel_kategori['id_kategori'] ?? '')) ?>"
                        method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="thumbnail_lama"
                            value="<?= $all_data_artikel_kategori['thumbnail'] ?? '' ?>">
                        <input type="hidden" name="id_kategori"
                            value="<?= $all_data_artikel_kategori['id_kategori'] ?? '' ?>">

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label">Nama Kategori (ID)</label>
                                    <input type="text"
                                        class="form-control <?= session('errors.nama_kategori_id') ? 'is-invalid' : '' ?>"
                                        id="nama_kategori_id" name="nama_kategori_id"
                                        value="<?= old('nama_kategori_id', $all_data_artikel_kategori['nama_kategori_id'] ?? '') ?>">
                                    <?php if (session('errors.nama_kategori_id')): ?>
                                        <div class="invalid-feedback">
                                            <?= session('errors.nama_kategori_id') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Nama Kategori (EN)</label>
                                    <input type="text"
                                        class="form-control <?= session('errors.nama_kategori_en') ? 'is-invalid' : '' ?>"
                                        id="nama_kategori_en" name="nama_kategori_en"
                                        value="<?= old('nama_kategori_en', $all_data_artikel_kategori['nama_kategori_en'] ?? '') ?>">
                                    <?php if (session('errors.nama_kategori_en')): ?>
                                        <div class="invalid-feedback">
                                            <?= session('errors.nama_kategori_en') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Slug (ID)</label>
                                    <input type="text" class="form-control" id="slug_id" name="slug_id"
                                        value="<?= old('slug_id', $all_data_artikel_kategori['slug_id'] ?? '') ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Slug (EN)</label>
                                    <input type="text" class="form-control" id="slug_en" name="slug_en"
                                        value="<?= old('slug_en', $all_data_artikel_kategori['slug_en'] ?? '') ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Meta Title (ID)</label>
                                    <input type="text" class="form-control" id="meta_title_id" name="meta_title_id"
                                        value="<?= old('meta_title_id', $all_data_artikel_kategori['meta_title_id'] ?? '') ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Meta Description (ID)</label>
                                    <textarea class="form-control" id="meta_description_id" name="meta_description_id"
                                        rows="3"><?= old('meta_description_id', $all_data_artikel_kategori['meta_description_id'] ?? '') ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Meta Title (EN)</label>
                                    <input type="text" class="form-control" id="meta_title_en" name="meta_title_en"
                                        value="<?= old('meta_title_en', $all_data_artikel_kategori['meta_title_en'] ?? '') ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Meta Description (EN)</label>
                                    <textarea class="form-control" id="meta_description_en" name="meta_description_en"
                                        rows="3"><?= old('meta_description_en', $all_data_artikel_kategori['meta_description_en'] ?? '') ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Thumbnail Kategori</label>
                                    <?php if (!empty($all_data_artikel_kategori['thumbnail'])): ?>
                                        <div class="mb-2">
                                            <img src="<?= base_url('uploads/kategori/' . $all_data_artikel_kategori['thumbnail']) ?>"
                                                alt="Thumbnail Kategori" style="max-height: 150px;">
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" class="form-control" id="thumbnail" name="thumbnail"
                                        accept="image/*">
                                    <small class="text-muted">Format: JPG, PNG, JPEG. Maksimal ukuran: 2MB</small>
                                </div>

                                <div class="row mt-4">
                                    <div class="col">
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        <a href="<?= base_url('admin/kategoriArtikel/index') ?>"
                                            class="btn btn-secondary">Batal</a>
                                    </div>
                                    <div class="col text-end">
                                        <?php if (!empty(session()->getFlashdata('success'))): ?>
                                            <div class="alert alert-success mb-0" role="alert">
                                                <?= session()->getFlashdata('success') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Script untuk generate slug otomatis
    document.addEventListener('DOMContentLoaded', function () {
        const namaId = document.getElementById('nama_kategori_id');
        const namaEn = document.getElementById('nama_kategori_en');
        const slugId = document.getElementById('slug_id');
        const slugEn = document.getElementById('slug_en');

        if (namaId && slugId) {
            namaId.addEventListener('input', function () {
                if (!slugId.value) { // Hanya generate jika slug kosong
                    slugId.value = generateSlug(this.value);
                }
            });
        }

        if (namaEn && slugEn) {
            namaEn.addEventListener('input', function () {
                if (!slugEn.value) { // Hanya generate jika slug kosong
                    slugEn.value = generateSlug(this.value);
                }
            });
        }

        function generateSlug(text) {
            return text.toLowerCase()
                .replace(/[^\w\s-]/g, '')  // Hapus karakter khusus
                .replace(/[\s]+/g, '-')    // Ganti spasi dengan -
                .replace(/[-]+/g, '-');    // Ganti banyak - dengan satu -
        }
    });
</script>

<?= $this->endSection('content') ?>