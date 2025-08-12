<?= $this->extend('penulis/layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success'); ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error'); ?>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Artikel</h6>
            <a href="/penulis/artikel/create" class="btn btn-primary btn-sm">Tambah Artikel</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Tanggal Publikasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($artikels as $artikel) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= esc($artikel['judul_id']); ?></td>
                                <td>
                                    <?php
                                    $kategoriFound = false;
                                    foreach ($kategoris as $kategori) {
                                        if ($kategori['id_kategori'] == $artikel['id_kategori']) {
                                            echo esc($kategori['nama_kategori']);
                                            $kategoriFound = true;
                                            break;
                                        }
                                    }
                                    if (!$kategoriFound) {
                                        echo '<span class="text-danger">Kategori tidak ditemukan</span>';
                                    }
                                    ?>
                                </td>
                                <td><?= date('d F Y', strtotime($artikel['published_at'])); ?></td>
                                <td>
                                    <a href="/penulis/artikel/edit/<?= $artikel['id_artikel']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="/penulis/artikel/delete/<?= $artikel['id_artikel']; ?>" method="post" class="d-inline">
                                        <?= csrf_field(); ?>
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin?')">Hapus</button>
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
<?= $this->endSection(); ?>