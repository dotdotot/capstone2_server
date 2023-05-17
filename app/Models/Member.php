<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jenssegers\Mongodb\Eloquent\HybridRelations;

/**
 * public @method departments()
 */
class Member extends BaseModel
{
    use HybridRelations;
    use SoftDeletes;

    protected $connection = 'pgsql';
    protected $table = 'members';

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable = [
        'club_id', 'department_id', 'user_id', 'team_id', 'rank_id', 'position', 'default', 'leader', 'created_at', 'updated_at', 'deleted_at'
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->club_id = isset($attributes['club_id']) ? $attributes['club_id'] : null;
        $this->department_id = isset($attributes['department_id']) ? $attributes['department_id'] : null;
        $this->team_id = isset($attributes['team_id']) ? $attributes['team_id'] : null;
        $this->user_id = isset($attributes['user_id']) ? $attributes['user_id'] : null;
        $this->rank_id = isset($attributes['rank_id']) ? $attributes['rank_id'] : null;
        $this->default = isset($attributes['default']) ? $attributes['default'] : false;
        $this->leader = isset($attributes['leader']) ? $attributes['leader'] : false;
        $this->position = isset($attributes['position']) ? $attributes['position'] : 0;
        // 삭제/추가/수정 시간
        $this->created_at = isset($attributes['created_at']) ? $attributes['created_at'] : Carbon::now();
        $this->updated_at = isset($attributes['updated_at']) ? $attributes['updated_at'] : Carbon::now();
        $this->deleted_at = isset($attributes['deleted_at']) ? $attributes['deleted_at'] : null;
    }
}
