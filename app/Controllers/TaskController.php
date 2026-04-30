<?php

namespace App\Controllers;

use App\Models\TaskModel;
use CodeIgniter\RESTful\ResourceController;

class TaskController extends ResourceController
{
    protected $modelName = 'App\Models\TaskModel';
    protected $format    = 'json';

    // GET /tasks
    public function index()
    {
        return $this->respond($this->model->findAll());
    }
    //Create a new task
    public function create()
    {
        $data = $this->request->getJSON(true);

        if (!isset($data['title'])) {
            return $this->respond([
    'error' => 'Title is required'
], 422);
        }

        $this->model->insert($data);

        return $this->respondCreated($data);
    }

    // PATCH 
    public function update($id = null)
    {
        $task = $this->model->find($id);

        if (!$task) {
            return $this->failNotFound('Task not found');
        }

        $data = $this->request->getJSON(true);

        $this->model->update($id, $data);

        return $this->respond(['message' => 'Updated']);
    }

    // DELETE 
    public function delete($id = null)
    {
        $task = $this->model->find($id);

        if (!$task) {
            return $this->failNotFound('Task not found');
        }

        $this->model->delete($id);

        return $this->respondDeleted(['message' => 'Deleted']);
    }
}