<?= $this->extend('templates/header'); ?>
<?= $this->section('title') ?>Enroll in Courses<?= $this->endSection() ?>

<?= $this->section('content'); ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Available Courses</h3>
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

    <div class="row g-4">
        <?php if (!empty($courses)): ?>
            <?php foreach ($courses as $course): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title fw-bold"><?= esc($course['course'] ?? $course['title'] ?? 'Untitled Course') ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted">
                                <i class="fas fa-chalkboard-teacher me-1"></i> 
                                <?= esc($course['teacher_name'] ?? 'No Teacher Assigned') ?>
                            </h6>
                            <p class="card-text text-muted">
                                <?= !empty($course['description']) ? esc($course['description']) : 'No description available.' ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-light text-dark">
                                    <i class="far fa-calendar-alt me-1"></i>
                                    <?= !empty($course['created_at']) ? date('M d, Y', strtotime($course['created_at'])) : 'N/A' ?>
                                </span>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <?php if (in_array($course['id'], $enrolledCourseIds ?? [])): ?>
                                <button class="btn btn-success btn-sm w-100" disabled>
                                    <i class="fas fa-check-circle me-1"></i> Enrolled
                                </button>
                            <?php else: ?>
                                <button class="btn btn-primary btn-sm w-100 enroll-btn" 
                                        data-course-id="<?= $course['id'] ?>">
                                    <i class="fas fa-plus-circle me-1"></i> Enroll Now
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No courses available at the moment. Please check back later.
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<!-- Add CSRF token meta tag if not exists -->
<?php if (!isset($this->metaTags['csrf-token'])): ?>
<meta name="csrf-token" content="<?= csrf_hash() ?>">
<?php endif; ?>

<script>
// Function to show alert message
function showAlert(message, type = 'success') {
    // Remove any existing alerts first
    $('.alert-dismissible').remove();

    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    // Insert after the page title
    $('h3').after(alertHtml);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        $(`.alert`).fadeOut(150, function() {
            $(this).remove();
        });
    }, 5000);
}

// (duplicate showAlert removed)

// Function to get CSRF token
function getCsrfToken() {
    return {
        name: '<?= csrf_token() ?>',
        hash: '<?= csrf_hash() ?>'
    };
}

// Function to update CSRF token in form
function updateCsrfToken() {
    const csrf = getCsrfToken();
    $('input[name="' + csrf.name + '"]').val(csrf.hash);
    $('meta[name="csrf-token"]').attr('content', csrf.hash);
    return csrf;
}

// Handle enrollment when the document is ready
$(document).ready(function() {
    // Handle enroll button click
    $(document).on('click', '.enroll-btn', function(e) {
        e.preventDefault();
        
        const $button = $(this);
        const courseId = $button.data('course-id');
        const $card = $button.closest('.card');
        const originalHtml = $button.html();
        
        if (!courseId) {
            showAlert('Error: Invalid course information', 'danger');
            return;
        }
        
        // Show loading state
        $button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Enrolling...');
        
        // Get and update CSRF token
        const csrf = updateCsrfToken();
        
        // Prepare form data
        const formData = new FormData();
        formData.append('course_id', courseId);
        formData.append(csrf.name, csrf.hash);
        
        // Send AJAX request via $.post to unified route
        const postData = {};
        postData['course_id'] = courseId;
        postData[csrf.name] = csrf.hash;
        $.post('<?= site_url('student/enroll') ?>', postData, function(response) {
            if (response && response.success) {
                // Update button UI
                $button.html('<i class="fas fa-check-circle me-1"></i> Enrolled')
                       .removeClass('btn-primary')
                       .addClass('btn-success')
                       .prop('disabled', true);

                // Show success message
                showAlert(response.message || 'Successfully enrolled in the course!', 'success');

                // Update CSRF token from response
                if (response.csrf && response.csrf.hash) {
                    updateCsrfToken(response.csrf.hash);
                }
            } else {
                const errorMsg = (response && response.message) || 'Failed to enroll. Please try again.';
                showAlert(errorMsg, 'danger');
                $button.prop('disabled', false).html(originalHtml);
            }
        }, 'json').fail(function(xhr) {
            let errorMsg = 'An error occurred while processing your request.';
            try {
                const response = JSON.parse(xhr.responseText);
                errorMsg = response.message || errorMsg;
            } catch (_) {
                errorMsg = 'Network error. Please check your connection and try again.';
            }
            showAlert(errorMsg, 'danger');
            $button.prop('disabled', false).html(originalHtml);
        });
    });
});
</script>

<style>
.card {
    transition: all 0.3s ease;
    border: 1px solid rgba(0,0,0,.125);
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,.1);
}
.card-title {
    color: #2c3e50;
    font-size: 1.1rem;
    margin-bottom: 0.75rem;
}
.card-text {
    font-size: 0.9rem;
    min-height: 60px;
}
.badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
}
</style>
<?= $this->endSection(); ?>
