<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('login', 'LoginController::index');
$routes->post('login/process', 'LoginController::process');
$routes->get('logout', 'LoginController::logout');

$routes->get('sitemap.xml', 'Sitemap::index');

// Group untuk admin
$routes->group('admin', ['filter' => 'auth:admin'], function ($routes) {
    $routes->get('dashboard', 'admin\DashboardController::index');

    // Kategori Artikel
    $routes->get('kategoriArtikel/index', 'admin\KategoriArtikel::index');
    $routes->get('kategoriArtikel/tambah', 'admin\KategoriArtikel::tambah');
    $routes->post('kategoriArtikel/proses_tambah', 'admin\KategoriArtikel::proses_tambah');
    $routes->get('kategoriArtikel/edit/(:num)', 'admin\KategoriArtikel::edit/$1');
    $routes->post('kategoriArtikel/proses_edit/(:num)', 'admin\KategoriArtikel::proses_edit/$1');
    $routes->get('kategoriArtikel/delete/(:any)', 'admin\KategoriArtikel::delete/$1');

    // Artikel
    $routes->get('artikel/index', 'admin\ArtikelController::index');
    $routes->get('artikel/tambah', 'admin\ArtikelController::tambah');
    $routes->post('artikel/proses_tambah', 'admin\ArtikelController::proses_tambah');
    $routes->get('artikel/edit/(:num)', 'admin\ArtikelController::edit/$1');
    $routes->post('artikel/proses_edit/(:num)', 'admin\ArtikelController::proses_edit/$1');
    $routes->get('artikel/delete/(:any)', 'admin\ArtikelController::delete/$1');
    $routes->get('artikel/set_approval/(:num)/(:any)', 'Admin\ArtikelController::set_approval/$1/$2');

    // Manajemen Penulis
    $routes->get('penulis/index', 'admin\PenulisController::index');
    $routes->get('penulis/tambah', 'admin\PenulisController::tambah');
    $routes->post('penulis/proses_tambah', 'admin\PenulisController::proses_tambah');
    $routes->get('penulis/edit/(:num)', 'admin\PenulisController::edit/$1');
    $routes->post('penulis/proses_edit/(:num)', 'admin\PenulisController::proses_edit/$1');
    $routes->delete('penulis/hapus/(:any)', 'admin\PenulisController::hapus/$1');
});

// Group untuk penulis
$routes->group('penulis', ['filter' => 'auth:penulis'], function ($routes) {
    $routes->get('dashboard', 'penulis\DashboardController::index');

    $routes->get('profil', 'penulis\Profil::index');
    $routes->get('profil/edit', 'penulis\Profil::edit');
    $routes->post('profil/proses_edit', 'penulis\Profil::proses_edit');

    // Kategori Artikel
    $routes->get('kategoriArtikel/index', 'penulis\KategoriArtikel::index');
    $routes->get('kategoriArtikel/tambah', 'penulis\KategoriArtikel::tambah');
    $routes->post('kategoriArtikel/proses_tambah', 'penulis\KategoriArtikel::proses_tambah');
    $routes->get('kategoriArtikel/edit/(:num)', 'penulis\KategoriArtikel::edit/$1');
    $routes->post('kategoriArtikel/proses_edit/(:num)', 'penulis\KategoriArtikel::proses_edit/$1');
    $routes->get('kategoriArtikel/delete/(:any)', 'penulis\KategoriArtikel::delete/$1');

    // Berita
    $routes->get('berita/index', 'penulis\BeritaController::index');
    $routes->get('berita/tambah', 'penulis\BeritaController::tambah');
    $routes->post('berita/proses_tambah', 'penulis\BeritaController::proses_tambah');
    $routes->get('berita/edit/(:num)', 'penulis\BeritaController::edit/$1');
    $routes->post('berita/proses_edit/(:num)', 'penulis\BeritaController::proses_edit/$1');
    $routes->get('berita/delete/(:any)', 'penulis\BeritaController::delete/$1');

    // Update Profil
    $routes->post('profil/update-username', 'penulis\Profil::updateUsername');
    $routes->post('profil/update-nama', 'penulis\Profil::updateNamaLengkap');
    $routes->post('profil/update-foto', 'penulis\Profil::updateFotoProfil');
    $routes->post('profil/update-password', 'penulis\Profil::updatePassword');
});

$routes->get('/', function () {
    return redirect()->to('/id');
});

// USER TANPA LOGIN
$routes->group('id', function ($routes) {
    $routes->get('/', 'Beranda::index');
    $routes->get('(:segment)', 'Artikel::kategori/id/$1');
    $routes->get('(:segment)/(:segment)', 'Artikel::detail/id/$1/$2');
    $routes->post('artikel/activity', 'Artikel::activity');
});

$routes->group('en', function ($routes) {
    $routes->get('/', 'Beranda::index');
    $routes->get('(:segment)', 'Artikel::kategori/en/$1'); // kirim lang 'en'
    $routes->get('(:segment)/(:segment)', 'Artikel::detail/en/$1/$2'); // kirim lang 'en'
    $routes->post('artikel/activity', 'Artikel::activity');
});
