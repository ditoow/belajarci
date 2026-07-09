<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DiscountModel;

class DiskonController extends BaseController
{
    protected $discountModel;

    public function __construct()
    {
        helper(['form', 'number']);
        $this->discountModel = new DiscountModel();
    }

    private function checkAdmin()
    {
        if (session()->get('role') !== 'admin') {
            return false;
        }
        return true;
    }

    public function index()
    {
        if (!$this->checkAdmin()) {
            return redirect()->to(base_url())->with('failed', 'Akses ditolak. Hanya untuk Admin.');
        }

        return view('v_diskon', [
            'discounts' => $this->discountModel->findAll()
        ]);
    }

    public function create()
    {
        if (!$this->checkAdmin()) {
            return redirect()->to(base_url())->with('failed', 'Akses ditolak. Hanya untuk Admin.');
        }

        $dataForm = [
            'tanggal' => $this->request->getPost('tanggal'),
            'nominal' => $this->request->getPost('nominal')
        ];

        if (!$this->discountModel->insert($dataForm)) {
            return redirect()->back()->withInput()->with('errors', $this->discountModel->errors());
        }

        return redirect()->to(base_url('diskon'))->with('success', 'Diskon berhasil ditambahkan.');
    }

    public function edit($id)
    {
        if (!$this->checkAdmin()) {
            return redirect()->to(base_url())->with('failed', 'Akses ditolak. Hanya untuk Admin.');
        }

        $existing = $this->discountModel->find($id);
        if (!$existing) {
            return redirect()->back()->with('failed', 'Data tidak ditemukan.');
        }

        $nominal = $this->request->getPost('nominal');

        // Validate nominal specifically since tanggal is readonly
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nominal' => 'required|numeric|greater_than[0]'
        ]);

        if (!$this->validate($validation->getRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $dataForm = [
            'id'      => $id,
            'tanggal' => $existing['tanggal'],
            'nominal' => $nominal
        ];

        if (!$this->discountModel->update($id, $dataForm)) {
            return redirect()->back()->withInput()->with('errors', $this->discountModel->errors());
        }

        return redirect()->to(base_url('diskon'))->with('success', 'Diskon berhasil diubah.');
    }

    public function delete($id)
    {
        if (!$this->checkAdmin()) {
            return redirect()->to(base_url())->with('failed', 'Akses ditolak. Hanya untuk Admin.');
        }

        $this->discountModel->delete($id);

        return redirect()->to(base_url('diskon'))->with('success', 'Diskon berhasil dihapus.');
    }
}
