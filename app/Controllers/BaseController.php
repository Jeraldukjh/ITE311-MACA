<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = ['url', 'form', 'session', 'html', 'security'];

    /**
     * User data from session
     *
     * @var array
     */
    protected $userData = [];
    
    /**
     * Current user's role
     *
     * @var string
     */
    protected $userRole = 'guest';
    
    /**
     * View data that will be passed to all views
     *
     * @var array
     */
    protected $viewData = [];

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Initialize user data from session
        $this->initializeUserData();
        
        // Set up common view data
        $this->setupViewData();
    }
    
    /**
     * Initialize user data from session
     */
    protected function initializeUserData()
    {
        $session = session();
        
        if ($session->get('isLoggedIn')) {
            $this->userData = [
                'id' => $session->get('userID'),
                'name' => $session->get('name'),
                'email' => $session->get('email'),
                'role' => $session->get('role')
            ];
            
            $this->userRole = $session->get('role') ?? 'guest';
        }
    }
    
    /**
     * Set up common view data
     */
    protected function setupViewData()
    {
        $this->viewData = [
            'title' => 'LMS System',
            'user' => $this->userData,
            'isLoggedIn' => !empty($this->userData)
        ];
    }
    
    /**
     * Check if user has a specific role
     *
     * @param string|array $roles Role or array of roles to check
     * @return bool
     */
    protected function hasRole($roles)
    {
        if (empty($this->userRole)) {
            return false;
        }
        
        if (is_string($roles)) {
            return $this->userRole === $roles;
        }
        
        if (is_array($roles)) {
            return in_array($this->userRole, $roles);
        }
        
        return false;
    }
    
    /**
     * Check if user is logged in
     *
     * @return bool
     */
    protected function isLoggedIn()
    {
        return !empty($this->userData);
    }
    
    /**
     * Require user to be logged in
     *
     * @return mixed
     */
    protected function requireLogin()
    {
        if (!$this->isLoggedIn()) {
            session()->setFlashdata('error', 'You must be logged in to access this page.');
            return redirect()->to('/login')->with('redirect', current_url());
        }
        
        return true;
    }
    
    /**
     * Require user to have a specific role
     *
     * @param string|array $roles Role or array of roles
     * @return mixed
     */
    protected function requireRole($roles)
    {
        $this->requireLogin();
        
        if (!$this->hasRole($roles)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('You do not have permission to access this page.');
        }
        
        return true;
    }
    
    /**
     * Render view with common data
     *
     * @param string $view View file path
     * @param array $data Data to pass to view
     * @return string
     */
    protected function render($view, array $data = [])
    {
        $data = array_merge($this->viewData, $data);
        return view($view, $data);
    }
    
    /**
     * Get CSRF token data
     * 
     * @return array CSRF token name and hash
     */
    protected function getCsrfData()
    {
        $csrfToken = csrf_token();
        $csrfHash = csrf_hash();
        
        // Also update the CSRF token in the session for the next request
        session()->set('csrf_' . $csrfToken, $csrfHash);
        
        return [
            'token' => $csrfToken,
            'hash' => $csrfHash
        ];
    }
}
