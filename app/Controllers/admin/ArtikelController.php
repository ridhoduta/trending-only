<?php

namespace App\Controllers\admin;

use App\Models\ArtikelModel;
use App\Models\KategoriModel;

class ArtikelController extends BaseController
{
    private $artikelModel;
    private $kategoriModel;

    public function __construct()
    {
        $this->artikelModel = new ArtikelModel();
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
            'artikels' => $this->artikelModel->findAll(),
        ];

        return view('admin/artikel/index', $data);
    }


    public function set_approval($id_artikel, $status)
    {
        // Pengecekan apakah pengguna sudah login atau belum
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        // Validasi status
        if (!in_array($status, ['sudah disetujui', 'belum disetujui', 'tidak disetujui'])) {
            session()->setFlashdata('error', 'Status persetujuan tidak valid.');
            return redirect()->back();
        }

        // Update status
        $this->artikelModel->update($id_artikel, ['is_approved' => $status]);

        session()->setFlashdata('success', 'Status persetujuan artikel berhasil diubah.');
        return redirect()->to(base_url('admin/artikel/index'));
    }
}
