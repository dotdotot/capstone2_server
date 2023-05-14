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
        # users table
        Schema::create('clubs', function (Blueprint $table) {
            # 칼럼
            $table->bigIncrements('id')->comment('동아리 번호');
            $table->string('name', 100)->nullable()->comment('동아리 이름');
            $table->string('code', 100)->nullable()->comment('동아리 코드');
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

        # users table
        Schema::create('users', function (Blueprint $table) {
            # 칼럼
            $table->bigIncrements('id')->comment('사용자 번호');
            $table->unsignedBigInteger('club_id')->comment('동아리 번호');
            $table->unsignedBigInteger('rank_id')->nullable()->comment('직위번호');
            $table->unsignedBigInteger('student_id')->nullable()->comment('학생 번호');
            $table->string('name', 100)->nullable()->comment('성+이름');
            $table->string('gender', 100)->nullable()->comment('성별');
            $table->json('phone')->nullable()->comment('전화번호');
            $table->string('email', 100)->nullable()->comment('이메일 주소');
            $table->string('address', 200)->nullable()->comment('생년월일');
            $table->date('birth_date')->nullable()->comment('생년월일');
            $table->string('password')->nullable()->comment('비밀번호');
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clubs');
        Schema::dropIfExists('users');
        Schema::dropIfExists('personal_access_tokens');
    }
};
