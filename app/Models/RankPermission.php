<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jenssegers\Mongodb\Eloquent\HybridRelations;

/**
 * public @method departments()
 */
class RankPermission extends BaseModel
{
    use HybridRelations;
    use SoftDeletes;

    protected $connection = 'pgsql';
    protected $table = 'rank_permissions';

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable = [
        'club_id', 'rank_id', 'board_access', 'comment_access', 'image_add_access', 'anonymous_comment_access', 'community_add_access', 'user_ben_access', 'admin_board_access', 'user_change_access', 'admin_access', 'created_at', 'updated_at', 'deleted_at'
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        # 동아리 아이디
        $this->club_id = isset($attributes['club_id']) ? $attributes['club_id'] : null;
        # 랭크 아이디
        $this->rank_id = isset($attributes['rank_id']) ? $attributes['rank_id'] : null;
        # 게시판 권한
        $this->board_access = isset($attributes['board_access']) ? $attributes['board_access'] : true;
        # 댓글 권한
        $this->comment_access = isset($attributes['comment_access']) ? $attributes['comment_access'] : true;
        # 이미지 업로드 권한
        $this->image_add_access = isset($attributes['image_add_access']) ? $attributes['image_add_access'] : true;
        # 익명 댓글 권한
        $this->anonymous_comment_access = isset($attributes['anonymous_comment_access']) ? $attributes['anonymous_comment_access'] : true;

        # 커뮤니티 추가 권한
        $this->community_add_access = isset($attributes['community_add_access']) ? $attributes['community_add_access'] : false;
        # 사용자 벤 권한
        $this->user_ben_access = isset($attributes['user_ben_access']) ? $attributes['user_ben_access'] : false;
        # 관리자 관련 게시판 탭 권한
        $this->admin_board_access = isset($attributes['admin_board_access']) ? $attributes['admin_board_access'] : false;
        # 특정 사용자 변경 권한
        $this->user_change_access = isset($attributes['user_change_access']) ? $attributes['user_change_access'] : false;
        # 어드민 권한
        $this->admin_access = isset($attributes['admin_access']) ? $attributes['admin_access'] : false;

        $this->position = isset($attributes['position']) ? $attributes['position'] : 0;

        // 삭제/추가/수정 시간
        $this->created_at = isset($attributes['created_at']) ? $attributes['created_at'] : Carbon::now();
        $this->updated_at = isset($attributes['updated_at']) ? $attributes['updated_at'] : Carbon::now();
        $this->deleted_at = isset($attributes['deleted_at']) ? $attributes['deleted_at'] : null;
    }

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function rank()
    {
        return $this->hasOne(Rank::class);
    }
}
