<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jenssegers\Mongodb\Eloquent\HybridRelations;

/**
 * public @method departments()
 */
class ClubEmergencyContactNetwork extends BaseModel
{
    use HybridRelations;
    use SoftDeletes;

    protected $connection = 'pgsql';
    protected $table = 'club_emergency_contact_network';

    protected $dates = [
        'deleted_at'
    ];

    protected $casts = [
        'phone' => 'array',
    ];

    protected $fillable = [
        'club_id', 'email', 'phone', 'location', 'created_at', 'updated_at', 'deleted_at'
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->club_id = isset($attributes['club_id']) ? $attributes['club_id'] : null;
        $this->email = isset($attributes['email']) ? $attributes['email'] : null;
        $this->phone = isset($attributes['phone']) ? $attributes['phone'] : [];
        $this->location = isset($attributes['location']) ? $attributes['location'] : null;

        // 삭제/추가/수정 시간
        $this->created_at = isset($attributes['created_at']) ? $attributes['created_at'] : Carbon::now();
        $this->updated_at = isset($attributes['updated_at']) ? $attributes['updated_at'] : Carbon::now();
        $this->deleted_at = isset($attributes['deleted_at']) ? $attributes['deleted_at'] : null;
    }
}
