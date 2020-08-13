#!/bin/bash
php vendor/bin/openapi --bootstrap app/LaraJS/Development/swagger-constants.php --format json --output public/swagger/swagger.json app/LaraJS/Development/swagger-v1.php app/LaraJS/Swagger
