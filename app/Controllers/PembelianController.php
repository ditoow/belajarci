<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;

class PembelianController extends BaseController
{
    protected $transactionModel;
    protected $transactionDetailModel;

    public function __construct()
    {
        helper(['form', 'number']);
        $this->transactionModel = new TransactionModel();
        $this->transactionDetailModel = new TransactionDetailModel();
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

        $transactions = $this->transactionModel->orderBy('created_at', 'DESC')->findAll();
        $transactionIds = array_column($transactions, 'id');
        $products = $this->transactionDetailModel->getProductsByTransactionIds($transactionIds);

        return view('v_pembelian', [
            'transactions' => $transactions,
            'products' => $products
        ]);
    }

    public function toggle_status($id)
    {
        if (!$this->checkAdmin()) {
            return redirect()->to(base_url())->with('failed', 'Akses ditolak. Hanya untuk Admin.');
        }

        $transaction = $this->transactionModel->find($id);
        if ($transaction) {
            $newStatus = ($transaction['status'] == 0) ? 1 : 0;
            $this->transactionModel->update($id, [
                'status' => $newStatus
            ]);
            return redirect()->to(base_url('pembelian'))->with('success', 'Status transaksi berhasil diperbarui.');
        }

        return redirect()->to(base_url('pembelian'))->with('failed', 'Transaksi tidak ditemukan.');
    }
}
