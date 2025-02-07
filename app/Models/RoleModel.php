<?php

namespace App\Models;

use App\Http\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Repository\CrudInterface;

class RoleModel extends Model implements CrudInterface
{
    use HasFactory;
    use Uuid;
    use SoftDeletes;
    protected $table = 'm_user_roles';
    protected $fillable = [
        'name',
        'access',
    ];
    public $timestamps = true;
    protected $attributes = [
        'access' => '{"create":false,"read":false,"update":false,"delete":false}',
    ];
    public function users()
    {
        return $this->hasMany(UserModel::class, 'm_user_roles_id', 'id');
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = '')
    {
        $role = $this->query();

        if (!empty($filter['name'])) {
            $role->where('name', 'LIKE', '%'.$filter['name'].'%');
        }

        $sort = $sort ?: 'id DESC';
        $role->orderByRaw($sort);
        $itemPerPage = ($itemPerPage > 0) ? $itemPerPage : false ;

        return $role->paginate($itemPerPage)->appends('sort', $sort);
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
