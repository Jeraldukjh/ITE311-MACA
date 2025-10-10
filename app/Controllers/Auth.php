<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\EnrollmentModel;

class Auth extends BaseController
{
    protected $validation;
    
    public function __construct()
    {
        $this->validation = \Config\Services::validation();
    }
    
    public function register()
    {
        $db = \Config\Database::connect();
        
        if ($this->request->getMethod() === 'POST') {
            // Get raw input for validation
            $input = $this->request->getPost();
            
            // Set validation rules with stricter patterns
            $validationRules = [
                'name' => [
                    'label' => 'Full Name',
                    'rules' => 'required|min_length[3]|max_length[50]|regex_match[/^[\p{L}\s\'\-\.]+$/u]',
                    'errors' => [
                        'required' => 'Name is required.',
                        'min_length' => 'Name must be at least 3 characters long.',
                        'max_length' => 'Name cannot exceed 50 characters.',
                        'regex_match' => 'Name can only contain letters, spaces, hyphens, apostrophes, and dots.'
                    ]
                ],
                'email' => [
                    'label' => 'Email',
                    'rules' => 'required|valid_email|max_length[100]|is_unique[users.email]',
                    'errors' => [
                        'required' => 'Email is required.',
                        'valid_email' => 'Please enter a valid email address.',
                        'max_length' => 'Email cannot exceed 100 characters.',
                        'is_unique' => 'This email is already registered.'
                    ]
                ],
                'password' => [
                    'label' => 'Password',
                    'rules' => 'required|min_length[8]|max_length[255]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$/]',
                    'errors' => [
                        'required' => 'Password is required.',
                        'min_length' => 'Password must be at least 8 characters long.',
                        'max_length' => 'Password cannot exceed 255 characters.',
                        'regex_match' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.'
                    ]
                ],
                'password_confirm' => [
                    'label' => 'Password Confirmation',
                    'rules' => 'required|matches[password]',
                    'errors' => [
                        'required' => 'Password confirmation is required.',
                        'matches' => 'Password confirmation does not match.'
                    ]
                ]
            ];

            // Set data for validation
            $this->validation->setRules($validationRules);

            if ($this->validation->run($input)) {
                // Generate secure password hash
                $hashedPassword = password_hash($input['password'], PASSWORD_BCRYPT);

                // Prepare user data with escaped values
                $userData = [
                    'name' => esc($input['name']),
                    'email' => filter_var($input['email'], FILTER_SANITIZE_EMAIL),
                    'password' => $hashedPassword,
                    'role' => 'student',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                // Use Query Builder with parameter binding to prevent SQL injection
                $builder = $db->table('users');
                
                try {
                    if ($builder->insert($userData)) {
                        $userId = $db->insertID();
                        
                        // Clear sensitive data
                        unset($userData['password']);
                        
                        // Set success message with escaped output
                        session()->setFlashdata('success', 'Registration successful! Please log in.');
                        return redirect()->to('/login');
                    }
                } catch (\Exception $e) {
                    // Log the error but don't expose details to the user
                    log_message('error', 'Registration error: ' . $e->getMessage());
                    session()->setFlashdata('error', 'An error occurred during registration. Please try again.');
                }
            } else {
                // Get validation errors
                $errors = $this->validation->getErrors();
                
                // Sanitize error messages before displaying
                array_walk($errors, function(&$value, $key) {
                    $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                });
                
                session()->setFlashdata('errors', $errors);
                
                // Repopulate form with sanitized input
                session()->setFlashdata('form_data', [
                    'name' => esc($input['name'] ?? ''),
                    'email' => filter_var($input['email'] ?? '', FILTER_SANITIZE_EMAIL)
                ]);
            }
        }

        return view('auth/register');
    }

    public function login()
    {
        // If user is already logged in, redirect to dashboard
        if ($this->isLoggedIn()) {
            return redirect()->to('/dashboard');
        }
        
        $db = \Config\Database::connect();
        
        if ($this->request->getMethod() === 'POST') {
            $validationRules = [
                'email' => [
                    'rules' => 'required|valid_email',
                    'errors' => [
                        'required' => 'Email is required.',
                        'valid_email' => 'Please enter a valid email address.'
                    ]
                ],
                'password' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Password is required.'
                    ]
                ]
            ];

            if ($this->validate($validationRules)) {
                $email = $this->request->getPost('email');
                $password = $this->request->getPost('password');

                // Check if user exists
                $builder = $db->table('users');
                $user = $builder->where('email', $email)
                              ->where('is_active', 1) // Only allow active users
                              ->get()
                              ->getRowArray();

                if ($user && password_verify($password, $user['password'])) {
                    // Create user session data
                    $userData = [
                        'userID' => $user['id'],
                        'name' => $user['name'],
                        'email' => $user['email'],
                        'role' => $user['role'],
                        'isLoggedIn' => true,
                        'last_activity' => time()
                    ];
                    
                    // Set session data
                    $session = session();
                    $session->set($userData);
                    
                    // Regenerate session ID to prevent session fixation
                    $session->regenerate(true);
                    // Get redirect URL (either from session or default to dashboard)
                    $redirectUrl = $session->get('redirect') ?? '/dashboard';
                    if ($session->has('redirect')) {
                        $session->remove('redirect');
                    }
                    
                    // Set success message and redirect
                    $session->setFlashdata('success', 'Welcome back, ' . esc($user['name']) . '!');
                    return redirect()->to($redirectUrl);
                } else {
                    // Handle failed login attempt
                    $this->handleFailedLogin($user ? $user['id'] : null, $email);
                    
                    // Generic error message to prevent user enumeration
                    return redirect()->back()
                                   ->withInput()
                                   ->with('error', 'Invalid email or password.');
                }
            } else {
                return redirect()->back()
                               ->withInput()
                               ->with('errors', $this->validator->getErrors());
            }
        }

