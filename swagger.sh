#!/bin/bash
php vendor/bin/openapi --bootstrap app/Larajs/Development/swagger-constants.php --format json --output public/swagger/swagger.json app/Larajs/Development/swagger-v1.php app/Larajs/Swagger
