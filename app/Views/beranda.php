<?= $this->extend('layouts/template'); ?>
<?= $this->section('content'); ?>

<div class="section">
    <?php
    // Ambil artikel terbaru 4 jam terakhir
    $db = \Config\Database::connect();
    $fourHoursAgo = date('Y-m-d H:i:s', strtotime('-4 hours'));
    $builder = $db->table('tb_artikel');
    $builder->select('tb_artikel.*, tb_users.nama_lengkap, tb_kategori.nama_kategori_id as nama_kategori_' . $lang);
    $builder->join('tb_users', 'tb_users.id_user = tb_artikel.id_user');
    $builder->join('tb_kategori', 'tb_kategori.id_kategori = tb_artikel.id_kategori', 'left');
    $builder->where('tb_artikel.published_at >=', $fourHoursAgo);
    $builder->orderBy('tb_artikel.published_at', 'DESC');
    $recentArticles = $builder->get()->getResultArray();
    ?>

    <div class="container">
        <div id="hot-post" class="row hot-post">
            <?php if (!empty($latestArticles)): ?>
                <div class="col-md-8 hot-post-left">
                    <!-- Artikel Utama -->
                    <div class="post post-thumb">
                        <a class="post-img" href="/<?= $lang; ?>/<?= $latestArticles[0]['kategori']['slug_' . $lang]; ?>/<?= $latestArticles[0]['slug_' . $lang]; ?>">
                            <img src="<?= base_url('/uploads/thumbnail/' . $latestArticles[0]['thumbnail']); ?>" alt="<?= $latestArticles[0]['judul_' . $lang]; ?>">
                        </a>
                        <div class="post-body">
                            <div class="post-category">
                                <a href="/<?= $lang; ?>/kategori/<?= $latestArticles[0]['kategori']['slug_' . $lang]; ?>">
                                    <?= $latestArticles[0]['kategori']['nama_kategori_' . $lang]; ?>
                                </a>
                            </div>
                            <h3 class="post-title title-lg">
                                <a href="/<?= $lang; ?>/<?= $latestArticles[0]['kategori']['slug_' . $lang]; ?>/<?= $latestArticles[0]['slug_' . $lang]; ?>">
                                    <?= $latestArticles[0]['judul_' . $lang]; ?>
                                </a>
                            </h3>
                            <div class="article-meta">
                                <span class="author"><?= $latestArticles[0]['nama_lengkap'] ?? '' ?></span>
                                <span class="publish-date"><?= date('d F Y', strtotime($latestArticles[0]['published_at'])) ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Artikel ke-2 dan ke-3 -->
                <div class="col-md-4 hot-post-right">
                    <?php for ($i = 1; $i < min(3, count($latestArticles)); $i++): ?>
                        <div class="post post-thumb">
                            <a class="post-img" href="/<?= $lang; ?>/<?= $latestArticles[$i]['kategori']['slug_' . $lang]; ?>/<?= $latestArticles[$i]['slug_' . $lang]; ?>">
                                <img src="<?= base_url('uploads/thumbnail/' . $latestArticles[$i]['thumbnail']); ?>" alt="<?= $latestArticles[$i]['judul_' . $lang]; ?>">
                            </a>
                            <div class="post-body">
                                <div class="post-category">
                                    <a href="/<?= $lang; ?>/kategori/<?= $latestArticles[$i]['kategori']['slug_' . $lang]; ?>">
                                        <?= $latestArticles[$i]['kategori']['nama_kategori_' . $lang]; ?>
                                    </a>
                                </div>
                                <h3 class="post-title">
                                    <a href="/<?= $lang; ?>/<?= $latestArticles[$i]['kategori']['slug_' . $lang]; ?>/<?= $latestArticles[$i]['slug_' . $lang]; ?>">
                                        <?= $latestArticles[$i]['judul_' . $lang]; ?>
                                    </a>
                                </h3>
                                <div class="article-meta">
                                    <span class="author"><?= $latestArticles[$i]['nama_lengkap'] ?? '' ?></span>
                                    <span class="publish-date"><?= date('d F Y', strtotime($latestArticles[$i]['published_at'])) ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Konten Utama & Sidebar -->
