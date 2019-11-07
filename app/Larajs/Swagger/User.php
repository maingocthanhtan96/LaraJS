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
 *          @OA\JsonContent(ref="#/components/schemas/User")
 *      ),
 *     @OA\Response(response="200", ref="#/components/responses/OK"),
 *     @OA\Response(response="404", ref="#/components/responses/NotFound"),
 *     @OA\Response(response="500", ref="#/components/responses/Error"),
 * )
 *
 *  @OA\Get(
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
 *         @OA\JsonContent(ref="#/components/schemas/User")
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
 *     required={"name", "email", "password", "role_id"},
 * )
 */
class User
{
    /**
     * Field[name]
     * @OA\Property(
     *     title="Name",
     *     default="None",
     *     description="",
     * )
     * @var string
     */
    protected $name;

    /**
     * Field[email]
     * @OA\Property(
     *     title="Email",
     *     default="None",
     *     example="example@larajs.com",
     *     description="",
     * )
     * @var string
     */
    protected $email;

    /**
     * Field[avatar]
     * @OA\Property(
     *     title="Avatar",
     *     default="None",
     *     example="https://lorempixel.com/150/150/?57749",
     *     description="",
     * )
     * @var string
     */
    protected $avatar;

    /**
     * Field[role_id]
     * @OA\Property(
     *     title="Role",
     *     default="None",
     *     example="2",
     *     description="You cann't set id as 1(role: admin)",
     * )
     * @var integer
     */
    protected $role_id;

    /**
     * Field[password]
     * @OA\Property(
     *     title="Password",
     *     default="None",
     *     example="larajs",
     *     description="",
     * )
     * @var string
     */
    protected $password;

    /**
     * Field[created_at]
     * @OA\Property(
     *     title="Create date",
     *     default="timestamp",
     *     example="",
     *     description="",
     * )
     * @var dateTime
     */
    protected $created_at;

    /**
     * Field[updated_at]
     * @OA\Property(
     *     title="Update date",
     *     default="timestamp",
     *     example="",
     *     description="",
     * )
     * @var dateTime
     */
    protected $updated_at;

    /**
     * Field[deleted_at]
     * @OA\Property(
     *     title="Delete date",
     *     default="timestamp",
     *     example="",
     *     description="",
     * )
     * @var dateTime
     */
    protected $deleted_at;

    //{{SWAGGER_PROPERTY_NOT_DELETE_THIS_LINE}}
}
