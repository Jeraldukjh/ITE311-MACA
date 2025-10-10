<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CourseModel;

class Courses extends BaseController
{
    public function index()
    {
        // Admin only
        $this->requireRole('admin');

        $db = \Config\Database::connect();
        $courses = $db->table('courses c')
            ->select('c.id, c.title, c.description, c.created_at, u.name as teacher_name, u.id as teacher_id')
            ->join('users u', 'u.id = c.teacher_id', 'left')
            ->orderBy('c.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return $this->render('admin/courses/index', [
            'title' => 'Manage Courses',
            'courses' => $courses,
        ]);
    }

    public function create()
    {
        $this->requireRole('admin');
        
        $db = \Config\Database::connect();
        $teachers = $db->table('users')->select('id, name, email')
            ->where('role', 'teacher')->where('is_active', 1)
            ->orderBy('name', 'ASC')->get()->getResultArray();

        return $this->render('admin/courses/create', [
            'title' => 'Create Course',
            'teachers' => $teachers,
            'validation' => \Config\Services::validation(),
        ]);
    }

    public function store()
    {
        $this->requireRole('admin');

        if (!$this->request->is('post')) {
            return redirect()->to('/admin/courses')->with('error', 'Invalid request method.');
        }

        // Check if form was submitted
        if (!$this->request->getPost('submit')) {
            return redirect()->back()->with('error', 'Invalid form submission.');
        }

        // Get form data
        $data = [
            'title' => trim($this->request->getPost('title') ?? ''),
            'description' => trim($this->request->getPost('description') ?? ''),
            'teacher_id' => $this->request->getPost('teacher_id')
        ];

        // Debug: Log the received data
        log_message('debug', 'Form data: ' . print_r($data, true));

        // Validation rules
        $rules = [
            'title' => 'required|min_length[3]|max_length[100]|is_unique[courses.title]',
            'teacher_id' => 'required|integer|greater_than[0]',
            'description' => 'max_length[1000]'
        ];

        $messages = [
            'title' => [
                'required' => 'Course title is required',
                'min_length' => 'Title must be at least 3 characters',
                'max_length' => 'Title cannot exceed 100 characters',
                'is_unique' => 'A course with this title already exists'
            ],
            'teacher_id' => [
                'required' => 'Please select a teacher',
                'integer' => 'Please select a valid teacher',
                'greater_than' => 'Please select a teacher'
            ],
            'description' => [
                'max_length' => 'Description cannot exceed 1000 characters'
            ]
        ];

        // Set validation rules and messages
        $validation = \Config\Services::validation();
        $validation->setRules($rules, $messages);

        // Run validation
        if (!$validation->run($data)) {
            return redirect()->back()
                ->with('errors', $validation->getErrors())
                ->withInput();
        }

        // Convert teacher_id to integer after validation
        $data['teacher_id'] = (int)$data['teacher_id'];

        // Verify teacher exists and is active
        $db = \Config\Database::connect();
        $teacher = $db->table('users')
            ->where([
                'id' => $data['teacher_id'],
                'role' => 'teacher',
                'is_active' => 1
            ])
            ->countAllResults();

        if ($teacher === 0) {
            return redirect()->back()->withInput()->with('error', 'Selected teacher is not available.');
        }

        // Insert course
        $courseModel = new CourseModel();
        
        try {
            $courseModel->save([
                'title' => $data['title'],
                'description' => !empty($data['description']) ? $data['description'] : null,
                'teacher_id' => $data['teacher_id']
            ]);
            
            $courseId = $courseModel->getInsertID();
            
            if ($courseId) {
                return redirect()->to('/admin/courses')->with('success', 'Course created successfully!');
            } else {
                throw new \Exception('Failed to create course');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error creating course: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to create course. Please try again.');
        }
    }
}
