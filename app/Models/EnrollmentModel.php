<?php

namespace App\Models;

use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $errors = [];
    
    /**
     * Get the last error that occurred during enrollment
     * 
     * @return array Array of error messages
     */
    public function getErrors()
    {
        return $this->errors;
    }
    
    protected $table = 'enrollments';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['student_id', 'course_id', 'enrolled_at'];
    protected $useTimestamps = false;

    /**
     * Insert a new enrollment record
     *
     * @param array{student_id:int,course_id:int,enrolled_at?:string} $data
     * @return int|false Insert ID on success or false on failure
     */
    public function enrollUser(array $data)
    {
        // Reset errors
        $this->errors = [];
        
        if (!isset($data['student_id'], $data['course_id'])) {
            $this->errors['enrollment'] = 'Missing required fields: student_id and course_id are required';
            return false;
        }

        // Verify student exists
        $student = $this->db->table('users')
            ->where('id', $data['student_id'])
            ->where('is_active', 1)
            ->countAllResults();

        if ($student === 0) {
            $this->errors['enrollment'] = 'Invalid student ID or student is not active';
            return false;
        }

        // Verify course exists
        $course = $this->db->table('courses')
            ->where('id', $data['course_id'])
            ->countAllResults();

        if ($course === 0) {
            $this->errors['enrollment'] = 'Invalid course ID';
            return false;
        }

        $data['enrolled_at'] = $data['enrolled_at'] ?? date('Y-m-d H:i:s');

        try {
            $result = $this->insert($data);
            
            if ($result === false) {
                // Get database errors if any
                $dbError = $this->errors();
                if (!empty($dbError)) {
                    $this->errors['database'] = $dbError;
                }
                log_message('error', 'Enrollment failed: ' . print_r($this->errors, true));
                return false;
            }
            
            return $this->getInsertID();
        } catch (\Exception $e) {
            log_message('error', 'Enrollment exception: ' . $e->getMessage());
            $this->errors['enrollment'] = $e->getMessage();
            return false;
        }
    }

    /**
     * Fetch all courses a user is enrolled in
     *
     * @param int $userId
     * @return array<int,array<string,mixed>>
     */
    public function getUserEnrollments(int $userId): array
    {
        return $this->db->table($this->table)
            ->select('courses.id, courses.course, courses.description, enrollments.enrolled_at')
            ->join('courses', 'courses.id = enrollments.course_id')
            ->where('enrollments.student_id', $userId)
            ->orderBy('enrollments.enrolled_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Check if a user is already enrolled in a specific course
     *
     * @param int $userId
     * @param int $courseId
     * @return bool
     */
    public function isAlreadyEnrolled(int $userId, int $courseId): bool
    {
        return (bool) $this->where([
            'student_id' => $userId,
            'course_id'  => $courseId,
        ])->countAllResults();
    }
}
