<?php
// Pastikan variabel $lang, $currentCategorySlug, $currentArticleSlug, $categories sudah tersedia

// Ambil bahasa dari session, fallback ke 'id'
$lang_segment = session()->get('lang') ?? 'id';

// Tentukan bahasa baru
$newLang = ($lang_segment === 'id') ? 'en' : 'id';

// Definisikan homeLink berdasarkan bahasa saat ini
$homeLink = $lang_segment; // base_url('en') atau base_url('id')

// Model kategori & artikel
$categoryModel = new \App\Models\KategoriModel();
$articleModel = new \App\Models\ArtikelModel();

// Default switchUrl ke homepage bahasa baru
$switchUrl = base_url($newLang);

// Logika switch URL
// Kalau di halaman detail artikel
if (!empty($currentCategorySlug) && !empty($currentArticleSlug)) {
    // Cari kategori berdasarkan slug dan bahasa saat ini
    $category = $categoryModel->where("slug_{$lang_segment}", $currentCategorySlug)->first();
    // Cari artikel berdasarkan slug dan bahasa saat ini
    $article = $articleModel->where("slug_{$lang_segment}", $currentArticleSlug)->first();

    if ($category && $article) {
        // Ambil slug kategori dan artikel di bahasa target
        $newCategorySlug = $category["slug_{$newLang}"];
        $newArticleSlug = $article["slug_{$newLang}"];

        // Bangun URL switch bahasa ke detail artikel
        $switchUrl = base_url("{$newLang}/{$newCategorySlug}/{$newArticleSlug}");
    }
}

// Kalau di halaman kategori (tidak detail artikel)
elseif (!empty($currentCategorySlug) && empty($currentArticleSlug)) {
    $category = $categoryModel->where("slug_{$lang_segment}", $currentCategorySlug)->first();

    if ($category) {
        $newCategorySlug = $category["slug_{$newLang}"];
        // URL switch bahasa ke halaman kategori
        $switchUrl = base_url("{$newLang}/{$newCategorySlug}");
    }
}
// Kalau halaman lain, tetap arahkan ke homepage bahasa baru (default)
else {
    $switchUrl = base_url($newLang);
}

// Buat link kategori navbar
$categoryLinks = [];
if (!empty($categories)) {
    foreach ($categories as $cat) {
        $slug = $cat["slug_{$lang_segment}"];
        $name = $cat["nama_kategori_{$lang_segment}"];
        $categoryLinks[] = [
            'url' => base_url("{$lang_segment}/{$slug}"),
            'name' => $name
        ];
    }
}
?>






<div id="nav">
    <!-- Top Nav -->
    <div id="nav-top">
        <div class="container">
            <!-- social -->
            <!--<ul class="nav-social">-->
            <!--    <li><a href="#"><i class="fa fa-facebook"></i></a></li>-->
            <!--    <li><a href="#"><i class="fa fa-twitter"></i></a></li>-->
            <!--    <li><a href="#"><i class="fa fa-google-plus"></i></a></li>-->
            <!--    <li><a href="#"><i class="fa fa-instagram"></i></a></li>-->
            <!--</ul>-->
            <!-- /social -->

            <!-- logo -->
            <div class="nav-logo">
                <a href="#" class="logo"><img src="<?= base_url('assets/Trending only.png'); ?>" alt=""></a>
            </div>
            <!-- /logo -->

            <!-- search & aside toggle -->
            <div class="nav-btns">
                <button class="aside-btn" aria-label="Buka menu navigasi">
  <i class="fa fa-bars"></i>
