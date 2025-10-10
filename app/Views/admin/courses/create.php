<?php
/** @var array $teachers */
?>
<?= $this->extend('templates/header'); ?>
<?= $this->section('title') ?>Create Course<?= $this->endSection() ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Create Course</h3>
        <a href="<?= base_url('admin/courses') ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($errors = session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0 small">
                <?php foreach ($errors as $e): ?>
                    <li><?= esc($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="post" action="<?= base_url('admin/courses') ?>">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?= esc(old('title')) ?>" required minlength="3" maxlength="100">
                </div>

                <div class="mb-3">
                    <label for="teacher_id" class="form-label">Assign Teacher</label>
                    <select class="form-select" id="teacher_id" name="teacher_id" required>
                        <option value="" disabled <?= old('teacher_id') ? '' : 'selected' ?>>Select a teacher</option>
                        <?php foreach ($teachers as $t): ?>
                            <option value="<?= (int)$t['id'] ?>" <?= old('teacher_id') == $t['id'] ? 'selected' : '' ?>>
                                <?= esc($t['name']) ?> (<?= esc($t['email']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description (optional)</label>
                    <textarea class="form-control" id="description" name="description" rows="4" maxlength="1000" placeholder="Brief description of the course..."><?= esc(old('description')) ?></textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" name="submit" value="1" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save
                    </button>
                    <a href="<?= base_url('admin/courses') ?>" class="btn btn-light">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
