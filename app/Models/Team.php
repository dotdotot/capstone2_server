<?php

namespace App\Models;

use Carbon\Carbon;
// use Franzose\ClosureTable\Models\Entity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use ElasticScoutDriverPlus\Searchable;
use Jenssegers\Mongodb\Eloquent\HybridRelations;

class Team extends Entity
{
    // use Searchable;
    use SoftDeletes;
    use HybridRelations;

    protected $dateFormat = 'Y-m-d H:i:s.uO';

    public $timestamps = true;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $connection = 'pgsql';
    protected $table = 'teams';

    protected $dates = [
        'deleted_at',
    ];
    /**
     * ClosureTable model instance.
     *
     * @var \App\Models\TeamClosure
     */
    protected $closure = TeamClosure::class;

    protected $fillable = [
        'club_id', 'parent_id', 'name', 'position', 'path'
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->club_id = isset($attributes['company_id']) ? $attributes['company_id'] : null;
        $this->parent_id = isset($attributes['parent_id']) ? $attributes['parent_id'] : null;
        $this->name = isset($attributes['name']) ? $attributes['name'] : null;
        $this->position = isset($attributes['position']) ? $attributes['position'] : 0;
        $this->path = isset($attributes['path']) ? $attributes['path'] : null;

        // 삭제/추가/수정 시간
        $this->created_at = isset($attributes['created_at']) ? $attributes['created_at'] : Carbon::now();
        $this->updated_at = isset($attributes['updated_at']) ? $attributes['updated_at'] : Carbon::now();
        $this->deleted_at = isset($attributes['deleted_at']) ? $attributes['deleted_at'] : null;
    }

    public function parent()
    {
        return $this->belongsTo(Club::class, 'parent_id');
    }

    public function members()
    {
        return $this->hasMany(Member::class);
    }

    public function closureDescendants()
    {
        return $this->hasMany(TeamClosure::class, 'ancestor');
    }

    public function closureAncestors()
    {
        return $this->hasMany(TeamClosure::class, 'descendant');
    }
}
