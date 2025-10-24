<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MaterialModel;
use App\Models\EnrollmentModel;
use App\Models\CourseModel;

class Materials extends BaseController
{
    protected $materialModel;
    protected $enrollmentModel;
    protected $courseModel;

    public function __construct()
    {
        $this->materialModel = new MaterialModel();
        $this->enrollmentModel = new EnrollmentModel();
        $this->courseModel = new CourseModel();
    }

    /**
     * Display upload form and handle file upload for a course
     */
    public function upload($course_id = null)
    {
        // Check if user is logged in and is admin/teacher
        if (!$this->isLoggedIn() || !$this->hasRole(['admin', 'teacher'])) {
            return redirect()->to('/login');
        }

        $course_id = (int) $course_id;

        // Verify course exists
        $course = $this->courseModel->find($course_id);
        if (!$course) {
            session()->setFlashdata('error', 'Course not found.');
            return redirect()->to('/admin/courses');
        }

        // Check if POST request (file upload)
        if ($this->request->getMethod() === 'POST') {
            return $this->handleUpload($course_id);
        }

        // Display upload form
        $data = [
            'course' => $course,
            'materials' => $this->materialModel->getMaterialsByCourse($course_id),
            'title' => 'Upload Material - ' . esc($course['course'])
        ];

        return $this->render('admin/materials/upload', $data);
    }

    /**
     * Handle the actual file upload
     */
    private function handleUpload($course_id)
    {
        $validation = \Config\Services::validation();

        // Validate file upload
        $validation->setRules([
            'material_file' => [
                'label' => 'Material File',
                'rules' => 'uploaded[material_file]|max_size[material_file,10240]|ext_in[material_file,pdf,doc,docx,ppt,pptx,zip,rar]',
                'errors' => [
                    'uploaded' => 'Please select a file to upload.',
                    'max_size' => 'File size must not exceed 10MB.',
                    'ext_in' => 'Only PDF, DOC, DOCX, PPT, PPTX, ZIP, and RAR files are allowed.'
                ]
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            session()->setFlashdata('error', implode('<br>', $validation->getErrors()));
            return redirect()->back();
        }

        $file = $this->request->getFile('material_file');

        if (!$file->isValid()) {
            session()->setFlashdata('error', 'Invalid file uploaded.');
            return redirect()->back();
        }

        // Create upload directory if it doesn't exist
        $uploadPath = WRITEPATH . 'uploads/materials/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Generate unique filename
        $newName = $file->getRandomName();
        $filePath = 'materials/' . $newName;

        // Move file to upload directory
        if (!$file->move($uploadPath, $newName)) {
            session()->setFlashdata('error', 'Failed to upload file. Please try again.');
            return redirect()->back();
        }

        // Save to database
        $materialData = [
            'course_id' => $course_id,
            'file_name' => $file->getClientName(),
            'file_path' => $filePath
        ];

        $result = $this->materialModel->insertMaterial($materialData);

        if ($result) {
            session()->setFlashdata('success', 'Material uploaded successfully!');
        } else {
            // If database insert failed, remove the uploaded file
            if (file_exists($uploadPath . $newName)) {
                unlink($uploadPath . $newName);
            }
            session()->setFlashdata('error', 'Failed to save material information. Please try again.');
        }

        return redirect()->to('/admin/course/' . $course_id . '/upload');
    }

    /**
     * Delete a material
     */
    public function delete($material_id = null)
    {
        // Check if user is logged in and is admin/teacher
        if (!$this->isLoggedIn() || !$this->hasRole(['admin', 'teacher'])) {
            return redirect()->to('/login');
        }

        $material_id = (int) $material_id;

        // Get material info
        $material = $this->materialModel->getMaterialById($material_id);
        if (!$material) {
            session()->setFlashdata('error', 'Material not found.');
            return redirect()->to('/admin/courses');
        }

        // Delete file from filesystem
        $filePath = WRITEPATH . 'uploads/materials/' . basename($material['file_path']);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Delete from database
        $result = $this->materialModel->deleteMaterial($material_id);

        if ($result) {
            session()->setFlashdata('success', 'Material deleted successfully!');
        } else {
            session()->setFlashdata('error', 'Failed to delete material. Please try again.');
        }

        return redirect()->to('/admin/course/' . $material['course_id'] . '/upload');
    }

    /**
     * Download a material file
     */
    public function download($material_id = null)
    {
        // Check if user is logged in
        if (!$this->isLoggedIn()) {
            return redirect()->to('/login');
        }

        $material_id = (int) $material_id;

        // Get material info
        $material = $this->materialModel->getMaterialById($material_id);
        if (!$material) {
            session()->setFlashdata('error', 'Material not found.');
            return redirect()->back();
        }

        // Check if user is enrolled in the course (for students) or is admin/teacher
        $canAccess = false;
        if ($this->hasRole(['admin', 'teacher'])) {
            $canAccess = true;
        } else {
            // Check enrollment for students
            $canAccess = $this->enrollmentModel->isAlreadyEnrolled($this->userData['id'], $material['course_id']);
        }

        if (!$canAccess) {
            session()->setFlashdata('error', 'You do not have permission to download this material.');
            return redirect()->back();
        }

        // Check if file exists
        $filePath = WRITEPATH . 'uploads/materials/' . basename($material['file_path']);
        if (!file_exists($filePath)) {
            session()->setFlashdata('error', 'File not found on server.');
            return redirect()->back();
        }

        // Force download
        return $this->response->download($filePath, null);
    }

    /**
     * Display materials for a specific course (for students)
     */
    public function courseMaterials($course_id = null)
    {
        // Check if user is logged in
        if (!$this->isLoggedIn()) {
            return redirect()->to('/login');
        }

        $course_id = (int) $course_id;

        // Check if user is enrolled in the course (for students) or is admin/teacher
        if ($this->hasRole('student')) {
            $isEnrolled = $this->enrollmentModel->isAlreadyEnrolled($this->userData['id'], $course_id);
            if (!$isEnrolled) {
                session()->setFlashdata('error', 'You are not enrolled in this course.');
                return redirect()->to('/student/dashboard');
            }
        }

        // Get course info
        $course = $this->courseModel->find($course_id);
        if (!$course) {
            session()->setFlashdata('error', 'Course not found.');
            return redirect()->back();
        }

        // Get materials for the course
        $materials = $this->materialModel->getMaterialsByCourse($course_id);

        $data = [
            'course' => $course,
            'materials' => $materials,
            'title' => 'Course Materials - ' . esc($course['course'])
        ];

        return $this->render('student/materials/index', $data);
    }
}
