<?php

namespace App\Models;

use App\Http\Traits\Uuid;
use App\Repository\CrudInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Testing\Fluent\Concerns\Has;

class ProductCategoryModel extends Model implements CrudInterface
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;
    public $timestamps = true;
    protected $fillable = [
        'id',
        'name'
    ];
    protected $table = 'm_product_category';

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = '')
    {
        $user = $this->query();
        if (!empty($filter['name'])) {
            $user->where('name', 'like', '%' . $filter['name'] . '%');
        }

        $sort = $sort ?: 'id DESC';
        $user->orderByRaw($sort);
        $itemPerPage = ($itemPerPage > 0) ? $itemPerPage : false ;

        return $user->paginate($itemPerPage)->appends('sort', $sort);
    }

    public function getById(string $id)
    {
        return $this->find($id);
    }

    public function store(array $payload)
    {
        return $this->create($payload);
    }

    public function edit(array $payload, string $id)
    {
        return $this->find($id)->update($payload);
    }

    public function drop(string $id)
    {
        return $this->find($id)->delete();
    }
}
