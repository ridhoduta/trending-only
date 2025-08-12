<!DOCTYPE html>
<html lang="<?= session()->get('lang') ?? 'id'; ?>" aria-label="Situs berita Trending-only.com">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Optimized Viewport -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
    
    <title><?= $lang == 'id' ? ($meta['meta_title_id'] ?? 'Trending-only.com | Berita & Tren Terkini') : ($meta['meta_title_en'] ?? 'Trending-only.com | Latest News & Trends'); ?></title>
    
    <meta name="description" content="<?= $lang == 'id' ? ($meta['meta_description_id'] ?? 'Berita terkini dan tren viral') : ($meta['meta_description_en'] ?? 'Latest news and viral trends'); ?>">
    
    <!-- Preload Critical Resources -->
    <link rel="preload" href="https://fonts.googleapis.com/css?family=Montserrat:400,700%7CMuli:400,700" as="style">
    <link rel="preload" href="<?= base_url('assets/css/font-awesome.min.css'); ?>" as="style">
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,700%7CMuli:400,700">
    <link rel="stylesheet" href="<?= base_url('assets/css/font-awesome.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css'); ?>">
    
    <!-- Favicon -->
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/icon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    
    <!-- Canonical & Hreflang -->
    <link rel="canonical" href="<?= isset($canonical) ? $canonical : base_url(uri_string()) ?>">
    <link rel="alternate" hreflang="en" href="https://trending-only.com/en">
    <link rel="alternate" hreflang="id" href="https://trending-only.com/id">
</head>

<body class="mobile-optimized">
    <!-- HEADER -->
    <header id="header" role="banner">
        <?= $this->include('layouts/navbar'); ?>
        <?= $this->renderSection('pageHeader'); ?>
    </header>

    <!-- MAIN CONTENT -->
    <main class="main-content" role="main">
        <?= $this->renderSection('content'); ?>
    </main>

    <!-- FOOTER -->
    <footer role="contentinfo">
        <?= $this->include('layouts/footer'); ?>
    </footer>

    <!-- SCRIPTS -->
    <script src="<?= base_url('assets/js/jquery.min.js'); ?>" defer></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js'); ?>" defer></script>
    <script src="<?= base_url('assets/js/main.js'); ?>" defer></script>

    <noscript>
        <div class="no-js-warning">
            <p>Enable JavaScript for best experience</p>
        </div>
    </noscript>
</body>
</html>