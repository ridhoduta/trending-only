<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ArtikelModel;
use App\Models\KategoriModel;
use CodeIgniter\HTTP\ResponseInterface;

class Sitemap extends BaseController
{
    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = 'https://trending-only.com/';
    }

    public function index()
    {
        header("Content-Type: application/xml; charset=UTF-8");
        header("X-Content-Type-Options: nosniff");

        // Initialize models
        $kategoriModel = new KategoriModel();
        $artikelModel = new ArtikelModel();

        // Get data
        $kategoriList = $kategoriModel->select('id_kategori, slug_id, slug_en')->findAll() ?? [];
        $artikelList = $artikelModel->select('id_kategori, slug_id, slug_en')->findAll() ?? [];

        // Create XML
        $xml = new \SimpleXMLElement('<urlset/>');
        $xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        // Add homepage - Indonesian first
        $this->addUrl($xml, "{$this->baseUrl}id", '1.0');
        $this->addUrl($xml, "{$this->baseUrl}en", '1.0');

        // ================= INDONESIAN VERSION FIRST =================

        // Add Indonesian category pages
        foreach ($kategoriList as $kategori) {
            $this->addUrl($xml, "{$this->baseUrl}id/{$kategori['slug_id']}", '0.8');
        }

        // Add Indonesian article pages
        foreach ($artikelList as $artikel) {
            foreach ($kategoriList as $kategori) {
                if ($kategori['id_kategori'] == $artikel['id_kategori']) {
                    $this->addUrl($xml, "{$this->baseUrl}id/{$kategori['slug_id']}/{$artikel['slug_id']}", '0.7');
                    break;
                }
            }
        }

        // ================= ENGLISH VERSION AFTER =================

        // Add English category pages
        foreach ($kategoriList as $kategori) {
            $this->addUrl($xml, "{$this->baseUrl}en/{$kategori['slug_en']}", '0.8');
        }

        // Add English article pages
        foreach ($artikelList as $artikel) {
            foreach ($kategoriList as $kategori) {
                if ($kategori['id_kategori'] == $artikel['id_kategori']) {
                    $this->addUrl($xml, "{$this->baseUrl}en/{$kategori['slug_en']}/{$artikel['slug_en']}", '0.7');
                    break;
                }
            }
        }

        // Format and output XML
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());

        // Add XSLT stylesheet
        $xslt = $dom->createProcessingInstruction(
            'xml-stylesheet',
            'type="text/xsl" href="/sitemap.xsl"'
        );
        $dom->insertBefore($xslt, $dom->firstChild);

        echo $dom->saveXML();
    }

    private function addUrl($xml, $loc, $priority = '0.5')
    {
        $url = $xml->addChild('url');
        $url->addChild('loc', htmlspecialchars($loc, ENT_QUOTES, 'UTF-8'));
        $url->addChild('lastmod', date('Y-m-d'));
        $url->addChild('changefreq', 'weekly');
        $url->addChild('priority', $priority);
    }

    public function htmlSitemap()
    {
        // Initialize models
        $kategoriModel = new Kategori();
        $artikelModel = new Artikel();

        // Get data
        $kategoriList = $kategoriModel->select('id_kategori, slug_id, slug_en, nama_kategori_id, nama_kategori_en')->findAll() ?? [];
        $artikelList = $artikelModel->select('id_kategori, slug_id, slug_en, judul_id, judul_en')->findAll() ?? [];

        // Group articles by category - Indonesian first
        $articlesByCategoryId = [];
        foreach ($artikelList as $artikel) {
            foreach ($kategoriList as $kategori) {
                if ($kategori['id_kategori'] == $artikel['id_kategori']) {
                    if (!isset($articlesByCategoryId[$kategori['slug_id']])) {
                        $articlesByCategoryId[$kategori['slug_id']] = [
                            'name' => $kategori['nama_kategori_id'],
                            'articles' => []
                        ];
                    }
                    $articlesByCategoryId[$kategori['slug_id']]['articles'][] = [
                        'title' => $artikel['judul_id'],
                        'slug' => $artikel['slug_id']
                    ];
                    break;
                }
            }
        }

        // Group articles by category - English after
        $articlesByCategoryEn = [];
        foreach ($artikelList as $artikel) {
            foreach ($kategoriList as $kategori) {
                if ($kategori['id_kategori'] == $artikel['id_kategori']) {
                    if (!isset($articlesByCategoryEn[$kategori['slug_en']])) {
                        $articlesByCategoryEn[$kategori['slug_en']] = [
                            'name' => $kategori['nama_kategori_en'],
                            'articles' => []
                        ];
                    }
                    $articlesByCategoryEn[$kategori['slug_en']]['articles'][] = [
                        'title' => $artikel['judul_en'],
                        'slug' => $artikel['slug_en']
                    ];
                    break;
                }
            }
        }

        // Data for view
        $data = [
            'baseUrl' => $this->baseUrl,
            'categoriesId' => $kategoriList,
            'articlesByCategoryId' => $articlesByCategoryId,
            'categoriesEn' => $kategoriList,
            'articlesByCategoryEn' => $articlesByCategoryEn
        ];

        // Load view
        return view('sitemap_html', $data);
    }
}
