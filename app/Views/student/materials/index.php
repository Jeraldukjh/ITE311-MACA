<?php
/** @var array $course */
/** @var array $materials */
?>
<?= $this->extend('templates/header'); ?>
<?= $this->section('title') ?>Course Materials - <?= esc($course['course']) ?><?= $this->endSection() ?>

<?= $this->section('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="mb-0">Course Materials</h3>
                    <p class="text-muted mb-0">
                        <i class="fas fa-chalkboard-teacher me-1"></i>
                        <?= esc($course['course']) ?> - <?= esc($course['name'] ?? '') ?>
                    </p>
                </div>
                <a href="<?= base_url('student/courses') ?>" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Courses
                </a>
            </div>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (empty($materials)): ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="mb-3">
                            <i class="fas fa-file-alt fa-3x text-muted"></i>
                        </div>
                        <h5 class="text-muted">No Materials Available</h5>
                        <p class="text-muted mb-0">No learning materials have been uploaded for this course yet.</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="row g-3">
                    <?php foreach ($materials as $material): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-body">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-shrink-0 me-3">
                                            <?php
                                            $fileExtension = strtolower(pathinfo($material['file_name'], PATHINFO_EXTENSION));
                                            $iconClass = 'fas fa-file';

                                            switch ($fileExtension) {
                                                case 'pdf':
                                                    $iconClass = 'fas fa-file-pdf text-danger';
                                                    break;
                                                case 'doc':
                                                case 'docx':
                                                    $iconClass = 'fas fa-file-word text-primary';
                                                    break;
                                                case 'ppt':
                                                case 'pptx':
                                                    $iconClass = 'fas fa-file-powerpoint text-warning';
                                                    break;
                                                case 'zip':
                                                case 'rar':
                                                    $iconClass = 'fas fa-file-archive text-secondary';
                                                    break;
                                                default:
                                                    $iconClass = 'fas fa-file text-muted';
                                            }
                                            ?>
                                            <i class="<?= $iconClass ?> fa-2x"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="card-title mb-1">
                                                <?= esc($material['file_name']) ?>
                                            </h6>
                                            <p class="card-text small text-muted mb-2">
                                                <i class="fas fa-clock me-1"></i>
                                                <?= date('M d, Y', strtotime($material['created_at'])) ?>
                                            </p>
                                            <a href="<?= base_url('materials/download/' . $material['id']) ?>"
                                               class="btn btn-primary btn-sm">
                                                <i class="fas fa-download me-1"></i>Download
                                            </a>
                                        </div>
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
