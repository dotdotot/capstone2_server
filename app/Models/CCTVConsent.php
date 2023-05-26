<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Carbon\Carbon;

class CCTVConsent extends BaseMongoModel
{
    use SoftDeletes;

    protected $connection = 'mongodb';
    protected $table = 'cctv_consents';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'club_id', 'user_id', 'consent'
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->club_id = isset($attributes['company_id']) ? $attributes['company_id'] : null;
        $this->user_id = isset($attributes['user_id']) ? $attributes['user_id'] : null;
        $this->consent = isset($attributes['consent']) ? $attributes['consent'] : false;

        // 삭제/추가/수정 시간
        $this->deleted_at = isset($attributes['deleted_at']) ? $attributes['deleted_at'] : null;
        $this->updated_at = isset($attributes['updated_at']) ? $attributes['updated_at'] : Carbon::now();
        $this->created_at = isset($attributes['created_at']) ? $attributes['created_at'] : Carbon::now();
    }
}
