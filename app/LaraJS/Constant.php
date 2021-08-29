<?php
/**
 * File Constant.php
 *
 * @author Tan Mai <maingocthanhtan96@gmail.com>
 * @package LaraJS
 * @version 1.0
 */

namespace App\LaraJS;

final class Constant
{
    /**
     * RESTful api
     * @type int
     */
    public const STATUS_200 = 200; // OK – response successfully methods GET, PUT, PATCH hoặc DELETE.
    public const STATUS_201 = 201; // Created – Returns when a Resource has been created successfully
    public const STATUS_204 = 204; // No Content – Returns when Resource deleted successfully.
    public const STATUS_304 = 304; // Not Modified – Clients can use cached data.
    public const STATUS_400 = 400; // Bad Request – The request is not valid
    public const STATUS_401 = 401; // Unauthorized – Request requires auth.
    public const STATUS_403 = 403; // Forbidden – Rejected not allowed.
    public const STATUS_404 = 404; // Not Found – No resource found from URI
    public const STATUS_405 = 405; // Method Not Allowed – The method is not allowed on the current user.
    public const STATUS_410 = 410; // Gone – Resource no longer exists, the old version is no longer supported.
    public const STATUS_415 = 415; // Unsupported Media Type – Does not support this type of Resource.
    public const STATUS_422 = 422; // Unprocessable Entity – Data not validated
    public const STATUS_429 = 429; // Too Many Requests – Request was rejected due to restrictions
    public const STATUS_500 = 500; // Internal Server Error — API developers should avoid this error.
    // If an error occurs in the global catch blog, the stracktrace should be logged and not returned as response.
}
