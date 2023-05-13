<?php

namespace App\Models;

use DateTimeInterface;
use Jenssegers\Mongodb\Eloquent\Model;

class BaseMongoModel extends Model
{
    protected $dateFormat = 'Y-m-d H:i:s.uO';

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s.uO');
    }

    public function toBaseSearchableArray($base, $array)
    {
        $array['oid'] = $base['_id'];
        $array['created_at'] = isset($base['created_at']) ? $base['created_at'] : null;
        $array['updated_at'] = isset($base['updated_at']) ? $base['updated_at'] : null;
        $array['deleted_at'] = isset($base['deleted_at']) ? $base['deleted_at'] : null;
        unset($array['_id']);

        return $array;
    }
}
