<?php

namespace App\Models;

use App\Http\Traits\Uuid;
use App\Repository\CrudInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesModel extends Model implements CrudInterface
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;
    protected $table = 't_sales';
    protected $fillable = [
        'm_customer_id',
        'date',
    ];
    public $timestamps = true;

    public function customer()
    {
        return $this->belongsTo(CustomerModel::class, 'm_customer_id', 'id');
    }

    public function details()
    {
        return $this->hasMany(SalesDetailModel::class, 't_sales_id', 'id');
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = '')
    {
        $sales = $this->query();
        if (!empty($filter['type'])) {
            $sales->where('type', 'like', '%' . $filter['type'] . '%');
        }

        $sort = $sort ?: 'id DESC';
        $sales->orderByRaw($sort);
        $itemPerPage = ($itemPerPage > 0) ? $itemPerPage : false ;

        return $sales->paginate($itemPerPage)->appends('sort', $sort);
    }

    public function getSalesByCategory($startDate, $endDate, $category = '')
    {
        $sales = $this->query->with([
            'detail.product' => function ($query) use ($category) {
                if (!empty($category)) {
                    $query->where('m_product_category_id', $category);
                }
            },
            'detail',
            'detail.product.category'
        ]);

        if (!empty($startDate) && !empty($endDate)) {
            $sales->whereRaw('date >= "' . $startDate . ' 00:00:01" and date <= "' . $endDate . ' 23:59:59"');
        }
        return $sales->orderByDesc('date')->limit(2)->get();
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
