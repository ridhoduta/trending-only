<?php

namespace App\Controllers;

use App\Models\ArtikelModel;
use App\Models\KategoriModel;
use App\Models\PenulisModel;

class Penulis extends BaseController
{
    protected $artikelModel;
    protected $kategoriModel;
    protected $penulisModel;
    protected $helpers = ['form', 'url'];

    public function __construct()
    {
        $this->artikelModel = new ArtikelModel();
        $this->kategoriModel = new KategoriModel();
        $this->penulisModel = new PenulisModel();
    }

    // List Artikel
    public function index()
    {
        $data = [
            'title' => 'Daftar Artikel Saya',
            'artikels' => $this->artikelModel->where('id_user', session()->get('id_user'))->findAll(),
            'kategoris' => $this->kategoriModel->findAll()
        ];

        // Ubah struktur kategoris untuk memudahkan pencarian
        $data['kategoriMap'] = array_column($data['kategoris'], 'nama_kategori', 'id_kategori');

        return view('penulis/artikel/index', $data);
    }

    // Create Artikel
    public function create()
    {
        $data = [
            'title' => 'Tambah Artikel Baru',
            'kategoris' => $this->kategoriModel->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('penulis/artikel/create', $data);
    }

    // Store Artikel
    public function store()
    {
        $rules = [
            'judul_id' => 'required',
            'konten_id' => 'required',
            'id_kategori' => 'required',
            'thumbnail' => [
                'rules' => 'uploaded[thumbnail]|max_size[thumbnail,1024]|is_image[thumbnail]|mime_in[thumbnail,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'uploaded' => 'Pilih gambar thumbnail terlebih dahulu',
                    'max_size' => 'Ukuran gambar terlalu besar (Maksimal 1MB)',
                    'is_image' => 'File yang anda pilih bukan gambar',
                    'mime_in' => 'File yang anda pilih bukan gambar'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput();
        }

        // Ambil file thumbnail
        $fileThumbnail = $this->request->getFile('thumbnail');
        $namaThumbnail = $fileThumbnail->getRandomName();
        $fileThumbnail->move('uploads/artikel', $namaThumbnail);

        // Generate slug
        $slug_id = url_title($this->request->getVar('judul_id'), '-', true);

        $data = [
            'id_user' => session()->get('id_user'),
            'id_kategori' => $this->request->getVar('id_kategori'),
            'judul_id' => $this->request->getVar('judul_id'),
            'judul_en' => $this->request->getVar('judul_en'),
            'slug_id' => $slug_id,
            'slug_en' => url_title($this->request->getVar('judul_en'), '-', true),
            'konten_id' => $this->request->getVar('konten_id'),
            'konten_en' => $this->request->getVar('konten_en'),
            'thumbnail' => $namaThumbnail,
            'tags_id' => $this->request->getVar('tags_id'),
            'tags_en' => $this->request->getVar('tags_en'),
            'meta_title_id' => $this->request->getVar('meta_title_id'),
            'meta_title_en' => $this->request->getVar('meta_title_en'),
            'meta_description_id' => $this->request->getVar('meta_description_id'),
            'meta_description_en' => $this->request->getVar('meta_description_en'),
            'published_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->artikelModel->save($data);
        return redirect()->to('/penulis/artikel')->with('success', 'Artikel berhasil ditambahkan');
    }

    // Edit Artikel
    public function edit($id)
    {
        $artikel = $this->artikelModel->find($id);

        // Cek apakah artikel milik penulis yang login
        if ($artikel['id_user'] != session()->get('id_user')) {
            return redirect()->to('/penulis/artikel')->with('error', 'Anda tidak memiliki akses ke artikel ini');
        }

        $data = [
            'title' => 'Edit Artikel',
            'artikel' => $artikel,
            'kategoris' => $this->kategoriModel->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('penulis/artikel/edit', $data);
    }

    // Update Artikel
    public function update($id)
    {
        $artikel = $this->artikelModel->find($id);

        // Cek apakah artikel milik penulis yang login
        if ($artikel['id_user'] != session()->get('id_user')) {
            return redirect()->to('/penulis/artikel')->with('error', 'Anda tidak memiliki akses ke artikel ini');
        }

        $rules = [
            'judul_id' => 'required',
            'konten_id' => 'required',
            'id_kategori' => 'required'
        ];

        if ($this->request->getFile('thumbnail')->getName() != '') {
            $rules['thumbnail'] = [
                'rules' => 'uploaded[thumbnail]|max_size[thumbnail,1024]|is_image[thumbnail]|mime_in[thumbnail,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'uploaded' => 'Pilih gambar thumbnail terlebih dahulu',
                    'max_size' => 'Ukuran gambar terlalu besar (Maksimal 1MB)',
                    'is_image' => 'File yang anda pilih bukan gambar',
                    'mime_in' => 'File yang anda pilih bukan gambar'
                ]
            ];
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput();
        }

        $data = [
            'id_artikel' => $id,
            'id_kategori' => $this->request->getVar('id_kategori'),
            'judul_id' => $this->request->getVar('judul_id'),
            'judul_en' => $this->request->getVar('judul_en'),
            'slug_id' => url_title($this->request->getVar('judul_id'), '-', true),
            'slug_en' => url_title($this->request->getVar('judul_en'), '-', true),
            'konten_id' => $this->request->getVar('konten_id'),
            'konten_en' => $this->request->getVar('konten_en'),
            'tags_id' => $this->request->getVar('tags_id'),
            'tags_en' => $this->request->getVar('tags_en'),
            'meta_title_id' => $this->request->getVar('meta_title_id'),
            'meta_title_en' => $this->request->getVar('meta_title_en'),
            'meta_description_id' => $this->request->getVar('meta_description_id'),
            'meta_description_en' => $this->request->getVar('meta_description_en'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Handle thumbnail update
        if ($this->request->getFile('thumbnail')->getName() != '') {
            $fileThumbnail = $this->request->getFile('thumbnail');
            $namaThumbnail = $fileThumbnail->getRandomName();
            $fileThumbnail->move('uploads/artikel', $namaThumbnail);

            // Hapus thumbnail lama
            if ($artikel['thumbnail'] != '') {
                unlink('uploads/artikel/' . $artikel['thumbnail']);
            }

            $data['thumbnail'] = $namaThumbnail;
        }

        $this->artikelModel->save($data);
        return redirect()->to('/penulis/artikel')->with('success', 'Artikel berhasil diperbarui');
    }

    // Delete Artikel
    public function delete($id)
    {
        $artikel = $this->artikelModel->find($id);

        // Cek apakah artikel milik penulis yang login
        if ($artikel['id_user'] != session()->get('id_user')) {
            return redirect()->to('/penulis/artikel')->with('error', 'Anda tidak memiliki akses ke artikel ini');
        }

        // Hapus thumbnail
        if ($artikel['thumbnail'] != '') {
            unlink('uploads/artikel/' . $artikel['thumbnail']);
        }

        $this->artikelModel->delete($id);
        return redirect()->to('/penulis/artikel')->with('success', 'Artikel berhasil dihapus');
    }
}
