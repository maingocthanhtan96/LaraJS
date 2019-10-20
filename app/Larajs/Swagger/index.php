<?php
/**
 * @OA\Parameter(
 *      name="id",
 *      in="path",
 *      description="ID",
 *      required=true,
 *      @OA\Schema(type="integer")
 * )
 */

/**
 * @OA\Response(
 *      response="OK",
 *      description="Success",
 *      @OA\JsonContent(
 *          @OA\Property(property="success", type="string", example="true"),
 *          @OA\Property(property="data", type="string", example="[]"),
 *      )
 * ),
 *
 * @OA\Response(
 *      response="NotFound",
 *      description="Error",
 *      @OA\JsonContent(
 *          @OA\Property(property="success", type="string", example="false"),
 *          @OA\Property(property="message", type="string", example="404 not found"),
 *      )
 * ),
 * @OA\Response(
 *      response="Error",
 *      description="Error",
 *      @OA\JsonContent(
 *          @OA\Property(property="success", type="string", example="false"),
 *          @OA\Property(property="message", type="string", example="Error server"),
 *      )
 * ),
 */



// Oauth2 passport
/**
 * OA\SecurityScheme(
 *      type="oauth2",
 *      securityScheme="Oauth2Password",
 *      name="Password Based",
 *      scheme="bearer",
 *      description="Authencation",
 *      in="header",
 *      OA\Flow(
 *          flow="password",
 *          authorizationUrl="/oauth/authorize",
 *          tokenUrl="/oauth/token",
 *          refreshUrl="/oauth/token/refresh",
 *          scopes={}
 *      )
 * )
 */

// Upload Image
/**
 *          OA\MediaType(
 *              mediaType="multipart/form-data",
 *              OA\Schema(
 *                  OA\Property(
 *                      property="avatar",
 *                      description="Upload avatar",
 *                      type="file",
 *                      OA\Items(type="string", format="binary")
 *                   ),
 *               ),
 *           ),
 */
