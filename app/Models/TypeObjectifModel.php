<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeObjectifModel extends Model
{
    protected $table = 'type_objectif';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['libelle'];
    protected $useTimestamps = false;

    public function findAllOrdered(): array
    {
        $items = $this->findAll();

        usort($items, static function (array $left, array $right): int {
            return strcmp((string) ($left['libelle'] ?? ''), (string) ($right['libelle'] ?? ''));
        });

        return $items;
    }
}
