<?php

/**
 * Table[roles]
 * @OA\Schema(
 *     type="object",
 *     title="Role",
 *     required={"name", "guard_name"},
 * )
 */
class Role
{
    /**
     * @OA\Property(readOnly=true)
     * @var integer
     */
    protected $id;

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
     * Field[guard_name]
     * @OA\Property(
     *     title="Guard name",
     *     default="None",
     *     description="",
     * )
     * @var string
     */
    protected $guard_name;

    /**
     * Field[description]
     * @OA\Property(
     *     title="Description",
     *     default="None",
     *     description="",
     * )
     * @var string
     */
    protected $description;

    //{{SWAGGER_PROPERTY_NOT_DELETE_THIS_LINE}}

    /**
     * @OA\Property(
     *     title="Create date",
     *     readOnly=true
     * )
     * @var dateTime
     */
    protected $created_at;

    /**
     * @OA\Property(
     *     title="Update date",
     *     readOnly=true
     * )
     * @var dateTime
     */
    protected $updated_at;
}
