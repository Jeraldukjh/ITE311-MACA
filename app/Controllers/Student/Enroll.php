<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use CodeIgniter\HTTP\ResponseInterface;

class Enroll extends BaseController
{
    protected $courseModel;
    protected $enrollmentModel;

    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
    }

    /**
     * Display the enrollment page
     */
    public function index()
    {
        $this->requireRole('student');
        
        $userId = $this->userData['id'] ?? 0;
        
        // Get all available courses with teacher information
        $courses = $this->courseModel->select('courses.*, users.name as teacher_name')
            ->join('users', 'users.id = courses.teacher_id')
            ->where('users.is_active', 1)
            ->findAll();
            
        // Get user's enrolled course IDs
        $enrolledCourses = $this->enrollmentModel->getUserEnrollments($userId);
        $enrolledCourseIds = array_column($enrolledCourses, 'id');

        return $this->render('student/enrollcourses', [
            'title' => 'Enroll in Courses',
            'courses' => $courses,
            'enrolledCourseIds' => $enrolledCourseIds
        ]);
    }

    /**
     * API Endpoint: Get available courses for enrollment
     * (Optional - can be used for AJAX loading)
     */
    public function courses()
    {
        $this->requireRole('student');
        
        $userId = $this->userData['id'] ?? 0;
        
        // Get all available courses with teacher information
        $courses = $this->courseModel->select('courses.*, users.name as teacher_name')
            ->join('users', 'users.id = courses.teacher_id')
            ->where('users.is_active', 1)
            ->findAll();
            
        // Get user's enrolled course IDs
        $enrolledCourses = $this->enrollmentModel->getUserEnrollments($userId);
        $enrolledCourseIds = array_column($enrolledCourses, 'id');
        
        // Mark which courses the student is enrolled in
        foreach ($courses as &$course) {
            $course['is_enrolled'] = in_array($course['id'], $enrolledCourseIds);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $courses
        ]);
    }
    
    /**
     * Handle course enrollment
     */
    public function enroll()
    {
        // Check if user is logged in
        if (!$this->isLoggedIn()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please log in to enroll in courses.',
                'csrf'    => $this->getCsrfData()
            ])->setStatusCode(401);
        }
        
        // Only allow POST requests
        if (!$this->request->isAJAX() || !$this->request->is('post')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method.',
                'csrf'    => $this->getCsrfData()
            ])->setStatusCode(405);
        }
        
        // Get course ID from POST data
        $courseId = (int)$this->request->getPost('course_id');
        $userId = $this->userData['id'];
        
        if (empty($courseId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No course selected.',
                'csrf'    => $this->getCsrfData()
            ])->setStatusCode(400);
        }
        
        // Check if course exists
        $course = $this->courseModel->find($courseId);
        if (!$course) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Course not found.',
                'csrf'    => $this->getCsrfData()
            ])->setStatusCode(404);
        }
        
        // Check if already enrolled
        $isEnrolled = $this->enrollmentModel->where([
            'student_id' => $userId,
            'course_id'  => $courseId
        ])->countAllResults() > 0;
        
        if ($isEnrolled) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You are already enrolled in this course.',
                'csrf'    => $this->getCsrfData()
            ])->setStatusCode(400);
        }
        
        // Enroll the student
        try {
            $enrollmentData = [
                'student_id'  => $userId,
                'course_id'   => $courseId,
                'enrolled_at' => date('Y-m-d H:i:s')
            ];
            
            // Insert the enrollment
            $this->enrollmentModel->insert($enrollmentData);
            $enrollmentId = $this->enrollmentModel->getInsertID();
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Successfully enrolled in the course!',
                'course'  => $course,
                'enrollment' => [
                    'id' => $enrollmentId,
                    'enrolled_at' => $enrollmentData['enrolled_at']
                ],
                'csrf' => $this->getCsrfData()
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Enrollment error: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while processing your enrollment. Please try again.',
                'error'   => $e->getMessage(),
                'csrf'    => $this->getCsrfData()
            ])->setStatusCode(500);
        }
    }
}
