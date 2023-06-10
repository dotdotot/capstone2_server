<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jenssegers\Mongodb\Eloquent\HybridRelations;

use Illuminate\Support\Facades\Hash;

/**
 * public @method departments()
 */
class Menu extends BaseModel
{
    use HybridRelations;
    use SoftDeletes;

    protected $connection = 'pgsql';
    protected $table = 'menus';

    protected $dates = [
        'deleted_at',
    ];

    protected $casts = [
    ];

    protected $fillable = [
        'club_id', 'title', 'type', 'position', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $hidden = [
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        # 클럽 아이디
        $this->club_id = isset($attributes['club_id']) ? $attributes['club_id'] : null;
        # 제목
        $this->title = isset($attributes['title']) ? $attributes['title'] : null;
        # 메뉴 타입 (board, bulletin, image_board)
        $this->type = isset($attributes['type']) ? $attributes['type'] : null;
        # 순서
        $this->position = isset($attributes['position']) ? $attributes['position'] : 0;

        // 삭제/추가/수정 시간
        $this->created_at = isset($attributes['created_at']) ? $attributes['created_at'] : Carbon::now();
        $this->updated_at = isset($attributes['updated_at']) ? $attributes['updated_at'] : Carbon::now();
        $this->deleted_at = isset($attributes['deleted_at']) ? $attributes['deleted_at'] : null;
    }
}
