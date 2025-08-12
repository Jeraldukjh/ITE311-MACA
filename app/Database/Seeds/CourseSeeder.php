<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class CourseSeeder extends Seeder
{
    public function run()
    {
        // Get instructor IDs
        $instructors = $this->db->table('users')
            ->where('role', 'instructor')
            ->get()
            ->getResult();
            
        if (empty($instructors)) {
            echo "No instructors found. Please run UserSeeder first.\n";
            return;
        }

        $courses = [
            [
                'title' => 'Introduction to Web Development',
                'description' => 'Learn the basics of HTML, CSS, and JavaScript to build your first website.',
                'instructor_id' => $instructors[0]->id,
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ],
            [
                'title' => 'Advanced PHP with CodeIgniter',
                'description' => 'Master PHP web development using the CodeIgniter framework.',
                'instructor_id' => $instructors[0]->id,
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ],
            [
                'title' => 'Database Design Fundamentals',
                'description' => 'Learn how to design and implement efficient database schemas.',
                'instructor_id' => $instructors[1]->id,
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ],
        ];

        $this->db->table('courses')->insertBatch($courses);
        
        // Get the inserted course IDs
        $courseIds = $this->db->insertID();
        $courseIds = [
            $courseIds - 2, // First course ID
            $courseIds - 1, // Second course ID
            $courseIds,     // Third course ID
        ];
        
        // Create enrollments
        $students = $this->db->table('users')
            ->where('role', 'student')
            ->get()
            ->getResult();
            
        $enrollments = [];
        foreach ($students as $student) {
            // Enroll each student in all courses
            foreach ($courseIds as $courseId) {
                $enrollments[] = [
                    'student_id' => $student->id,
                    'course_id' => $courseId,
                    'enrollment_date' => Time::now(),
                    'status' => 'active',
                    'created_at' => Time::now(),
                    'updated_at' => Time::now(),
                ];
            }
        }
        
        if (!empty($enrollments)) {
            $this->db->table('enrollments')->insertBatch($enrollments);
        }
        
        // Add lessons to courses
        $this->addLessons($courseIds[0], 'Introduction to Web Development');
        $this->addLessons($courseIds[1], 'Advanced PHP with CodeIgniter');
        $this->addLessons($courseIds[2], 'Database Design Fundamentals');
        
        echo "Successfully seeded courses, enrollments, and lessons.\n";
    }
    
    private function addLessons($courseId, $courseTitle)
    {
        $lessons = [];
        
        switch ($courseTitle) {
            case 'Introduction to Web Development':
                $lessons = [
                    ['title' => 'HTML Basics', 'content' => 'Introduction to HTML tags and structure', 'order' => 1],
                    ['title' => 'CSS Fundamentals', 'content' => 'Styling web pages with CSS', 'order' => 2],
                    ['title' => 'JavaScript Introduction', 'content' => 'Adding interactivity with JavaScript', 'order' => 3],
                ];
                break;
                
            case 'Advanced PHP with CodeIgniter':
                $lessons = [
                    ['title' => 'MVC Architecture', 'content' => 'Understanding the Model-View-Controller pattern', 'order' => 1],
                    ['title' => 'CodeIgniter Basics', 'content' => 'Setting up and configuring CodeIgniter', 'order' => 2],
                    ['title' => 'Database Operations', 'content' => 'Working with databases in CodeIgniter', 'order' => 3],
                ];
                break;
                
            case 'Database Design Fundamentals':
                $lessons = [
                    ['title' => 'Relational Database Concepts', 'content' => 'Understanding tables, rows, and relationships', 'order' => 1],
                    ['title' => 'Normalization', 'content' => 'Database normalization techniques', 'order' => 2],
                    ['title' => 'SQL Queries', 'content' => 'Writing efficient SQL queries', 'order' => 3],
                ];
                break;
        }
        
        foreach ($lessons as $lesson) {
            $lessonData = [
                'course_id' => $courseId,
                'title' => $lesson['title'],
                'content' => $lesson['content'],
                'lesson_order' => $lesson['order'],
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ];
            
            $this->db->table('lessons')->insert($lessonData);
            $lessonId = $this->db->insertID();
            
            // Add a quiz to each lesson
            $this->addQuiz($lessonId, $lesson['title']);
        }
    }
    
    private function addQuiz($lessonId, $lessonTitle)
    {
        $quizData = [
            'lesson_id' => $lessonId,
            'title' => $lessonTitle . ' Quiz',
            'description' => 'Test your knowledge of ' . $lessonTitle,
            'passing_score' => 70,
            'time_limit_minutes' => 30,
            'is_published' => true,
            'created_at' => Time::now(),
            'updated_at' => Time::now(),
        ];
        
        $this->db->table('quizzes')->insert($quizData);
        $quizId = $this->db->insertID();
        
        // Add questions to the quiz
        $this->addQuestions($quizId, $lessonTitle);
    }
    
    private function addQuestions($quizId, $lessonTitle)
    {
        $questions = [
            [
                'text' => 'What is the main topic of this lesson?',
                'type' => 'multiple_choice',
                'answers' => [
                    ['text' => $lessonTitle, 'is_correct' => true],
                    ['text' => 'Something else', 'is_correct' => false],
                    ['text' => 'Not sure', 'is_correct' => false],
                    ['text' => 'None of the above', 'is_correct' => false],
                ]
            ],
            [
                'text' => 'This lesson was easy to understand.',
                'type' => 'true_false',
                'answers' => [
                    ['text' => 'True', 'is_correct' => true],
                    ['text' => 'False', 'is_correct' => false],
                ]
            ],
            [
                'text' => 'What did you learn in this lesson?',
                'type' => 'short_answer',
                'answers' => [
                    ['text' => 'Sample answer', 'is_correct' => true],
                ]
            ],
        ];
        
        foreach ($questions as $qIndex => $question) {
            $questionData = [
                'quiz_id' => $quizId,
                'question_text' => $question['text'],
                'question_type' => $question['type'],
                'points' => 1,
                'question_order' => $qIndex + 1,
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ];
            
            $this->db->table('quiz_questions')->insert($questionData);
            $questionId = $this->db->insertID();
            
            // Add answers for this question
            $answers = [];
            foreach ($question['answers'] as $answer) {
                $answers[] = [
                    'question_id' => $questionId,
                    'answer_text' => $answer['text'],
                    'is_correct' => $answer['is_correct'],
                    'created_at' => Time::now(),
                    'updated_at' => Time::now(),
                ];
            }
            
            if (!empty($answers)) {
                $this->db->table('quiz_answers')->insertBatch($answers);
            }
        }
    }
}
