<?= $this->extend('templates/header'); ?>
<?= $this->section('title') ?><?= ucfirst($user['role']) ?> Dashboard<?= $this->endSection() ?>
<style>
    :root {
        --primary-color: #6c5ce7;
        --primary-light: #a29bfe;
        --secondary-color: #00b894;
        --warning-color: #fdcb6e;
        --danger-color: #ff7675;
        --light-bg: #f8f9fa;
        --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        --card-hover-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    
    /* Reset default margins and paddings */
    h1, h2, h3, h4, h5, h6, p, ul, ol, li, figure, figcaption, blockquote, dl, dd {
        margin: 0;
        padding: 0;
    }

    /* Welcome Card */
    .welcome-card {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
        border-radius: 12px;
        color: white;
        padding: 1.75rem 2rem;
        margin: 1rem 0 2rem 0;
        box-shadow: 0 4px 12px rgba(45, 75, 93, 0.15);
        border: none;
        position: relative;
        overflow: hidden;
        width: 100%;
        box-sizing: border-box;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .welcome-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(45, 75, 93, 0.2);
    }
    
    .welcome-card h2 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: white;
    }
    
    .welcome-card p {
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 0;
    }
    
    .welcome-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
        transform: rotate(30deg);
    }

    /* Dashboard Cards */
    .dashboard-card {
        background: var(--card-bg);
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        height: 100%;
        position: relative;
        overflow: hidden;
        box-shadow: var(--card-shadow);
    }
    
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        border-color: rgba(45, 75, 93, 0.1);
    }
    
    .dashboard-card .card-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-right: 1rem;
    }
    
    /* Stats Cards */
    .stats-card {
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        height: 100%;
        position: relative;
        overflow: hidden;
        background: var(--card-bg);
        box-shadow: var(--card-shadow);
        padding: 1.5rem;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        border-color: rgba(45, 75, 93, 0.1);
    }
    
    .stats-card .card-icon {
        width: 54px;
        height: 54px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-right: 1.25rem;
        background: rgba(45, 75, 93, 0.1);
        color: var(--primary-color);
    }
    
    .stats-card h2 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0.5rem 0;
        color: #2d3436;
    }
    
    .stats-card h6 {
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
        color: #636e72;
    }
    
    .stats-card p.small {
        color: #7f8c8d;
        margin-bottom: 1rem;
    }
    
    .stats-card .btn {
        font-size: 0.8rem;
        font-weight: 500;
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    
    .stats-card .btn-soft-primary {
        background-color: rgba(108, 92, 231, 0.1);
        color: #6c5ce7;
        border: 1px solid rgba(108, 92, 231, 0.2);
    }
    
    .stats-card .btn-soft-primary:hover {
        background-color: rgba(108, 92, 231, 0.2);
    }
    
    .stats-card .btn-soft-success {
        background-color: rgba(0, 184, 148, 0.1);
        color: #00b894;
        border: 1px solid rgba(0, 184, 148, 0.2);
    }
    
    .stats-card .btn-soft-success:hover {
        background-color: rgba(0, 184, 148, 0.2);
    }
    
    .stats-card .btn-soft-info {
        background-color: rgba(9, 132, 227, 0.1);
        color: #0984e3;
        border: 1px solid rgba(9, 132, 227, 0.2);
    }
    
    .stats-card .btn-soft-info:hover {
        background-color: rgba(9, 132, 227, 0.2);
    }
    
    /* Activity Section */
    .recent-activity {
        position: relative;
        padding-left: 1.5rem;
    }
    
    .activity-item {
        position: relative;
        padding: 1rem 0;
        border-left: 2px solid #e9ecef;
        padding-left: 1.5rem;
    }
    
    .activity-item:last-child {
        border-left-color: transparent;
    }
    
    .activity-dot {
        position: absolute;
        left: -0.5rem;
        top: 1.5rem;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        background: #6c5ce7;
        border: 3px solid #fff;
        z-index: 1;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .welcome-card {
            text-align: center;
            padding: 1.25rem !important;
        }
        
        .welcome-card .d-flex {
            flex-direction: column;
            text-align: center;
        }
        
        .welcome-card .btn {
            margin-top: 1rem;
            display: inline-block;
        }
        
        .stats-card {
            margin-bottom: 1rem;
        }
        
        .stats-card .d-flex {
            flex-direction: column;
            text-align: center;
        }
        
        .stats-card .card-icon {
            margin: 0 auto 1rem auto;
        }
        
        .stats-card .btn {
            margin: 0.5rem auto 0 auto;
            display: inline-block;
        }
    }
    /* Dashboard Card Styles */
    .dashboard-card {
        background: white;
        border: none;
        border-radius: 12px;
        transition: all 0.3s ease;
        height: 100%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
        border-left: 4px solid var(--primary-color);
    }
    
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .dashboard-card .card-icon {
        position: absolute;
        top: 1rem;
        right: 1rem;
        font-size: 2.5rem;
        opacity: 0.1;
        color: var(--primary-color);
        margin: 0;
        width: auto;
        height: auto;
        pointer-events: none;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--card-hover-shadow);
    }

    .card-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .stats-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: rgba(255, 255, 255, 0.1);
        transform: rotate(30deg);
        pointer-events: none;
    }

    .stats-card .icon {
        font-size: 2.5rem;
        opacity: 0.8;
        margin-bottom: 0.5rem;
    }

    .recent-activity {
        position: relative;
        padding-left: 1.5rem;
        border-left: 2px solid #e9ecef;
        margin-bottom: 1.5rem;
    }

    .activity-item {
        position: relative;
        padding-bottom: 1.5rem;
    }

    .activity-item:last-child {
        padding-bottom: 0;
    }

    .activity-dot {
        position: absolute;
        left: -1.6rem;
        top: 0.25rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: var(--primary-color);
        border: 2px solid white;
    }
    /* Responsive Adjustments */
    @media (max-width: 992px) {
        .welcome-card h2 {
            font-size: 1.25rem;
        }
        
        .stats-card {
            margin-bottom: 1rem;
        }
        
        .stats-card h3 {
            font-size: 1.5rem;
        }
    }
    
    @media (max-width: 768px) {
        .welcome-card {
            padding: 1.25rem;
        }
        
        .welcome-card h2 {
            font-size: 1.1rem;
        }
        
        .stats-card {
            padding: 1.25rem;
        }
        
        .stats-card h3 {
            font-size: 1.25rem;
        }
    }</style>

