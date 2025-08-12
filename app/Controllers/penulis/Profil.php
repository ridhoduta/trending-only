<?php

namespace App\Controllers\penulis;

use App\Models\ProfilModel;
use App\Models\SliderModel;
use App\Models\UserModel;

class Profil extends BaseController
{
    protected $model;
    protected $id_user;
    protected $user;

    public function __construct()
    {
        $this->model = new UserModel();
        $this->id_user = session()->get('id_user');
        $this->user = $this->model->find($this->id_user);
    }

    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        if (!$this->user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pengguna tidak ditemukan');
        }

        return view('penulis/profil/edit', [
            'user' => $this->user,
            'validation' => \Config\Services::validation()
        ]);
    }

    public function updateUsername()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        // Validasi input
        $rules = [
            'username' => 'required|min_length[5]|max_length[20]|is_unique[tb_users.username]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('username_errors', $this->validator->getErrors());
        }

        $newUsername = $this->request->getPost("username");

        $this->model->update($this->id_user, [
            'username' => $newUsername,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        session()->setFlashdata('success', 'Username berhasil diperbarui');
        return redirect()->to(base_url('penulis/profil'));
    }

    public function updateNamaLengkap()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        // Validasi input
        $rules = [
            'nama_lengkap' => 'required|min_length[3]|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('nama_errors', $this->validator->getErrors());
        }

        $newNama = $this->request->getPost("nama_lengkap");

        $this->model->update($this->id_user, [
            'nama_lengkap' => $newNama,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        session()->setFlashdata('success', 'Nama lengkap berhasil diperbarui');
        return redirect()->to(base_url('penulis/profil'));
    }

    public function updateFotoProfil()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        // Validasi input
        $rules = [
            'foto_profil' => [
                'uploaded[foto_profil]',
                'mime_in[foto_profil,image/jpg,image/jpeg,image/png]',
                'max_size[foto_profil,1024]',
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('foto_errors', $this->validator->getErrors());
        }

        $fotoProfil = $this->request->getFile('foto_profil');
        $newName = $fotoProfil->getRandomName();

        // Pindahkan file baru
        $fotoProfil->move(ROOTPATH . 'public/uploads/penulis', $newName);

        // Hapus foto lama jika ada
        if ($this->user['foto_profil'] && file_exists(ROOTPATH . 'public/uploads/penulis/' . $this->user['foto_profil'])) {
            unlink(ROOTPATH . 'public/uploads/penulis/' . $this->user['foto_profil']);
        }

        $this->model->update($this->id_user, [
            'foto_profil' => $newName,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        session()->setFlashdata('success', 'Foto profil berhasil diperbarui');
        return redirect()->to(base_url('penulis/profil'));
    }

    public function updatePassword()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        // Validasi input
        $rules = [
            'password_lama' => 'required',
            'password_baru' => 'required|min_length[8]',
            'konfirmasi_password' => 'required|matches[password_baru]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('password_errors', $this->validator->getErrors());
        }

        // Verifikasi password lama
        if (!password_verify($this->request->getPost("password_lama"), $this->user['password'])) {
            session()->setFlashdata('error', 'Password lama tidak sesuai');
            return redirect()->to(base_url('penulis/profil'));
        }

        // Update password baru
        $newPassword = password_hash($this->request->getPost("password_baru"), PASSWORD_DEFAULT);
        $this->model->update($this->id_user, ['password' => $newPassword]);

        session()->setFlashdata('success', 'Password berhasil diperbarui');
        return redirect()->to(base_url('penulis/profil'));
    }
}
