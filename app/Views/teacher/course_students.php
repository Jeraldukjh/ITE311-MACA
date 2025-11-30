<?= $this->extend('templates/header'); ?>
<?= $this->section('title') ?>Enrolled Students - <?= esc($course['course'] ?? '') ?><?= $this->endSection() ?>

<?= $this->section('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="mb-0">Enrolled Students</h3>
                    <p class="text-muted mb-0">
                        <?= esc($course['course'] ?? '') ?>
                    </p>
                </div>
                <a href="<?= base_url('teacher/courses') ?>" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back to My Courses
                </a>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (empty($students)): ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <h5 class="text-muted">No Students Enrolled</h5>
                        <p class="text-muted mb-0">There are currently no students enrolled in this course.</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped align-middle mb-0">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Enrolled At</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($students as $student): ?>
                                    <tr>
                                        <td><?= esc($student['name'] ?? '') ?></td>
                                        <td><?= esc($student['email'] ?? '') ?></td>
                                        <td><?= !empty($student['enrolled_at']) ? date('M d, Y H:i', strtotime($student['enrolled_at'])) : '' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
