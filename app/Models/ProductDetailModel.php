<?php

namespace App\Models;

use App\Http\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use App\Repository\CrudInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductDetailModel extends Model implements CrudInterface
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;
    public $timestamps = true;
    protected $fillable = [
        'type',
        'description',
        'price',
        'm_product_id'
    ];
    protected $table = 'm_product_detail';

    public function product()
    {
        return $this->belongsTo(ProductModel::class, 'm_product_id', 'id');
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = '')
    {
        $user = $this->query();
        if (!empty($filter['type'])) {
            $user->where('type', 'like', '%' . $filter['type'] . '%');
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
