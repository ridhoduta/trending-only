<?= $this->extend('layouts/template') ?>

<?= $this->section('pageHeader') ?>
<div id="post-header" class="page-header">
    <div class="page-header-bg"
        style="background-image: <?= !empty($artikel['thumbnail']) ? 'url(' . base_url('uploads/thumbnail/' . $artikel['thumbnail']) . ')' : 'none' ?>;
            background-color: #000000;"
        data-stellar-background-ratio="0.5">
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <div class="post-category">
                    <a
                        href="<?= base_url($lang . '/' . $kategori[service('request')->getLocale() == 'en' ? 'slug_en' : 'slug_id']) ?>">
                        <?= esc($kategori[service('request')->getLocale() == 'en' ? 'nama_kategori_en' : 'nama_kategori_id']) ?>
                    </a>
                </div>
                <h1><?= esc($artikel[service('request')->getLocale() == 'en' ? 'judul_en' : 'judul_id']) ?></h1>
                <ul class="post-meta">
                    <li><a href="#"><?= esc($artikel['nama_lengkap']) ?></a></li>
                    <li><?= date('d F Y', strtotime(esc($artikel['published_at']))) ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="section">
    <div class="container">
        <div class="row">
            <!-- Konten artikel -->
            <div class="col-md-8">
                <div class="section-row">
                    <?php if (!empty($artikel['gambar_besar'])): ?>
                    <div class="featured-image-container" style="width: 100%; margin: 0 auto 20px;">
                        <img src="<?= base_url('uploads/gambar_besar/' . $artikel['gambar_besar']) ?>"
                            alt="<?= esc($artikel['judul_id']) ?>"
                            style="width: 100%; max-width: 100%; height: auto; max-height: 500px; display: block; margin: 0 auto;">
                    </div>
                    <div
                        style="font-size:12px; color:#666; margin-top:8px; line-height:1.4; font-family:Arial,sans-serif;">
                        <span style="display:inline-block; vertical-align:top; width:60px;">Sumber :</span>
                        <span
                            style="word-break:break-all; display:inline-block; width:calc(100% - 65px); vertical-align:top;">
                            <?= !empty($artikel['sumber_gambar']) && trim($artikel['sumber_gambar']) !== '' ? esc($artikel['sumber_gambar']) : (service('request')->getLocale() == 'en' ? 'Unknown' : 'Tidak Diketahui') ?>
                        </span>
                    </div>
                    <br>
                    <?php else: ?>
                    <div class="alert alert-warning">
                        <?= $lang == 'id' ? 'Gambar tidak tersedia' : 'Picture not available' ?>
                    </div>
                    <?php endif; ?>
                    <p><?= $artikel[service('request')->getLocale() == 'en' ? 'konten_en' : 'konten_id'] ?></p>
                </div>

                <!-- Tags -->
                <div class="section-row">
                    <div class="post-tags">
                        <ul>
                            <li><?= service('request')->getLocale() == 'en' ? 'TAGS:' : 'TAG:' ?></li>
                            <?php
                            $tags = explode(',', $artikel[service('request')->getLocale() == 'en' ? 'tags_en' : 'tags_id']);
                            foreach ($tags as $tag):
                                $trimmedTag = trim($tag);
                                if (!empty($trimmedTag)):
                            ?>
                            <li><a href="#"><?= $trimmedTag ?></a></li>
                            <?php
                                endif;
                            endforeach;
                            ?>
                        </ul>
                    </div>
                </div>

                <!-- Related posts -->
                <div>
                    <div class="section-title">
                        <h3 class="title">
                            <?= service('request')->getLocale() == 'en' ? 'Related Posts' : 'Post Terkait' ?></h3>
                    </div>
                    <div class="row">
                        <?php foreach ($relatedArticles as $related): ?>
                        <div class="col-md-4">
                            <div class="post post-sm">
                                <a class="post-img"
                                    href="<?= base_url($lang . '/' . $related['kategori_slug_' . $lang] . '/' . $related['slug_' . $lang]) ?>">
                                    <img src="<?= base_url('uploads/thumbnail/' . $related['thumbnail']) ?>"
                                        alt="<?= $related['judul_' . $lang] ?>">
                                </a>
                                <div class="post-body">
                                    <div class="post-category">
                                        <a href="<?= base_url($lang . '/' . $related['kategori_slug_' . $lang]) ?>">
                                            <?= $related['nama_kategori_' . $lang] ?>
                                        </a>
                                    </div>
                                    <h3 class="post-title title-sm">
                                        <a
                                            href="<?= base_url($lang . '/' . $related['kategori_slug_' . $lang] . '/' . $related['slug_' . $lang]) ?>">
                                            <?= $related['judul_' . $lang] ?>
                                        </a>
                                    </h3>
                                    <ul class="post-meta">
                                        <li><a href="#"><?= $related['nama_lengkap'] ?></a></li>
                                        <li><?= date('d F Y', strtotime($related['published_at'])) ?></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-md-4">
                <!-- ad widget -->
                <div class="aside-widget text-center">
                    <a href="Https://wa.me/6282245975428" style="display: inline-block;margin: auto;">
                        <img class="img-responsive" src="<?= base_url('assets/img/ad-3.jpg') ?>" alt="">
                    </a>
                </div>

                <!-- Recommended widget -->
                <?php if (!empty($recommendedArticles)): ?>
                <div class="aside-widget">
                    <div class="section-title">
                        <h2 class="title">
                            <?= service('request')->getLocale() == 'en' ? 'Recommended for You' : 'Rekomendasi untuk Anda' ?>
                        </h2>
                    </div>
                    <?php foreach ($recommendedArticles as $rec): ?>
                    <div class="post post-widget">
                        <a class="post-img"
                            href="<?= base_url($lang . '/' . $rec['kategori']['slug_' . $lang] . '/' . $rec['slug_' . $lang]) ?>">
                            <img src="<?= base_url('uploads/thumbnail/' . $rec['thumbnail']) ?>"
                                alt="<?= esc($rec['judul_' . $lang]) ?>">
                        </a>
                        <div class="post-body">
                            <div class="post-category">
                                <a href="<?= base_url($lang . '/' . $rec['kategori']['slug_' . $lang]) ?>">
                                    <?= esc($rec['kategori']['nama_kategori_' . $lang]) ?>
                                </a>
                            </div>
                            <h3 class="post-title">
                                <a
                                    href="<?= base_url($lang . '/' . $rec['kategori']['slug_' . $lang] . '/' . $rec['slug_' . $lang]) ?>">
                                    <?= esc($rec['judul_' . $lang]) ?>
                                </a>
                            </h3>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Popular widget -->
                <div class="aside-widget">
                    <div class="section-title">
                        <h2 class="title">
                            <?= service('request')->getLocale() == 'en' ? 'Popular Posts' : 'Post Populer' ?>
                        </h2>
                    </div>
                    <?php foreach ($popularArticles as $article): ?>
                    <div class="post post-widget">
                        <a class="post-img"
                            href="<?= base_url($lang . '/' . $article['kategori']['slug_' . $lang] . '/' . $article['slug_' . $lang]) ?>">
                            <img src="<?= base_url('uploads/thumbnail/' . $article['thumbnail']) ?>"
                                alt="<?= $article['judul_' . $lang] ?>">
                        </a>
                        <div class="post-body">
                            <div class="post-category">
                                <a href="<?= base_url($lang . '/' . $article['kategori']['slug_' . $lang]) ?>">
                                    <?= $article['kategori']['nama_kategori_' . $lang] ?>
                                </a>
                            </div>
                            <h3 class="post-title">
                                <a
                                    href="<?= base_url($lang . '/' . $article['kategori']['slug_' . $lang] . '/' . $article['slug_' . $lang]) ?>">
                                    <?= $article['judul_' . $lang] ?>
                                </a>
                            </h3>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Tracking aktivitas -->
    <script>
        const activityUrl = "<?= rtrim(base_url($lang . '/artikel/activity'), '/') ?>";
        document.addEventListener("DOMContentLoaded", function() {
            let startTime = Date.now();
            let maxScroll = 0;

            window.addEventListener("scroll", function() {
                let scrollTop = window.scrollY;
                let docHeight = document.documentElement.scrollHeight - window.innerHeight;
                let scrollPercent = (scrollTop / docHeight) * 100;
                maxScroll = Math.max(maxScroll, scrollPercent);
            });

            function sendActivity(type, value) {
                fetch(activityUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        article_id: <?= $artikel['id_artikel'] ?>,
                        type: type,
                        value: value
                    })
                }).catch(err => console.error("Gagal kirim aktivitas:", err));
            }

            setInterval(function() {
                let timeSpent = Math.floor((Date.now() - startTime) / 1000);
                sendActivity("scroll_percentage", maxScroll.toFixed(2));
                sendActivity("time_spent_seconds", timeSpent);
            }, 10000);

            window.addEventListener("beforeunload", function() {
                let timeSpent = Math.floor((Date.now() - startTime) / 1000);

                let timeData = new Blob(
                    [JSON.stringify({
                        article_id: <?= $artikel['id_artikel'] ?>,
                        type: "time_spent_seconds",
                        value: timeSpent
                    })], {
                        type: 'application/json'
                    }
                );

                let scrollData = new Blob(
                    [JSON.stringify({
                        article_id: <?= $artikel['id_artikel'] ?>,
                        type: "scroll_percentage",
                        value: maxScroll.toFixed(2)
                    })], {
                        type: 'application/json'
                    }
                );

                navigator.sendBeacon(activityUrl, timeData);
                navigator.sendBeacon(activityUrl, scrollData);
            });
        });
    </script>
</div>
<?= $this->endSection() ?>
