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
            transition: margin 0.25s ease-out;
            position: relative;
            z-index: 1000;
            background-color: var(--primary-color) !important;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        
        #page-content-wrapper {
            width: 100%;
            min-width: 0;
            flex: 1;
        }
        
        #wrapper.toggled #sidebar-wrapper {
            margin-left: 0;
        }
        
        .sidebar-heading {
            background-color: rgba(0, 0, 0, 0.1);
        }
        
        .list-group-item {
            border: none;
            border-radius: 0;
            padding: 0.75rem 1.5rem;
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
        <!-- Sidebar -->
        <div class="bg-danger text-white" id="sidebar-wrapper">
            <div class="sidebar-heading p-3 d-flex justify-content-between align-items-center">
                    <span class="fw-bold">LMS MACA</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
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
    
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

