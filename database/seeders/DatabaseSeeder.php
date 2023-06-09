<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\AnnouncementBoard;
use App\Models\CommonMoney;
use App\Models\RankPermission;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (app()->environment('local')) {
            # pgsql table created
            # 동아리 생성
            $this->call(ClubSeeder::class);
            # 학과 생성(동아리별 학과 존재)
            $this->call(DepartmentSeeder::class);
            # 랭크 생성
            $this->call(RankSeeder::class);
            # 팀 생성
            $this->call(TeamSeeder::class);
            # 사용자 생성
            $this->call(UserSeeder::class);
            # 멤버 생성
            $this->call(MemberSeeder::class);
            # 랭크 권한 생성
            $this->call(RankPermissionSeeder::class);
            # 비상 연락망 생성
            $this->call(ClubEmergencyContactNetworkSeeder::class);

            # 사용자 접속 ip 생성
            $this->call(UserLoginSeeder::class);
            # cctv 동의여부 생성
            $this->call(CCTVConsentSeeder::class);
            # 프로젝트 동의여부 생성
            $this->call(ProjectConsentSeeder::class);

            // # 공지사항 게시판 생성
            // $this->call(AnnouncementBoardSeeder::class);
            # 메뉴 생성
            $this->call(MenuSeeder::class);
            # 게시판 생성
            $this->call(BoardSeeder::class);
            # 공지 생성
            $this->call(BulletinSeeder::class);
            # 이미지 게시판 생성
            $this->call(ImageBoardSeeder::class);
            # 댓글 생성
            $this->call(CommentSeeder::class);
        }
    }
}
