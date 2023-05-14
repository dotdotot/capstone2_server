<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jenssegers\Mongodb\Eloquent\HybridRelations;

/**
 * public @method departments()
 */
class User extends BaseModel
{
    use HybridRelations;
    use SoftDeletes;

    protected $connection = 'pgsql';
    protected $table = 'users';

    protected $dates = [
        'password_updated_at',
        'last_login_at',
        'banned_at',
        'deleted_at',
    ];

    protected $casts = [
        'phone' => 'array',
    ];

    protected $fillable = [
        'name', 'gender', 'birth_date', 'phone', 'email', 'address', 'club_id', 'rank_id', 'student_id', 'last_login_at',
        'created_at', 'fail_count','password_updated_at','banned_at','updated_at','deleted_at'
    ];

    protected $hidden = [
        'password',
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->name = isset($attributes['name']) ? $attributes['name'] : '';
        $this->gender = isset($attributes['gender']) ? $attributes['gender'] : '';
        $this->phone = isset($attributes['phone']) ? $attributes['phone'] : [];
        $this->email = isset($attributes['email']) ? $attributes['email'] : '';
        $this->address = isset($attributes['address']) ? $attributes['address'] : '';
        $this->club_id = isset($attributes['club_id']) ? $attributes['club_id'] : null;
        $this->rank_id = isset($attributes['rank_id']) ? $attributes['rank_id'] : null;
        $this->student_id = isset($attributes['student_id']) ? $attributes['student_id'] : null;
        $this->password = isset($attributes['password']) ? $attributes['password'] : null;
        $this->fail_count = isset($attributes['fail_count']) ? $attributes['fail_count'] : 0;

        $this->birth_date = isset($attributes['birth_date']) ? $attributes['birth_date'] : null;
        $this->password_updated_at = isset($attributes['password_updated_at']) ? $attributes['password_updated_at'] : null;
        $this->last_login_at = isset($attributes['last_login_at']) ? $attributes['last_login_at'] : null;
        // 삭제/추가/수정 시간
        $this->deleted_at = isset($attributes['deleted_at']) ? $attributes['deleted_at'] : null;
        $this->updated_at = isset($attributes['updated_at']) ? $attributes['updated_at'] : Carbon::now();
        $this->created_at = isset($attributes['created_at']) ? $attributes['created_at'] : Carbon::now();
    }
}
