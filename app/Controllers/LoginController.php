<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProfilModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class LoginController extends BaseController
{
    public function index()
    {
        if (session()->get('logged_in')) {
            $dashboard = session()->get('role') === 'admin' ? 'admin/dashboard' : 'penulis/dashboard';
            return redirect()->to(base_url($dashboard));
        }
        return view('penulis/login/index');
    }

    public function process()
    {
        $users = new UserModel();
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        // Ambil data user dari database
        $dataUser = $users->where(['username' => $username])->first();
        // dd(password_verify($password, $dataUser['password']));

        if ($dataUser) {
            // Periksa role user dan password
            if (($dataUser['role'] === 'penulis') &&
                password_verify($password, $dataUser['password'])
            ) {

                session()->set([
                    'username' => $dataUser['username'],
                    'nama_lengkap' => $dataUser['nama_lengkap'],
                    'id_user' => $dataUser['id_user'],
                    'role' => $dataUser['role'], // Tambahkan role ke session
                    'logged_in' => TRUE
                ]);
                return redirect()->to(base_url('penulis/dashboard'));
            } else if (($dataUser['role'] === 'admin') &&
                password_verify($password, $dataUser['password'])
            ) {

                session()->set([
                    'username' => $dataUser['username'],
                    'nama_lengkap' => $dataUser['nama_lengkap'],
                    'id_user' => $dataUser['id_user'],
                    'role' => $dataUser['role'], // Tambahkan role ke session
                    'logged_in' => TRUE
                ]);
                return redirect()->to(base_url('admin/dashboard'));
            } else {
                // Jika role tidak sesuai atau password salah
                session()->setFlashdata('error', 'Username & Password Salah atau Anda tidak memiliki akses');
                return redirect()->back();
            }
        } else {
            session()->setFlashdata('error', 'Username & Password Salah');
            return redirect()->back();
        }
    }


    function logout()
    {
        session()->destroy();
        return redirect()->to('login');
    }
}
