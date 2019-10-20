<?php

/**
 *  @OA\Post(
 *      path="/login",
 *      tags={"Login"},
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\MediaType(
 *              mediaType="application/x-www-form-urlencoded",
 *              @OA\Schema(
 *                  required={"email", "password"},
 *                  @OA\Property(type="string", property="email", example="example@larajs.com"),
 *                  @OA\Property(type="string", property="password", format="password"),
 *              )
 *          )
 *      ),
 *     @OA\Response(response="200", description="Login Success!",)
 * ),
 */

/**
 * @OA\Get(
 *      path="/users",
 *      tags={"User"},
 *      summary="List User",
 *      security={{"authApi":{}}},
 *      @OA\Response(response="200", ref="#/components/responses/OK"),
 *      @OA\Response(response="404", ref="#/components/responses/NotFound"),
 *      @OA\Response(response="500", ref="#/components/responses/Error"),
 * )
 *
 * @OA\Post(
 *      path="/users",
 *      tags={"User"},
 *      summary="Create User",
 *      security={{"authApi":{}}},
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(ref="#/components/schemas/User")
 *       ),
 *      @OA\Response(response="200", ref="#/components/responses/OK"),
 *      @OA\Response(response="404", ref="#/components/responses/NotFound"),
 *      @OA\Response(response="500", ref="#/components/responses/Error"),
 * )
 *
 * @OA\Put(
 *      path="/users/{id}",
 *      tags={"User"},
 *      summary="Edit User",
 *      security={{"authApi":{}}},
 *      @OA\Parameter(ref="#/components/parameters/id"),
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(ref="#/components/schemas/User")
 *       ),
 *      @OA\Response(response="200", ref="#/components/responses/OK"),
 *      @OA\Response(response="404", ref="#/components/responses/NotFound"),
 *      @OA\Response(response="500", ref="#/components/responses/Error"),
 * )
 *
 * @OA\Delete(
 *      path="/users/{id}",
 *      tags={"User"},
 *      summary="Delete User",
 *      security={{"authApi":{}}},
 *      @OA\Parameter(ref="#/components/parameters/id"),
 *      @OA\Response(response="200", ref="#/components/responses/OK"),
 *      @OA\Response(response="404", ref="#/components/responses/NotFound"),
 *      @OA\Response(response="500", ref="#/components/responses/Error"),
 * )
 */

/**
 * @OA\Schema(
 *     type="object",
 *     title="User",
 * )
 */
class User
{
    /**
     * @OA\Property(
     *     title="Name",
     * )
     * @var string
     */
    protected $name;

    /**
     * @OA\Property(
     *     title="Email",
     *     example="example@larajs.com"
     * )
     * @var string
     */
    protected $email;

    /**
     * @OA\Property(
     *     title="Avatar",
     *     example="https://lorempixel.com/150/150/?57749",
     * )
     * @var string
     */
    protected $avatar;

    /**
     * @OA\Property(
     *     title="Password",
     *     example="larajs"
     * )
     * @var string
     */
    protected $password;
}
