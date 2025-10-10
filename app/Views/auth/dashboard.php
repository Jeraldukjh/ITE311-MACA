<?= $this->extend('templates/header'); ?>
<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-4">Dashboard</h1>
    
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Welcome Card -->
        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-0">Welcome, <?= esc($user['name'] ?? 'User') ?>!</h5>
                    <p class="text-muted mb-0"><?= date('l, F j, Y') ?></p>
                </div>
            </div>
        </div>

        <!-- Enroll Courses Section -->
        <?php if ($user['role'] === 'student'): ?>
        <div class="col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Enroll in New Courses</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Browse and enroll in available courses to start learning.</p>
                    <div class="d-grid">
                        <a href="<?= base_url('student/courses') ?>" class="btn btn-outline-primary">
                            <i class="fas fa-search me-1"></i> Browse Courses
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Courses Section -->
        <div class="col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-book me-2"></i>My Courses</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($enrolledCourses)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($enrolledCourses as $course): ?>
                                <a href="<?= base_url('student/course/' . $course['id']) ?>" class="list-group-item list-group-item-action border-0 px-0">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><?= esc($course['title']) ?></h6>
                                        <span class="badge bg-primary"><?= $course['progress'] ?? '0' ?>%</span>
                                    </div>
                                    <small class="text-muted">Last accessed: <?= $course['last_accessed'] ?? 'Never' ?></small>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">You haven't enrolled in any courses yet.</p>
                        <a href="<?= base_url('student/courses') ?>" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-plus me-1"></i> Find Courses
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Quick Actions -->
        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Quick Actions</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <?php if ($user['role'] === 'admin'): ?>
                            <a href="<?= base_url('admin/courses') ?>" class="btn btn-primary">
                                <i class="fas fa-book me-1"></i> Manage Courses
                            </a>
                            <a href="<?= base_url('admin/users') ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-users me-1"></i> Manage Users
                            </a>
                        <?php elseif ($user['role'] === 'teacher'): ?>
                            <a href="<?= base_url('teacher/courses') ?>" class="btn btn-primary">
                                <i class="fas fa-chalkboard-teacher me-1"></i> My Courses
                            </a>
                        <?php else: ?>
                            <a href="<?= base_url('student/courses') ?>" class="btn btn-primary">
                                <i class="fas fa-graduation-cap me-1"></i> View Available Courses
                            </a>
                            <a href="<?= base_url('student/enrollments') ?>" class="btn btn-outline-primary">
                                <i class="fas fa-list-check me-1"></i> My Enrollments
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0"><i class="fas fa-history me-2"></i>Recent Activity</h5>
                </div>
                <div class="card-body pt-0">
                    <div class="list-group list-group-flush">
                        <?php if (!empty($recentActivities)): ?>
                            <?php foreach ($recentActivities as $activity): ?>
                                <div class="list-group-item border-0 px-0 py-3">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-<?= $activity['icon'] ?? 'bell' ?> text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1"><?= esc($activity['title']) ?></h6>
                                                <small class="text-muted"><?= timespan(strtotime($activity['created_at']), time(), 1) ?> ago</small>
                                            </div>
                                            <p class="mb-1 small"><?= esc($activity['description']) ?></p>
                                            <?php if (!empty($activity['action_url'])): ?>
                                                <a href="<?= $activity['action_url'] ?>" class="btn btn-sm btn-outline-primary mt-2">
                                                    <?= $activity['action_text'] ?? 'View Details' ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No recent activities found</p>
                                <p class="small text-muted">Your activities will appear here</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
