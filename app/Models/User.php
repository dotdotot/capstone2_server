<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jenssegers\Mongodb\Eloquent\HybridRelations;

use Illuminate\Support\Facades\Hash;

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
        'name', 'gender', 'birth_date', 'phone', 'email', 'address', 'club_id', 'department_id', 'rank_id', 'student_id', 'last_login_at',
        'created_at', 'password', 'password_fail_count','password_updated_at','banned_at','updated_at','deleted_at'
    ];

    protected $hidden = [
        'password'
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->club_id = isset($attributes['club_id']) ? $attributes['club_id'] : null;
        $this->department_id = isset($attributes['department_id']) ? $attributes['department_id'] : null;
        $this->rank_id = isset($attributes['rank_id']) ? $attributes['rank_id'] : null;

        # 개인정보
        $this->name = isset($attributes['name']) ? $attributes['name'] : '';
        $this->student_id = isset($attributes['student_id']) ? $attributes['student_id'] : null;
        $this->gender = isset($attributes['gender']) ? $attributes['gender'] : '';
        $this->phone = isset($attributes['phone']) ? $attributes['phone'] : [];
        $this->email = isset($attributes['email']) ? $attributes['email'] : '';
        $this->address = isset($attributes['address']) ? $attributes['address'] : '';
        $this->birth_date = isset($attributes['birth_date']) ? $attributes['birth_date'] : null;
        # 경고 횟수
        $this->out_count = isset($attributes['out_count']) ? $attributes['out_count'] : 0;

        # 비밀번호 틀린 횟수
        $this->password = isset($attributes['password']) ? $attributes['password'] : null;
        $this->password_fail_count = isset($attributes['password_fail_count']) ? $attributes['password_fail_count'] : 0;

        $this->password_updated_at = isset($attributes['password_updated_at']) ? $attributes['password_updated_at'] : null;
        $this->last_login_at = isset($attributes['last_login_at']) ? $attributes['last_login_at'] : null;
        // 삭제/추가/수정 시간
        $this->created_at = isset($attributes['created_at']) ? $attributes['created_at'] : Carbon::now();
        $this->updated_at = isset($attributes['updated_at']) ? $attributes['updated_at'] : Carbon::now();
        $this->deleted_at = isset($attributes['deleted_at']) ? $attributes['deleted_at'] : null;
    }

    # 비밀번호 암호화
    public static function passwordEncode($password)
    {
        if ($password === null) {
            return false;
        }

        $hashPassword = Hash::make($password);
        return $hashPassword;
    }

    # 비밀번호 확인
    public static function passwordDecode($userId, $password)
    {
        $user = User::where('id', $userId)->first();
        if($user === null || $user->isEmpty()) {
            return false;
        }

        if (Hash::check($password, $user->password)) {
            return true;
        } else {
            $user->password_fail_count += 1;
            $user->save();
            return false;
        }
    }
}
