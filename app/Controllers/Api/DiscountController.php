<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\DiscountModel;

class DiscountController extends BaseController
{
    use ResponseTrait;
    protected $model;
    private $token;

    public function __construct()
    {
        $this->model = new DiscountModel();
        $this->token = env('MY_API_KEY');
    }

    private function authenticate()
    {
        $header = $this->request->getHeaderLine('Authorization');

        if (empty($header)) {
            return false;
        }

        if (!preg_match('/Bearer\s+(.*)$/i', $header, $matches)) {
            return false;
        }

        return $matches[1] === $this->token;
    }

    private function unauthorized()
    {
        return $this->respond([
            'status'  => false,
            'message' => 'Unauthorized'
        ], 401);
    }

    public function index()
    {
        if (!$this->authenticate()) {
            return $this->unauthorized();
        }

        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = (int) ($this->request->getGet('per_page') ?? 10);

        $discounts = $this->model->paginate($perPage, 'default', $page);

        return $this->respond([
            'data' => $discounts,
            'pagination' => [
                'current_page' => $page,
                'per_page'     => $perPage,
                'last_page'    => $this->model->pager->getPageCount(),
                'total_data'   => $this->model->pager->getTotal(),
                'has_next'     => $page < $this->model->pager->getPageCount(),
                'has_prev'     => $page > 1,
            ]
        ]);
    }

    public function show($id = null)
    {
        if (!$this->authenticate()) {
            return $this->unauthorized();
        }

        $discount = $this->model->find($id);

        if (!$discount) {
            return $this->failNotFound('Diskon tidak ditemukan');
        }

        return $this->respond($discount);
    }

    public function create()
    {
        if (!$this->authenticate()) {
            return $this->unauthorized();
        }

        $data = $this->request->getJSON(true);

        if (!$this->model->insert($data)) {
            return $this->failValidationErrors($this->model->errors());
        }

        return $this->respondCreated([
            'message' => 'Diskon berhasil ditambahkan'
        ]);
    }

    public function update($id = null)
    {
        if (!$this->authenticate()) {
            return $this->unauthorized();
        }

        $existing = $this->model->find($id);
        if (!$existing) {
            return $this->failNotFound('Diskon tidak ditemukan');
        }

        $data = $this->request->getJSON(true);
        if (empty($data)) {
            $data = [];
        }

        // Merge existing id and tanggal to pass validation rules
        $data['id'] = $id;
        if (!isset($data['tanggal'])) {
            $data['tanggal'] = $existing['tanggal'];
        }

        if (!$this->model->update($id, $data)) {
            return $this->failValidationErrors($this->model->errors());
        }

        return $this->respond([
            'message' => 'Diskon berhasil diperbarui'
        ]);
    }

    public function delete($id = null)
    {
        if (!$this->authenticate()) {
            return $this->unauthorized();
        }

        if (!$this->model->find($id)) {
            return $this->failNotFound('Diskon tidak ditemukan');
        }

        $this->model->delete($id);

        return $this->respondDeleted([
            'message' => 'Diskon berhasil dihapus'
        ]);
    }
}
