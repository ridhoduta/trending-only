<?= $this->extend('layouts/template'); ?>

<?= $this->section('pageHeader'); ?>
<div class="page-header">
    <div class="page-header-bg" style="background-image: url('<?=
                                                                !empty($kategori['thumbnail'])
                                                                    ? base_url('uploads/kategori/' . $kategori['thumbnail'])
                                                                    : base_url('uploads/background-olahraga.jpg')
                                                                ?>');" data-stellar-background-ratio="0.5"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-offset-1 col-md-10 text-center">
                <h1 class="text-uppercase"><?= esc($kategori['nama_kategori_' . $lang]) ?></h1>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <div class="col-md-8">
                <!-- post -->
                <div class="post post-thumb">
                    <?php if (!empty($artikels) && isset($artikels[0])): ?>
                        <?php
                        $firstArticle = $artikels[0];
                        $categorySlug = $firstArticle['kategori_slug'] ?? $firstArticle['slug_kategori'] ?? $kategori['slug_' . $lang] ?? 'uncategorized';
                        $categoryName = $firstArticle['nama_kategori'] ?? $kategori['nama_kategori_' . $lang] ?? 'Uncategorized';
                        ?>

                        <a class="post-img" href="/<?= $lang; ?>/<?= $categorySlug ?>/<?= $firstArticle['slug_' . $lang] ?>">
                            <img src="<?= base_url('uploads/thumbnail/' . ($firstArticle['thumbnail'] ?? 'default-thumbnail.jpg')) ?>" 
                                 alt="<?= htmlspecialchars($firstArticle['judul_' . $lang] ?? '') ?>" 
                                 loading="lazy"
                                 width="800"
                                 height="450">
                        </a>

                        <div class="post-body">
                            <div class="post-category">
                                <a href="/<?= $lang; ?>/<?= $categorySlug ?>"><?= htmlspecialchars($categoryName) ?></a>
                            </div>
                            <h3 class="post-title title-lg">
                                <a href="/<?= $lang; ?>/<?= $categorySlug ?>/<?= $firstArticle['slug_' . $lang] ?>">
                                    <?= htmlspecialchars($firstArticle['judul_' . $lang] ?? 'No Title') ?>
                                </a>
                            </h3>
                            <div class="article-meta">
                                <span class="author"><?= htmlspecialchars($firstArticle['nama_lengkap'] ?? 'Penulis Tidak Diketahui', ENT_QUOTES) ?></span>
                                <span class="publish-date"><?= date('d F Y', strtotime($firstArticle['published_at'] ?? 'now')) ?></span>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <?= ($lang == 'id') ? 'Gambar tidak tersedia' : 'Picture not available' ?>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- /post -->

                <div class="row">
                    <!-- post -->
                    <div class="col-md-6">
                        <div class="post">
                            <?php
                            $article = $artikels[0] ?? null;
                            if ($article):
                                $catSlug = $article['kategori_slug'] ??
                                    $article['slug_kategori'] ??
                                    ($article['kategori']['slug_' . $lang] ??
                                        ($kategori['slug_' . $lang] ?? 'uncategorized'));

                                $catName = $article['nama_kategori'] ??
                                    ($article['kategori']['nama_kategori_' . $lang] ??
                                        ($kategori['nama_kategori_' . $lang] ?? 'Uncategorized'));
                            ?>
                                <a class="post-img" href="/<?= $lang; ?>/<?= $catSlug ?>/<?= $article['slug_' . $lang] ?>">
                                    <img src="<?= base_url('uploads/thumbnail/' . ($article['thumbnail'] ?? 'assets/img/post-3.jpg')) ?>"
                                        alt="<?= htmlspecialchars($article['judul_' . $lang] ?? '') ?>" 
                                        loading="lazy"
                                        width="400"
                                        height="225">
                                </a>
                                <div class="post-body">
                                    <div class="post-category">
                                        <a href="/<?= $lang; ?>/<?= $catSlug ?>"><?= htmlspecialchars($catName) ?></a>
                                    </div>
                                    <h3 class="post-title">
                                        <a href="/<?= $lang; ?>/<?= $catSlug ?>/<?= $article['slug_' . $lang] ?>">
                                            <?= htmlspecialchars($article['judul_' . $lang] ?? 'No Title') ?>
                                        </a>
                                    </h3>
                                    <ul class="post-meta">
                                        <li>
                                            <a href="/<?= $lang; ?>/author/<?= $article['penulis_slug'] ?? 'unknown' ?>">
                                                <?= htmlspecialchars($article['nama_lengkap'] ?? 'Penulis Tidak Diketahui') ?>
                                            </a>
                                        </li>
                                        <li><?= date('d F Y', strtotime($article['published_at'] ?? 'now')) ?></li>
                                    </ul>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning">Artikel tidak tersedia</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- /post -->

                    <!-- post -->
                    <div class="col-md-6">
                        <div class="post">
                            <?php
                            $article = $artikels[1] ?? null;
                            if ($article):
                                $catSlug = $article['kategori_slug'] ??
                                    $article['slug_kategori'] ??
                                    ($article['kategori']['slug_' . $lang] ??
                                        ($kategori['slug_' . $lang] ?? 'uncategorized'));

                                $catName = $article['nama_kategori'] ??
                                    ($article['kategori']['nama_kategori_' . $lang] ??
                                        ($kategori['nama_kategori_' . $lang] ?? 'Uncategorized'));
                            ?>
                                <a class="post-img" href="/<?= $lang; ?>/<?= $catSlug ?>/<?= $article['slug_' . $lang] ?>">
                                    <img src="<?= base_url('uploads/thumbnail/' . ($article['thumbnail'] ?? 'assets/img/post-3.jpg')) ?>"
                                        alt="<?= htmlspecialchars($article['judul_' . $lang] ?? '') ?>" 
                                        loading="lazy"
                                        width="400"
                                        height="225">
                                </a>
                                <div class="post-body">
                                    <div class="post-category">
                                        <a href="/<?= $lang; ?>/<?= $catSlug ?>"><?= htmlspecialchars($catName) ?></a>
                                    </div>
                                    <h3 class="post-title">
                                        <a href="/<?= $lang; ?>/<?= $catSlug ?>/<?= $article['slug_' . $lang] ?>">
                                            <?= htmlspecialchars($article['judul_' . $lang] ?? 'No Title') ?>
                                        </a>
                                    </h3>
                                    <ul class="post-meta">
                                        <li>
                                            <a href="/author/<?= $article['penulis_slug'] ?? 'unknown' ?>">
                                                <?= htmlspecialchars($article['nama_lengkap'] ?? 'Penulis Tidak Diketahui') ?>
                                            </a>
                                        </li>
                                        <li><?= date('d F Y', strtotime($article['published_at'] ?? 'now')) ?></li>
                                    </ul>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning">Artikel tidak tersedia</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- /post -->
                </div>

                <!-- post list -->
                <?php if (empty($artikels)): ?>
                    <p>Belum ada artikel di kategori ini.</p>
                <?php else: ?>
                    <?php foreach ($artikels as $artikel): ?>
                        <div class="post post-row">
                            <a class="post-img" href="/<?= esc($lang) ?>/<?= esc($kategori['slug_' . $lang]) ?>/<?= esc($artikel['slug_' . $lang]) ?>">
                                <img src="<?= base_url('uploads/thumbnail/' . ($artikel['thumbnail'] ?? 'assets/img/post-3.jpg')); ?>" 
                                     alt="<?= esc($artikel['judul_' . $lang]) ?>" 
                                     loading="lazy"
                                     width="150"
                                     height="100">
                            </a>
                            <div class="post-body">
                                <div class="post-category">
                                    <a href="/<?= esc($lang) ?>/<?= esc($kategori['slug_' . $lang]) ?>"><?= esc($kategori['nama_kategori_' . $lang]) ?></a>
                                </div>
                                <h3 class="post-title">
                                    <a href="/<?= esc($lang) ?>/<?= esc($kategori['slug_' . $lang]) ?>/<?= esc($artikel['slug_' . $lang]) ?>">
                                        <?= esc($artikel['judul_' . $lang]) ?>
                                    </a>
                                </h3>
                                <ul class="post-meta">
                                    <li><a href="/<?= esc($lang) ?>/author/<?= esc($artikel['penulis_slug'] ?? 'unknown') ?>">
                                        <?= esc($artikel['nama_lengkap'] ?? 'Penulis Tidak Diketahui') ?>
                                    </a></li>
                                    <li><?= date('d F Y', strtotime($artikel['published_at'] ?? 'now')) ?></li>
                                </ul>
                                <br>
                                <?php
                                $content = strip_tags($artikel['konten_' . $lang]);
                                $limitedContent = mb_substr($content, 0, 200, 'UTF-8');
                                $showReadMore = mb_strlen($content) > 200;
                                ?>
                                
                                <p class="post-excerpt">
                                    <?= esc($limitedContent) ?>
                                    <?php if ($showReadMore): ?>
                                        <span class="read-more-dots">...</span>
                                    <?php endif; ?>
                                </p>
                                
                                <?php if ($showReadMore): ?>
                                    <a href="<?= base_url($lang.'/'.$kategori['slug_' . $lang] . '/' . $artikel['slug_' . $lang]); ?>" class="read-more">
                                        Baca Selengkapnya
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <!-- /post list -->

                <?php if (!empty($artikels) && count($artikels) > 1): ?>
                    <div class="section-row loadmore text-center">
                        <a href="#" class="primary-button">Load More</a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-md-4">
                <!-- ad widget-->
                <div class="aside-widget text-center">
                    <a href="https://wa.me/6282245975428" style="display: inline-block;margin: auto;">
                        <img class="img-responsive" 
                             src="<?= base_url('assets/img/ad-3.jpg'); ?>" 
                             alt="Iklan"
                             width="300"
                             height="250">
                    </a>
                </div>
                <!-- /ad widget -->

                <!-- post widget -->
                <div class="aside-widget">
                    <div class="section-title">
                        <h2 class="title">Popular Posts</h2>
                    </div>
                    <?php foreach ($popularArticles as $article): ?>
                        <!-- post -->
                        <div class="post post-widget">
                            <a class="post-img" href="/<?= esc($lang) ?>/<?= esc($article['kategori']['slug_' . $lang]) ?>/<?= esc($article['slug_' . $lang]) ?>">
                                <img src="<?= base_url('uploads/thumbnail/' . $article['thumbnail']) ?>" 
                                     alt="<?= $article['judul_' . $lang] ?>" 
                                     loading="lazy"
                                     width="100"
                                     height="80">
                            </a>
                            <div class="post-body">
                                <div class="post-category">
                                    <a href="/<?= esc($lang) ?>/<?= esc($article['kategori']['slug_' . $lang]) ?>">
                                        <?= esc($article['kategori']['nama_kategori_' . $lang]) ?>
                                    </a>
                                </div>
                                <h3 class="post-title">
                                    <a href="/<?= esc($lang) ?>/<?= esc($article['kategori']['slug_' . $lang]) ?>/<?= esc($article['slug_' . $lang]) ?>">
                                        <?= esc($article['judul_' . $lang]) ?>
                                    </a>
                                </h3>
                            </div>
                        </div>
                        <!-- /post -->
                    <?php endforeach; ?>
                </div>
                <!-- /post widget -->

                <!-- Ad widget -->
                <div class="aside-widget text-center">
                    <a href="https://wa.me/6282245975428" style="display: inline-block;margin: auto;">
                        <img class="img-responsive" 
                             src="<?= base_url('assets/img/ad-1.jpg'); ?>" 
                             alt="Iklan"
                             width="300"
                             height="250">
                    </a>
                </div>
                <!-- /Ad widget -->
            </div>
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>

<?= $this->endSection(); ?>