<div class="section">
    <div class="container">
        <div class="row">
            <!-- Konten Utama -->
            <div class="col-md-8">
                <?php
                $limitedCategories = array_slice($kategoriArtikel, 0, 3);
                foreach ($limitedCategories as $item):
                    $displayArticles = $item['artikels'];
                ?>
                    <div class="section-title">
                        <h2 class="title"><?= esc($item['kategori']['nama_kategori_' . $lang]) ?></h2>
                    </div>
                    <div class="row">
                        <?php if (!empty($displayArticles)): ?>
                            <?php foreach ($displayArticles as $artikel): ?>
                                <div class="col-md-4">
                                    <div class="post post-sm">
                                        <a class="post-img" href="/<?= esc($lang) ?>/<?= esc($item['kategori']['slug_' . $lang]) ?>/<?= esc($artikel['slug_' . $lang]) ?>">
                                            <img src="<?= base_url('uploads/thumbnail/' . $artikel['thumbnail']) ?>" alt="<?= $artikel['judul_' . $lang]; ?>" style="width:100%;height:200px;object-fit:cover;">
                                        </a>
                                        <div class="post-body">
                                            <div class="post-category">
                                                <a href="/<?= esc($lang) ?>/<?= esc($item['kategori']['slug_' . $lang]) ?>">
                                                    <?= esc($item['kategori']['nama_kategori_' . $lang]) ?>
                                                </a>
                                            </div>
                                            <h3 class="post-title title-sm">
                                                <a href="/<?= esc($lang) ?>/<?= esc($item['kategori']['slug_' . $lang]) ?>/<?= esc($artikel['slug_' . $lang]) ?>">
                                                    <?= esc($artikel['judul_' . $lang]) ?>
                                                </a>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Tidak ada artikel.</p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Sidebar -->
            <div class="col-md-4">
                <!-- Rekomendasi -->
                <?php if (!empty($recommendedArticles)): ?>
                    <div class="aside-widget">
                        <div class="section-title">
                            <h2 class="title"><?= $lang === 'en' ? 'Recommended for You' : 'Rekomendasi untuk Anda' ?></h2>
                        </div>
                        <?php foreach ($recommendedArticles as $artikel): ?>
                            <div class="post post-widget">
                                <a class="post-img" href="/<?= esc($lang) ?>/<?= esc($artikel['kategori']['slug_' . $lang]) ?>/<?= esc($artikel['slug_' . $lang]) ?>">
                                    <img src="<?= !empty($artikel['thumbnail']) ? base_url('uploads/thumbnail/' . $artikel['thumbnail']) : base_url('assets/img/default-thumbnail.jpg') ?>"
                                         style="max-height:80px;object-fit:cover;">
                                </a>
                                <div class="post-body">
                                    <div class="post-category">
                                        <a href="/<?= esc($lang) ?>/kategori/<?= esc($artikel['kategori']['slug_' . $lang]) ?>">
                                            <?= esc($artikel['kategori']['nama_kategori_' . $lang]) ?>
                                        </a>
                                    </div>
                                    <h3 class="post-title" style="font-size:14px;line-height:1.4;">
                                        <a href="/<?= esc($lang) ?>/<?= esc($artikel['kategori']['slug_' . $lang]) ?>/<?= esc($artikel['slug_' . $lang]) ?>">
                                            <?= esc($artikel['judul_' . $lang]) ?>
                                        </a>
                                    </h3>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Popular Posts -->
                <div class="aside-widget">
                    <div class="section-title">
                        <h2 class="title"><?= $lang === 'en' ? 'Popular Posts' : 'Artikel Populer' ?></h2>
                    </div>
                    <?php foreach ($popularArticles as $article): ?>
                        <div class="post post-widget">
                            <a class="post-img" href="/<?= $lang; ?>/<?= esc($article['kategori']['slug_' . $lang] ?? '') ?>/<?= esc($article['slug_' . $lang] ?? '') ?>">
                                <img src="<?= !empty($article['thumbnail']) ? base_url('uploads/thumbnail/' . $article['thumbnail']) : base_url('assets/img/default-thumbnail.jpg') ?>"
                                     style="max-height:80px;object-fit:cover;">
                            </a>
                            <div class="post-body">
                                <div class="post-category">
                                    <a href="/<?= esc($lang) ?>/<?= esc($article['kategori']['slug_' . $lang] ?? '') ?>">
                                        <?= esc($article['kategori']['nama_kategori_' . $lang] ?? 'Uncategorized') ?>
                                    </a>
                                </div>
                                <h3 class="post-title" style="font-size:14px;line-height:1.4;">
                                    <a href="/<?= esc($lang) ?>/<?= esc($article['kategori']['slug_' . $lang] ?? '') ?>/<?= esc($article['slug_' . $lang] ?? '') ?>">
                                        <?= esc($article['judul_' . $lang] ?? 'Judul tidak tersedia') ?>
                                    </a>
                                </h3>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
