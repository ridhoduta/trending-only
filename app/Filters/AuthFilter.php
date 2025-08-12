<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Jika belum login
        if (!$session->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Jika membutuhkan role tertentu
        if (!empty($arguments)) {
            $role = $session->get('role');

            if (!in_array($role, $arguments)) {
                if ($role === 'admin') {
                    return redirect()->to('/admin/dashboard');
                } elseif ($role === 'penulis') {
                    return redirect()->to('/penulis/dashboard');
                }

                // Logout jika role tidak valid
                return redirect()->to('/logout');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
