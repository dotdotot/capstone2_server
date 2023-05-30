<?php

return [
    'no_access' => '접근권한이 없습니다.',
    'request' => '잘못된 요청입니다.',
    'logout_or_long_term_inactivity' => '로그아웃 되었거나 장시간 사용하지 않아 연결이 종료되었습니다.',
    'does_not_exist' => [
        'club_id' => '존재하지 않는 동아리아이디입니다.',
        'club_code' => '존재하지 않는 동아리입니다.',
        'department' => '동아리에 학과가 존재하지 않습니다',
        'uploaded_file' => '업로드 파일이 존재하지 않습니다.',
        'message' => '존재하지 않는 메시지입니다.',
        'user' => '존재하지 않는 사용자입니다.',
        'department' => '존재하지 않는 학과입니다.',
        'policy' => '존재하지 않는 정책입니다.',
        'notice' => '공지사항이 존재하지 않습니다.',
    ],
    'does_not_match' => [
        'user_id' => '사용자 아이디가 일치하지 않습니다.',
        'password' => '사용자 비밀번호가 일치하지 않습니다.',
        'number_of_uploaded_files' => '업로드된 파일 수와 요청된 파일 수가 일치하지 않습니다.',
    ],
    'wrong' => [
        'account' => '아이디(이메일) 및 비밀번호가 일치하지 않습니다.',
        'token' => '잘못된 토큰입니다.',
        'exceed_maximum_number_of_uploads' => '최대 업로드 파일 개수를 초과하였습니다.',
        'can_not_use_secret_comment' => '비밀댓글을 사용할 수 없습니다.',
        'image_file' => '이미지파일이 아닙니다.',
        'not_a_service_customer' => '서비스 이용 고객이 아닙니다. 본 서비스를 포함한 멤버십 구독 후 이용하시기 바랍니다.',
    ],
    'failed' => [
        'authorization' => '접속 정보를 확인해 주세요.',
        'server_is_not_stable' => '인증서버와의 통신이 원활하지 않습니다.' . PHP_EOL . '잠시 후 다시 시도해주세요.',
        'upload_file' => '프로필이미지를 업로드하지 못하였습니다.',
    ],
    'cannot' => [
        'download' => '정책으로 인해 다운로드할 수 없습니다.',
        'create_board' => '정책으로 인해 게시판을 개설할 수 없습니다.',
        'create_comment' => '정책으로 인해 댓글을 등록할 수 없습니다.',
    ]
];
