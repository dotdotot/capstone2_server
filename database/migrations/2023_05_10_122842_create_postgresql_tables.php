<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        # clubs table
        Schema::create('clubs', function (Blueprint $table) {
            # 칼럼
            $table->bigIncrements('id')->comment('동아리 번호');
            $table->string('name', 100)->nullable()->comment('동아리 이름');
            $table->unsignedBigInteger('code')->nullable()->comment('동아리 코드');
            $table->unsignedBigInteger('position')->nullable()->comment('동아리 번호');
            $table->string('grade', 100)->nullable()->comment('동아리 권한');
            $table->timestampsTz($precision = 3);
            $table->softDeletesTz($column = 'deleted_at', $precision = 3);

            # 유니크 값
            $table->unique('name');
            $table->unique('code');

            # 인덱스
            $table->index('id');
            $table->index('updated_at');
            $table->index('deleted_at');
        });

        # departments table
        Schema::create('departments', function (Blueprint $table) {
            # 칼럼
            $table->bigIncrements('id')->comment('학과 번호');
            $table->unsignedBigInteger('club_id')->nullable()->comment('동아리 번호');
            $table->string('name', 100)->nullable()->comment('학과 이름');
            $table->string('code', 100)->nullable()->comment('학과 코드');
            $table->unsignedBigInteger('position')->nullable()->comment('학과 순서');
            $table->timestampsTz($precision = 3);
            $table->softDeletesTz($column = 'deleted_at', $precision = 3);

            # 유니크 값
            $table->unique('name');
            $table->unique('code');

            # 인덱스
            $table->index('id');
            $table->index('updated_at');
            $table->index('deleted_at');

            # 키값
            $table->foreign('club_id')->references('id')->on('clubs')->onUpdate('cascade')->onDelete('cascade');
        });

        # ranks table
        Schema::create('ranks', function (Blueprint $table) {
            # 칼럼
            $table->bigIncrements('id')->comment('랭크 번호');
            $table->unsignedBigInteger('club_id')->nullable()->comment('동아리 번호');
            $table->string('name', 100)->nullable()->comment('랭크 이름');
            $table->unsignedBigInteger('position')->nullable()->comment('랭크 순서');
            $table->timestampsTz($precision = 3);
            $table->softDeletesTz($column = 'deleted_at', $precision = 3);

            # 유니크 값
            $table->unique('name');

            # 인덱스
            $table->index('id');
            $table->index('updated_at');
            $table->index('deleted_at');

            # 키값
            $table->foreign('club_id')->references('id')->on('clubs')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('teams', function (Blueprint $table) {
            $table->increments('id')->comment('팀 아이디');
            $table->unsignedBigInteger('club_id')->nullable()->comment('동아리 번호');
            $table->string('name', 50)->nullable()->comment('팀 이름');
            $table->string('path', 100)->nullable()->comment('상위팀 포함 팀 경로');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('position');
            $table->timestampsTz($precision = 3);
            $table->softDeletesTz($column = 'deleted_at', $precision = 3);

            # 유니크 값
            $table->unique('name');

            # 인덱스
            $table->index('id');
            $table->index('club_id');
            $table->index('parent_id');
            $table->index('position');
            $table->index('deleted_at');

            # 키값
            $table->foreign('club_id')->references('id')->on('teams')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('teams')->onUpdate('cascade')->onDelete('set null');
        });

        Schema::create('team_closure', function (Blueprint $table) {
            $table->bigIncrements('closure_id');
            $table->unsignedBigInteger('ancestor');
            $table->unsignedBigInteger('descendant');
            $table->unsignedInteger('depth');

            # 인덱스
            $table->index('ancestor');
            $table->index('descendant');

            # 키값
            $table->foreign('ancestor')->references('id')->on('teams')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('descendant')->references('id')->on('teams')->onUpdate('cascade')->onDelete('cascade');
        });

        // # teams table
        // Schema::create('teams', function (Blueprint $table) {
        //     # 칼럼
        //     $table->bigIncrements('id')->comment('팀 번호 ');
        //     $table->unsignedBigInteger('club_id')->nullable()->comment('동아리 번호');
        //     $table->unsignedBigInteger('parent_team_id')->nullable()->comment('상위 팀 번호');
        //     $table->string('name', 100)->nullable()->comment('팀 이름');
        //     $table->unsignedBigInteger('position')->nullable()->comment('팀 순서');
        //     $table->timestampsTz($precision = 3);
        //     $table->softDeletesTz($column = 'deleted_at', $precision = 3);

        //     # 유니크 값
        //     $table->unique('name');

        //     # 인덱스
        //     $table->index('id');
        //     $table->index('updated_at');
        //     $table->index('deleted_at');

        //     # 키값
        //     $table->foreign('club_id')->references('id')->on('clubs')->onUpdate('cascade')->onDelete('cascade');
        // });

        # users table
        Schema::create('users', function (Blueprint $table) {
            # 칼럼
            $table->bigIncrements('id')->comment('사용자 번호');
            $table->unsignedBigInteger('club_id')->comment('동아리 번호');
            $table->unsignedBigInteger('department_id')->comment('학과 번호');
            $table->unsignedBigInteger('rank_id')->nullable()->comment('직위번호');
            $table->unsignedBigInteger('student_id')->nullable()->comment('학생 번호');
            $table->string('name', 100)->nullable()->comment('성+이름');
            $table->string('gender', 100)->nullable()->comment('성별');
            $table->json('phone')->nullable()->comment('전화번호');
            $table->string('email', 100)->nullable()->comment('이메일 주소');
            $table->string('address', 200)->nullable()->comment('주소');
            $table->date('birth_date')->nullable()->comment('생년월일');
            $table->string('password')->nullable()->comment('비밀번호');
            $table->unsignedBigInteger('out_count')->nullable()->comment('경고 횟수');
            $table->unsignedBigInteger('password_fail_count')->nullable()->comment('비밀번호 틀린 횟수');
            $table->timestampTz('password_updated_at', $precision = 3)->nullable()->comment('비밀번호 변경 일시');
            $table->timestampTz('last_login_at', $precision = 3)->nullable()->comment('마지막 로그인 시간');
            $table->timestampTz('banned_at', $precision = 3)->nullable()->comment('접속제한 일시');
            $table->timestampsTz($precision = 3);
            $table->softDeletesTz($column = 'deleted_at', $precision = 3);

            # 유니크 값
            $table->unique('student_id');

            # 인덱스
            $table->index('id');
            $table->index('updated_at');
            $table->index('deleted_at');

            # 키값
            $table->foreign('club_id')->references('id')->on('clubs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('rank_id')->references('id')->on('ranks')->onUpdate('cascade')->onDelete('cascade');
        });

        # members table
        Schema::create('members', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('club_id')->comment('동아리번호');
            $table->unsignedBigInteger('department_id')->nullable()->comment('학과번호');
            $table->unsignedBigInteger('team_id')->nullable()->comment('팀번호');
            $table->unsignedBigInteger('user_id')->comment('동아리 회원 번호');
            $table->unsignedBigInteger('rank_id')->nullable()->comment('직위번호');
            $table->unsignedSmallInteger('position')->comment('표시순서');
            $table->boolean('default')->default(false)->comment('사용자의 메인 팀 여부');
            $table->boolean('leader')->default(false)->comment('팀 리더 여부');
            $table->timestampsTz($precision = 3);
            $table->softDeletesTz($column = 'deleted_at', $precision = 3);

            // 유니크
            $table->unique(['team_id', 'user_id']);

            // 인덱스
            $table->index('club_id');
            $table->index('department_id');
            $table->index('user_id');
            $table->index('deleted_at');

            // 키 값
            $table->foreign('club_id')->references('id')->on('clubs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('teams')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('rank_id')->references('id')->on('ranks')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        # 테이블 삭제
        Schema::dropIfExists('members');
        Schema::dropIfExists('users');
        Schema::dropIfExists('team_closure');
        Schema::dropIfExists('teams');
        Schema::dropIfExists('ranks');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('clubs');
        Schema::dropIfExists('personal_access_tokens');
    }
};
