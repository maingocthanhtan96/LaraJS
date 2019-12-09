<?php

return [
    'actions' => 'Actions',
    'user' => [
        'id' => "STT",
        'name' => 'Tên',
        'email' => 'Email',
        'avatar' => 'Ảnh đại diện',
        'role' => 'Quyền',
        'password' => 'Mật khẩu',
        'password_confirmation' => 'Xác nhận mật khẩu'
    ],
    'rolePermission' => [
        'id' => 'No.',
        'name' => 'Tên',
        'description' => 'Miêu tả',
    ],
    'test_generator' => [
        'id' => 'No.',
        'int' => 'Int',
        'big_int' => 'Big int',
        'float' => 'Float',
        'double' => 'Double',
        'boolean' => 'Boolean',
        'date' => 'Date',
        'date_time' => 'Date time',
        'time' => 'Time',
        'year' => 'Year',
        'varchar' => 'Varchar',
        'text' => 'Text',
        'long_text' => 'Long text',
        'enum_1' => 'Enum',
        'file_1' => 'FIle',
    ],
    //{{LANG_TABLE_NOT_DELETE_THIS_LINE}}
    'texts' => [
        'count' => 'Hiển thị {from} đến {to} trong số {count} dữ liệu|{count} dữ liệu|Một dữ liệu',
        'first' => 'Đầu',
        'last' => 'Cuối',
        'filter' => "Bộ lọc:",
        'filterPlaceholder' => "Tìm kiếm...",
        'limit' => "Giới hạn:",
        'page' => "Trang:",
        'noResults' => "Không có dữ liệu phù hợp",
        'filterBy' => "Lọc bởi {column}",
        'loading' => 'Chờ đợi...',
        'defaultOption' => 'Chọn {column}',
        'columns' => 'Cột'
    ]
];
