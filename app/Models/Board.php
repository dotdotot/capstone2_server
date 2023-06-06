<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jenssegers\Mongodb\Eloquent\HybridRelations;

use Illuminate\Support\Facades\Hash;

/**
 * public @method departments()
 */
class Board extends BaseModel
{
    use HybridRelations;
    use SoftDeletes;

    protected $connection = 'pgsql';
    protected $table = 'boards';

    protected $dates = [
        'deleted_at',
    ];

    protected $casts = [
    ];

    protected $fillable = [
        'club_id', 'user_id', 'title', 'content', 'hits', 'created_at', 'updated_at','deleted_at'
    ];

    protected $hidden = [
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        # 클럽 아이디
        $this->club_id = isset($attributes['club_id']) ? $attributes['club_id'] : null;
        # 사용자 아이디
        $this->user_id = isset($attributes['user_id']) ? $attributes['user_id'] : null;
        # 제목
        $this->title = isset($attributes['title']) ? $attributes['title'] : null;
        # 내용
        $this->content = isset($attributes['content']) ? $attributes['content'] : null;
        # 조회 수
        $this->hits = isset($attributes['hits']) ? $attributes['hits'] : 0;

        # 이미지 여부
        $this->image = isset($attributes['image']) ? $attributes['image'] : false;

        // 삭제/추가/수정 시간
        $this->created_at = isset($attributes['created_at']) ? $attributes['created_at'] : Carbon::now();
        $this->updated_at = isset($attributes['updated_at']) ? $attributes['updated_at'] : Carbon::now();
        $this->deleted_at = isset($attributes['deleted_at']) ? $attributes['deleted_at'] : null;
    }
}
