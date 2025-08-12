<?php

namespace App\Controllers;

use App\Models\ArtikelModel;
use App\Models\KategoriModel;
use CodeIgniter\Controller;

class Beranda extends BaseController
{
    protected $artikelModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->artikelModel = new ArtikelModel();
        $this->kategoriModel = new KategoriModel();
    }

    public function index()
    {
        $lang = session()->get('lang') ?? 'id';

        // Inisialisasi variabel
        $data = [
            'lang' => $lang,
            'kategoriArtikel' => [],
            'allKategoris' => [],
            'latestArticles' => $this->getLatestArticles(3),
            'popularArticles' => $this->getPopularArticles(4),
            'recommendedArticles' => $this->getRecommendedArticles(5)
        ];
        // $data['recommendedArticles'] = $this->getRecommendedArticles(5);

        // Proses data kategori
        $kategoris = $this->kategoriModel->findAll();
        foreach ($kategoris as $kategori) {
            $count = $this->artikelModel->where('id_kategori', $kategori['id_kategori'])->countAllResults();
            $data['allKategoris'][] = [
                'kategori' => $kategori,
                'count' => $count
            ];

            $limit = (strtolower($kategori['nama_kategori_id']) === 'olahraga') ? 6 : 3;
            $artikel = $this->artikelModel->getLatestByKategori($kategori['id_kategori'], $limit);

            $data['kategoriArtikel'][] = [
                'kategori' => $kategori,
                'artikels' => $artikel
            ];
        }

        return view('beranda', $data);
    }

    /**
     * Mendapatkan artikel terbaru
     */
    protected function getLatestArticles($limit = 3)
    {
        $articles = $this->artikelModel
            ->where('published_at <=', date('Y-m-d H:i:s'))
            ->orderBy('published_at', 'DESC')
            ->findAll($limit);

        // Jika artikel kurang dari limit, tambahkan artikel sebelumnya
        if (count($articles) < $limit) {
            $lastDate = !empty($articles) ?
                $articles[count($articles) - 1]['published_at'] :
                date('Y-m-d H:i:s');

            $additionalNeeded = $limit - count($articles);
            $olderArticles = $this->artikelModel
                ->where('published_at <=', date('Y-m-d H:i:s'))
                ->where('published_at <', $lastDate)
                ->orderBy('published_at', 'DESC')
                ->findAll($additionalNeeded);

            $articles = array_merge($articles, $olderArticles);
        }

        // Tambahkan info kategori
        return $this->addCategoryInfo($articles);
    }

    /**
     * Mendapatkan artikel populer
     */
    protected function getPopularArticles($limit = 4)
    {
    $timeWindows = [
        24 * 60 * 60,    // 24 hours in seconds
        48 * 60 * 60,    // 48 hours
        96 * 60 * 60,    // 96 hours
        168 * 60 * 60,   // 1 week
        0                // All time
    ];
    
    $popularArticles = [];
    $excludeIds = [];
    
    foreach ($timeWindows as $window) {
        $query = $this->artikelModel
            ->where('published_at <=', date('Y-m-d H:i:s'));
            
        if ($window > 0) {
            $query->where('published_at >=', date('Y-m-d H:i:s', time() - $window));
        }
        
        $currentBatch = $query
            ->whereNotIn('id_artikel', $excludeIds ?: [0])
            ->orderBy('views', 'DESC')
            ->orderBy('published_at', 'DESC')
            ->findAll($limit - count($popularArticles));
            
        if (!empty($currentBatch)) {
            $popularArticles = array_merge($popularArticles, $currentBatch);
            $excludeIds = array_column($popularArticles, 'id_artikel');
            
            if (count($popularArticles) >= $limit) {
                break;
            }
        }
    }
    
    // If still not enough articles, get latest articles as fallback
    if (count($popularArticles) < $limit) {
        $additionalNeeded = $limit - count($popularArticles);
        $latestArticles = $this->artikelModel
            ->where('published_at <=', date('Y-m-d H:i:s'))
            ->whereNotIn('id_artikel', $excludeIds ?: [0])
            ->orderBy('published_at', 'DESC')
            ->findAll($additionalNeeded);
            
        $popularArticles = array_merge($popularArticles, $latestArticles);
    }
    
    // Add category information
    return $this->addCategoryInfo($popularArticles);
}

    /**
     * Menambahkan informasi kategori ke artikel
     */
    protected function addCategoryInfo(array $articles)
    {
        foreach ($articles as &$article) {
            if (!isset($article['kategori'])) {
                $article['kategori'] = $this->kategoriModel->find($article['id_kategori']);
            }
        }
        return $articles;
    }
    protected function getRecommendedArticles($limit = 5)
{
    if (!session()->has('session_id')) {
        session()->set('session_id', bin2hex(random_bytes(16)));
    }
    $sessionId = session('session_id');

    try {
        $client = \Config\Services::curlrequest();

        $response = $client->post('http://localhost:5000/recommend_by_activity', [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'session_id' => $sessionId,
                'num_recommendations' => $limit
            ]
        ]);

        $result = json_decode($response->getBody(), true);

        if (empty($result['recommendations'])) {
            return [];
        }

        // Ambil ID artikel dari rekomendasi Python
        $articleIds = array_column($result['recommendations'], 'id_artikel');

        if (empty($articleIds)) {
            return [];
        }

        // Ambil data artikel dari DB
        $articles = $this->artikelModel
            ->whereIn('id_artikel', $articleIds)
            ->findAll();

        // Tambahkan info kategori
        return $this->addCategoryInfo($articles);

    } catch (\Exception $e) {
        log_message('error', 'Error fetching recommendations: ' . $e->getMessage());
        return [];
    }
}


}
