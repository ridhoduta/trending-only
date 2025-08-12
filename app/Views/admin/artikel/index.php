<?= $this->extend('admin/template/template'); ?>
<?= $this->section('content'); ?>

<div class="app-content pt-3 p-md-3 p-lg-4">
    <div class="container-xl">
        <div class="row g-3 mb-4 align-items-center justify-content-between">
            <div class="col-auto">
                <h1 class="app-page-title mb-0">Daftar Artikel</h1>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="<?= base_url('admin/artikel/tambah') ?>" class="btn btn-primary me-md-2">
                    <i class="fas fa-plus me-1"></i> Tambah Artikel
                </a>
            </div>
        </div>

        <?php if (session()->has('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= esc(session('success')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="app-card app-card-orders-table shadow-sm mb-5">
            <div class="app-card-body">
                <div class="table-responsive">
                    <table class="table app-table-hover mb-0 text-left">
                        <thead class="table-light">
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th width="25%">Judul Artikel</th>
                                <th width="20%">Kategori</th>
                                <th width="15%" class="text-center">Status</th>
                                <th width="15%" class="text-center">Gambar</th>
                                <th width="20%" class="text-center">Persetujuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($artikels) && is_array($artikels)): ?>
                                <?php foreach ($artikels as $index => $artikel): ?>
                                    <tr>
                                        <td class="text-center"><?= $index + 1 ?></td>
                                        <td>
                                            <div class="fw-semibold"><?= esc($artikel['judul_id'] ?? 'Belum diisi') ?></div>
                                            <?php if (!empty($artikel['judul_en'])): ?>
                                                <div class="text-muted small mt-1">
                                                    <?= esc($artikel['judul_en']) ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= esc($artikel['nama_kategori'] ?? 'Belum dikategorikan') ?></td>
                                        <td class="text-center">
                                            <?php if (isset($artikel['is_approved'])): ?>
                                                <?php if ($artikel['is_approved'] == 'sudah disetujui'): ?>
                                                    <span class="badge bg-success">Sudah Disetujui</span>
                                                <?php elseif ($artikel['is_approved'] == 'belum disetujui'): ?>
                                                    <span class="badge bg-warning text-dark">Belum Disetujui</span>
                                                <?php elseif ($artikel['is_approved'] == 'tidak disetujui'): ?>
                                                    <span class="badge bg-danger">Tidak Disetujui</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Status Tidak Valid</span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Belum Ada Status</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if (!empty($artikel['thumbnail'])): ?>
                                                <img src="<?= base_url('assets/img/artikel/' . esc($artikel['thumbnail'])) ?>"
                                                    class="img-thumbnail" style="max-height: 60px;"
                                                    alt="<?= esc($artikel['alt_id'] ?? 'Gambar Artikel') ?>" loading="lazy">
                                            <?php else: ?>
                                                <span class="badge bg-secondary">No Image</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <!-- Dropdown untuk status persetujuan -->
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm <?=
                                                    ($artikel['is_approved'] == 'sudah disetujui') ? 'btn-success' :
                                                    (($artikel['is_approved'] == 'tidak disetujui') ? 'btn-danger' : 'btn-warning')
                                                    ?> dropdown-toggle" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="fas fa-check-circle"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="<?= base_url('admin/artikel/set_approval/' . $artikel['id_artikel'] . '/sudah disetujui') ?>">
                                                            <i class="fas fa-check text-success me-2"></i> Setujui
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="<?= base_url('admin/artikel/set_approval/' . $artikel['id_artikel'] . '/belum disetujui') ?>">
                                                            <i class="fas fa-clock text-warning me-2"></i> Belum Disetujui
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="<?= base_url('admin/artikel/set_approval/' . $artikel['id_artikel'] . '/tidak disetujui') ?>">
                                                            <i class="fas fa-times text-danger me-2"></i> Tidak Disetujui
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="fas fa-info-circle me-2"></i> Belum ada data artikel
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if (isset($pager)): ?>
                    <div class="mt-3">
                        <?= $pager->links() ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection('content') ?>