<?php

namespace App\Controllers;

use App\Models\ArtikelModel;
use App\Models\KategoriModel;
use App\Models\UserActivityModel;

class Artikel extends BaseController
{
    protected $artikelModel;
    protected $kategoriModel;
    protected $activityModel;

    public function __construct()
    {
        $this->artikelModel = new ArtikelModel();
        $this->kategoriModel = new KategoriModel();
        $this->activityModel = new UserActivityModel(); 
    }

    public function kategori($lang, $kategoriSlug)
    {
        // Ambil slug sesuai bahasa (slug_id atau slug_en)
        $kategori = $this->kategoriModel->getBySlug($kategoriSlug, $lang);

        // Jika tidak ketemu di bahasa saat ini, coba cek di bahasa lain
        if (!$kategori) {
            $otherLang = $lang === 'id' ? 'en' : 'id';
            $kategori = $this->kategoriModel->getBySlug($kategoriSlug, $otherLang);

            if ($kategori) {
                // Redirect ke slug versi bahasa yang benar
                $correctSlug = ($lang === 'id') ? $kategori['slug_id'] : $kategori['slug_en'];
                $canonical = base_url("$lang/" . $correctSlug);
                return redirect()->to($canonical);
            }

            // Jika tetap tidak ditemukan, lempar 404
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Kategori tidak ditemukan');
        }

        // Slug saat ini
        $correctSlug = ($lang === 'id') ? $kategori['slug_id'] : $kategori['slug_en'];
        $canonical = base_url("$lang/" . $correctSlug);

        // Redirect jika slug tidak sesuai
        if (current_url() !== $canonical) {
            return redirect()->to($canonical);
        }

        // Ambil semua data kategori, artikel, dll (lanjut seperti biasa)
        $kategoris = $this->kategoriModel->findAll();
        $categories = $this->kategoriModel->get_categories_with_thumbnails();
        $artikels = $this->artikelModel->getByKategori($kategori['id_kategori']);

        // Hitung jumlah artikel per kategori
        $kategoriWithCount = [];
        foreach ($kategoris as $kg) {
            $count = $this->artikelModel->where('id_kategori', $kg['id_kategori'])->countAllResults();
            $kategoriWithCount[] = [
                'kategori' => $kg,
                'count' => $count
            ];
        }

        // Ambil artikel populer
        $popularArticles = $this->artikelModel
            ->where('published_at <=', date('Y-m-d H:i:s'))
            ->orderBy('views', 'DESC')
            ->orderBy('published_at', 'DESC')
            ->findAll(4);

        foreach ($popularArticles as &$article) {
            $article['kategori'] = $this->kategoriModel->find($article['id_kategori']);
        }

        // Metadata
        $meta = $this->kategoriModel->getMetaOnly($correctSlug, $lang);

        $data = [
            'lang' => $lang,
            'meta' => $meta,
            'kategori' => $kategori,
            'artikels' => $artikels,
            'categories' => $categories,
            'allKategoris' => $kategoriWithCount,
            'popularArticles' => $popularArticles,
            'currentCategorySlug' => $correctSlug,
        ];

        return view('artikel/kategori', $data);
    }


