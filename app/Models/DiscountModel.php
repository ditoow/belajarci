<?php

namespace App\Models;

use CodeIgniter\Model;

class DiscountModel extends Model
{
    protected $table            = 'discount';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['tanggal', 'nominal'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'id'      => 'permit_empty|numeric',
        'tanggal' => 'required|valid_date|is_unique[discount.tanggal,id,{id}]',
        'nominal' => 'required|numeric|greater_than[0]'
    ];
    protected $validationMessages   = [
        'tanggal' => [
            'is_unique' => 'The tanggal field must contain a unique value.'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
