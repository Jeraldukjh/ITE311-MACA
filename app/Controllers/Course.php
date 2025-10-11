<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EnrollmentModel;
use CodeIgniter\HTTP\ResponseInterface;

class Course extends BaseController
{
    /**
     * Handle AJAX enrollment requests
     */
    public function enroll()
    {
        // Enable error reporting for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        // Log the start of the enrollment process with full request data
        log_message('debug', '=== START ENROLLMENT REQUEST ===');
        log_message('debug', 'POST data: ' . print_r($this->request->getPost(), true));
        log_message('debug', 'Headers: ' . print_r($this->request->headers(), true));
        log_message('debug', 'User data: ' . print_r($this->userData ?? [], true));
        
        // Check if user is logged in
        if (!$this->isLoggedIn()) {
            $errorMsg = 'Enrollment failed: User not logged in';
            log_message('error', $errorMsg);
            
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)
                ->setJSON([
                    'success' => false,
                    'message' => 'Unauthorized. Please log in.',
                    'csrf'    => [ 'token' => csrf_token(), 'hash' => csrf_hash() ],
                    'debug'   => ['error' => $errorMsg]
                ]);
        }
        
        // Get user ID from session
        $userId = (int) ($this->userData['id'] ?? 0);
        log_message('debug', 'Processing enrollment for user ID: ' . $userId);

        // Only accept POST
        if ($this->request->getMethod() !== 'post') {
            $errorMsg = 'Invalid request method: ' . $this->request->getMethod();
            log_message('error', $errorMsg);
            
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_METHOD_NOT_ALLOWED)
                ->setJSON([
                    'success' => false,
                    'message' => 'Invalid request method.',
                    'csrf'    => [ 'token' => csrf_token(), 'hash' => csrf_hash() ],
                    'debug'   => ['error' => $errorMsg]
                ]);
        }
        
        // Verify CSRF token
        if (!$this->request->isAJAX() || !$this->request->getPost(csrf_token())) {
            $errorMsg = 'Invalid CSRF token or not an AJAX request';
            log_message('error', $errorMsg);
            
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_FORBIDDEN)
                ->setJSON([
                    'success' => false,
                    'message' => 'Invalid request.',
                    'csrf'    => [ 'token' => csrf_token(), 'hash' => csrf_hash() ],
                    'debug'   => ['error' => $errorMsg]
                ]);
        }

        $courseId = (int) $this->request->getPost('course_id');
        
        log_message('debug', sprintf('Attempting enrollment - User ID: %d, Course ID: %d', $userId, $courseId));
        
        if ($userId <= 0 || $courseId <= 0) {
            $errorMsg = sprintf('Invalid IDs - User: %d, Course: %d', $userId, $courseId);
            log_message('error', $errorMsg);
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request data.',
                'csrf'    => [ 'token' => csrf_token(), 'hash' => csrf_hash() ],
                'debug'   => ['error' => $errorMsg]
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        // Validate input
        if ($courseId <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid course ID.',
                'csrf'    => [ 'token' => csrf_token(), 'hash' => csrf_hash() ],
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $db = \Config\Database::connect();
        
        log_message('debug', 'Database connection established');

        // Verify course exists and is active
        $course = $db->table('courses')
            ->select('id, course, description')
            ->where('id', $courseId)
            ->get()
            ->getRowArray();
            
        log_message('debug', 'Course query result: ' . print_r($course, true));

        if (!$course) {
            $errorMsg = sprintf('Course not found - ID: %d', $courseId);
            log_message('error', $errorMsg);
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Course not found or inactive.',
                'csrf'    => [ 'token' => csrf_token(), 'hash' => csrf_hash() ],
                'debug'   => ['error' => $errorMsg]
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $enrollmentModel = new EnrollmentModel();
        log_message('debug', 'EnrollmentModel initialized');

        // Check for existing enrollment
        $isEnrolled = $enrollmentModel->isAlreadyEnrolled($userId, $courseId);
        log_message('debug', 'Is already enrolled: ' . ($isEnrolled ? 'Yes' : 'No'));
        
        if ($isEnrolled) {
            $errorMsg = sprintf('User %d is already enrolled in course %d', $userId, $courseId);
            log_message('warning', $errorMsg);
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You are already enrolled in this course.',
                'csrf'    => [ 'token' => csrf_token(), 'hash' => csrf_hash() ],
                'debug'   => ['info' => $errorMsg]
            ])->setStatusCode(ResponseInterface::HTTP_CONFLICT);
        }

        // Create enrollment
        try {
            $enrollmentData = [
                'student_id' => $userId,
                'course_id'  => $courseId,
                'enrolled_at' => date('Y-m-d H:i:s')
            ];
            
            log_message('debug', 'Attempting to enroll user with data: ' . print_r($enrollmentData, true));
            
            // Direct database insertion for debugging
            $db = \Config\Database::connect();
            $builder = $db->table('enrollments');
            $builder->insert($enrollmentData);
            $insertId = $db->insertID();
            
            log_message('debug', 'Direct DB insert result - Insert ID: ' . $insertId);
            log_message('debug', 'DB Error: ' . $db->error()['message'] ?? 'No error');
            
            if (!$insertId) {
                $error = $db->error();
                $errorMsg = $error['message'] ?? 'Unknown database error';
                log_message('error', 'Direct DB enrollment failed: ' . $errorMsg);
                
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to enroll: ' . $errorMsg,
                    'csrf'    => [ 'token' => csrf_token(), 'hash' => csrf_hash() ],
                    'debug'   => ['error' => $error]
                ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            $errorMsg = 'Enrollment exception: ' . $e->getMessage();
            log_message('error', $errorMsg);
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            $response = [
                'success' => false,
                'message' => 'An error occurred while processing your request.',
                'error'   => $e->getMessage(),
                'csrf'    => [ 'token' => csrf_token(), 'hash' => csrf_hash() ],
                'debug'   => [
                    'exception' => get_class($e),
                    'trace'     => explode("\n", $e->getTraceAsString())
                ]
            ];
            
            log_message('error', 'Error response: ' . json_encode($response));
            
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        } finally {
            log_message('debug', '=== END ENROLLMENT REQUEST ===');
        }

        $enrolledAt = $enrollmentData['enrolled_at'];
        
        log_message('debug', sprintf('Enrollment successful - ID: %d, User: %d, Course: %d', 
            $insertId, $userId, $courseId));
        
        $response = [
            'success' => true,
            'message' => 'Enrolled successfully.',
            'course'  => [
                'id'          => $course['id'],
                'course'      => $course['course'],
                'description' => $course['description'],
                'enrolled_at' => $enrolledAt,
            ],
            'debug' => [
                'insert_id' => $insertId,
            ],
            'csrf' => [
                'token' => csrf_token(),
                'hash'  => csrf_hash()
            ]
        ];
        
        log_message('debug', 'Sending success response: ' . json_encode($response));
        
        return $this->response->setJSON($response);
    }
}
