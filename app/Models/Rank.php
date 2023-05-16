<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jenssegers\Mongodb\Eloquent\HybridRelations;

use Illuminate\Support\Facades\Hash;

/**
 * public @method departments()
 */
class Rank extends BaseModel
{
    use HybridRelations;
    use SoftDeletes;

    protected $connection = 'pgsql';
    protected $table = 'ranks';

    protected $dates = [
    ];

    protected $casts = [
    ];

    protected $fillable = [
        'club_id', 'name', 'position'
    ];

    protected $hidden = [
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->club_id = isset($attributes['club_id']) ? $attributes['club_id'] : null;
        $this->name = isset($attributes['name']) ? $attributes['name'] : null;
        $this->position = isset($attributes['position']) ? $attributes['position'] : null;

        // 삭제/추가/수정 시간
        $this->created_at = isset($attributes['created_at']) ? $attributes['created_at'] : Carbon::now();
        $this->updated_at = isset($attributes['updated_at']) ? $attributes['updated_at'] : Carbon::now();
        $this->deleted_at = isset($attributes['deleted_at']) ? $attributes['deleted_at'] : null;
    }

    public function club()
    {
        return $this->belongsTo(Club::class);
    }
}