<?= $this->section('content'); ?>
<div class="content-wrapper" style="width: 100%; max-width: 1400px; margin: 0 auto;">
    <!-- Welcome Card -->
    <div class="welcome-card">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h3 mb-2 fw-bold">Welcome back, <?= esc($user['name']); ?>! 👋</h2>
                <p class="mb-0 opacity-75"><?= date('l, F j, Y') ?></p>
            </div>
            <div class="d-flex align-items-center">
                <span class="badge bg-white text-primary rounded-pill px-3 py-2 fw-normal">
                    <i class="fas fa-user-shield me-1"></i> <?= esc(ucfirst($user['role'])); ?>
                </span>
            </div>
        </div>
    </div>
    <!-- Alerts -->
    <div class="row">
        <div class="col-12">
            <?php if (session()->getFlashdata('success')) : ?>
                <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-2"></i>
                        <div><?= session()->getFlashdata('success') ?></div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <div><?= session()->getFlashdata('error') ?></div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($user['role'] === 'admin') : ?>
        <!-- Admin Dashboard -->
        <div class="admin-dashboard">
            <div class="row g-4 mb-4">
                <!-- Stats Cards -->
                <div class="col-md-3">
                    <div class="stats-card" style="background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <div class="icon"><i class="fas fa-users"></i></div>
                                <h3 class="mb-0 fw-bold">1,254</h3>
                                <p class="mb-0 small">Total Users</p>
                            </div>
                            <div class="ms-auto">
                                <span class="badge bg-white text-primary rounded-pill px-3 py-1">+12%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card" style="background: linear-gradient(135deg, #00b894 0%, #55efc4 100%);">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <div class="icon"><i class="fas fa-book"></i></div>
                                <h3 class="mb-0 fw-bold">356</h3>
                                <p class="mb-0 small">Active Courses</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card" style="background: linear-gradient(135deg, #0984e3 0%, #74b9ff 100%);">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <div class="icon"><i class="fas fa-user-graduate"></i></div>
                                <h3 class="mb-0 fw-bold">982</h3>
                                <p class="mb-0 small">Students</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card" style="background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <div class="icon"><i class="fas fa-chalkboard-teacher"></i></div>
                                <h3 class="mb-0 fw-bold">124</h3>
                                <p class="mb-0 small">Teachers</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row g-4 mb-4">
                <div class="col-12">
                    <h4 class="fw-bold mb-3">Quick Actions</h4>
                </div>
                <div class="col-md-3">
                    <div class="dashboard-card p-4">
                        <div class="d-flex align-items-center">
                            <div class="card-icon" style="background-color: rgba(108, 92, 231, 0.1); color: #6c5ce7;">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 fw-bold">Manage Users</h6>
                                <p class="text-muted small mb-0">View and manage all users</p>
                            </div>
                        </div>
                        <a href="/admin/users" class="stretched-link"></a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dashboard-card p-4">
                        <div class="d-flex align-items-center">
                            <div class="card-icon" style="background-color: rgba(0, 184, 148, 0.1); color: #00b894;">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 fw-bold">System Settings</h6>
                                <p class="text-muted small mb-0">Configure system preferences</p>
                            </div>
                        </div>
                        <a href="/admin/settings" class="stretched-link"></a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dashboard-card p-4">
                        <div class="d-flex align-items-center">
                            <div class="card-icon" style="background-color: rgba(9, 132, 227, 0.1); color: #0984e3;">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 fw-bold">Reports</h6>
                                <p class="text-muted small mb-0">View system analytics</p>
                            </div>
                        </div>
                        <a href="/admin/reports" class="stretched-link"></a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dashboard-card p-4">
                        <div class="d-flex align-items-center">
                            <div class="card-icon" style="background-color: rgba(253, 203, 110, 0.1); color: #fdcb6e;">
                                <i class="fas fa-bell"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 fw-bold">Notifications</h6>
                                <p class="text-muted small mb-0">Manage system alerts</p>
                            </div>
                        </div>
                        <a href="/admin/notifications" class="stretched-link"></a>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="mb-0 fw-bold">Recent Activity</h5>
                                <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                            </div>
                            <div class="recent-activity">
                                <div class="activity-item">
                                    <div class="activity-dot"></div>
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <p class="mb-1 fw-medium">New user registered</p>
                                            <p class="text-muted small mb-0">John Doe just signed up</p>
                                        </div>
                                        <span class="text-muted small">2 min ago</span>
                                    </div>
                                </div>
                                <div class="activity-item">
                                    <div class="activity-dot"></div>
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <p class="mb-1 fw-medium">Course completed</p>
                                            <p class="text-muted small mb-0">Sarah Johnson completed Web Development</p>
                                        </div>
                                        <span class="text-muted small">1 hour ago</span>
                                    </div>
                                </div>
                                <div class="activity-item">
                                    <div class="activity-dot"></div>
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <p class="mb-1 fw-medium">New course added</p>
                                            <p class="text-muted small mb-0">Introduction to Data Science</p>
                                        </div>
                                        <span class="text-muted small">3 hours ago</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php elseif ($user['role'] === 'teacher') : ?>
        <!-- Teacher Dashboard -->
        <div class="teacher-dashboard">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-soft-purple p-3 rounded-3 me-3">
                                    <i class="fas fa-chalkboard-teacher text-purple" style="font-size: 1.5rem;"></i>
                                </div>
                                <div>
                                    <h5 class="card-title mb-1">My Courses</h5>
                                    <p class="text-muted mb-0 small">Manage your courses and lessons</p>
                                </div>
                            </div>
                            <a href="/teacher/courses" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-soft-orange p-3 rounded-3 me-3">
                                    <i class="fas fa-user-graduate text-orange" style="font-size: 1.5rem;"></i>
                                </div>
                                <div>
                                    <h5 class="card-title mb-1">Student Progress</h5>
                                    <p class="text-muted mb-0 small">Track students' progress and grades</p>
                                </div>
                            </div>
                            <a href="/teacher/progress" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php else : ?>
        <!-- Student Dashboard -->
        <div class="student-dashboard">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="mb-1"><i class="fas fa-user-graduate me-2" style="color: #6c5ce7;"></i>Student Dashboard</h3>
                    <p class="text-muted mb-0 small">Welcome back to your learning journey</p>
                </div>
                <div class="last-login text-muted small">
                    <i class="far fa-clock me-1"></i> Last login: <?= !empty($user['last_login']) ? date('M j, Y g:i A', strtotime($user['last_login'])) : 'First login' ?>
                </div>
            </div>
            
            <?php if (session()->getFlashdata('welcome')): ?>
                <div class="alert alert-light alert-dismissible fade show border-0 shadow-sm" role="alert" style="background-color: #f8f9fa;">
                    <div class="d-flex align-items-center">
                        <div class="bg-soft-primary p-2 rounded-3 me-3">
                            <i class="fas fa-sparkles text-primary"></i>
                        </div>
                        <div>
                            <?= session()->getFlashdata('welcome') ?>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm hover-scale">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-soft-primary p-3 rounded-3 me-3">
                                    <i class="fas fa-book text-primary" style="font-size: 1.5rem;"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Enrolled Courses</h6>
                                    <h2 class="mb-0 mt-1" style="color: #6c5ce7;"><?= $enrolled_courses ?? 0 ?></h2>
                                </div>
                            </div>
                            <p class="text-muted mb-3 small">Continue learning from where you left off</p>
                            <a href="<?= site_url('student/courses') ?>" class="btn btn-sm btn-soft-primary stretched-link">
                                View All <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm hover-scale">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-soft-success p-3 rounded-3 me-3">
                                    <i class="fas fa-tasks text-success" style="font-size: 1.5rem;"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Completed Lessons</h6>
                                    <h2 class="mb-0 mt-1" style="color: #00b894;"><?= $completed_lessons ?? 0 ?></h2>
                                </div>
                            </div>
                            <p class="text-muted mb-3 small">Track your learning progress</p>
                            <a href="<?= site_url('student/progress') ?>" class="btn btn-sm btn-soft-success stretched-link">
                                View Progress <i class="fas fa-chart-line ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm hover-scale">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-soft-info p-3 rounded-3 me-3">
                                    <i class="fas fa-calendar-check text-info" style="font-size: 1.5rem;"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Upcoming</h6>
                                    <h2 class="mb-0 mt-1" style="color: #0984e3;">0</h2>
                                </div>
                            </div>
                            <p class="text-muted mb-3 small">View your upcoming classes</p>
                            <a href="<?= site_url('student/calendar') ?>" class="btn btn-sm btn-soft-info stretched-link">
                                View Calendar <i class="far fa-calendar-alt ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                            </div>
                            
            <!-- Recent Activity Section -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-history me-2" style="color: #6c5ce7;"></i>Recent Activity</h5>
                        <a href="#" class="btn btn-sm btn-link text-decoration-none" style="color: #6c5ce7;">View All</a>
                    </div>
                                <div class="card-body">
                                    <?php if (!empty($recent_courses)): ?>
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Course</th>
                                                        <th>Last Accessed</th>
                                                        <th>Progress</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($recent_courses as $course): ?>
                                                        <tr>
                                                            <td>
                                                                <h6 class="mb-1"><?= esc($course['title']) ?></h6>
                                                                <small class="text-muted"><?= esc($course['code'] ?? '') ?></small>
                                                            </td>
                                                            <td><?= date('M j, Y', strtotime($course['last_accessed'] ?? 'N/A')) ?></td>
                                                            <td>
                                                                <div class="progress" style="height: 6px;">
                                                                    <div class="progress-bar bg-primary" role="progressbar" 
                                                                         style="width: <?= $course['progress'] ?? 0 ?>%;" 
                                                                         aria-valuenow="<?= $course['progress'] ?? 0 ?>" 
                                                                         aria-valuemin="0" 
                                                                         aria-valuemax="100">
                                                                    </div>
                                                                </div>
                                                                <small><?= $course['progress'] ?? 0 ?>% Complete</small>
                                                            </td>
                                                            <td>
                                                                <a href="<?= site_url('student/courses/' . $course['id']) ?>" 
                                                                   class="btn btn-sm btn-outline-primary">
                                                                    Continue <i class="fas fa-arrow-right ms-1"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center py-4">
                                            <div class="mb-3">
                                                <i class="fas fa-inbox fa-3x text-muted"></i>
                                            </div>
                                            <h5>No recent activity</h5>
                                            <p class="text-muted">Your recent courses and activities will appear here.</p>
                                            <a href="<?= site_url('student/courses') ?>" class="btn btn-primary">
                                                Browse Courses <i class="fas fa-arrow-right ms-1"></i>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Security Tips Card -->
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0"><i class="fas fa-shield-alt me-2 text-warning"></i>Security Tips</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning mb-0">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                                    </div>
                                    <div>
                                        <h6>Important Security Notice</h6>
                                        <p class="mb-2">For your security, please ensure you log out after each session, especially when using shared devices. Your last login was from IP: <strong><?= $user['last_login_ip'] ?? 'Unknown' ?></strong> on <?= !empty($user['last_login']) ? date('M j, Y g:i A', strtotime($user['last_login'])) : 'Never' ?>.</p>
                                        <div class="d-flex flex-wrap gap-2">
                                            <a href="<?= site_url('profile/security') ?>" class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-lock me-1"></i> Security Settings
                                            </a>
                                            <a href="<?= site_url('profile/activity') ?>" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-history me-1"></i> View Activity Log
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Session Timeout Warning Modal -->
    <div class="modal fade" id="sessionTimeoutModal" tabindex="-1" aria-labelledby="sessionTimeoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="sessionTimeoutModalLabel">
                        <i class="fas fa-clock me-2"></i>Session About to Expire
                    </h5>
                </div>
                <div class="modal-body">
                    <p>Your session will expire in <span id="countdown">2:00</span> due to inactivity.</p>
                    <p>Would you like to continue your session?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Log Out</button>
                    <button type="button" class="btn btn-primary" id="extendSession">
                        <i class="fas fa-sync-alt me-1"></i> Continue Session
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
