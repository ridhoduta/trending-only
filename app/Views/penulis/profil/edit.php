<?= $this->extend('penulis/template/template'); ?>
<?= $this->section('content'); ?>

<div class="app-content pt-3 p-md-3 p-lg-4">
    <div class="container-xl">
        <h1 class="app-page-title">Ubah Profil</h1>

        <!-- Menampilkan pesan error/success -->
        <?php if (!empty(session()->getFlashdata('error'))) : ?>
            <div class="alert alert-danger" role="alert">
                <h4>Error</h4>
                <p><?= session()->getFlashdata('error'); ?></p>
            </div>
        <?php endif; ?>

        <?php if (session()->has('success')) : ?>
            <div class="alert alert-success">
                <?= session('success') ?>
            </div>
        <?php endif; ?>

        <div class="row gy-4">
            <!-- Form Informasi Dasar -->
            <div class="col-12 col-lg-6">
                <div class="app-card app-card-account shadow-sm d-flex flex-column align-items-start mb-4">
                    <div class="app-card-header p-3 border-bottom-0">
                        <div class="row align-items-center gx-3">
                            <div class="col-auto">
                                <div class="app-icon-holder">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div class="col-auto">
                                <h4 class="app-card-title">Informasi Dasar</h4>
                            </div>
                        </div>
                    </div>

                    <div class="app-card-body px-4 w-100">
                        <form action="<?= base_url('penulis/profil/update-username'); ?>" method="post">
                            <?= csrf_field(); ?>

                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control <?= session('username_errors.username') ? 'is-invalid' : '' ?>"
                                    id="username" name="username" value="<?= esc($user['username']); ?>" required>
                                <?php if (session('username_errors.username')) : ?>
                                    <div class="invalid-feedback">
                                        <?= session('username_errors.username') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Update Username</button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <form action="<?= base_url('penulis/profil/update-nama'); ?>" method="post">
                            <?= csrf_field(); ?>

                            <div class="mb-3">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control <?= session('nama_errors.nama_lengkap') ? 'is-invalid' : '' ?>"
                                    id="nama_lengkap" name="nama_lengkap" value="<?= esc($user['nama_lengkap']); ?>" required>
                                <?php if (session('nama_errors.nama_lengkap')) : ?>
                                    <div class="invalid-feedback">
                                        <?= session('nama_errors.nama_lengkap') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Update Nama</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Form Foto Profil -->
            <div class="col-12 col-lg-6">
                <div class="app-card app-card-account shadow-sm d-flex flex-column align-items-start mb-4">
                    <div class="app-card-header p-3 border-bottom-0">
                        <div class="row align-items-center gx-3">
                            <div class="col-auto">
                                <div class="app-icon-holder">
                                    <i class="fas fa-camera"></i>
                                </div>
                            </div>
                            <div class="col-auto">
                                <h4 class="app-card-title">Foto Profil</h4>
                            </div>
                        </div>
                    </div>

                    <div class="app-card-body px-4 w-100">
                        <div class="text-center mb-4">
                            <?php if ($user['foto_profil']) : ?>
                                <img src="<?= base_url('uploads/penulis/' . $user['foto_profil']); ?>"
                                    class="rounded-circle" width="150" height="150" alt="Foto Profil">
                            <?php else : ?>
                                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center"
                                    style="width: 150px; height: 150px;">
                                    <i class="fas fa-user fa-3x text-secondary"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <form action="<?= base_url('penulis/profil/update-foto'); ?>" method="post" enctype="multipart/form-data">
                            <?= csrf_field(); ?>

                            <div class="mb-3">
                                <label for="foto_profil" class="form-label">Ubah Foto Profil</label>
                                <input type="file" class="form-control <?= session('foto_errors.foto_profil') ? 'is-invalid' : '' ?>"
                                    id="foto_profil" name="foto_profil" accept="image/jpeg, image/png">
                                <div class="form-text">Format: JPG/PNG, maksimal 1MB</div>
                                <?php if (session('foto_errors.foto_profil')) : ?>
                                    <div class="invalid-feedback">
                                        <?= session('foto_errors.foto_profil') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Update Foto</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Form Password -->
            <div class="col-12">
                <div class="app-card app-card-account shadow-sm d-flex flex-column align-items-start mb-4">
                    <div class="app-card-header p-3 border-bottom-0">
                        <div class="row align-items-center gx-3">
                            <div class="col-auto">
                                <div class="app-icon-holder">
                                    <i class="fas fa-lock"></i>
                                </div>
                            </div>
                            <div class="col-auto">
                                <h4 class="app-card-title">Ubah Password</h4>
                            </div>
                        </div>
                    </div>

                    <div class="app-card-body px-4 w-100">
                        <form action="<?= base_url('penulis/profil/update-password'); ?>" method="post">
                            <?= csrf_field(); ?>

                            <div class="mb-3">
                                <label for="password_lama" class="form-label">Password Lama</label>
                                <input type="password" class="form-control <?= session('password_errors.password_lama') ? 'is-invalid' : '' ?>"
                                    id="password_lama" name="password_lama" required>
                                <?php if (session('password_errors.password_lama')) : ?>
                                    <div class="invalid-feedback">
                                        <?= session('password_errors.password_lama') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="password_baru" class="form-label">Password Baru</label>
                                <input type="password" class="form-control <?= session('password_errors.password_baru') ? 'is-invalid' : '' ?>"
                                    id="password_baru" name="password_baru" required>
                                <div class="form-text">Minimal 8 karakter</div>
                                <?php if (session('password_errors.password_baru')) : ?>
                                    <div class="invalid-feedback">
                                        <?= session('password_errors.password_baru') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="konfirmasi_password" class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control <?= session('password_errors.konfirmasi_password') ? 'is-invalid' : '' ?>"
                                    id="konfirmasi_password" name="konfirmasi_password" required>
                                <?php if (session('password_errors.konfirmasi_password')) : ?>
                                    <div class="invalid-feedback">
                                        <?= session('password_errors.konfirmasi_password') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Update Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>