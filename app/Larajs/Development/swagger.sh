#!/bin/bash
php ../../../vendor/bin/openapi --bootstrap ./swagger-constants.php --format json --output ../../../public/swagger/swagger.json ./swagger-v1.php ../Swagger
