<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jenssegers\Mongodb\Eloquent\HybridRelations;

/**
 * public @method departments()
 */
class Department extends BaseModel
{
    use HybridRelations;
    use SoftDeletes;

    protected $connection = 'pgsql';
    protected $table = 'departments';

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable = [
        'club_id', 'name', 'code', 'position', 'created_at', 'updated_at', 'deleted_at'
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->club_id = isset($attributes['club_id']) ? $attributes['club_id'] : null;
        $this->name = isset($attributes['name']) ? $attributes['name'] : '';
        $this->code = isset($attributes['code']) ? $attributes['code'] : null;
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

    public static function departmentCodeCreate()
    {
        # Minimum,Maximum value of the random code
        $min = 1000;
        $max = 9999;

        do {
            $randomCode = random_int($min, $max);
        } while (Department::where('code', 1)->get()->isNotEmpty());

        return $randomCode;
    }
}
