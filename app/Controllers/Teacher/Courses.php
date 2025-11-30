<?php

namespace App\Controllers\Teacher;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use Config\Database;

class Courses extends BaseController
{
    protected $courseModel;
    protected $enrollmentModel;

    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
    }

    public function index()
    {
        $this->requireRole('teacher');

        $teacherId = $this->userData['id'] ?? null;
        if (!$teacherId) {
            return redirect()->to('/login');
        }

        $db = Database::connect();
        $courses = $db->table('courses c')
            ->select('c.id, c.course, c.description, c.created_at')
            ->where('c.teacher_id', $teacherId)
            ->orderBy('c.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return $this->render('teacher/courses', [
            'title' => 'My Courses',
            'courses' => $courses,
        ]);
    }

    public function students($courseId = null)
    {
        $this->requireRole('teacher');

        $courseId = (int) $courseId;

        if ($courseId <= 0) {
            return redirect()->to('teacher/courses')->with('error', 'Invalid course.');
        }

        $course = $this->courseModel->find($courseId);

        if (!$course || (int) $course['teacher_id'] !== (int) ($this->userData['id'] ?? 0)) {
            return redirect()->to('teacher/courses')->with('error', 'Course not found or access denied.');
        }

        $students = $this->enrollmentModel->getStudentsByCourse($courseId);

        return $this->render('teacher/course_students', [
            'title' => 'Enrolled Students - ' . ($course['course'] ?? ''),
            'course' => $course,
            'students' => $students,
        ]);
    }
}
