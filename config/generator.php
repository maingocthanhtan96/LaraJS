<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Templates
    |--------------------------------------------------------------------------
    |
    */
    'template' => [
        'laravel' => public_path('generator/templates/Laravel/'),
        'vuejs' => public_path('generator/templates/Vuejs/'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Path
    |--------------------------------------------------------------------------
    |
    */
    'path' => [
        'laravel' => [
            'migration' => base_path('database/migrations/'),
            'seeder' => base_path('database/seeds/'),
            'model' => app_path('Models/'),
            'repositories' => app_path('Repositories/'),
            'api_routes' => base_path('routes/api.php'),
            'api_controller' => app_path('Http/Controllers/Api/v1/'),
            'lang' => base_path('resources/lang/'),
            'request' => app_path('Http/Requests/'),
            'swagger' => app_path('Larajs/Swagger/'),
        ],
        'vuejs' => [
            'api' => base_path('resources/js/api/'),
            'views' => base_path('resources/js/views/'),
            'router_modules' => base_path('resources/js/router/modules/'),
            'router' => base_path('resources/js/router/'),
            'resource_js' => base_path('resources/js/'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Model
    |--------------------------------------------------------------------------
    |
    */

    'model' => [
        'options' => [
            'sort_deletes' => 'Soft Deletes',
            'datatables' => 'Datatables',
            'role_admin' => 'Role Admin',
            'ignore_migrate' => 'Ignore Migrate',
            'test_cases' => 'Test Cases',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | DB type
    |--------------------------------------------------------------------------
    |
    */
    'db_type' => [
        'integer' => 'INT',
        'bigInteger' => 'BIGINT',
        'float' => 'FLOAT',
        'double' => 'DOUBLE',
        'boolean' => 'BOOLEAN',
        'date' => 'DATE',
        'dateTime' => 'DATETIME',
        'time' => 'TIME',
        'year' => 'YEAR',
        'string' => 'VARCHAR',
        'text' => 'TEXT',
        'longtext' => 'LONGTEXT',
        'enum' => 'ENUM',
        'json' => 'JSON',
        'file' => 'FILE',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default value
    |--------------------------------------------------------------------------
    |
    */

    'default_value' => [
        'none' => 'None',
        'null' => 'NULL',
        'as_define' => 'As define',
    ],

    /*
    |--------------------------------------------------------------------------
    | Namespace
    |--------------------------------------------------------------------------
    |
    */

    'namespace' => [
        'model' => 'App\Models',
        'repository' => 'App\Repositories',
        'api_controller' => 'App\Http\Controllers\Api\v1',
    ],

    /*
    |--------------------------------------------------------------------------
    | NOT DELETE
    |--------------------------------------------------------------------------
    |
    */
    'not_delete' => [
        'laravel' => [
            'controller' => [
                'relationship' => '//{{CONTROLLER_RELATIONSHIP_NOT_DELETE_THIS_LINE}}',
                'relationship_mtm_create' => '//{{CONTROLLER_RELATIONSHIP_MTM_CREATE_NOT_DELETE_THIS_LINE}}',
                'relationship_mtm_update' => '//{{CONTROLLER_RELATIONSHIP_MTM_UPDATE_NOT_DELETE_THIS_LINE}}',
                'relationship_mtm_delete' => '//{{CONTROLLER_RELATIONSHIP_MTM_DELETE_NOT_DELETE_THIS_LINE}}',
                'relationship_mtm_show' => '//{{CONTROLLER_RELATIONSHIP_MTM_SHOW_NOT_DELETE_THIS_LINE}}',
            ],
            'model' => [
                'use_class' => '//{{USE_CLASS_NOT_DELETE_THIS_LINE}}',
                'use' => '//{{USE_NOT_DELETE_THIS_LINE}}',
                'relationship' => '//{{RELATIONS_NOT_DELETE_THIS_LINE}}',
                'cats' => '//{{CATS_NOT_DELETE_THIS_LINE}}',
                'fill_able' => '//{{FILL_ABLE_NOT_DELETE_THIS_LINE}}',
            ],
            'route' => [
                'api' => [
                    'admin' => '//{{ROUTE_ADMIN_NOT_DELETE_THIS_LINE}}',
                    'user' => '//{{ROUTE_USER_NOT_DELETE_THIS_LINE}}',
                ],
            ],
            'lang' => [
                'vi' => [
                    'route' => '//{{LANG_ROUTE_NOT_DELETE_THIS_LINE}}',
                    'table' => '//{{LANG_TABLE_NOT_DELETE_THIS_LINE}}',
                ],
                'en' => [
                    'route' => '//{{LANG_ROUTE_NOT_DELETE_THIS_LINE}}',
                    'table' => '//{{LANG_TABLE_NOT_DELETE_THIS_LINE}}',
                ],
                'ja' => [
                    'route' => '//{{LANG_ROUTE_NOT_DELETE_THIS_LINE}}',
                    'table' => '//{{LANG_TABLE_NOT_DELETE_THIS_LINE}}',
                ],
            ],
            'db' => [
                'seeder' => '//{{SEEDER_NOT_DELETE_THIS_LINE}}',
            ],
            'request' => [
                'rule' => '//{{REQUEST_RULES_NOT_DELETE_THIS_LINE}}',
            ],
            'swagger' => [
                'property' => '//{{SWAGGER_PROPERTY_NOT_DELETE_THIS_LINE}}',
                'soft_deletes' => '//{{SWAGGER_SOFT_DELETES_NOT_DELETE_THIS_LINE}}',
            ],
        ],
        'vuejs' => [
            'route' => [
                'import' => '// {{$IMPORT_ROUTE_NOT_DELETE_THIS_LINE$}}',
                'async' => '// {{$ROUTE_ASYNC_NOT_DELETE_THIS_LINE$}}',
            ],
            'form' => [
                'item' => '<!--{{$FROM_ITEM_NOT_DELETE_THIS_LINE$}}-->',
                'fields' => '// {{$FORM_FIELDS_NOT_DELETE_THIS_LINE$}}',
                'rules' => '// {{$RULES_NOT_DELETE_THIS_LINE$}}',
                'import_component' => '// {{$IMPORT_COMPONENT_NOT_DELETE_THIS_LINE$}}',
                'import_component_name' => '// {{$IMPORT_COMPONENT_NAME_NOT_DELETE_THIS_LINE$}}',
                'methods' => '// {{$METHODS_NOT_DELETE_THIS_LINE$}}',
                'data' => '// {{$DATA_NOT_DELETE_THIS_LINE$}}',
                'stringify' => '// {{$FILE_JSON_STRINGIFY_NOT_DELETE_THIS_LINE$}}',
                'reset_field' => '// {{$RESET_FIELD_NOT_DELETE_THIS_LINE$}}', // reset file
                'api' => '// {{$API_NOT_DELETE_THIS_LINE$}}',
                'this_check' => '// {{$$}}',
            ],
            'views' => [
                'templates' => '<!--{{$TEMPLATES_NOT_DELETE_THIS_LINE$}}-->',
                'headings' => '// {{$HEADING_FIELDS_NOT_DELETE_THIS_LINE$}}',
                'column_classes' => '// {{$COLUMN_CLASSES_FIELDS_NOT_DELETE_THIS_LINE$}}',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IMPORT
    |--------------------------------------------------------------------------
    |
    */
    'import' => [
        'laravel' => [
            'use' => [
                'file' => 'use Illuminate\Database\Eloquent\SoftDeletes;',
                'name' => 'use SoftDeletes;',
            ],
        ],
        'vuejs' => [
            'tinymce' => [
                'file' => "import Tinymce from '@/components/Tinymce';",
                'name' => 'Tinymce,',
            ],
            'json_editor' => [
                'file' => "import JsonEditor from '@/components/JsonEditor';",
                'name' => 'JsonEditor,',
            ],
            'vue_dropzone' => [
                'file' => "import VueDropzone from '@/components/VueDropzone';",
                'name' => 'VueDropzone,',
                'request' => "import { removeFile } from '@/api/file';",
            ],
        ],
    ],

    /*
	|--------------------------------------------------------------------------
	| RELATIONSHIP
	|--------------------------------------------------------------------------
	|
	*/
    'relationship' => [
        'ignore_model' => ['Generator', 'Permission', 'Role', 'BaseModel'],
        'relationship' => [
            'has_one' => 'hasOne',
            'has_many' => 'hasMany',
            'belongs_to_many' => 'belongsToMany',
            'belongs_to' => 'belongsTo',
        ],
        'options' => [
            'search' => 'Search',
            'sort' => 'Sort',
            'show' => 'Show',
        ],
    ],
];
