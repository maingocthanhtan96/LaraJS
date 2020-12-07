<?php

/**
 * @OA\Post(
 *     path="/login",
 *     tags={"Auth"},
 *     summary="Login",
 *     @OA\RequestBody(
 *          required=true,
 *          @OA\MediaType(
 *              mediaType="application/x-www-form-urlencoded",
 *              @OA\Schema(
 *                  required={"email", "password"},
 *                  @OA\Property(type="string", property="email", example="example@larajs.com"),
 *                  @OA\Property(type="string", property="password", format="password"),
 *              )
 *          )
 *     ),
 *     @OA\Response(response="200", description="Login Success!",)
 * ),
 * @OA\Get(
 *     path="/user-info",
 *     tags={"Auth"},
 *     summary="Info user",
 *     security={{"authApi":{}}},
 *     @OA\Response(response="200", ref="#/components/responses/OK"),
 *     @OA\Response(response="404", ref="#/components/responses/NotFound"),
 *     @OA\Response(response="500", ref="#/components/responses/Error"),
 * ),
 */

/**
 * @OA\Get(
 *     path="/users",
 *     tags={"User"},
 *     summary="List User",
 *     security={{"authApi":{}}},
 *     @OA\Response(response="200", ref="#/components/responses/OK"),
 *     @OA\Response(response="404", ref="#/components/responses/NotFound"),
 *     @OA\Response(response="500", ref="#/components/responses/Error"),
 * )
 *
 * @OA\Post(
 *     path="/users",
 *     tags={"User"},
 *     summary="Create User",
 *     security={{"authApi":{}}},
 *     @OA\RequestBody(
 *          required=true,
 *          @OA\MediaType(
 *              mediaType="multipart/form-data",
 *              @OA\Schema (
 *                  required={},
 *                  @OA\Property(property="name", type="string", example="tanmnt", description=""),
 *                  @OA\Property(property="email", type="string", example="tanmnt@larajs.com", description=""),
 *                  @OA\Property(property="avatar", type="string", example="/images/logo-tanmnt.png", description=""),
 *                  @OA\Property(property="role_id", type="number", example="2", description=""),
 *                  @OA\Property(property="password", type="string", example="secret", description=""),
 *                  @OA\Property(property="password_confirmation", type="string", example="secret", description=""),
 *                  x="{{SWAGGER_PROPERTY_JSON_CONTENT_NOT_DELETE_THIS_LINE}}"*
 *              )
 *          )
 *      ),
 *     @OA\Response(response="200", ref="#/components/responses/OK"),
 *     @OA\Response(response="404", ref="#/components/responses/NotFound"),
 *     @OA\Response(response="500", ref="#/components/responses/Error"),
 * )
 *
 * @OA\Get(
 *     path="/users/{id}",
 *     tags={"User"},
 *     summary="Find User",
 *     security={{"authApi":{}}},
 *     @OA\Parameter(ref="#/components/parameters/id"),
 *     @OA\Response(response="200", ref="#/components/responses/OK"),
 *     @OA\Response(response="404", ref="#/components/responses/NotFound"),
 *     @OA\Response(response="500", ref="#/components/responses/Error"),
 * )
 *
 * @OA\Put(
 *     path="/users/{id}",
 *     tags={"User"},
 *     summary="Update User",
 *     security={{"authApi":{}}},
 *     @OA\Parameter(ref="#/components/parameters/id"),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *              mediaType="multipart/form-data",
 *              @OA\Schema (
 *                  required={},
 *                  @OA\Property(property="name", type="string", example="tanmnt"),
 *                  @OA\Property(property="email", type="string", example="tanmnt@larajs.com"),
 *                  @OA\Property(property="avatar", type="string", example="/images/logo-tanmnt.png"),
 *                  @OA\Property(property="role_id", type="number", example="2"),
 *                  @OA\Property(property="password", type="string", example="secret"),
 *                  @OA\Property(property="password_confirmation", type="string", example="secret"),
 *                  x="{{SWAGGER_PROPERTY_JSON_CONTENT_NOT_DELETE_THIS_LINE}}"
 *              )
 *         )
 *     ),
 *     @OA\Response(response="200", ref="#/components/responses/OK"),
 *     @OA\Response(response="404", ref="#/components/responses/NotFound"),
 *     @OA\Response(response="500", ref="#/components/responses/Error"),
 * )
 *
 * @OA\Delete(
 *     path="/users/{id}",
 *     tags={"User"},
 *     summary="Delete User",
 *     security={{"authApi":{}}},
 *     @OA\Parameter(ref="#/components/parameters/id"),
 *     @OA\Response(response="200", ref="#/components/responses/OK"),
 *     @OA\Response(response="404", ref="#/components/responses/NotFound"),
 *     @OA\Response(response="500", ref="#/components/responses/Error"),
 * )
 */

/**
 * Table[users]
 * @OA\Schema(
 *     type="object",
 *     title="User",
 *     required={"id", "name", "email", "password", "role_id"},
 * )
 */
class User
{
    /**
 * @OA\Property(property="id", type="AUTO_INCREMENT", description=""),
 */
/**
 * @OA\Property(property="name", type="VARCHAR", default="None", description="")
 */
/**
 * @OA\Property(property="email", type="VARCHAR", default="None", description="")
 */
/**
 * @OA\Property(property="avatar", type="VARCHAR", default="None", description="")
 */
/**
 * @OA\Property(property="role_id", default="None", description="You cann't set id as 1(role: admin)")
 * @var Role
 */
/**
 * @OA\Property(property="password", type="VARCHAR", default="None", description="")
 */
//{{SWAGGER_PROPERTY_NOT_DELETE_THIS_LINE}}
/**
 * @OA\Property(property="created_at", type="TIMESTAMP", default="NULL", description="")
 */
/**
 * @OA\Property(property="updated_at", type="TIMESTAMP", default="NULL", description="")
 */
/**
 * @OA\Property(property="deleted_at", type="TIMESTAMP", default="NULL", description="")
 */
}
