<?php
/**
 * @OA\Info(
 *   title="Manage API",
 *   version="1.0.0",
 *   @OA\Contact(
 *     email="maingocthanhtan96@gmail.com"
 *   )
 * )
 *
 * @OA\Server(
 *  url="http://local.larajs.com/api/v1",
 *  description="Api v1(LARAJS)"
 * )
 *
 * @OA\Server(
 *  url="http://local.larajs.com/api/v2",
 *  description="Api v2(LARAJS)"
 * )
 *
 * @OA\SecurityScheme(
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="authApi",
 *     in="header",
 * )
 */
