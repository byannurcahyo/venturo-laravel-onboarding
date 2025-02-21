<?php

namespace App\Models;

use App\Http\Traits\Uuid;
use App\Repository\CrudInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Testing\Fluent\Concerns\Has;

class UserModel extends Model implements CrudInterface
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;
    public $timestamps = true;
    protected $fillable = [
        'name',
        'email',
        'password',
        'photo',
        'phone_number',
    ];
    protected $attributes = [
        'm_user_roles_id' => 'dc492e77-742f-4c58-9479-f270887ffe5c',
    ];
    protected $table = 'm_users';

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = '')
    {
        $user = $this->query();
        if (!empty($filter['name'])) {
            $user->where('name', 'LIKE', '%'.$filter['name'].'%');
        }
        if (!empty($filter['email'])) {
            $user->where('email', 'LIKE', '%'.$filter['email'].'%');
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

    public function drop(string $id) {
        return $this->find($id)->delete();
    }
}

