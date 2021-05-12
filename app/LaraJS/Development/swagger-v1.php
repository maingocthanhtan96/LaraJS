<?php
/**
 * @OA\Info(
 *   title="Manage API",
 *   version="1.0.0",
 *   description="Documents Api",
 *   @OA\License(
 *      name="Go to homepage",
 *      url="/"
 *   ),
 *   @OA\Contact(
 *      email="maingocthanhtan96@gmail.com"
 *   ),
 * )
 *
 * @OA\Server(url=API_HOST, description="Base Api")
 *
 * @OA\Server(url=API_HOST_V1, description="Api v1")
 *
 * @OA\Server(url=API_HOST_V2, description="Api v2")
 *
 * @OA\SecurityScheme(
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="authApi",
 *     in="header",
 * )
 */
