<?= $this->extend('admin/template/template'); ?>
<?= $this->section('content'); ?>

<div class="app-content pt-3 p-md-3 p-lg-4">
  <div class="container-xl">
    <div class="row g-3 mb-4 align-items-center justify-content-between">
      <div class="col-auto">
        <h1 class="app-page-title mb-0">Daftar Penulis</h1>
      </div>
      <div class="col-auto">
        <div class="page-utilities">
          <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
            <div class="col-auto">
              <a href="<?= base_url('admin/penulis/tambah'); ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Penulis
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php if (session()->has('success')) : ?>
      <div class="alert alert-success">
        <?= session('success') ?>
      </div>
    <?php endif; ?>

    <div class="app-card app-card-orders-table shadow-sm mb-5">
      <div class="app-card-body">
        <div class="table-responsive">
          <table class="table app-table-hover mb-0 text-left">
            <thead>
              <tr>
                <th class="cell">No</th>
                <th class="cell">Foto</th>
                <th class="cell">Username</th>
                <th class="cell">Nama Lengkap</th>
                <th class="cell">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; ?>
              <?php foreach ($penulis as $p) : ?>
                <tr>
                  <td class="cell"><?= $no++; ?></td>
                  <td class="cell">
                    <?php if ($p['foto_profil']) : ?>
                      <img src="<?= base_url('uploads/penulis/' . $p['foto_profil']); ?>"
                        class="rounded-circle" width="40" height="40" alt="Foto Profil">
                    <?php else : ?>
                      <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center"
                        style="width: 40px; height: 40px;">
                        <i class="fas fa-user text-secondary"></i>
                      </div>
                    <?php endif; ?>
                  </td>
                  <td class="cell"><?= esc($p['username']); ?></td>
                  <td class="cell"><?= esc($p['nama_lengkap']); ?></td>
                  <td class="cell">
                    <a href="<?= base_url('admin/penulis/edit/' . $p['id_user']); ?>" class="btn btn-sm btn-warning">
                      <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="<?= base_url('admin/penulis/hapus/' . $p['id_user']); ?>" method="post" class="d-inline">
                      <?= csrf_field(); ?>
                      <input type="hidden" name="_method" value="DELETE">
                      <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin?')">
                        <i class="fas fa-trash"></i> Hapus
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection(); ?>