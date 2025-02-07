<?php

namespace App\Models;

use App\Repository\CrudInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Http\Traits\Uuid;

class SalesDetailModel extends Model implements CrudInterface
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;
    protected $table = 't_sales_details';
    protected $fillable = [
        't_sales_id',
        'm_product_id',
        'm_product_detail_id',
        'total_item',
        'price',
    ];
    public $timestamps = true;

    public function sale()
    {
        return $this->belongsTo(SalesModel::class, 't_sales_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(ProductModel::class, 'm_product_id', 'id');
    }

    public function productDetail()
    {
        return $this->belongsTo(ProductDetailModel::class, 'm_product_detail_id');
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = '')
    {
        $salesDetail = $this->query();

        if (!empty($filter['name'])) {
            $salesDetail->where('name', 'LIKE', '%'.$filter['name'].'%');
        }

        $sort = $sort ?: 'id DESC';
        $salesDetail->orderByRaw($sort);
        $itemPerPage = ($itemPerPage > 0) ? $itemPerPage : false ;

        return $salesDetail->paginate($itemPerPage)->appends('sort', $sort);
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
