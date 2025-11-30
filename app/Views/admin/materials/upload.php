<?php
/** @var array $course */
?>
<?= $this->extend('templates/header'); ?>
<?= $this->section('title') ?>Upload Material - <?= esc($course['course']) ?><?= $this->endSection() ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                </ol>
            </nav>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-upload me-2"></i>
                        Upload Material for <?= esc($course['course']) ?>
                    </h5>
                </div>
                <div class="card-body">
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

                    <form action="<?= base_url('admin/course/' . $course['id'] . '/upload') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="material_file" class="form-label">
                                <i class="fas fa-file-alt me-2"></i>Select Material File
                            </label>
                            <input type="file" class="form-control" id="material_file" name="material_file" required>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Supported formats: PDF, DOC, DOCX, PPT, PPTX, ZIP, RAR (Max: 10MB)
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <?php $role = session()->get('role'); ?>
                                <?php $backUrl = ($role === 'teacher') ? base_url('teacher/courses') : base_url('admin/courses'); ?>
                                <a href="<?= $backUrl ?>" class="btn btn-secondary w-100">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Courses
                                </a>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-upload me-2"></i>Upload Material
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Materials List Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Uploaded Materials (<?= count($materials ?? []) ?>)
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($materials)): ?>
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="fas fa-file-alt fa-3x text-muted"></i>
                            </div>
                            <h6 class="text-muted">No Materials Uploaded</h6>
                            <p class="text-muted mb-0">No learning materials have been uploaded for this course yet.</p>
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
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="<?= base_url('materials/download/' . $material['id']) ?>"
                                                           class="btn btn-primary">
                                                            <i class="fas fa-download me-1"></i>Download
                                                        </a>
                                                        <a href="<?= base_url('admin/materials/delete/' . $material['id']) ?>"
                                                           class="btn btn-danger"
                                                           onclick="return confirm('Are you sure you want to delete this material?')">
                                                            <i class="fas fa-trash me-1"></i>Delete
                                                        </a>
                                                    </div>
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
    </div>
</div>
<?= $this->endSection() ?>
