<?php

namespace App\Repositories;

abstract class EloquentRepository implements RepositoryInterface
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     * @author tanmnt
     */
    protected $model;

    /**
     * EloquentRepository constructor.
     * @author tanmnt
     */
    public function __construct()
    {
        $this->setModel();
    }

    /**
     * get model
     * @return string
     * @author tanmnt
     */
    abstract public function getModel();

    /**
     * Set model
     * @author tanmnt
     */
    public function setModel()
    {
        $this->model = app()->make($this->getModel());
    }

    /**
     * Get All
     * @return \Illuminate\Database\Eloquent\Collection|array<static>
     * @author tanmnt
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * Get one
     * @param $id
     * @return mixed
     * @author tanmnt
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Create
     * @param array $attributes
     * @return mixed
     * @author tanmnt
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * Update
     * @param $id
     * @param array $attributes
     * @return bool|mixed
     * @author tanmnt
     */
    public function update($id, array $attributes)
    {
        $result = $this->find($id);
        if ($result) {
            $result->update($attributes);
            return $result;
        }

        return false;
    }

    /**
     * Delete
     *
     * @param $id
     * @return bool
     * @author tanmnt
     */
    public function delete($id)
    {
        $result = $this->find($id);
        if ($result) {
            $result->delete();

            return true;
        }

        return false;
    }
}
