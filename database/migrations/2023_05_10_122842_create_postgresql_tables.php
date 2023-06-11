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

        # club_emergency_contact_network table
        Schema::create('club_emergency_contact_network', function (Blueprint $table) {
            # 칼럼
            $table->bigIncrements('id')->comment('동아리 비상연락망 번호');
            $table->unsignedBigInteger('club_id')->comment('동아리 번호');
            $table->string('email', 100)->nullable()->comment('비상 이메일');
            $table->json('phone')->nullable()->comment('비상 전화번호');
            $table->string('location', 100)->nullable()->comment('동아리 위치');
            $table->timestampsTz($precision = 3);
            $table->softDeletesTz($column = 'deleted_at', $precision = 3);

            # 유니크 값
            $table->unique('club_id');

            # 인덱스
            $table->index('id');
            $table->index('club_id');
            $table->index('updated_at');
            $table->index('deleted_at');

            # 키값
            $table->foreign('club_id')->references('id')->on('clubs')->onUpdate('cascade')->onDelete('cascade');
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
            $table->index('name');
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

        # 클로저 테이블
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
            $table->unique(['club_id', 'student_id']);

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

        # rank_permissions table
        Schema::create('rank_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('club_id')->nullable()->comment('동아리번호');
            $table->unsignedBigInteger('rank_id')->nullable()->comment('랭크번호');
            $table->boolean('board_access')->comment('게시판 권한');
            $table->boolean('comment_access')->comment('댓글 권한');
            $table->boolean('image_add_access')->comment('이미지 업로드 권한');
            $table->boolean('anonymous_comment_access')->comment('익명 댓글 권한');
            $table->boolean('community_add_access')->comment('커뮤니티 추가 권한');
            $table->boolean('user_ben_access')->comment('사용자 벤 권한');
            $table->boolean('admin_board_access')->comment('관리자 관련 게시판 탭 권한');
            $table->boolean('user_change_access')->comment('특정 사용자 변경 권한');
            $table->boolean('admin_access')->comment('어드민 권한');
            $table->unsignedSmallInteger('position')->comment('표시순서');
            $table->timestampsTz($precision = 3);
            $table->softDeletesTz($column = 'deleted_at', $precision = 3);

            // 유니크
            $table->unique(['club_id', 'rank_id']);

            // 인덱스
            $table->index('club_id');
            $table->index('rank_id');
            $table->index('deleted_at');

            // 키 값
            $table->foreign('club_id')->references('id')->on('clubs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('rank_id')->references('id')->on('ranks')->onUpdate('cascade')->onDelete('cascade');
        });

        # access_tokens table
        Schema::create('jwt_token', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('club_id')->comment('동아리번호');
            $table->unsignedBigInteger('user_id')->comment('사용자아이디');
            $table->string('access_token', 500)->nullable()->comment('액세스토큰');
            $table->timestampTz('access_token_end_at')->nullable()->comment('액세스토큰 만료 일시');
            $table->string('refresh_token', 500)->nullable()->comment('재발급토큰');
            $table->timestampTz('refresh_token_end_at')->nullable()->comment('재발급토큰 만료 일시');
            $table->timestampsTz($precision = 3);
            $table->softDeletesTz($column = 'deleted_at', $precision = 3);

            // 유니크
            $table->unique(['club_id', 'user_id']);

            // 인덱스
            $table->index('club_id');
            $table->index('user_id');
            $table->index('deleted_at');

            // 키 값
            $table->foreign('club_id')->references('id')->on('clubs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });

        # access_tokens table
        Schema::create('user_login', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('club_id')->comment('동아리번호');
            $table->unsignedBigInteger('user_id')->comment('사용자아이디');
            $table->string('ip', 100)->nullable()->comment('접속 ip');
            $table->string('device', 100)->nullable()->comment('접속 기기');
            $table->string('etc', 100)->nullable()->comment('기타');
            $table->timestampsTz($precision = 3);
            $table->softDeletesTz($column = 'deleted_at', $precision = 3);

            // 인덱스
            $table->index('club_id');
            $table->index('user_id');
            $table->index('deleted_at');

            // 키 값
            $table->foreign('club_id')->references('id')->on('clubs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });

        # project_consents table
        Schema::create('project_consents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('club_id')->comment('동아리번호');
            $table->unsignedBigInteger('user_id')->comment('랭크번호');
            $table->boolean('consent')->default(true)->comment('여부');
            $table->timestampsTz($precision = 3);
            $table->softDeletesTz($column = 'deleted_at', $precision = 3);

            // 인덱스
            $table->index('club_id');
            $table->index('user_id');
            $table->index('deleted_at');

            // 키 값
            $table->foreign('club_id')->references('id')->on('clubs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });

        # cctv_consents table
        Schema::create('cctv_consents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('club_id')->comment('동아리번호');
            $table->unsignedBigInteger('user_id')->comment('사용자번호');
            $table->boolean('consent')->default(true)->comment('동의여부');
            $table->timestampsTz($precision = 3);
            $table->softDeletesTz($column = 'deleted_at', $precision = 3);

            // 인덱스
            $table->index('club_id');
            $table->index('user_id');
            $table->index('deleted_at');

            // 키 값
            $table->foreign('club_id')->references('id')->on('clubs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });

        // # announcement_boards table
        // Schema::create('announcement_boards', function (Blueprint $table) {
        //     $table->bigIncrements('id');
        //     $table->unsignedBigInteger('club_id')->comment('동아리번호');
        //     $table->unsignedBigInteger('user_id')->comment('사용자번호');
        //     $table->string('title', 100)->nullable()->comment('제목');
        //     $table->string('content', 2000)->nullable()->comment('내용');
        //     $table->unsignedBigInteger('hits')->comment('조회 수');
        //     $table->boolean('image')->default(false)->comment('이미지 여부');
        //     $table->boolean('block_comment')->default(false)->comment('댓글 금지 여부');
        //     $table->timestampsTz($precision = 3);
        //     $table->softDeletesTz($column = 'deleted_at', $precision = 3);

        //     // 인덱스
        //     $table->index('club_id');
        //     $table->index('user_id');
        //     $table->index('deleted_at');

        //     // 키 값
        //     $table->foreign('club_id')->references('id')->on('clubs')->onUpdate('cascade')->onDelete('cascade');
        //     $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        // });

        # menus table
        Schema::create('menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('club_id')->comment('동아리번호');
            $table->string('title', 100)->nullable()->comment('제목');
            $table->string('type', 50)->nullable()->comment('메뉴 타입');
            $table->unsignedBigInteger('position')->comment('순서');
            $table->timestampsTz($precision = 3);
            $table->softDeletesTz($column = 'deleted_at', $precision = 3);

            // 인덱스
            $table->index('club_id');
            $table->index('type');
            $table->index('deleted_at');

            // 키 값
            $table->foreign('club_id')->references('id')->on('clubs')->onUpdate('cascade')->onDelete('cascade');
        });

        # boards table
        Schema::create('boards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('club_id')->comment('동아리번호');
            $table->unsignedBigInteger('user_id')->comment('사용자번호');
            $table->unsignedBigInteger('menu_id')->comment('메뉴번호');
            $table->string('title', 100)->nullable()->comment('제목');
            $table->string('content', 2000)->nullable()->comment('내용');
            $table->unsignedBigInteger('hits')->comment('조회 수');
            $table->unsignedBigInteger('position')->comment('순서');
            $table->boolean('image')->default(false)->comment('이미지 여부');
            $table->boolean('block_comment')->default(false)->comment('댓글 금지 여부');
            $table->timestampsTz($precision = 3);
            $table->softDeletesTz($column = 'deleted_at', $precision = 3);

            // 인덱스
            $table->index('club_id');
            $table->index('user_id');
            $table->index('menu_id');
            $table->index('deleted_at');

            // 키 값
            $table->foreign('club_id')->references('id')->on('clubs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('menu_id')->references('id')->on('menus')->onUpdate('cascade')->onDelete('cascade');
        });

        # bulletins table
        Schema::create('bulletins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('club_id')->comment('동아리번호');
            $table->unsignedBigInteger('user_id')->comment('사용자번호');
            $table->unsignedBigInteger('menu_id')->comment('메뉴번호');
            $table->string('title', 100)->nullable()->comment('제목');
            $table->string('content', 2000)->nullable()->comment('내용');
            $table->unsignedBigInteger('position')->comment('순서');
            $table->timestampsTz($precision = 3);
            $table->softDeletesTz($column = 'deleted_at', $precision = 3);

            // 인덱스
            $table->index('club_id');
            $table->index('user_id');
            $table->index('menu_id');
            $table->index('deleted_at');

            // 키 값
            $table->foreign('club_id')->references('id')->on('clubs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('menu_id')->references('id')->on('menus')->onUpdate('cascade')->onDelete('cascade');
        });

        # common_moneys table
        Schema::create('common_moneys', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('club_id')->comment('동아리번호');
            $table->unsignedBigInteger('menu_id')->comment('메뉴번호');
            $table->unsignedBigInteger('money')->comment('돈');
            $table->unsignedBigInteger('position')->comment('순서');
            $table->timestampsTz($precision = 3);
            $table->softDeletesTz($column = 'deleted_at', $precision = 3);

            // 유니크
            $table->unique(['club_id', 'menu_id']);

            // 인덱스
            $table->index('club_id');
            $table->index('menu_id');
            $table->index('deleted_at');

            // 키 값
            $table->foreign('club_id')->references('id')->on('clubs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('menu_id')->references('id')->on('menus')->onUpdate('cascade')->onDelete('cascade');
        });

        # image_boards table
        Schema::create('image_boards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('club_id')->comment('동아리번호');
            $table->unsignedBigInteger('user_id')->comment('사용자번호');
            $table->unsignedBigInteger('menu_id')->comment('메뉴번호');
            $table->unsignedBigInteger('image_id')->nullable()->comment('이미지번호');

            $table->string('title', 100)->nullable()->comment('제목');
            $table->unsignedBigInteger('money')->nullable()->comment('지불');

            $table->unsignedBigInteger('position')->comment('순서');
            $table->timestampsTz($precision = 3);
            $table->softDeletesTz($column = 'deleted_at', $precision = 3);

            // 인덱스
            $table->index('club_id');
            $table->index('user_id');
            $table->index('menu_id');
            $table->index('image_id');
            $table->index('deleted_at');

            // 키 값
            $table->foreign('club_id')->references('id')->on('clubs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('menu_id')->references('id')->on('menus')->onUpdate('cascade')->onDelete('cascade');
        });

        # comments table
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('club_id')->comment('동아리번호');
            $table->unsignedBigInteger('user_id')->comment('사용자번호');
            $table->unsignedBigInteger('board_id')->comment('게시판번호');
            $table->string('content', 300)->nullable()->comment('내용');
            $table->boolean('hidden_comment')->default(false)->comment('비밀 댓글 여부');
            $table->timestampsTz($precision = 3);
            $table->softDeletesTz($column = 'deleted_at', $precision = 3);

            // 인덱스
            $table->index('club_id');
            $table->index('user_id');
            $table->index('board_id');
            $table->index('deleted_at');

            // 키 값
            $table->foreign('club_id')->references('id')->on('clubs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('board_id')->references('id')->on('boards')->onUpdate('cascade')->onDelete('cascade');
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

        # 토큰 관련 테이블
        Schema::dropIfExists('oauth_access_tokens');
        Schema::dropIfExists('oauth_auth_codes');
        Schema::dropIfExists('oauth_clients');
        Schema::dropIfExists('oauth_personal_access_clients');
        Schema::dropIfExists('oauth_refresh_tokens');
        # 실제 사용 테이블
        Schema::dropIfExists('comments');
        Schema::dropIfExists('image_boards');
        Schema::dropIfExists('bulletins');
        Schema::dropIfExists('boards');
        Schema::dropIfExists('common_moneys');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('announcement_boards');
        Schema::dropIfExists('cctv_consents');
        Schema::dropIfExists('project_consents');
        Schema::dropIfExists('user_login');
        Schema::dropIfExists('jwt_token');
        Schema::dropIfExists('rank_permissions');
        Schema::dropIfExists('members');
        Schema::dropIfExists('users');
        Schema::dropIfExists('team_closure');
        Schema::dropIfExists('teams');
        Schema::dropIfExists('ranks');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('club_emergency_contact_network');
        Schema::dropIfExists('clubs');
        Schema::dropIfExists('personal_access_tokens');
    }
};
