<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Jenssegers\Mongodb\Schema\Blueprint as JenssegersBlueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    protected $mongoConnection = 'mongodb';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection($this->mongoConnection)->hasTable('user_login')) {
            Schema::connection($this->mongoConnection)
                ->table('user_login', function (JenssegersBlueprint $collection) {
                    $collection->index('club_id');
                    $collection->index('user_id');
                    $collection->index('updated_at');
                    $collection->index('deleted_at');
                });
        }

        if (!Schema::connection($this->mongoConnection)->hasTable('cctv_consents')) {
            Schema::connection($this->mongoConnection)
                ->table('cctv_consents', function (JenssegersBlueprint $collection) {
                    $collection->index('club_id');
                    $collection->index('user_id');
                    $collection->index('updated_at');
                    $collection->index('deleted_at');
                });
        }

        if (!Schema::connection($this->mongoConnection)->hasTable('project_consents')) {
            Schema::connection($this->mongoConnection)
                ->table('project_consents', function (JenssegersBlueprint $collection) {
                    $collection->index('club_id');
                    $collection->index('user_id');
                    $collection->index('updated_at');
                    $collection->index('deleted_at');
                });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection($this->mongoConnection)->dropIfExists('user_login');
    }
};
