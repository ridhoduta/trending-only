<footer id="footer">
    <div class="container">
        <div class="row">
            <!-- Logo & Deskripsi -->
            <div class="col-md-3">
                <div class="footer-widget">
                    <div class="footer-logo">
                         <a href="#" class="logo"><img src="<?= base_url('assets/Trending only.png'); ?>" alt="" style="max-width: 350px; height: auto; margin-top: -50px; margin-left: -15px;" ></a>
                    </div>
                    <p>
        <?php if (isset($meta)) : ?>
            <?= $lang == 'id' ? ($meta['meta_description_id'] ?? 'Berita terkini dan tren viral') : ($meta['meta_description_en'] ?? 'Latest news and viral trends'); ?>
        <?php else : ?>
            Nec feugiat nisl pretium fusce id velit ut tortor pretium. Nisl purus in mollis nunc sed. Nunc non blandit massa enim nec.
        <?php endif; ?>
    </p>
                </div>
            </div>

            <!-- Kategori -->
            <div class="col-md-6">
                <div class="footer-widget">
                    <h3 class="footer-title">Categories</h3>
                    <div class="row">
                        <?php
                        $chunks = array_chunk($allKategoris, 4); // Bagi setiap 4 item per kolom
                        foreach ($chunks as $chunk): ?>
                            <div class="col-md-6">
                                <div class="category-widget">
                                    <ul>
                                        <?php foreach ($chunk as $item): ?>
                                            <?php
                                            $slug = $item['kategori']['slug_' . $lang] ?? null;
                                            $nama = $item['kategori']['nama_kategori_' . $lang] ?? 'Tanpa Nama';
                                            ?>
                                            <?php if ($slug): ?>
                                                <li>
                                                    <a href="<?= base_url('en/' . $slug) ?>">
                                                        <?= esc($nama) ?>
                                                        <span><?= esc($item['count']) ?></span>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Newsletter -->
            <div class="col-md-3">
                <div class="footer-widget">
                    <h3 class="footer-title">Newsletter</h3>
                    <div class="newsletter-widget">
                        <form>
                            <p>Nec feugiat nisl pretium fusce id velit ut tortor pretium.</p>
                            <input class="input" name="newsletter" placeholder="Enter Your Email" disabled>
                            <button class="primary-button" disabled>Subscribe</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer bottom -->
        <div class="footer-bottom row">
            <div class="col-md-6 col-md-push-6">
                <ul class="footer-nav">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Contacts</a></li>
                    <li><a href="#">Advertise</a></li>
                    <li><a href="#">Privacy</a></li>
                </ul>
            </div>
            <div class="col-md-6 col-md-pull-6">
                <div class="footer-copyright">
                    Copyright &copy;<script>document.write(new Date().getFullYear());</script>
                    All rights reserved | Made with <i class="fa fa-heart-o" aria-hidden="true"></i> by
                    <a href="https://colorlib.com" target="_blank">Colorlib</a> &amp; distributed by
                    <a href="https://themewagon.com" target="_blank">ThemeWagon</a>
                </div>
            </div>
        </div>
    </div>
</footer>
