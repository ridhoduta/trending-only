<?= $this->extend('admin/template/template'); ?>
<?= $this->section('content'); ?>

<div class="app-content pt-3 p-md-3 p-lg-4">
  <div class="container-xl">
    <h1 class="app-page-title">Tambah Penulis</h1>

    <?php if (!empty(session()->getFlashdata('errors'))) : ?>
      <div class="alert alert-danger" role="alert">
        <h4>Error</h4>
        <ul>
          <?php foreach (session()->getFlashdata('errors') as $error) : ?>
            <li><?= $error ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <div class="row gy-4">
      <div class="app-card app-card-account shadow-sm d-flex flex-column align-items-start">
        <div class="app-card-body px-4 w-100">
          <form action="<?= base_url('admin/penulis/proses_tambah'); ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field(); ?>

            <div class="mb-3">
              <label for="username" class="form-label">Username</label>
              <input type="text" class="form-control" id="username" name="username" required>
            </div>

            <div class="mb-3">
              <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
              <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
            </div>

            <div class="mb-3">
              <label for="foto_profil" class="form-label">Foto Profil</label>
              <input type="file" class="form-control" id="foto_profil" name="foto_profil" accept="image/jpeg, image/png">
              <div class="form-text">Format: JPG/PNG, maksimal 1MB</div>
            </div>

            <div class="mt-4">
              <button type="submit" class="btn btn-primary">Simpan</button>
              <a href="<?= base_url('admin/penulis'); ?>" class="btn btn-secondary">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection(); ?>