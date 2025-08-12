<?php

namespace App\Controllers\penulis;

use App\Models\BeritaModel;
use App\Models\KategoriModel;

class BeritaController extends BaseController
{
    private $artikelModel;
    private $kategoriModel;

    public function __construct()
    {
        $this->artikelModel = new BeritaModel();
        $this->kategoriModel = new KategoriModel();
    }

    public function generateSlug($string)
    {
        // Ubah string menjadi huruf kecil
        $slug = strtolower($string);
        // Hapus semua karakter non-alfanumerik kecuali spasi
        $slug = preg_replace('/[^a-z0-9\s]/', '', $slug);
        // Ganti spasi dengan tanda hubung
        $slug = preg_replace('/\s+/', '-', $slug);
        return $slug;
    }


    public function index()
    {
        // Pengecekan apakah pengguna sudah login atau belum
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login')); // Sesuaikan dengan halaman login Anda
        }

        $data = [
            'artikels' => $this->artikelModel->where('id_user', session()->get('id_user'))->findAll(),
        ];

        return view('penulis/berita/index', $data);
    }

    public function tambah()
    {
        // Pengecekan apakah pengguna sudah login atau belum
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login')); // Sesuaikan dengan halaman login Anda
        }

        $aktivitas_kategori = new KategoriModel();
        $all_data_kategori = $aktivitas_kategori->findAll();

        return view('penulis/berita/tambah', [
            'all_data_kategori' => $all_data_kategori,
            'validation' => $this->validator
        ]);
    }

    public function proses_tambah()
    {
        // Pengecekan login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        $id_user = session()->get('id_user');
        $judul_id = $this->request->getVar('judul_id');
        $judul_en = $this->request->getVar('judul_en');
        $konten_id = $this->request->getVar('konten_id');
        $konten_en = $this->request->getVar('konten_en');
        $id_kategori = $this->request->getVar('id_kategori');
        $tags_id = $this->request->getVar('tags_id');
        $tags_en = $this->request->getVar('tags_en');
        $meta_title_id = $this->request->getVar('meta_title_id');
        $meta_title_en = $this->request->getVar('meta_title_en');
        $meta_description_id = $this->request->getVar('meta_description_id');
        $meta_description_en = $this->request->getVar('meta_description_en');
        $photo_source = $this->request->getVar('photo_source');

        // Generate slug
        $slug_id = $this->generateSlug($judul_id);
        $slug_en = $this->generateSlug($judul_en);

        // Validasi judul
        // if (!preg_match('/^[a-zA-Z0-9\s]+$/', $judul_id)) {
        //     session()->setFlashdata('error', 'Judul artikel (ID) hanya boleh berisi huruf dan angka.');
        //     return redirect()->back()->withInput();
        // }
        // if (!preg_match('/^[a-zA-Z0-9\s]+$/', $judul_en)) {
        //     session()->setFlashdata('error', 'Judul artikel (EN) hanya boleh berisi huruf dan angka.');
        //     return redirect()->back()->withInput();
        // }

        // Validasi upload (tanpa validasi dimensi karena sudah di-crop)
        $validationRules = [
            'thumbnail' => [
                'rules' => 'uploaded[thumbnail]|is_image[thumbnail]|max_size[thumbnail,100]|mime_in[thumbnail,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'uploaded' => 'Thumbnail wajib diisi',
                    'is_image' => 'File thumbnail harus berupa gambar',
                    'max_size' => 'Ukuran thumbnail maksimal 100 KB',
                    'mime_in' => 'Format thumbnail harus JPG/JPEG/PNG'
                ]
            ],
            'featured_image' => [
                'rules' => 'is_image[featured_image]|max_size[featured_image,300]|mime_in[featured_image,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'is_image' => 'File gambar utama harus berupa gambar',
                    'max_size' => 'Ukuran gambar utama maksimal 300 KB',
                    'mime_in' => 'Format gambar utama harus JPG/JPEG/PNG'
                ]
            ]
        ];

        if (!$this->validate($validationRules)) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->back()->withInput();
        }

        // Proses thumbnail (cropped)
        $thumbnailCropped = $this->request->getVar('thumbnail_cropped');
        $thumbnailName = null;
        if (!empty($thumbnailCropped)) {
            $thumbnailName = $this->saveCroppedImage(
                $thumbnailCropped,
                'thumbnail-' . $judul_id,
                'uploads/thumbnail/'
            );
        }

        // Proses featured image (cropped)
        $featuredCropped = $this->request->getVar('featured_cropped');
        $featuredName = null;
        if (!empty($featuredCropped)) {
            $featuredName = $this->saveCroppedImage(
                $featuredCropped,
                'featured-' . $judul_id,
                'uploads/gambar_besar/'
            );
        }

        // Simpan ke database
        $data = [
            'id_user' => $id_user,
            'id_kategori' => $id_kategori,
            'judul_id' => $judul_id,
            'judul_en' => $judul_en,
            'slug_id' => $slug_id,
            'slug_en' => $slug_en,
            'konten_id' => $konten_id,
            'konten_en' => $konten_en,
            'thumbnail' => $thumbnailName,
            'gambar_besar' => $featuredName,
            'sumber_gambar' => $photo_source,
            'tags_id' => $tags_id,
            'tags_en' => $tags_en,
            'meta_title_id' => $meta_title_id,
            'meta_title_en' => $meta_title_en,
            'meta_description_id' => $meta_description_id,
            'meta_description_en' => $meta_description_en,
            'published_at' => date('Y-m-d H:i:s')
        ];

        $this->artikelModel->insert($data);

        session()->setFlashdata('success', 'Artikel berhasil ditambahkan!');
        return redirect()->to(base_url('penulis/berita/index'));
    }

    /**
     * Helper untuk menyimpan gambar cropped (base64) ke file
     */
    private function saveCroppedImage($base64Data, $prefix, $uploadPath)
    {
    // Hapus header base64
    $base64Data = preg_replace('#^data:image/\w+;base64,#i', '', $base64Data);
    $imageData = base64_decode($base64Data);

    // Generate nama file unik dengan mengganti spasi menjadi tanda hubung
    $fileName = str_replace(' ', '-', $prefix) . '-' . time() . '.jpg';
    $filePath = $uploadPath . $fileName;

    // Simpan ke direktori
    file_put_contents(FCPATH . $filePath, $imageData);

    return $fileName;
    }

     public function edit($id_artikel)
    {
        // Pengecekan apakah pengguna sudah login atau belum
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }
    
        $data = [
            'artikel' => $this->artikelModel->find($id_artikel),
            'all_data_kategori' => $this->kategoriModel->findAll(),
            'validation' => \Config\Services::validation()
        ];
    
        if (empty($data['artikel'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Artikel tidak ditemukan');
        }
    
        // Pastikan penulis hanya bisa mengedit artikelnya sendiri
        if ($data['artikel']['id_user'] != session()->get('id_user')) {
            return redirect()->to(base_url('penulis/berita/index'))->with('error', 'Anda tidak memiliki akses ke artikel ini');
        }
    
        return view('penulis/berita/edit', $data);
    }
    
    public function proses_edit($id_artikel)
    {
        // Pengecekan login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }
    
        // Validasi input
        $validationRules = [
            'judul_id' => 'required',
            'judul_en' => 'required',
            'konten_id' => 'required',
            'konten_en' => 'required',
            'id_kategori' => 'required',
            'thumbnail' => [
                'rules' => 'is_image[thumbnail]|max_size[thumbnail,300]|mime_in[thumbnail,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'is_image' => 'File thumbnail harus berupa gambar',
                    'max_size' => 'Ukuran thumbnail maksimal 300 KB',
                    'mime_in' => 'Format thumbnail harus JPG/JPEG/PNG'
                ]
            ],
            'featured_image' => [
                'rules' => 'is_image[featured_image]|max_size[featured_image,100]|mime_in[featured_image,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'is_image' => 'File gambar utama harus berupa gambar',
                    'max_size' => 'Ukuran gambar utama maksimal 100 KB',
                    'mime_in' => 'Format gambar utama harus JPG/JPEG/PNG'
                ]
            ]
        ];
    
        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }
    
        // Dapatkan data artikel yang akan diupdate
        $artikel = $this->artikelModel->find($id_artikel);
        if (!$artikel || $artikel['id_user'] != session()->get('id_user')) {
            return redirect()->to(base_url('penulis/berita/index'))->with('error', 'Artikel tidak ditemukan atau Anda tidak memiliki akses');
        }
    
        // Generate slug baru
        $slug_id = $this->generateSlug($this->request->getVar('judul_id'));
        $slug_en = $this->generateSlug($this->request->getVar('judul_en'));
    
        // Handle thumbnail
        $thumbnailName = $artikel['thumbnail'];
        $thumbnailCropped = $this->request->getVar('thumbnail_cropped');
        if (!empty($thumbnailCropped)) {
            // Hapus thumbnail lama jika ada
            if ($thumbnailName && file_exists(FCPATH . 'uploads/thumbnail/' . $thumbnailName)) {
                unlink(FCPATH . 'uploads/thumbnail/' . $thumbnailName);
            }
            $thumbnailName = $this->saveCroppedImage(
                $thumbnailCropped,
                'thumbnail-' . $this->request->getVar('judul_id'),
                'uploads/thumbnail/'
            );
        } elseif ($this->request->getFile('thumbnail')->isValid()) {
            // Hapus thumbnail lama jika ada
            if ($thumbnailName && file_exists(FCPATH . 'uploads/thumbnail/' . $thumbnailName)) {
                unlink(FCPATH . 'uploads/thumbnail/' . $thumbnailName);
            }
            $thumbnailFile = $this->request->getFile('thumbnail');
            $thumbnailName = 'thumbnail-' . $this->request->getVar('judul_id') . '-' . time() . '.' . $thumbnailFile->getExtension();
            $thumbnailFile->move(FCPATH . 'uploads/thumbnail/', $thumbnailName);
        }
    
        // Handle featured image
        $featuredName = $artikel['gambar_besar'];
        $featuredCropped = $this->request->getVar('featured_cropped');
        if (!empty($featuredCropped)) {
            // Hapus featured image lama jika ada
            if ($featuredName && file_exists(FCPATH . 'uploads/gambar_besar/' . $featuredName)) {
                unlink(FCPATH . 'uploads/gambar_besar/' . $featuredName);
            }
            $featuredName = $this->saveCroppedImage(
                $featuredCropped,
                'featured-' . $this->request->getVar('judul_id'),
                'uploads/gambar_besar/'
            );
        } elseif ($this->request->getFile('featured_image')->isValid()) {
            // Hapus featured image lama jika ada
            if ($featuredName && file_exists(FCPATH . 'uploads/gambar_besar/' . $featuredName)) {
                unlink(FCPATH . 'uploads/gambar_besar/' . $featuredName);
            }
            $featuredFile = $this->request->getFile('featured_image');
            $featuredName = 'featured-' . $this->request->getVar('judul_id') . '-' . time() . '.' . $featuredFile->getExtension();
            $featuredFile->move(FCPATH . 'uploads/gambar_besar/', $featuredName);
        }
    
        // Data untuk diupdate
        $data = [
            'id_kategori' => $this->request->getVar('id_kategori'),
            'judul_id' => $this->request->getVar('judul_id'),
            'judul_en' => $this->request->getVar('judul_en'),
            'slug_id' => $slug_id,
            'slug_en' => $slug_en,
            'konten_id' => $this->request->getVar('konten_id'),
            'konten_en' => $this->request->getVar('konten_en'),
            'thumbnail' => $thumbnailName,
            'gambar_besar' => $featuredName,
            'sumber_gambar' => $this->request->getVar('photo_source'),
            'tags_id' => $this->request->getVar('tags_id'),
            'tags_en' => $this->request->getVar('tags_en'),
            'meta_title_id' => $this->request->getVar('meta_title_id'),
            'meta_title_en' => $this->request->getVar('meta_title_en'),
            'meta_description_id' => $this->request->getVar('meta_description_id'),
            'meta_description_en' => $this->request->getVar('meta_description_en'),
            'published_at' => $this->request->getVar('published_at') ?: date('Y-m-d H:i:s')
        ];
    
        $this->artikelModel->update($id_artikel, $data);
    
        return redirect()->to(base_url('penulis/berita/index'))->with('success', 'Artikel berhasil diperbarui!');
    }

    public function delete($id = false)
    {
        // Pengecekan apakah pengguna sudah login atau belum
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login')); // Sesuaikan dengan halaman login Anda
        }

        $data = $this->artikelModel->find($id);
        unlink('uploads/thumbnail/' . $data['thumbnail']);
        unlink('uploads/gambar_besar/' . $data['gambar_besar']);
        $this->artikelModel->delete($id);

        return redirect()->to(base_url('penulis/berita/index'));
    }
}
