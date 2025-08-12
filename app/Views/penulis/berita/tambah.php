<?= $this->extend('penulis/template/template'); ?>
<?= $this->section('content'); ?>

<div class="app-content pt-3 p-md-3 p-lg-4">
    <div class="container-xl">
        <h1 class="app-page-title">Tambahkan Artikel</h1>
        <hr class="mb-4">
        <div class="row g-4 settings-section">
            <div class="app-card app-card-settings shadow-sm p-4">
                <div class="card-body">

                    <?php if (!empty(session()->getFlashdata('error'))) : ?>
                        <div class="alert alert-danger" role="alert">
                            <h4>Error</h4>
                            <p><?php echo session()->getFlashdata('error'); ?></p>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('penulis/berita/proses_tambah') ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>
                        <div class="row">
                            <div class="col">
                                <!-- Input untuk Judul Artikel dalam Bahasa Indonesia -->
                                <div class="mb-3">
                                    <label class="form-label">Judul Artikel (ID) <br><span class="custom-color custom-label">Judul Artikel hanya boleh mengandung huruf dan angka</span></label>
                                    <input type="text" class="form-control" id="judul_id" name="judul_id" value="<?= old('judul_id') ?>" required>
                                </div>

                                <!-- Input untuk Judul Artikel dalam Bahasa Inggris -->
                                <div class="mb-3">
                                    <label class="form-label">Judul Artikel (EN) <br><span class="custom-color custom-label">Judul Artikel hanya boleh mengandung huruf dan angka</span></label>
                                    <input type="text" class="form-control" id="judul_en" name="judul_en" value="<?= old('judul_en') ?>" required>
                                </div>

                                <!-- Input untuk Konten Artikel dalam Bahasa Indonesia -->
                                <div class="mb-3">
                                    <label class="form-label">Konten Artikel (ID)</label>
                                    <textarea type="text" class="form-control tiny" id="konten_id" name="konten_id"><?= old('konten_id') ?></textarea>
                                </div>

                                <!-- Input untuk Konten Artikel dalam Bahasa Inggris -->
                                <div class="mb-3">
                                    <label class="form-label">Konten Artikel (EN)</label>
                                    <textarea type="text" class="form-control tiny" id="konten_en" name="konten_en"><?= old('konten_en') ?></textarea>
                                </div>

                                <!-- Input untuk Kategori Artikel -->
                                <div class="mb-3">
                                    <label class="form-label">Kategori Artikel <br>
                                        <span class="custom-color custom-label">Pilih salah satu kategori Artikel yang tersedia</span>
                                    </label>
                                    <select class="form-select" id="id_kategori" name="id_kategori" required>
                                        <option value="" selected disabled>Pilih Kategori Artikel</option>
                                        <?php foreach ($all_data_kategori as $kategori): ?>
                                            <option value="<?= esc($kategori['id_kategori']) ?>" <?= old('id_kategori') == $kategori['id_kategori'] ? 'selected' : '' ?>>
                                                <?= esc($kategori['nama_kategori_id']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Input untuk Thumbnail Artikel -->
                                <div class="mb-3">
                                    <label class="form-label">Thumbnail Artikel</label>
                                    <input class="form-control" type="file" id="thumbnail" name="thumbnail" accept="image/*" required>
                                    <!-- Preview & Crop Area -->
                                    <div class="mt-3" id="thumbnail-crop-container" style="display: none;">
                                        <div class="img-container">
                                            <img id="thumbnail-preview" src="#" alt="Preview" style="max-width: 100%;">
                                        </div>
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-success btn-sm" id="thumbnail-crop-btn">Potong Gambar</button>
                                            <button type="button" class="btn btn-danger btn-sm" id="thumbnail-cancel-btn">Batal</button>
                                        </div>
                                    </div>
                                    <input type="hidden" id="thumbnail-cropped-data" name="thumbnail_cropped">
                                    <small class="text-muted">*Ukuran direkomendasikan: 700x467 pixels (rasio 3:2)</small>
                                </div>

                                <!-- Input untuk Gambar Besar -->
                                <div class="mb-3">
                                    <label class="form-label">Gambar Utama Artikel</label>
                                    <input class="form-control" type="file" id="featured_image" name="featured_image" accept="image/*">
                                    <!-- Preview & Crop Area -->
                                    <div class="mt-3" id="featured-crop-container" style="display: none;">
                                        <div class="img-container">
                                            <img id="featured-preview" src="#" alt="Preview" style="max-width: 100%;">
                                        </div>
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-success btn-sm" id="featured-crop-btn">Potong Gambar</button>
                                            <button type="button" class="btn btn-danger btn-sm" id="featured-cancel-btn">Batal</button>
                                        </div>
                                    </div>
                                    <input type="hidden" id="featured-cropped-data" name="featured_cropped">
                                    <small class="text-muted">*Ukuran direkomendasikan: 800x700 pixels (rasio layar lebar)</small>
                                </div>

                                <!-- Input untuk Sumber Foto -->
                                <div class="mb-3">
                                    <label class="form-label">Sumber Foto</label>
                                    <input type="text" class="form-control" id="photo_source" name="photo_source" placeholder="Masukkan sumber foto (contoh: Unsplash, Nama Fotografer)" value="<?= old('photo_source') ?>">
                                </div>

                                <!-- Input untuk Tags -->
                                <div class="mb-3">
                                    <label class="form-label">Tags (ID) <br><span class="custom-color custom-label">Pisahkan dengan koma (contoh: teknologi,ai,digital)</span></label>
                                    <input type="text" class="form-control" id="tags_id" name="tags_id" value="<?= old('tags_id') ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tags (EN) <br><span class="custom-color custom-label">Separate with comma (e.g: technology,ai,digital)</span></label>
                                    <input type="text" class="form-control" id="tags_en" name="tags_en" value="<?= old('tags_en') ?>">
                                </div>

                                <!-- Input untuk Meta Data -->
                                <div class="mb-3">
                                    <label class="form-label">Meta Title (ID)</label>
                                    <input type="text" class="form-control" id="meta_title_id" name="meta_title_id" placeholder="Masukkan Meta Title (ID)" value="<?= old('meta_title_id') ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Meta Description (ID)</label>
                                    <textarea class="form-control" id="meta_description_id" name="meta_description_id" placeholder="Masukkan Meta Description (ID)"><?= old('meta_description_id') ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Meta Title (EN)</label>
                                    <input type="text" class="form-control" id="meta_title_en" name="meta_title_en" placeholder="Masukkan Meta Title (EN)" value="<?= old('meta_title_en') ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Meta Description (EN)</label>
                                    <textarea class="form-control" id="meta_description_en" name="meta_description_en" placeholder="Masukkan Meta Description (EN)"><?= old('meta_description_en') ?></textarea>
                                </div>

                                <!-- Input untuk Published At -->
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Publikasi</label>
                                    <input type="datetime-local" class="form-control" id="published_at" name="published_at" value="<?= old('published_at') ?? date('Y-m-d\TH:i') ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                            <div class="col">
                                <?php if (!empty(session()->getFlashdata('success'))) : ?>
                                    <div class="alert alert-success" role="alert">
                                        <?php echo session()->getFlashdata('success') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div><!--//app-card-->
        </div><!--//row-->

        <hr class="my-4">
    </div><!--//container-fluid-->
</div><!--//app-content-->

<?= $this->endSection('content'); ?>