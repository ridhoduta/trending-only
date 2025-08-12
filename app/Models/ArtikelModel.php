<?php

namespace App\Models;

use CodeIgniter\Model;

class ArtikelModel extends Model
{
    protected $table = 'tb_artikel';
    protected $primaryKey = 'id_artikel';
    protected $allowedFields = ['id_user', 'id_kategori', 'judul_id', 'judul_en', 'slug_id', 'slug_en', 'konten_id', 'konten_en', 'thumbnail', 'tags_id', 'tags_en', 'views', 'meta_title_id', 'meta_title_en', 'meta_description_id', 'meta_description_en', 'published_at', 'created_at', 'updated_at'];
    protected $useTimestamps = false;

    /**
     * Mengambil artikel populer berdasarkan jumlah views
     * 
     * @param int $limit Jumlah artikel yang ingin diambil
     * @param string $language Bahasa yang digunakan (id/en)
     * @return array
     */


    /**
     * Mengambil artikel populer berdasarkan jumlah views
     * 
     * @param int $limit Jumlah artikel yang ingin diambil
     * @param string $language Bahasa yang digunakan (id/en)
     * @return array
     */
    public function getPopularArticles($limit = 1, $language = 'id')
    {
        return $this->select("
        tb_artikel.*, 
        tb_users.nama_lengkap as nama_penulis,  // Pastikan alias sesuai
        tb_kategori.nama_kategori_{$language} as nama_kategori,
        tb_kategori.slug_{$language} as slug_kategori
    ")
            ->join('tb_users', 'tb_users.id_user = tb_artikel.id_user', 'left')
            ->join('tb_kategori', 'tb_kategori.id_kategori = tb_artikel.id_kategori', 'left')
            ->where('published_at IS NOT NULL')
            ->where('published_at <=', date('Y-m-d H:i:s'))
            ->orderBy('views', 'DESC')
            ->orderBy('published_at', 'DESC')
            ->limit($limit)
            ->find();
    }

    public function getByKategori($id_kategori)
    {
        return $this->select('tb_artikel.*, tb_users.nama_lengkap')
            ->join('tb_users', 'tb_users.id_user = tb_artikel.id_user', 'left')
            ->where('id_kategori', $id_kategori)
            ->orderBy('published_at', 'DESC')
            ->findAll();
    }

    public function getDetailArtikel($slug, $kategoriId, $lang)
    {
        $slugColumn = $lang === 'en' ? 'slug_en' : 'slug_id';

        $result = $this->select('tb_artikel.*, tb_users.nama_lengkap, tb_artikel.slug_id, tb_artikel.slug_en')
            ->join('tb_users', 'tb_users.id_user = tb_artikel.id_user', 'left')
            ->where("tb_artikel.$slugColumn", $slug)
            ->where('tb_artikel.id_kategori', $kategoriId)
            ->first();

        log_message('debug', "getDetailArtikel($slug, $kategoriId, $lang) => " . json_encode($result));

        return $result;
    }



    // Artikel terkait (kategori sama, kecuali artikel ini)
    public function getRelatedArticles($artikelId, $kategoriId, $limit = 3)
    {
        return $this->select('
        tb_artikel.*, 
        tb_kategori.slug_id as kategori_slug_id, 
        tb_kategori.slug_en as kategori_slug_en,
        tb_kategori.nama_kategori_id,
        tb_kategori.nama_kategori_en,
        tb_users.nama_lengkap
    ')
            ->join('tb_kategori', 'tb_kategori.id_kategori = tb_artikel.id_kategori')
            ->join('tb_users', 'tb_users.id_user = tb_artikel.id_user')  // sesuaikan kolom id sesuai tabelmu
            ->where('tb_artikel.id_kategori', $kategoriId)
            ->where('tb_artikel.id_artikel !=', $artikelId)
            ->orderBy('tb_artikel.published_at', 'DESC')
            ->findAll($limit);
    }




    public function getMetaOnly($slug_id, $id_kategori)
    {
        return $this->select('meta_description_id, meta_description_en, meta_title_id, meta_title_en')
            ->where('slug_id', $slug_id)
            ->where('id_kategori', $id_kategori)
            ->first();
    }
    
    public function getMetaOnlyFix($slug_id, $id_kategori, $lang = 'id')
    {
        if ($lang === 'en') {
            return $this->select('meta_title_en, meta_description_en')
                ->where('slug_en', $slug_id)
                ->where('id_kategori', $id_kategori)
                ->first();
        }

        return $this->select('meta_title_id, meta_description_id')
            ->where('slug_id', $slug_id)
            ->where('id_kategori', $id_kategori)
            ->first();
    }

    // Artikel Kategori di Beranda
    public function getLatestByKategori($id_kategori, $limit = 6)
    {

        return $this->select('tb_artikel.*, tb_users.nama_lengkap')
            ->join('tb_users', 'tb_users.id_user = tb_artikel.id_user', 'left')
            ->where('id_kategori', $id_kategori)
            ->orderBy('published_at', 'DESC')
            ->limit($limit)
            ->find();
    }



    public function countByKategori($id_kategori)
    {
        return $this->where('id_kategori', $id_kategori)
            ->countAllResults();
    }

    public function getArtikelByKategori($id_kategori, $limit, $excluded_ids = [])
    {
        $builder = $this->db->table('tb_artikel')
            ->select('tb_artikel.*, tb_users.nama_lengkap')
            ->join('tb_users', 'tb_users.id_user = tb_artikel.id_user', 'left')
            ->where('id_kategori', $id_kategori)
            ->orderBy('published_at', 'DESC');

        if (!empty($excluded_ids)) {
            $builder->whereNotIn('id_artikel', $excluded_ids);
        }

        return $builder->limit($limit)->get()->getResultArray();
    }

    // public function getRelatedArticles($id_kategori, $current_article_id, $limit = 3)
    // {
    //     return $this->select('tb_artikel.*, tb_users.nama_lengkap')
    //         ->join('tb_users', 'tb_users.id_user = tb_artikel.id_user', 'left')
    //         ->where('id_kategori', $id_kategori)
    //         ->where('id_artikel !=', $current_article_id)
    //         ->orderBy('published_at', 'DESC')
    //         ->limit($limit)
    //         ->find();
    // }
}
