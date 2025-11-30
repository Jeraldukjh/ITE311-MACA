<?= $this->extend('templates/header'); ?>
<?= $this->section('title') ?>My Courses<?= $this->endSection() ?>

<?= $this->section('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="mb-0">My Courses</h3>
                </div>
            </div>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (empty($courses)): ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <h5 class="text-muted">No Courses Assigned</h5>
                        <p class="text-muted mb-0">You don't have any courses assigned yet.</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="row g-3">
                    <?php foreach ($courses as $course): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title mb-2">
                                        <?= esc($course['course']) ?>
                                    </h5>
                                    <?php if (!empty($course['description'])): ?>
                                        <p class="card-text text-muted small mb-3">
                                            <?= esc($course['description']) ?>
                                        </p>
                                    <?php endif; ?>
                                    <p class="card-text small text-muted mb-3 mt-auto">
                                        <i class="fas fa-clock me-1"></i>
                                        <?= date('M d, Y', strtotime($course['created_at'])) ?>
                                    </p>
                                    <div class="d-flex justify-content-between gap-2">
                                        <a href="<?= base_url('admin/course/' . $course['id'] . '/upload') ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-upload me-1"></i>Upload Materials
                                        </a>
                                        <a href="<?= base_url('teacher/course/' . $course['id'] . '/students') ?>" class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-users me-1"></i>View Students
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
