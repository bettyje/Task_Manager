<?php

namespace App\Controllers;

use App\Models\TaskModel;
use CodeIgniter\RESTful\ResourceController;

class TaskController extends ResourceController
{
    protected $modelName = 'App\Models\TaskModel';
    protected $format    = 'json';



    //Create Tasks

        public function create()
    {
        $data = $this->request->getJSON(true);

        if (!$data || empty(trim($data['title'] ?? ''))) {
            return $this->failValidationErrors('Title is required');
        }

        $insertData = [
            'title' => trim($data['title']),
            'description' => $data['description'] ?? null,
            'completed' => !empty($data['completed']) ? 1 : 0,
        ];

        $id = $this->model->insert($insertData);

        if (!$id) {
            return $this->failServerError($this->model->errors());
        }

        return $this->respondCreated([
            'data' => $this->model->find($id)
        ]);
    }

    // GET /tasks
    public function index()
    {
        $page  = $this->request->getGet('page') ?? 1;
        $limit = $this->request->getGet('limit') ?? 5;

        $offset = ($page - 1) * $limit;

        $tasks = $this->model
            ->orderBy('created_at', 'DESC')
            ->findAll($limit, $offset);

        $total = $this->model->countAll();

        return $this->respond([
            'data' => $tasks,
            'meta' => [
                'current_page' => (int)$page,
                'per_page' => (int)$limit,
                'total' => $total,
                'total_pages' => ceil($total / $limit)
            ]
        ]);
    }


       public function show($id = null)
    {
        $task = $this->model->find($id);

        if (!$task) {
            return $this->failNotFound('Task not found');
        }

        return $this->respond($task);
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