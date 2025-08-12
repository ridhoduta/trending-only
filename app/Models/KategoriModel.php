<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table = 'tb_kategori';
    protected $primaryKey = 'id_kategori';
    protected $allowedFields = ['nama_kategori_id', 'nama_kategori_en', 'slug_id', 'slug_en', 'thumbnail', 'meta_title_id', 'meta_title_en', 'meta_description_id', 'meta_description_en', 'created_at'];
    protected $useTimestamps = false;

    public function getBySlug($slug, $lang)
    {
        $slugColumn = $lang === 'en' ? 'slug_en' : 'slug_id';

        $result = $this->where($slugColumn, $slug)->first();



        return $result;
    }




    public function get_categories_with_thumbnails(): array
    {
        $builder = $this->builder();
        return $builder->select('id_kategori, nama_kategori_id,nama_kategori_en, slug_id,slug_en, thumbnail')
            ->where('thumbnail IS NOT NULL')
            ->where('thumbnail !=', '')
            ->orderBy('nama_kategori_id', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getMetaOnly($slug, $lang = 'id')
    {
        if ($lang === 'en') {
            return $this->select('meta_title_en, meta_description_en')
                ->where('slug_en', $slug)
                ->first();
        }

        return $this->select('meta_title_id, meta_description_id')
            ->where('slug_id', $slug)
            ->first();
    }
}
