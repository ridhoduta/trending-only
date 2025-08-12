<?= $this->extend('penulis/template/template'); ?>
<?= $this->section('content'); ?>

<div class="app-content pt-3 p-md-3 p-lg-4">
    <div class="container-xl">
        <h1 class="app-page-title">Edit Artikel</h1>
        <hr class="mb-4">
        <div class="row g-4 settings-section">
            <div class="app-card app-card-settings shadow-sm p-4">
                <div class="card-body">

                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('penulis/berita/proses_edit/' . $artikel['id_artikel']) ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>

                        <div class="row">
                            <div class="col">
                                <!-- Input untuk Judul Artikel dalam Bahasa Indonesia -->
                                <div class="mb-3">
                                    <label class="form-label">Judul Artikel (ID)</label>
                                    <input type="text" class="form-control <?= (session('validation') && session('validation')->hasError('judul_id')) ? 'is-invalid' : '' ?>" 
                                        id="judul_id" name="judul_id" value="<?= old('judul_id', $artikel['judul_id']) ?>" required>
                                    <?php if (session('validation') && session('validation')->hasError('judul_id')) : ?>
                                        <div class="invalid-feedback">
                                            <?= session('validation')->getError('judul_id') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Input untuk Judul Artikel dalam Bahasa Inggris -->
                                <div class="mb-3">
                                    <label class="form-label">Judul Artikel (EN)</label>
                                    <input type="text" class="form-control <?= (session('validation') && session('validation')->hasError('judul_en')) ? 'is-invalid' : '' ?>" 
                                        id="judul_en" name="judul_en" value="<?= old('judul_en', $artikel['judul_en']) ?>" required>
                                    <?php if (session('validation') && session('validation')->hasError('judul_en')) : ?>
                                        <div class="invalid-feedback">
                                            <?= session('validation')->getError('judul_en') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Input untuk Konten Artikel dalam Bahasa Indonesia -->
                                <div class="mb-3">
                                    <label class="form-label">Konten Artikel (ID)</label>
                                    <textarea class="form-control tiny <?= (session('validation') && session('validation')->hasError('konten_id')) ? 'is-invalid' : '' ?>" 
                                        id="konten_id" name="konten_id"><?= old('konten_id', $artikel['konten_id']) ?></textarea>
                                    <?php if (session('validation') && session('validation')->hasError('konten_id')) : ?>
                                        <div class="invalid-feedback">
                                            <?= session('validation')->getError('konten_id') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Input untuk Konten Artikel dalam Bahasa Inggris -->
                                <div class="mb-3">
                                    <label class="form-label">Konten Artikel (EN)</label>
                                    <textarea class="form-control tiny <?= (session('validation') && session('validation')->hasError('konten_en')) ? 'is-invalid' : '' ?>" 
                                        id="konten_en" name="konten_en"><?= old('konten_en', $artikel['konten_en']) ?></textarea>
                                    <?php if (session('validation') && session('validation')->hasError('konten_en')) : ?>
                                        <div class="invalid-feedback">
                                            <?= session('validation')->getError('konten_en') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Input untuk Kategori Artikel -->
                                <div class="mb-3">
                                    <label class="form-label">Kategori Artikel</label>
                                    <select class="form-select <?= (session('validation') && session('validation')->hasError('id_kategori')) ? 'is-invalid' : '' ?>" 
                                        id="id_kategori" name="id_kategori" required>
                                        <option value="">Pilih Kategori Artikel</option>
                                        <?php foreach ($all_data_kategori as $kategori): ?>
                                            <option value="<?= esc($kategori['id_kategori']) ?>" 
                                                <?= (old('id_kategori', $artikel['id_kategori']) == $kategori['id_kategori'] ? 'selected' : '') ?>>
                                                <?= esc($kategori['nama_kategori_id']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (session('validation') && session('validation')->hasError('id_kategori')) : ?>
                                        <div class="invalid-feedback">
                                            <?= session('validation')->getError('id_kategori') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Input untuk Thumbnail Artikel -->
                                <div class="mb-3">
                                    <label class="form-label">Thumbnail Artikel</label>
                                    <input type="file" class="form-control <?= (session('validation') && session('validation')->hasError('thumbnail')) ? 'is-invalid' : '' ?>" 
                                        id="thumbnail" name="thumbnail">
                                    <?php if (session('validation') && session('validation')->hasError('thumbnail')) : ?>
                                        <div class="invalid-feedback">
                                            <?= session('validation')->getError('thumbnail') ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($artikel['thumbnail'])) : ?>
                                        <div class="mt-2">
                                            <img src="<?= base_url('uploads/thumbnail/' . $artikel['thumbnail']) ?>" width="150" class="img-thumbnail">
                                            <input type="hidden" name="old_thumbnail" value="<?= $artikel['thumbnail'] ?>">
                                        </div>
                                    <?php endif; ?>
                                    <small class="text-muted">*Ukuran gambar maksimal 300KB</small><br>
                                    <small class="text-muted">*Format gambar harus JPG/JPEG/PNG</small>
                                </div>

                                <!-- Input untuk Featured Image -->
                                <div class="mb-3">
                                    <label class="form-label">Gambar Utama</label>
                                    <input type="file" class="form-control <?= (session('validation') && session('validation')->hasError('featured_image')) ? 'is-invalid' : '' ?>" 
                                        id="featured_image" name="featured_image">
                                    <?php if (session('validation') && session('validation')->hasError('featured_image')) : ?>
                                        <div class="invalid-feedback">
                                            <?= session('validation')->getError('featured_image') ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($artikel['gambar_besar'])) : ?>
                                        <div class="mt-2">
                                            <img src="<?= base_url('uploads/gambar_besar/' . $artikel['gambar_besar']) ?>" width="150" class="img-thumbnail">
                                            <input type="hidden" name="old_featured_image" value="<?= $artikel['gambar_besar'] ?>">
                                        </div>
                                    <?php endif; ?>
                                    <small class="text-muted">*Ukuran gambar maksimal 300KB</small><br>
                                    <small class="text-muted">*Format gambar harus JPG/JPEG/PNG</small>
                                </div>

                                <!-- Input untuk Sumber Gambar -->
                                <div class="mb-3">
                                    <label class="form-label">Sumber Gambar</label>
                                    <input type="text" class="form-control" id="photo_source" name="photo_source" 
                                        value="<?= old('photo_source', $artikel['sumber_gambar']) ?>">
                                </div>

                                <!-- Input untuk Tags -->
                                <div class="mb-3">
                                    <label class="form-label">Tags (ID)</label>
                                    <input type="text" class="form-control" id="tags_id" name="tags_id" 
                                        value="<?= old('tags_id', $artikel['tags_id']) ?>">
                                    <small class="text-muted">Pisahkan dengan koma (contoh: teknologi,ai,digital)</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tags (EN)</label>
                                    <input type="text" class="form-control" id="tags_en" name="tags_en" 
                                        value="<?= old('tags_en', $artikel['tags_en']) ?>">
                                    <small class="text-muted">Separate with comma (e.g: technology,ai,digital)</small>
                                </div>

                                <!-- Input untuk Meta Data -->
                                <div class="mb-3">
                                    <label class="form-label">Meta Title (ID)</label>
                                    <input type="text" class="form-control" id="meta_title_id" name="meta_title_id" 
                                        value="<?= old('meta_title_id', $artikel['meta_title_id']) ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Meta Description (ID)</label>
                                    <textarea class="form-control" id="meta_description_id" name="meta_description_id"><?= old('meta_description_id', $artikel['meta_description_id']) ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Meta Title (EN)</label>
                                    <input type="text" class="form-control" id="meta_title_en" name="meta_title_en" 
                                        value="<?= old('meta_title_en', $artikel['meta_title_en']) ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Meta Description (EN)</label>
                                    <textarea class="form-control" id="meta_description_en" name="meta_description_en"><?= old('meta_description_en', $artikel['meta_description_en']) ?></textarea>
                                </div>

                                <!-- Input untuk Published At -->
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Publikasi</label>
                                    <input type="datetime-local" class="form-control" id="published_at" name="published_at" 
                                        value="<?= old('published_at', date('Y-m-d\TH:i', strtotime($artikel['published_at']))) ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                <a href="<?= base_url('penulis/berita/index') ?>" class="btn btn-secondary">Batal</a>
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