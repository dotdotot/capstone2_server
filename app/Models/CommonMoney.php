<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jenssegers\Mongodb\Eloquent\HybridRelations;

use Illuminate\Support\Facades\Hash;

/**
 * public @method departments()
 */
class CommonMoney extends BaseModel
{
    use HybridRelations;
    use SoftDeletes;

    protected $connection = 'pgsql';
    protected $table = 'common_moneys';

    protected $dates = [
        'deleted_at',
    ];

    protected $casts = [
    ];

    protected $fillable = [
        'club_id', 'menu_id', 'money', 'position', 'created_at', 'updated_at','deleted_at'
    ];

    protected $hidden = [
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        # 클럽 아이디
        $this->club_id = isset($attributes['club_id']) ? $attributes['club_id'] : null;
        # 메뉴 아이디
        $this->menu_id = isset($attributes['menu_id']) ? $attributes['menu_id'] : null;
        # 돈
        $this->money = isset($attributes['money']) ? $attributes['money'] : 0;
        # 순서
        $this->position = isset($attributes['position']) ? $attributes['position'] : 0;

        // 삭제/추가/수정 시간
        $this->created_at = isset($attributes['created_at']) ? $attributes['created_at'] : Carbon::now();
        $this->updated_at = isset($attributes['updated_at']) ? $attributes['updated_at'] : Carbon::now();
        $this->deleted_at = isset($attributes['deleted_at']) ? $attributes['deleted_at'] : null;
    }
}
