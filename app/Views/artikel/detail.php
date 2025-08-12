<?= $this->extend('layouts/template'); ?>

<?= $this->section('pageHeader'); ?>
<div id="post-header" class="page-header">
    <div class="page-header-bg"
     style="background-image: <?= !empty($artikel['thumbnail']) ? 'url(' . base_url('uploads/thumbnail/' . $artikel['thumbnail']) . ')' : 'none' ?>;
            background-color: #000000;"
     data-stellar-background-ratio="0.5">
</div>

(
    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <div class="post-category">
                    <a href="<?= base_url($lang .'/'. $kategori[service('request')->getLocale() == 'en' ? 'slug_en' : 'slug_id']) ?>">
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
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <div class="col-md-8">
                <!-- post share -->
                <!--<div class="section-row">-->
                <!--    <div class="post-share">-->
                <!--        <a href="#" class="social-facebook"><i class="fa fa-facebook"></i><span><?= service('request')->getLocale() == 'en' ? 'Share' : 'Bagikan' ?></span></a>-->
                <!--        <a href="#" class="social-twitter"><i class="fa fa-twitter"></i><span><?= service('request')->getLocale() == 'en' ? 'Tweet' : 'Tweet' ?></span></a>-->
                <!--        <a href="#" class="social-pinterest"><i class="fa fa-pinterest"></i><span><?= service('request')->getLocale() == 'en' ? 'Pin' : 'Sematkan' ?></span></a>-->
                <!--        <a href="#"><i class="fa fa-envelope"></i><span><?= service('request')->getLocale() == 'en' ? 'Email' : 'Email' ?></span></a>-->
                <!--    </div>-->
                <!--</div>-->
                <!-- /post share -->

                <!-- post content -->
                <div class="section-row">
                    <?php if (!empty($artikel['gambar_besar'])): ?>
                        <div class="featured-image-container" style="width: 100%; margin: 0 auto 20px;">
                            <img src="<?= base_url('uploads/gambar_besar/' . $artikel['gambar_besar']) ?>"
                                alt="<?= esc($artikel['judul_id']) ?>"
                                style="width: 100%; max-width: 100%; height: auto; max-height: 500px; display: block; margin: 0 auto;">
                        </div>
                    <div style="font-size:12px; color:#666; margin-top:8px; line-height:1.4; font-family:Arial,sans-serif;">
                        <span style="display:inline-block; vertical-align:top; width:60px;">Sumber :</span>
                        <span style="word-break:break-all; display:inline-block; width:calc(100% - 65px); vertical-align:top;">
                            <?= (!empty($artikel['sumber_gambar']) && trim($artikel['sumber_gambar']) !== '')
                                ? esc($artikel['sumber_gambar'])
                                : (service('request')->getLocale() == 'en' ? 'Unknown' : 'Tidak Diketahui') ?>
                        </span>
                    </div>
                    <br>
                    <?php elseif (empty($artikel['gambar_besar'])): ?>
                        <div class="alert alert-warning">
                            <?= ($lang == 'id') ? 'Gambar tidak tersedia' : 'Picture not available' ?>
                        </div>
                    <?php endif; ?>
                    <p><?= ($artikel[service('request')->getLocale() == 'en' ? 'konten_en' : 'konten_id']) ?></p>
                </div>
                <!-- /post content -->

                <!-- post tags -->
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
                <!-- /post tags -->

                <!-- post nav -->
                <div class="section-row">
                    <div class="post-nav">
                        <?php if (!empty($previousArticle)): ?>
                            <div class="prev-post">
                                <a class="post-img" href="<?= base_url($lang . '/' . $previousArticle['kategori_slug_' . $lang] . '/' . $previousArticle['slug_' . $lang]) ?>">
                                    <img src="<?= base_url('uploads/thumbnail/' . $previousArticle['thumbnail']) ?>" alt="<?= $previousArticle['judul_' . $lang] ?>">
                                </a>
                                <h3 class="post-title">
                                    <a href="<?= base_url($lang . '/' . $previousArticle['kategori_slug_' . $lang] . '/' . $previousArticle['slug_' . $lang]) ?>">
                                        <?= $previousArticle['judul_' . $lang] ?>
                                    </a>
                                </h3>
                                <span><?= service('request')->getLocale() == 'en' ? 'Previous post' : 'Post sebelumnya' ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($nextArticle)): ?>
                            <div class="next-post">
                                <a class="post-img" href="<?= base_url($lang . '/' . $nextArticle['kategori_slug_' . $lang] . '/' . $nextArticle['slug_' . $lang]) ?>">
                                    <img src="<?= base_url('uploads/thumbnail/' . $nextArticle['thumbnail']) ?>" alt="<?= $nextArticle['judul_' . $lang] ?>">
                                </a>
                                <h3 class="post-title">
                                    <a href="<?= base_url($lang . '/' . $nextArticle['kategori_slug_' . $lang] . '/' . $nextArticle['slug_' . $lang]) ?>">
                                        <?= $nextArticle['judul_' . $lang] ?>
                                    </a>
                                </h3>
                                <span><?= service('request')->getLocale() == 'en' ? 'Next post' : 'Post berikutnya' ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- /post nav  -->

                <!-- post author -->
                <!--<div class="section-row">-->
                <!--    <div class="section-title">-->
                <!--        <h3 class="title"><?= service('request')->getLocale() == 'en' ? 'About' : 'Tentang' ?> <a href="#"><?= esc($artikel['nama_lengkap']) ?></a></h3>-->
                <!--    </div>-->
                <!--    <div class="author media">-->
                <!--        <div class="media-left">-->
                <!--            <a href="#">-->
                <!--                <img class="author-img media-object" src="<?= base_url('assets/img/avatar-1.jpg'); ?>" alt="">-->
                <!--            </a>-->
                <!--        </div>-->
                <!--        <div class="media-body">-->
                <!--            <p><?= service('request')->getLocale() == 'en' ? 'Author bio in English' : 'Bio penulis dalam Bahasa Indonesia' ?></p>-->
                <!--            <ul class="author-social">-->
                <!--                <li><a href="#"><i class="fa fa-facebook"></i></a></li>-->
                <!--                <li><a href="#"><i class="fa fa-twitter"></i></a></li>-->
                <!--                <li><a href="#"><i class="fa fa-google-plus"></i></a></li>-->
                <!--                <li><a href="#"><i class="fa fa-instagram"></i></a></li>-->
                <!--            </ul>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->
                <!-- /post author -->

                <!-- /related post -->
                <div>
                    <div class="section-title">
                        <h3 class="title"><?= service('request')->getLocale() == 'en' ? 'Related Posts' : 'Post Terkait' ?></h3>
                    </div>
                    <div class="row">
                        <?php foreach ($relatedArticles as $related): ?>
                            <!-- post -->
                            <div class="col-md-4">
                                <div class="post post-sm">
                                    <a class="post-img" href="<?= base_url($lang . '/' . $related['kategori_slug_' . $lang] . '/' . $related['slug_' . $lang]) ?>">
                                        <img src="<?= base_url('uploads/thumbnail/' . $related['thumbnail']) ?>" alt="<?= $related['judul_' . $lang] ?>">
                                    </a>
                                    <div class="post-body">
                                        <div class="post-category">
                                            <a href="<?= base_url($lang . '/' . $related['kategori_slug_' . $lang]) ?>">
                                                <?= $related['nama_kategori_' . $lang] ?>
                                            </a>
                                        </div>
                                        <h3 class="post-title title-sm">
                                            <a href="<?= base_url($lang . '/' . $related['kategori_slug_' . $lang] . '/' . $related['slug_' . $lang]) ?>">
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
                            <!-- /post -->
                        <?php endforeach; ?>
                    </div>
                </div>
                <!-- /related post -->
            </div>
            <div class="col-md-4">
                <!-- ad widget -->
                <div class="aside-widget text-center">
                    <a href="Https://wa.me/6282245975428" style="display: inline-block;margin: auto;">
                        <img class="img-responsive" src="<?= base_url('assets/img/ad-3.jpg') ?>" alt="">
                    </a>
                </div>
                <!-- /ad widget -->

                <!-- social widget -->
                <!--<div class="aside-widget">-->
                <!--    <div class="section-title">-->
                <!--        <h2 class="title"><?= service('request')->getLocale() == 'en' ? 'Social Media' : 'Media Sosial' ?></h2>-->
                <!--    </div>-->
                <!--    <div class="social-widget">-->
                <!--        <ul>-->
                <!--            <li>-->
                <!--                <a href="#" class="social-facebook">-->
                <!--                    <i class="fa fa-facebook"></i>-->
                <!--                    <span>21.2K<br><?= service('request')->getLocale() == 'en' ? 'Followers' : 'Pengikut' ?></span>-->
                <!--                </a>-->
                <!--            </li>-->
                <!--            <li>-->
                <!--                <a href="#" class="social-twitter">-->
                <!--                    <i class="fa fa-twitter"></i>-->
                <!--                    <span>10.2K<br><?= service('request')->getLocale() == 'en' ? 'Followers' : 'Pengikut' ?></span>-->
                <!--                </a>-->
                <!--            </li>-->
                <!--            <li>-->
                <!--                <a href="#" class="social-google-plus">-->
                <!--                    <i class="fa fa-google-plus"></i>-->
                <!--                    <span>5K<br><?= service('request')->getLocale() == 'en' ? 'Followers' : 'Pengikut' ?></span>-->
                <!--                </a>-->
                <!--            </li>-->
                <!--        </ul>-->
                <!--    </div>-->
                <!--</div>-->
                <!-- /social widget -->
                <!-- post widget -->
                <div class="aside-widget">
                    <div class="section-title">
                        <h2 class="title"><?= service('request')->getLocale() == 'en' ? 'Popular Posts' : 'Post Populer' ?></h2>
                    </div>
                    <?php foreach ($popularArticles as $article): ?>
                        <!-- post -->
                        <div class="post post-widget">
                            <a class="post-img" href="<?= base_url($lang . '/' . $article['kategori']['slug_' . $lang] . '/' . $article['slug_' . $lang]) ?>">
                                <img src="<?= base_url('uploads/thumbnail/' . $article['thumbnail']) ?>" alt="<?= $article['judul_' . $lang] ?>">
                            </a>
                            <div class="post-body">
                                <div class="post-category">
                                    <a href="<?= base_url($lang . '/' . $article['kategori']['slug_' . $lang]) ?>">
                                        <?= $article['kategori']['nama_kategori_' . $lang] ?>
                                    </a>
                                </div>
                                <h3 class="post-title">
                                    <a href="<?= base_url($lang . '/' . $article['kategori']['slug_' . $lang] . '/' . $article['slug_' . $lang]) ?>">
                                        <?= $article['judul_' . $lang] ?>
                                    </a>
                                </h3>
                            </div>
                        </div>
                        <!-- /post -->
                    <?php endforeach; ?>
                </div>
                <!-- /post widget -->

                <!-- newsletter widget -->
                <!--<div class="aside-widget">-->
                <!--    <div class="section-title">-->
                <!--        <h2 class="title"><?= service('request')->getLocale() == 'en' ? 'Newsletter' : 'Buletin' ?></h2>-->
                <!--    </div>-->
                <!--    <div class="newsletter-widget">-->
                <!--        <form>-->
                <!--            <p><?= service('request')->getLocale() == 'en' ? 'Newsletter description text' : 'Teks deskripsi buletin' ?></p>-->
                <!--            <input class="input" placeholder="<?= service('request')->getLocale() == 'en' ? 'Enter Your Email' : 'Masukkan Email Anda' ?>">-->
                <!--            <button class="primary-button"><?= service('request')->getLocale() == 'en' ? 'Subscribe' : 'Berlangganan' ?></button>-->
                <!--        </form>-->
                <!--    </div>-->
                <!--</div>-->
                <!-- /newsletter widget -->

                <!-- Ad widget -->
                <div class="aside-widget text-center">
                    <a href="Https://wa.me/6282245975428" style="display: inline-block;margin: auto;">
                        <img class="img-responsive" src="<?= base_url('assets/img/ad-1.jpg') ?>" alt="">
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