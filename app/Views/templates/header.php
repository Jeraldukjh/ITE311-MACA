<?php
$isLoggedIn = session()->get('isLoggedIn') ?? false;
$userRole = session()->get('role') ?? 'guest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'LMS System' ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- CSRF Meta Tags for AJAX -->
    <meta name="csrf-token-name" content="<?= csrf_token() ?>">
    <meta name="csrf-token-value" content="<?= csrf_hash() ?>">
    <meta name="csrf-header-name" content="<?= config('Security')->headerName ?>">
    <style>
        :root {
            --primary-color:rgb(45, 75, 93);  /* Red theme primary color */
            --secondary-color:rgb(91, 51, 46);  /* Darker red for secondary elements */
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --info-color: #3498db;
            --light-color: #f8f9fa;
            --dark-color:rgb(62, 34, 30);  /* Changed to match primary red */
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }
        
        /* Sidebar Styles */
        #wrapper {
            overflow-x: hidden;
            min-height: 100vh;
            display: flex;
        }
        
        #sidebar-wrapper {
            min-height: 100vh;
            width: 250px;
            margin-left: -250px;
            transition: all 0.3s ease;
            position: fixed;
            z-index: 1000;
            background: var(--primary-color);
            background-color: var(--primary-color) !important;
            box-shadow: 2px 0 15px rgba(0,0,0,0.1);
        }
        
        #sidebar-wrapper,
        #sidebar-wrapper .list-group-item,
        #sidebar-wrapper .dropdown-menu {
            background-color: var(--primary-color) !important;
            color: white !important;
        }
        
        #sidebar-wrapper .list-group-item {
            border-left: none;
            border-right: none;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        #sidebar-wrapper .list-group-item:hover,
        #sidebar-wrapper .list-group-item:focus {
            background-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        #sidebar-wrapper .dropdown-menu {
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        #page-content-wrapper {
            width: 100%;
            min-width: 0;
            flex: 1;
            transition: all 0.3s ease;
            transform-origin: top left;
            margin-left: 0;
            padding: 20px;
        }
        
        #wrapper.toggled #page-content-wrapper {
            transform: scale(0.95);
            margin-left: 0;
            padding: 20px;
        }
        
        @media (min-width: 992px) {
            #page-content-wrapper {
                margin-left: 250px;
                padding: 30px;
            }
            
            #wrapper.toggled #page-content-wrapper {
                margin-left: 0;
                transform: scale(0.9) translateX(0);
                padding: 30px;
            }
        }
        
        #wrapper.toggled #sidebar-wrapper {
            margin-left: 0;
            box-shadow: 2px 0 15px rgba(0,0,0,0.2);
            background-color: var(--primary-color) !important;
        }
        
        .sidebar-heading {
            background-color: rgba(0, 0, 0, 0.1);
        }
        
        .list-group-item {
            border: none;
            border-radius: 0;
            padding: 0.75rem 1.5rem;
            background-color: transparent;
        }
        
        .list-group-item:hover, .list-group-item:focus {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .list-group-item.active {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        @media (min-width: 992px) {
            #sidebar-wrapper {
                margin-left: 0;
            }
            
            #wrapper.toggled #sidebar-wrapper {
                margin-left: -250px;
            }
            
            #page-content-wrapper {
                min-width: 0;
                width: 100%;
            }
        }
        
        /* Navbar styles for mobile */
        .navbar {
            background-color: var(--primary-color) !important;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        
        .btn-primary, .btn-primary:hover, .btn-primary:focus {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .bg-primary {
            background-color: var(--primary-color) !important;
        }
        
        .text-primary {
            color: var(--primary-color) !important;
        }
        
        .border-primary {
            border-color: var(--primary-color) !important;
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        .navbar-brand, .nav-link {
            color: white !important;
        }
        
        .nav-link:hover {
            opacity: 0.9;
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: transform 0.2s;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .badge {
            font-weight: 500;
            padding: 0.4em 0.8em;
        }
        
        .welcome-text {
            color: #6c757d;
            font-size: 1.1rem;
    </style>
</head>
<body>
    <!-- Sidebar Navigation -->
    <div class="d-flex" id="wrapper">
        <!-- Sidebar Toggle Button -->
        <div class="position-fixed" style="z-index: 1100; margin: 10px;">
            <button class="btn btn-primary" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        
        <!-- Sidebar -->
        <div class="bg-danger text-white" id="sidebar-wrapper">
            <div class="sidebar-heading p-3">
                <span class="fw-bold ms-5 ps-4">LMS MACA</span>
            </div>
            <div class="list-group list-group-flush">
                <?php if ($isLoggedIn): ?>
                    <a href="<?= base_url('dashboard') ?>" class="list-group-item list-group-item-action text-white" style="background-color: var(--primary-color); border-color: rgba(255,255,255,0.1);">
                        Dashboard
                    </a>
                    
                    <?php if ($userRole === 'admin'): ?>
                        <!-- Admin Menu Items -->
                        <a class="list-group-item list-group-item-action bg-dark text-white" data-bs-toggle="collapse" href="#adminMenu" role="button" aria-expanded="false" aria-controls="adminMenu">
                            Admin
                        </a>
                        <div class="collapse" id="adminMenu">
                            <a href="<?= base_url('admin/users') ?>" class="list-group-item list-group-item-action text-white ps-5" style="background-color: var(--primary-color); border-color: rgba(255,255,255,0.1);">
                                Manage Users
                            </a>
                            <a href="<?= base_url('admin/courses') ?>" class="list-group-item list-group-item-action text-white ps-5" style="background-color: var(--primary-color); border-color: rgba(255,255,255,0.1);">
                                Manage Courses
                            </a>
                            <a href="<?= base_url('admin/settings') ?>" class="list-group-item list-group-item-action text-white ps-5" style="background-color: var(--primary-color); border-color: rgba(255,255,255,0.1);">
                            </a>
                        </div>
                        
                    <?php elseif ($userRole === 'teacher'): ?>
                        <!-- Teacher Menu Items -->
                        <a href="<?= base_url('teacher/courses') ?>" class="list-group-item list-group-item-action text-white" style="background-color: var(--danger-color); border-color: rgba(255,255,255,0.1);">
                            My Courses
                        </a>
                        <a href="<?= base_url('teacher/students') ?>" class="list-group-item list-group-item-action text-white" style="background-color: var(--danger-color); border-color: rgba(255,255,255,0.1);">
                            Students
                        </a>
                        
                    <?php elseif ($userRole === 'student'): ?>
                        <!-- Student Menu Items -->
                        <a href="<?= base_url('student/courses') ?>" class="list-group-item list-group-item-action text-white" style="background-color: var(--danger-color); border-color: rgba(255,255,255,0.1);">
                            My Learning
                        </a>
                        <a href="<?= base_url('student/progress') ?>" class="list-group-item list-group-item-action text-white" style="background-color: var(--danger-color); border-color: rgba(255,255,255,0.1);">
                            My Progress
                        </a>
                    <?php endif; ?>
                    
                <?php else: ?>
                    <!-- Guest Menu Items -->
                    <a href="<?= base_url('about') ?>" class="list-group-item list-group-item-action text-white" style="background-color: var(--danger-color); border-color: rgba(255,255,255,0.1);">
                    </a>
                    <a href="<?= base_url('courses') ?>" class="list-group-item list-group-item-action text-white" style="background-color: var(--primary-color); border-color: rgba(255,255,255,0.1);">
                        Courses
                    </a>
                <?php endif; ?>
            </div>
            
            <?php if ($isLoggedIn): ?>
                <!-- User Profile Section -->
                <div class="position-absolute bottom-0 w-100 p-3" style="background-color: var(--primary-color);">
                    <div class="dropdown">
                        <a class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" href="#" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="me-2 d-flex align-items-center justify-content-center rounded-circle bg-secondary" style="width: 40px; height: 40px;">
                                <span>U</span>
                            </div>
                            <div class="small">
                                <div class="fw-bold"><?= esc(session()->get('name')) ?></div>
                                <div class="text-muted"><?= ucfirst($userRole) ?></div>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="<?= base_url('profile') ?>">Profile</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('settings') ?>">Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= base_url('logout') ?>">Logout</a></li>
                        </ul>
                    </div>
                </div>
            <?php else: ?>
                <!-- Login/Register Buttons -->
                <div class="position-absolute bottom-0 w-100 p-3" style="background-color: var(--primary-color);">
                    <a href="<?= base_url('login') ?>" class="btn btn-outline-light w-100 mb-2">
                        Login
                    </a>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <!-- Top Navigation -->
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark d-lg-none">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">
                        LMS
                    </a>
                    <button class="btn btn-link text-white" id="menu-toggle-mobile">
                        ☰
                    </button>
                </div>
            </nav>
            
            <!-- Main Content -->
    <div class="container-fluid p-4">
                <?= $this->renderSection('content') ?>
    </div>
    
    <!-- jQuery (for AJAX helpers) -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" crossorigin="anonymous"></script>
    
    <!-- Global CSRF setup for AJAX -->
    <script>
        window.CSRF = {
            tokenName: document.querySelector('meta[name="csrf-token-name"]').getAttribute('content'),
            hash: document.querySelector('meta[name="csrf-token-value"]').getAttribute('content'),
            headerName: document.querySelector('meta[name="csrf-header-name"]').getAttribute('content') || 'X-CSRF-TOKEN'
        };
        if (window.jQuery) {
            // Default header for CI4 CSRF
            $.ajaxSetup({
                headers: (function(){
                    const h = {}; h[window.CSRF.headerName] = window.CSRF.hash; return h;
                })()
            });
            // Refresh CSRF hash if server returns a new one in JSON under resp.csrf.hash
            $(document).ajaxComplete(function(_evt, xhr){
                try {
                    const resp = xhr.responseJSON;
                    if (resp && resp.csrf && resp.csrf.hash) {
                        window.CSRF.hash = resp.csrf.hash;
                        document.querySelector('meta[name="csrf-token-value"]').setAttribute('content', window.CSRF.hash);
                    }
                } catch (e) { /* ignore */ }
            });
        }
    </script>
    
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Sidebar Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const closeSidebar = document.getElementById('closeSidebar');
            const wrapper = document.getElementById('wrapper');
            const pageContent = document.getElementById('page-content-wrapper');
            
            // Initialize the page content position based on screen size
            function updateContentPosition() {
                if (window.innerWidth >= 992) {
                    // Desktop view
                    pageContent.style.marginLeft = wrapper.classList.contains('toggled') ? '0' : '250px';
                } else {
                    // Mobile view
                    pageContent.style.marginLeft = '0';
                }
            }
            
            // Call on load
            updateContentPosition();
            
            // Update on window resize
            window.addEventListener('resize', updateContentPosition);
            
            // Toggle sidebar when clicking the hamburger menu
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    wrapper.classList.toggle('toggled');
                    updateContentPosition();
                });
            }
            
            // Close button functionality removed as per request
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                const isClickInside = document.getElementById('sidebar-wrapper').contains(event.target) || 
                                    document.getElementById('sidebarToggle').contains(event.target);
                
                if (!isClickInside && window.innerWidth < 992) {
                    wrapper.classList.add('toggled');
                    updateContentPosition();
                }
            });
        });
    </script>
    <script>
        // Toggle sidebar on mobile
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const menuToggleMobile = document.getElementById('menu-toggle-mobile');
            const wrapper = document.getElementById('wrapper');
            
            if (menuToggle) {
                menuToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    wrapper.classList.toggle('toggled');
                });
            }
            
            if (menuToggleMobile) {
                menuToggleMobile.addEventListener('click', function(e) {
                    e.preventDefault();
                    wrapper.classList.toggle('toggled');
                });
            }
            
            // Auto-close dropdowns when clicking outside
            document.addEventListener('click', function(event) {
                const dropdowns = document.querySelectorAll('.dropdown-menu.show');
                dropdowns.forEach(function(dropdown) {
                    if (!dropdown.parentElement.contains(event.target)) {
                        const dropdownInstance = bootstrap.Dropdown.getInstance(dropdown.previousElementSibling);
                        if (dropdownInstance) {
                            dropdownInstance.hide();
                        }
                    }
                });
            });
        });
    </script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>

