<?php

return [
    'actions' => 'Actions',
    'user' => [
        'id' => "No.",
        'name' => 'Name',
        'email' => 'Email',
        'avatar' => 'Avatar',
        'role' => 'Role',
        'password' => 'Password',
        'password_confirmation' => 'Confirmation password'
    ],
    'rolePermission' => [
        'id' => 'No.',
        'name' => 'Name',
        'description' => 'Description',
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
        'count' => 'Showing {from} to {to} of {count} records|{count} records|One record',
        'first' => 'First',
        'last' => 'Last',
        'filter' => "Filter:",
        'filterPlaceholder' => "Search...",
        'limit' => "Records:",
        'page' => "Page:",
        'noResults' => "No matching records",
        'filterBy' => "Filter by {column}",
        'loading' => 'Loading...',
        'defaultOption' => 'Select {column}',
        'columns' => 'Columns'
    ]
];
