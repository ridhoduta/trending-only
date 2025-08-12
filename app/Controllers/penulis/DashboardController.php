<?php

namespace App\Controllers\penulis;

use App\Controllers\BaseController;
use App\Models\BeritaModel;

use CodeIgniter\HTTP\ResponseInterface;
use DateTime;

class DashboardController extends BaseController
{
    // app/Controllers/DashboardController.php

    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        $artikelModel = new BeritaModel();
        $userId = session()->get('id_user'); // pastikan ini tersedia

        // Ambil jumlah artikel
        $data['artikelCount'] = $artikelModel->where('id_user', $userId)->countAllResults();

        // Ambil tanggal yang sudah upload
        $uploadedDatesRaw = $artikelModel->getTanggalUploadByUserThisMonth($userId);
        $uploadedDates = array_column($uploadedDatesRaw, 'tanggal');

        // Buat semua tanggal bulan ini
        $start = new DateTime(date('Y-m-01'));
        $end = new DateTime(date('Y-m-t'));
        $allDates = [];

        while ($start <= $end) {
            $allDates[] = $start->format('Y-m-d');
            $start->modify('+1 day');
        }

        // Pisahkan yang belum upload
        $notUploadedDates = array_diff($allDates, $uploadedDates);

        $data['uploadedDates'] = $uploadedDates;
        $data['notUploadedDates'] = $notUploadedDates;

        return view('penulis/dashboard/index', $data);
    }
}
