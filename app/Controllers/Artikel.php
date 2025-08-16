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
                $correctSlug = $lang === 'id' ? $kategori['slug_id'] : $kategori['slug_en'];
                $canonical = base_url("$lang/" . $correctSlug);
                return redirect()->to($canonical);
            }

            // Jika tetap tidak ditemukan, lempar 404
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Kategori tidak ditemukan');
        }

        // Slug saat ini
        $correctSlug = $lang === 'id' ? $kategori['slug_id'] : $kategori['slug_en'];
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
                'count' => $count,
            ];
        }

        // Ambil artikel populer
        $popularArticles = $this->artikelModel->where('published_at <=', date('Y-m-d H:i:s'))->orderBy('views', 'DESC')->orderBy('published_at', 'DESC')->findAll(4);

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

        // Ambil kategori sesuai bahasa
        $kategori = $this->kategoriModel->getBySlug($kategoriSlug, $lang);
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

        // Cek slug yang benar
        $correctKategoriSlug = strtolower($lang === 'id' ? $kategori['slug_id'] : $kategori['slug_en']);
        $correctArtikelSlug = strtolower($lang === 'id' ? $artikel['slug_id'] : $artikel['slug_en']);
        if (strtolower($kategoriSlug) !== $correctKategoriSlug || strtolower($artikelSlug) !== $correctArtikelSlug) {
            return redirect()->to(base_url("$lang/$correctKategoriSlug/$correctArtikelSlug"));
        }

        // Sidebar kategori
        $kategoriWithCount = [];
        foreach ($kategoris as $kg) {
            $count = $this->artikelModel->where('id_kategori', $kg['id_kategori'])->countAllResults();
            $kategoriWithCount[] = [
                'kategori' => $kg,
                'count' => $count,
            ];
        }

        // Artikel terkait
        $relatedArticles = $this->artikelModel->getRelatedArticles($artikel['id_artikel'], $kategori['id_kategori'], 3);

        // Artikel populer
        $popularArticles = $this->artikelModel->where('published_at <=', date('Y-m-d H:i:s'))->orderBy('views', 'DESC')->orderBy('published_at', 'DESC')->findAll(4);
        foreach ($popularArticles as &$article) {
            $article['kategori'] = $this->kategoriModel->find($article['id_kategori']);
        }

        // Meta
        $meta = $this->artikelModel->getMetaOnlyFix($artikelSlug, $kategori['id_kategori'], $lang);

        // Catat aktivitas view
        $this->logActivity($artikel['id_artikel'], 'view');

        // 🔹 Ambil rekomendasi personalisasi dari Python
        $recommendedArticles = $this->getRecommendedArticles(5);

        return view('artikel/detail', [
            'lang' => $lang,
            'meta' => $meta,
            'kategori' => $kategori,
            'artikel' => $artikel,
            'allKategoris' => $kategoriWithCount,
            'popularArticles' => $popularArticles,
            'relatedArticles' => $relatedArticles,
            'recommendedArticles' => $recommendedArticles, // ✅ Kirim ke view
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
            'session_id' => session('session_id'),
            'id_artikel' => $articleId,
            'activity_type' => $type,
            'activity_value' => $value,
            'user_agent' => $this->request->getUserAgent()->getAgentString(),
            'referrer' => $this->request->getServer('HTTP_REFERER'),
        ]);
    }
    public function activity()
    {
        $data = $this->request->getJSON(true); // Baca JSON jadi array

        $this->logActivity($data['article_id'] ?? null, $data['type'] ?? 'view', $data['value'] ?? null);

        return $this->response->setJSON(['status' => 'ok']);
    }
    // protected function getRecommendedArticles($limit = 5)
    // {
    //     if (!session()->has('session_id')) {
    //         session()->set('session_id', bin2hex(random_bytes(16)));
    //     }
    //     $sessionId = session('session_id');

    //     try {
    //         $client = \Config\Services::curlrequest();
    //         $response = $client->post('http://localhost:5000/recommend_by_activity', [
    //             'headers' => ['Content-Type' => 'application/json'],
    //             'json' => [
    //                 'session_id' => $sessionId,
    //                 'num_recommendations' => $limit,
    //             ],
    //         ]);
    //         $result = json_decode($response->getBody(), true);

    //         if (empty($result['recommendations'])) {
    //             return [];
    //         }

    //         // Ambil ID sesuai urutan rekomendasi
    //         $articleIds = array_column($result['recommendations'], 'id_artikel');
    //         $articles = $this->artikelModel->whereIn('id_artikel', $articleIds)->findAll();

    //         // Index agar urutan sesuai hasil Python
    //         $articlesIndexed = [];
    //         foreach ($articles as $article) {
    //             $articlesIndexed[$article['id_artikel']] = $article;
    //         }

    //         // Reorder sesuai model
    //         $ordered = [];
    //         foreach ($articleIds as $id) {
    //             if (isset($articlesIndexed[$id])) {
    //                 $art = $articlesIndexed[$id];
    //                 $art['kategori'] = $this->kategoriModel->find($art['id_kategori']);
    //                 $ordered[] = $art;
    //             }
    //         }

    //         return $ordered;
    //     } catch (\Exception $e) {
    //         log_message('error', 'Error fetching recommendations: ' . $e->getMessage());
    //         return [];
    //     }
    // }
    protected function getRecommendedArticles($limit = 5)
    {
        if (!session()->has('session_id')) {
            session()->set('session_id', bin2hex(random_bytes(16)));
        }
        $sessionId = session('session_id');

        try {
            $pythonPath = 'C:\Users\Bismillah\AppData\Local\Programs\Python\Python313\python.exe';
            $scriptPath = FCPATH . '..\ml\run_recomended.py';

            // Gunakan exec() sebagai pengganti shell_exec() untuk mendapatkan output array dan exit code
            $output_array = [];
            $return_var = 0;

            $command = "\"$pythonPath\" \"$scriptPath\" $sessionId $limit 2>&1";

            // Catatan: escapeshellcmd() tidak diperlukan jika Anda sudah mengapit path dengan tanda kutip
            exec($command, $output_array, $return_var);

            // --- Logging untuk debugging ---
            log_message('info', 'Command executed: ' . $command);
            log_message('info', 'Exit code: ' . $return_var);
            log_message('info', 'Output from Python (array): ' . print_r($output_array, true));
            // --- Akhir Logging ---

            if ($return_var !== 0) {
                log_message('error', 'Python script failed with exit code: ' . $return_var);
                return [];
            }

            // Cari baris yang berisi JSON yang valid
            $json_output = null;
            foreach ($output_array as $line) {
                $trimmed_line = trim($line);
                if (str_starts_with($trimmed_line, '[') && str_ends_with($trimmed_line, ']')) {
                    $json_output = $trimmed_line;
                    break;
                }
            }

            // Jika tidak ditemukan output JSON, log error
            if (is_null($json_output)) {
                log_message('error', 'Valid JSON output not found in Python script output.');
                return [];
            }

            $result = json_decode($json_output, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                log_message('error', 'JSON decode error: ' . json_last_error_msg());
                return [];
            }

            if (empty($result['recommendations'])) {
                log_message('warning', 'Recommendations key not found or empty.');
                return [];
            }

            // Ambil ID artikel dari hasil rekomendasi
            $articleIds = array_column($result['recommendations'], 'id_artikel');
            $articles = $this->artikelModel->whereIn('id_artikel', $articleIds)->findAll();

            // Re-index supaya urut sesuai rekomendasi
            $articlesIndexed = [];
            foreach ($articles as $article) {
                $articlesIndexed[$article['id_artikel']] = $article;
            }

            $ordered = [];
            foreach ($articleIds as $id) {
                if (isset($articlesIndexed[$id])) {
                    $art = $articlesIndexed[$id];
                    $art['kategori'] = $this->kategoriModel->find($art['id_kategori']);
                    $ordered[] = $art;
                }
            }

            return $ordered;
        } catch (\Exception $e) {
            log_message('error', 'Error menjalankan Python: ' . $e->getMessage());
            return [];
        }
    }
}
