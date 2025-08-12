<!DOCTYPE html>
<html lang="en">

<head>
    <title>Penulis</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">

    <meta name="description" content="Portal - Bootstrap 5 Admin Dashboard Template For Developers">
    <meta name="author" content="Xiaoying Riley at 3rd Wave Media">

    <!-- FontAwesome JS-->
    <script defer src="<?= base_url('assets/penulis/plugins/fontawesome/js/all.min.js') ?>"></script>

    <!-- App CSS -->
    <link id="theme-style" rel="stylesheet" href="<?= base_url('assets/penulis/css/portal.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">
    <!-- test -->
    <!-- <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script> -->
    <script src="<?= base_url('assets/penulis/js/tinymce.min.js') ?>"></script>
    <script>
        tinymce.init({
            selector: 'textarea.tiny',
            plugins: 'powerpaste advcode table lists checklist link image media',
            toolbar: 'undo redo | blocks | bold italic | bullist numlist checklist | code | table | link image media'
        });
    </script>
    <!-- end test -->

</head>

<body class="app">
    <?= $this->include('penulis/layout/header'); ?>

    <div class="app-wrapper">

        <?= $this->renderSection('content'); ?>

    </div>

    <script src="<?= base_url('assets/penulis/plugins/popper.min.js') ?>"></script>
    <script src="<?= base_url('assets/penulis/plugins/bootstrap/js/bootstrap.min.js') ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

    <!-- Page Specific JS -->
    <script src="<?= base_url('assets/penulis/js/app.js') ?>"></script>
    <script src="<?= base_url('assets') ?>/js/lazysizes.min.js"></script>
    <!--  -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Menambahkan class active pada navbar -->
    <script>
        // Ambil URL saat ini
        var currentUrl = window.location.href;

        // Dapatkan semua elemen tautan di dalam navbar
        var navLinks = document.querySelectorAll("#app-nav-main .nav-link");

        // Loop melalui setiap tautan untuk memeriksa URL saat ini
        navLinks.forEach(function(link) {
            // Dapatkan href dari tautan
            var linkHref = link.getAttribute("href");

            // Cek apakah URL saat ini mengandung substring terkait dan tambahkan kelas "active" ke tautan yang sesuai
            if (
                (currentUrl.indexOf("penulis/dashboard") !== -1 && linkHref.indexOf("penulis/dashboard") !== -1) ||
                (currentUrl.indexOf("produk") !== -1 && linkHref.indexOf("produk") !== -1) ||
                (currentUrl.indexOf("slider") !== -1 && linkHref.indexOf("slider") !== -1) ||
                (currentUrl.indexOf("aktivitas") !== -1 && linkHref.indexOf("aktivitas") !== -1) ||
                (currentUrl.indexOf("profil") !== -1 && linkHref.indexOf("profil") !== -1)
            ) {
                link.classList.add("active");
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Konfigurasi Crop untuk Thumbnail (700x467)
            const thumbnailInput = document.getElementById('thumbnail');
            const thumbnailPreview = document.getElementById('thumbnail-preview');
            const thumbnailCropContainer = document.getElementById('thumbnail-crop-container');
            const thumbnailCroppedData = document.getElementById('thumbnail-cropped-data');
            let thumbnailCropper;

            thumbnailInput.addEventListener('change', function(e) {
                if (e.target.files.length) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        thumbnailPreview.src = event.target.result;
                        thumbnailCropContainer.style.display = 'block';

                        // Inisialisasi Cropper
                        if (thumbnailCropper) {
                            thumbnailCropper.destroy();
                        }
                        thumbnailCropper = new Cropper(thumbnailPreview, {
                            aspectRatio: 3 / 2, 
                            viewMode: 1,
                            autoCropArea: 0.8,
                        });
                    };
                    reader.readAsDataURL(e.target.files[0]);
                }
            });

            // Tombol Crop Thumbnail
            document.getElementById('thumbnail-crop-btn').addEventListener('click', function() {
                const croppedCanvas = thumbnailCropper.getCroppedCanvas({
                    width: 700,
                    height: 467,
                });
                thumbnailCroppedData.value = croppedCanvas.toDataURL('image/jpeg');
                thumbnailCropper.destroy();
                thumbnailCropContainer.style.display = 'none';
            });

            // Tombol Batal Thumbnail
            document.getElementById('thumbnail-cancel-btn').addEventListener('click', function() {
                thumbnailInput.value = '';
                thumbnailCropContainer.style.display = 'none';
                if (thumbnailCropper) {
                    thumbnailCropper.destroy();
                }
            });

            const featuredInput = document.getElementById('featured_image');
            const featuredPreview = document.getElementById('featured-preview');
            const featuredCropContainer = document.getElementById('featured-crop-container');
            const featuredCroppedData = document.getElementById('featured-cropped-data');
            let featuredCropper;

            featuredInput.addEventListener('change', function(e) {
                if (e.target.files.length) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        featuredPreview.src = event.target.result;
                        featuredCropContainer.style.display = 'block';

                        // Inisialisasi Cropper
                        if (featuredCropper) {
                            featuredCropper.destroy();
                        }
                        featuredCropper = new Cropper(featuredPreview, {
                            aspectRatio: 800 / 600, 
                            viewMode: 1,
                            autoCropArea: 0.8,
                        });
                    };
                    reader.readAsDataURL(e.target.files[0]);
                }
            });

            // Tombol Crop Featured Image
            document.getElementById('featured-crop-btn').addEventListener('click', function() {
                const croppedCanvas = featuredCropper.getCroppedCanvas({
                    width: 800,
                    height: 600,
                });
                featuredCroppedData.value = croppedCanvas.toDataURL('image/jpeg');
                featuredCropper.destroy();
                featuredCropContainer.style.display = 'none';
            });

            // Tombol Batal Featured Image
            document.getElementById('featured-cancel-btn').addEventListener('click', function() {
                featuredInput.value = '';
                featuredCropContainer.style.display = 'none';
                if (featuredCropper) {
                    featuredCropper.destroy();
                }
            });
        });
    </script>
</body>

</html>