<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jenssegers\Mongodb\Eloquent\HybridRelations;

/**
 * public @method departments()
 */
class Club extends BaseModel
{
    use HybridRelations;
    use SoftDeletes;

    protected $connection = 'pgsql';
    protected $table = 'clubs';

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable = [
        'name', 'code', 'grade', 'position', 'created_at', 'updated_at', 'deleted_at'
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        # 클럽이름, 클럽 코드
        $this->name = isset($attributes['name']) ? $attributes['name'] : '';
        $this->code = isset($attributes['code']) ? $attributes['code'] : 0;
        $this->position = isset($attributes['position']) ? $attributes['position'] : 0;

        # 추후 수익 모델을 위한 칼럼
        $this->grade = isset($attributes['grade']) ? $attributes['grade'] : 'normal';

        // 삭제/추가/수정 시간
        $this->created_at = isset($attributes['created_at']) ? $attributes['created_at'] : Carbon::now();
        $this->updated_at = isset($attributes['updated_at']) ? $attributes['updated_at'] : Carbon::now();
        $this->deleted_at = isset($attributes['deleted_at']) ? $attributes['deleted_at'] : null;
    }

    public static function clubCodeCreate()
    {
        $randomCode = 0;
        for($i = 0; $i < 1000; $i++) {
            $randomCode = rand(1000, 9999);

            $club = Club::where('code', $randomCode)->first();
            if($club === null) {
                break;
            }
        }

        return $randomCode;
    }
}
