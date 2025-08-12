<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class PenulisController extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        $model = new UserModel();
        $data['penulis'] = $model->findAll();

        return view('admin/penulis/index', [
            'penulis' => $data['penulis'],
            'validation' => \Config\Services::validation()
        ]);
    }

    public function tambah()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        return view('admin/penulis/tambah', [
            'validation' => \Config\Services::validation()
        ]);
    }

    public function proses_tambah()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }
    
        // Validasi input
        $rules = [
            'username' => 'required|min_length[5]|max_length[20]|is_unique[tb_users.username]',
            'nama_lengkap' => 'required|min_length[3]|max_length[100]',
            'foto_profil' => [
                'mime_in[foto_profil,image/jpg,image/jpeg,image/png]',
                'max_size[foto_profil,2048]',
            ]
        ];
    
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
    
        // Handle upload foto profil
        $fotoProfil = $this->request->getFile('foto_profil');
        $newName = 'user.png'; // Nama file default
    
        // Jika ada file yang diupload dan valid
        if ($fotoProfil->isValid() && !$fotoProfil->hasMoved()) {
            $newName = $fotoProfil->getRandomName();
            $fotoProfil->move(ROOTPATH . 'uploads/penulis', $newName);
        }
    
        $data = [
            'username' => $this->request->getPost("username"),
            'nama_lengkap' => $this->request->getPost("nama_lengkap"),
            'password' => password_hash('12345678', PASSWORD_DEFAULT), // Password default
            'foto_profil' => $newName,
            'role' => 'penulis',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
    
        $model = new UserModel();
        $model->insert($data);
    
        session()->setFlashdata('success', 'Penulis berhasil ditambahkan');
        return redirect()->to(base_url('admin/penulis/index'));
}

    public function edit($id_penulis)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        $model = new UserModel();
        $penulis = $model->find($id_penulis);

        if (!$penulis) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Penulis tidak ditemukan');
        }
        
        dd($penulis);

        return view('admin/penulis/edit', [
            'penulis' => $penulis,
            'validation' => \Config\Services::validation()
        ]);
    }

    public function proses_edit($id_penulis = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        if (!$id_penulis) {
            return redirect()->back();
        }

        $model = new UserModel();
        $penulis = $model->find($id_penulis);

        if (!$penulis) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Penulis tidak ditemukan');
        }

        // Validasi input
        $rules = [
            'nama_lengkap' => 'required|min_length[3]|max_length[100]',
            'foto_profil' => [
                'mime_in[foto_profil,image/jpg,image/jpeg,image/png]',
                'max_size[foto_profil,2048]',
            ]
        ];

        // Jika username diubah, validasi unik
        if ($this->request->getPost("username") !== $penulis['username']) {
            $rules['username'] = 'required|min_length[5]|max_length[20]|is_unique[tb_users.username]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getPost("username"),
            'nama_lengkap' => $this->request->getPost("nama_lengkap"),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Handle upload foto profil jika ada
        $fotoProfil = $this->request->getFile('foto_profil');
        if ($fotoProfil->isValid() && !$fotoProfil->hasMoved()) {
            // Hapus foto lama jika ada
            if ($penulis['foto_profil'] && file_exists(ROOTPATH . 'public/uploads/penulis/' . $penulis['foto_profil'])) {
                unlink(ROOTPATH . 'public/uploads/penulis/' . $penulis['foto_profil']);
            }

            $newName = $fotoProfil->getRandomName();
            $fotoProfil->move(ROOTPATH . 'public/uploads/penulis', $newName);
            $data['foto_profil'] = $newName;
        }

        $model->update($id_penulis, $data);

        session()->setFlashdata('success', 'Penulis berhasil diperbarui');
        return redirect()->to(base_url('admin/penulis/index'));
    }

    public function hapus($id_penulis)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        $model = new UserModel();
        $penulis = $model->find($id_penulis);

        if (!$penulis) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Penulis tidak ditemukan');
        }

        // Hapus foto profil jika ada
        if ($penulis['foto_profil'] && file_exists(ROOTPATH . 'uploads/penulis/' . $penulis['foto_profil'])) {
            unlink(ROOTPATH . 'uploads/penulis/' . $penulis['foto_profil']);
        }

        $model->delete($id_penulis);

        session()->setFlashdata('success', 'Penulis berhasil dihapus');
        return redirect()->to(base_url('admin/penulis/index'));
    }
}