</button>

                <!--<button class="search-btn"><i class="fa fa-search"></i></button>-->
                <!--<div id="nav-search">-->
                <!--    <form>-->
                <!--        <input class="input" name="search" placeholder="Enter your search..." disabled>-->
                <!--    </form>-->
                <!--    <button class="nav-close search-close">-->
                <!--        <span></span>-->
                <!--    </button>-->
                <!--</div>-->
            </div>
            <!-- /search & aside toggle -->
        </div>
    </div>
    <!-- /Top Nav -->

    <!-- Main Nav -->
    <div id="nav-bottom">
        <div class="container">
            <ul class="nav-menu">
                <?php
                $slugField = ($lang_segment === 'en') ? 'slug_en' : 'slug_id';
                $nameField = ($lang_segment === 'en') ? 'nama_kategori_en' : 'nama_kategori_id';
                ?>

                <li><a href="<?= base_url($homeLink) ?>"><?= ($lang_segment === 'en') ? 'Home' : 'Beranda' ?></a></li>

                <?php
                $topCategories = array_slice($allKategoris, 0, 3);
                foreach ($topCategories as $item):
                    $slug = $item['kategori'][$slugField];
                    $name = $item['kategori'][$nameField];
                ?>
                    <li>
                        <a href="<?= base_url("$lang_segment/" . urlencode($slug)) ?>">
                            <?= esc($name) ?>
                        </a>
                    </li>
                <?php endforeach; ?>

                <?php if (count($allKategoris) > 3): ?>
                    <li class="has-dropdown megamenu">
                        <a href="#" 
   aria-haspopup="true" 
   aria-expanded="false">
    <?= ($lang_segment === 'en') ? 'More Categories' : 'Kategori Lainnya' ?>
</a>
                        <div class="dropdown">
                            <div class="dropdown-body">
                                <div class="row">
                                    <?php
                                    $otherCategories = array_slice($allKategoris, 3);
                                    $chunks = array_chunk($otherCategories, ceil(count($otherCategories) / 4));
                                    foreach ($chunks as $column): ?>
                                        <div class="col-md-3">
                                            <ul class="dropdown-list">
                                                <?php foreach ($column as $item):
                                                    $slug = $item['kategori'][$slugField];
                                                    $name = $item['kategori'][$nameField];
                                                ?>
                                                    <li>
                                                        <a href="<?= base_url("$lang_segment/" . urlencode($slug)) ?>">
                                                            <?= esc($name) ?>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endif; ?>


                <li class="nav-lang-switch">
                    <a href="<?= esc($switchUrl) ?>">
                        Switch to <?= strtoupper($newLang) ?>
                    </a>
                </li>

            </ul>
        </div>
    </div>


    <!-- /Main Nav -->

    <!-- Aside Nav -->
    <div id="nav-aside">
        <ul class="nav-aside-menu">
            <?php
            $slugField = ($lang_segment === 'en') ? 'slug_en' : 'slug_id';
            $nameField = ($lang_segment === 'en') ? 'nama_kategori_en' : 'nama_kategori_id';
            ?>

            <li><a href="<?= base_url($homeLink) ?>"><?= ($lang_segment === 'en') ? 'Home' : 'Beranda' ?></a></li>

            <?php
            // Show the same top 3 categories as in main nav
            $topCategories = array_slice($allKategoris, 0, 3);
            foreach ($topCategories as $item):
                $slug = $item['kategori'][$slugField];
                $name = $item['kategori'][$nameField];
            ?>
                <li>
                    <a href="<?= base_url("$lang_segment/" . urlencode($slug)) ?>">
                        <?= esc($name) ?>
                    </a>
                </li>
            <?php endforeach; ?>

            <?php if (count($allKategoris) > 3): ?>
                <li class="has-dropdown">
                    <a href="#" 
   aria-haspopup="true" 
   aria-expanded="false">
    <?= ($lang_segment === 'en') ? 'More Categories' : 'Kategori Lainnya' ?>
</a>
                    <ul class="dropdown">
                        <?php
                        // Show all remaining categories in a single column
                        $remainingCategories = array_slice($allKategoris, 3);
                        foreach ($remainingCategories as $item):
                            $slug = $item['kategori'][$slugField];
                            $name = $item['kategori'][$nameField];
                        ?>
                            <li>
                                <a href="<?= base_url("$lang_segment/" . urlencode($slug)) ?>">
                                    <?= esc($name) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <li class="nav-lang-switch">
                <a href="<?= esc($switchUrl) ?>">
                    <?= ($lang_segment === 'en') ? 'Switch to Indonesian' : 'Switch to English' ?>
                </a>
            </li>
        </ul>
       <button class="nav-close nav-aside-close" aria-label="Tutup menu navigasi">
  <i class="fa fa-times"></i>
</button>

    </div>
    <!-- /Aside Nav -->
</div>