        $data = [
            'title' => 'Login',
            'validation' => $this->validator ?? null
        ];
        
        return $this->render('auth/login', $data);
    }

    public function logout()
    {
        // Destroy the session
        session()->destroy();
        session()->setFlashdata('success', 'You have been logged out successfully.');
        return redirect()->to('/login');
    }

    /**
     * Handle failed login attempts
     */
    protected function handleFailedLogin($userId = null, $email = '')
    {
        if ($userId) {
            // Log the failed attempt
            $this->logLogin($userId, false, 'Invalid password');
        } else {
            // Log failed login attempt with non-existent email (to prevent user enumeration)
            $this->logLogin(null, false, 'Invalid email', $email);
        }
    }
    
    /**
     * Log login attempts
     */
    protected function logLogin($userId = null, $success = false, $notes = '', $email = '')
    {
        $db = \Config\Database::connect();
        
        // Get the email from post data if not provided
        if (empty($email) && $this->request->getMethod() === 'post') {
            $email = $this->request->getPost('email');
        }
        
        $data = [
            'user_id' => $userId,
            'email' => $email ?: 'unknown@example.com',
            'ip_address' => $this->request->getIPAddress() ?: '0.0.0.0',
            'user_agent' => $this->request->getUserAgent()->getAgentString() ?: 'Unknown',
            'success' => $success ? 1 : 0,
            'notes' => $notes ?: ($success ? 'Successful login' : 'Failed login attempt'),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        try {
            $db->table('login_attempts')->insert($data);
        } catch (\Exception $e) {
            log_message('error', 'Failed to log login attempt: ' . $e->getMessage());
        }
    }
    
    /**
     * Set secure session cookie parameters
     */
    protected function setSecureSessionCookie()
    {
        try {
            // Get config instances with fallbacks
            $appConfig = config('App');
            $sessionConfig = config('Session');
            
            // Get the response service
            $response = service('response');
            $session = session();
            
            // Get session ID
            $sessionId = $session->getSessionID();
            
            if (empty($sessionId)) {
                log_message('error', 'Failed to get session ID in setSecureSessionCookie');
                return false;
            }
            
            // Set cookie parameters with secure defaults
            $cookieParams = [
                'expires'  => $sessionConfig->expiration ? (time() + $sessionConfig->expiration) : 0,
                'path'     => $appConfig->cookiePath ?? '/',
                'domain'   => $appConfig->cookieDomain ?? '',
                'secure'   => $appConfig->cookieSecure ?? false,
                'httponly' => true, // JavaScript cannot access the cookie
                'samesite' => $sessionConfig->samesite ?? 'Lax',
            ];
            
            // Set the secure cookie using the response object
            $response->setCookie(
                $sessionConfig->cookieName ?? 'ci_session',
                $sessionId,
                $cookieParams
            );
            
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Error in setSecureSessionCookie: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Dashboard - Shows different content based on user role
     */
    public function dashboard()
    {
        // This will automatically redirect to login if not authenticated
        $this->requireLogin();
        
        $db = \Config\Database::connect();
        $userId = $this->userData['id'];
        $role = $this->userData['role'];
        
        $data = [
            'title' => 'Dashboard',
            'user' => $this->userData
        ];

        // Add role-specific data
        switch ($role) {
            case 'admin':
                // Get admin-specific data
                $data['stats'] = [
                    'total_users' => $db->table('users')->countAllResults(),
                    'total_courses' => $db->table('courses')->countAllResults(),
                    'total_enrollments' => $db->table('enrollments')->countAllResults(),
                    'recent_users' => $db->table('users')
                                      ->orderBy('created_at', 'DESC')
                                      ->limit(5)
                                      ->get()
                                      ->getResultArray()
                ];
                break;
                
            case 'teacher':
                // Get teacher-specific data
                $data['courses'] = $db->table('courses')
                    ->where('teacher_id', $userId)
                    ->countAllResults();
                    
                $data['students'] = $db->table('enrollments')
                    ->select('enrollments.student_id')
                    ->join('courses', 'courses.id = enrollments.course_id')
                    ->where('courses.teacher_id', $userId)
                    ->groupBy('enrollments.student_id')
                    ->countAllResults();
                    
                $data['recent_courses'] = $db->table('courses')
                    ->where('teacher_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->limit(3)
                    ->get()
                    ->getResultArray();
                break;
                
            case 'student':
                // Get student-specific data
                $data['enrolled_courses'] = $db->table('enrollments')
                    ->where('student_id', $userId)
                    ->countAllResults();
                    
                // Initialize completed lessons to 0 since we don't have the user_progress table
                $data['completed_lessons'] = 0;
                    
                $data['recent_courses'] = $db->table('enrollments')
                    ->select('courses.*')
                    ->join('courses', 'courses.id = enrollments.course_id')
                    ->where('enrollments.student_id', $userId)
                    ->orderBy('enrollments.enrolled_at', 'DESC')
                    ->limit(3)
                    ->get()
                    ->getResultArray();

                // Detailed list of enrolled courses for display
                $enrollmentModel = new EnrollmentModel();
                $data['enrolled_courses_list'] = $enrollmentModel->getUserEnrollments($userId);

                // Determine available courses the user is not yet enrolled in
                $enrolledIds = $enrollmentModel->where('student_id', $userId)->findColumn('course_id') ?? [];
                $builder = $db->table('courses')->select('id, title, description');
                if (!empty($enrolledIds)) {
                    $builder->whereNotIn('id', $enrolledIds);
                }
                $data['available_courses'] = $builder->orderBy('created_at', 'DESC')->limit(12)->get()->getResultArray();
                break;
        }
        
        return $this->render('auth/dashboard', $data);

        $role = strtolower(session()->get('role') ?? 'student');
        
        switch ($role) {
            case 'admin':
                return view('dashboard/admindashboard', $userData);
            case 'teacher':
                return view('dashboard/teacherdashboard', $userData);
            case 'student':
            default:
                return view('dashboard/studentdashboard', $userData);
        }
    }
}
