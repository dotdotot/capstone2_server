<?php

namespace App\Models;

use Franzose\ClosureTable\Models\ClosureTable;

class TeamClosure extends ClosureTable
{
    /**
     * The table associated with the model.
     *
     * @var string
     */

    protected $connection = 'pgsql';
    protected $table = 'team_closure';


    public function descendantDepartment()
    {
        return $this->belongsTo(Team::class, 'descendant');
    }

    public function ancestorDepartment()
    {
        return $this->belongsTo(Team::class, 'ancestor');
    }
}
