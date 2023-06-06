<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jenssegers\Mongodb\Eloquent\HybridRelations;

use Illuminate\Support\Facades\Hash;

/**
 * public @method departments()
 */
class Comment extends BaseModel
{
    use HybridRelations;
    use SoftDeletes;

    protected $connection = 'pgsql';
    protected $table = 'comments';

    protected $dates = [
        'deleted_at',
    ];

    protected $casts = [
    ];

    protected $fillable = [
        'club_id', 'user_id', 'board_id', 'content', 'hidden_comment', 'created_at', 'updated_at','deleted_at'
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
        # 게시판 아이디
        $this->board_id = isset($attributes['board_id']) ? $attributes['board_id'] : null;
        // # 부모 댓글 아이디
        // $this->parent_id = isset($attributes['parent_id']) ? $attributes['parent_id'] : null;
        # 내용
        $this->content = isset($attributes['content']) ? $attributes['content'] : null;
        # 비밀 댓글 여부
        $this->hidden_comment = isset($attributes['hidden_comment']) ? $attributes['hidden_comment'] : false;

        // 삭제/추가/수정 시간
        $this->created_at = isset($attributes['created_at']) ? $attributes['created_at'] : Carbon::now();
        $this->updated_at = isset($attributes['updated_at']) ? $attributes['updated_at'] : Carbon::now();
        $this->deleted_at = isset($attributes['deleted_at']) ? $attributes['deleted_at'] : null;
    }
}
