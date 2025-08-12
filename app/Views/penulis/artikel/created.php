<?= $this->extend('penulis/layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Artikel</h6>
        </div>
        <div class="card-body">
            <form action="/penulis/artikel/store" method="post" enctype="multipart/form-data">
                <?= csrf_field(); ?>

                <div class="form-group">
                    <label for="judul_id">Judul (Indonesia)</label>
                    <input type="text" class="form-control <?= ($validation->hasError('judul_id')) ? 'is-invalid' : ''; ?>" id="judul_id" name="judul_id" value="<?= old('judul_id'); ?>">
                    <div class="invalid-feedback">
                        <?= $validation->getError('judul_id'); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="judul_en">Judul (English)</label>
                    <input type="text" class="form-control" id="judul_en" name="judul_en" value="<?= old('judul_en'); ?>">
                </div>

                <div class="form-group">
                    <label for="id_kategori">Kategori</label>
                    <select class="form-control <?= ($validation->hasError('id_kategori')) ? 'is-invalid' : ''; ?>" id="id_kategori" name="id_kategori">
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach ($kategoris as $kategori) : ?>
                            <option value="<?= $kategori['id_kategori']; ?>" <?= (old('id_kategori') == $kategori['id_kategori']) ? 'selected' : ''; ?>>
                                <?= $kategori['nama_kategori']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">
                        <?= $validation->getError('id_kategori'); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="konten_id">Konten (Indonesia)</label>
                    <textarea class="form-control <?= ($validation->hasError('konten_id')) ? 'is-invalid' : ''; ?>" id="konten_id" name="konten_id" rows="5"><?= old('konten_id'); ?></textarea>
                    <div class="invalid-feedback">
                        <?= $validation->getError('konten_id'); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="konten_en">Konten (English)</label>
                    <textarea class="form-control" id="konten_en" name="konten_en" rows="5"><?= old('konten_en'); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="thumbnail">Thumbnail</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input <?= ($validation->hasError('thumbnail')) ? 'is-invalid' : ''; ?>" id="thumbnail" name="thumbnail">
                        <label class="custom-file-label" for="thumbnail">Pilih gambar</label>
                        <div class="invalid-feedback">
                            <?= $validation->getError('thumbnail'); ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="tags_id">Tags (Indonesia) <small class="text-muted">Pisahkan dengan koma</small></label>
                    <input type="text" class="form-control" id="tags_id" name="tags_id" value="<?= old('tags_id'); ?>">
                </div>

                <div class="form-group">
                    <label for="tags_en">Tags (English) <small class="text-muted">Pisahkan dengan koma</small></label>
                    <input type="text" class="form-control" id="tags_en" name="tags_en" value="<?= old('tags_en'); ?>">
                </div>

                <div class="form-group">
                    <label for="meta_title_id">Meta Title (Indonesia)</label>
                    <input type="text" class="form-control" id="meta_title_id" name="meta_title_id" value="<?= old('meta_title_id'); ?>">
                </div>

                <div class="form-group">
                    <label for="meta_title_en">Meta Title (English)</label>
                    <input type="text" class="form-control" id="meta_title_en" name="meta_title_en" value="<?= old('meta_title_en'); ?>">
                </div>

                <div class="form-group">
                    <label for="meta_description_id">Meta Description (Indonesia)</label>
                    <textarea class="form-control" id="meta_description_id" name="meta_description_id" rows="3"><?= old('meta_description_id'); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="meta_description_en">Meta Description (English)</label>
                    <textarea class="form-control" id="meta_description_en" name="meta_description_en" rows="3"><?= old('meta_description_en'); ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="/penulis/artikel" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>

<script>
    // Menampilkan nama file yang dipilih untuk thumbnail
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = document.getElementById("thumbnail").files[0].name;
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });
</script>
<?= $this->endSection(); ?>