    public function detail($lang, $kategoriSlug, $artikelSlug)
{
    $kategoris = $this->kategoriModel->findAll();

    // Coba ambil kategori berdasarkan slug dan bahasa saat ini
    $kategori = $this->kategoriModel->getBySlug($kategoriSlug, $lang);

    // Jika tidak ditemukan, coba cari berdasarkan bahasa lain
    if (!$kategori) {
        $otherLang = $lang === 'id' ? 'en' : 'id';
        $kategori = $this->kategoriModel->getBySlug($kategoriSlug, $otherLang);
        if ($kategori) {
            $correctKategoriSlug = $lang === 'id' ? $kategori['slug_id'] : $kategori['slug_en'];
            return redirect()->to(base_url("$lang/$correctKategoriSlug/$artikelSlug"));
        }
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Kategori tidak ditemukan');
    }

    // Ambil artikel
    $artikel = $this->artikelModel->getDetailArtikel($artikelSlug, $kategori['id_kategori'], $lang);

    // Jika tidak ditemukan, coba cek slug artikel dari bahasa lain
    if (!$artikel) {
        $otherLang = $lang === 'id' ? 'en' : 'id';
        $artikel = $this->artikelModel->getDetailArtikel($artikelSlug, $kategori['id_kategori'], $otherLang);

        if ($artikel) {
            $correctArtikelSlug = $lang === 'id' ? $artikel['slug_id'] : $artikel['slug_en'];
            $correctKategoriSlug = $lang === 'id' ? $kategori['slug_id'] : $kategori['slug_en'];
            return redirect()->to(base_url("$lang/$correctKategoriSlug/$correctArtikelSlug"));
        }

        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Artikel tidak ditemukan');
    }

    // Jika slug tidak sesuai, redirect
    $correctKategoriSlug = strtolower($lang === 'id' ? $kategori['slug_id'] : $kategori['slug_en']);
    $correctArtikelSlug = strtolower($lang === 'id' ? $artikel['slug_id'] : $artikel['slug_en']);

    if (
        strtolower($kategoriSlug) !== $correctKategoriSlug ||
        strtolower($artikelSlug) !== $correctArtikelSlug
    ) {
        return redirect()->to(base_url("$lang/$correctKategoriSlug/$correctArtikelSlug"));
    }

    // Sidebar kategori + jumlah artikel
    $kategoriWithCount = [];
    foreach ($kategoris as $kg) {
        $count = $this->artikelModel->where('id_kategori', $kg['id_kategori'])->countAllResults();
        $kategoriWithCount[] = [
            'kategori' => $kg,
            'count' => $count
        ];
    }

    // Artikel terkait
    $relatedArticles = $this->artikelModel->getRelatedArticles($artikel['id_artikel'], $kategori['id_kategori'], 3);

    // Artikel populer
    $popularArticles = $this->artikelModel
        ->where('published_at <=', date('Y-m-d H:i:s'))
        ->orderBy('views', 'DESC')
        ->orderBy('published_at', 'DESC')
        ->findAll(4);

    foreach ($popularArticles as &$article) {
        $article['kategori'] = $this->kategoriModel->find($article['id_kategori']);
    }

    // Meta
    $meta = $this->artikelModel->getMetaOnlyFix($artikelSlug, $kategori['id_kategori'], $lang);

    // ✅ Catat aktivitas view ke tb_user_activities
    $this->logActivity($artikel['id_artikel'], 'view');

    return view('artikel/detail', [
        'lang' => $lang,
        'meta' => $meta,
        'kategori' => $kategori,
        'artikel' => $artikel,
        'allKategoris' => $kategoriWithCount,
        'popularArticles' => $popularArticles,
        'relatedArticles' => $relatedArticles,
        'currentCategorySlug' => $correctKategoriSlug,
        'currentArticleSlug' => $correctArtikelSlug,
    ]);
}

/**
 * Simpan aktivitas user ke tabel tb_user_activities
 */
private function logActivity($articleId, $type = 'view', $value = null)
{
    // Buat session_id jika belum ada
    if (!session()->has('session_id')) {
        session()->set('session_id', bin2hex(random_bytes(16)));
    }

    // Gunakan model UserActivityModel
    $activityModel = new \App\Models\UserActivityModel();

    $activityModel->insert([
        'session_id'    => session('session_id'),
        'id_artikel'    => $articleId,
        'activity_type' => $type,
        'activity_value'=> $value,
        'user_agent'    => $this->request->getUserAgent()->getAgentString(),
        'referrer'      => $this->request->getServer('HTTP_REFERER')
    ]);
}

}
