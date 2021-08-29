<?php

namespace App\Repositories;

interface RepositoryInterface
{
    /**
     * Get all
     * @return mixed
     * @author tanmnt
     */
    public function getAll();

    /**
     * Get one
     * @param $id
     * @return mixed
     * @author tanmnt
     */
    public function find($id);

    /**
     * Create
     * @param array $attributes
     * @return mixed
     * @author tanmnt
     */
    public function create(array $attributes);

    /**
     * Update
     * @param $id
     * @param array $attributes
     * @return mixed
     * @author tanmnt
     */
    public function update($id, array $attributes);

    /**
     * Delete
     * @param $id
     * @return mixed
     * @author tanmnt
     */
    public function delete($id);
}
