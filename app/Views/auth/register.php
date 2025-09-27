<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - ITE311 MACA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background-color: #0d6efd;
            border: none;
        }
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        .strength-meter {
            height: 5px;
            margin-top: 5px;
            background: #e9ecef;
            border-radius: 3px;
            overflow: hidden;
        }
        .strength-meter-fill {
            height: 100%;
            width: 0;
            transition: width 0.3s ease, background-color 0.3s ease;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white mb-4">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('/') ?>">
                <i class="fas fa-graduation-cap me-2"></i> ITE311-MACA
            </a>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4">Create Account</h2>
                        
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

                        <?php if (session()->getFlashdata('errors')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                        <li class="small"><?= $error ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form id="registerForm" action="<?= base_url('register') ?>" method="POST" novalidate>
                            <?= csrf_field() ?>
                            
                            <div class="mb-4">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control form-control-lg" id="name" name="name" 
                                       value="<?= old('name') ?>" 
                                       placeholder="Enter your full name" 
                                       required>
                                <div id="nameError" class="error-message">
                                    <?= session()->getFlashdata('errors')['name'] ?? '' ?>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control form-control-lg" id="email" name="email" 
                                       value="<?= old('email') ?>" 
                                       placeholder="Enter your email" 
                                       required>
                                <div id="emailError" class="error-message">
                                    <?= session()->getFlashdata('errors')['email'] ?? '' ?>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-lg" id="password" name="password" 
                                           placeholder="Create a password" 
                                           minlength="8"
                                           maxlength="255"
                                           pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$"
                                           title="Must contain at least one uppercase letter, one lowercase letter, one number, and one special character"
                                           required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="strength-meter mt-2">
                                    <div class="strength-meter-fill" data-strength="0"></div>
                                </div>
                                <small class="form-text text-muted">
                                    Password must be at least 8 characters long and include uppercase, lowercase, number, and special character.
                                </small>
                                <div id="passwordError" class="error-message">
                                    <?= session()->getFlashdata('errors')['password'] ?? '' ?>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password_confirm" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-lg" id="password_confirm" name="password_confirm" 
                                           placeholder="Confirm your password" 
                                           required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#password_confirm">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div id="passwordConfirmError" class="error-message">
                                    <?= session()->getFlashdata('errors')['password_confirm'] ?? '' ?>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Register
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="text-center mt-4">
                        <p class="mb-2">Already have an account? <a href="<?= base_url('login') ?>" class="text-decoration-none">Login here</a></p>
                        <p class="mb-0"><a href="<?= base_url('/') ?>" class="text-decoration-none">Back to Home</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registerForm');
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const passwordConfirmInput = document.getElementById('password_confirm');
            const passwordStrength = document.querySelector('.strength-meter-fill');
            
            // Toggle password visibility
            document.querySelectorAll('.toggle-password').forEach(button => {
                button.addEventListener('click', function() {
                    const target = document.querySelector(this.getAttribute('data-target'));
                    const type = target.getAttribute('type') === 'password' ? 'text' : 'password';
                    target.setAttribute('type', type);
                    this.querySelector('i').classList.toggle('fa-eye');
                    this.querySelector('i').classList.toggle('fa-eye-slash');
                });
            });
            
            // Security patterns
            const patterns = {
                name: /^[\p{L}\s'\-\.]{2,50}$/u,
                email: /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i,
                password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$/
            };
            
            // Malicious patterns to block
            const maliciousPatterns = [
                // SQL Injection patterns
                /([';]|(--[^\r\n]*)|(\/\*[\s\S]*?\*\/)|(\b(ALTER|CREATE|DELETE|DROP|EXEC(UTE){0,1}|INSERT( +INTO){0,1}|MERGE|SELECT|UPDATE|UNION( +ALL){0,1}|INFORMATION_SCHEMA|CHAR\(|0x[0-9a-f]+)\b)/i,
                /['";]\s*OR\s*['"]?\d+['"]?\s*[=<>]\s*\d+/i,
                /['"]\s*=\s*['"]/,
                
                // XSS patterns
                /<[a-z][\s\S]*>/i,
                /javascript:/i,
                /data:/i,
                /vbscript:/i,
                /expression\(/i,
                /eval\(/i,
                /on\w+\s*=/i,
                
                // Other dangerous patterns
                /\x00|\\0|%00|\0/,
                /[\u0000-\u001F\u007F-\u009F\u2000-\u200F\u2028-\u202F\u205F-\u206F\u3000\uFEFF]/,
                /\{\{.*\}\}|\{%[^%]*%\}|\{\{#[^}]*\}\}/,
                
                // Command injection
                /\$\s*\(|`|\|\||&&|\n|\r/,
                
                // File path traversal
                /\.\.(?:\/|\\)|\/etc\/|\/bin\/|\/usr\//i,
                
                // Dangerous functions
                /\b(?:document\.|window\.|eval\(|setTimeout\(|setInterval\(|Function\(|new Function\()/i
            ];
            
            // Check for malicious patterns
            function hasMaliciousPatterns(value) {
                if (!value) return false;
                return maliciousPatterns.some(pattern => pattern.test(value));
            }
            
            // Sanitize input
            function sanitizeInput(value) {
                if (!value) return '';
                return value.toString()
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#x27;')
                    .replace(/\//g, '&#x2F;');
            }
            
            // Validate input against patterns
            function validateInput(input, pattern, errorId, errorMessage) {
                const errorElement = document.getElementById(errorId);
                const value = input.value.trim();
                
                if (hasMaliciousPatterns(value)) {
                    input.classList.add('is-invalid');
                    errorElement.textContent = 'Invalid input detected. Please remove any special characters or scripts.';
                    return false;
                }
                
                if (!pattern.test(value)) {
                    input.classList.add('is-invalid');
                    errorElement.textContent = errorMessage;
                    return false;
                }
                
                // Check for suspicious patterns in name field
                if (input === nameInput) {
                    // Block any HTML tags in name
                    if (/<[a-z][\s\S]*>/i.test(value)) {
                        input.classList.add('is-invalid');
                        errorElement.textContent = 'HTML tags are not allowed in the name field';
                        return false;
                    }
                    
                    // Block numeric-only names
                    if (/^\d+$/.test(value)) {
                        input.classList.add('is-invalid');
                        errorElement.textContent = 'Name cannot be numeric only';
                        return false;
                    }
                    
                    // Block very long repeated characters
                    if (/(.)\1{10,}/.test(value)) {
                        input.classList.add('is-invalid');
                        errorElement.textContent = 'Invalid name format';
                        return false;
                    }
                }
                
                input.classList.remove('is-invalid');
                errorElement.textContent = '';
                return true;
            }
            
            // Check password strength
            function checkPasswordStrength(password) {
                let strength = 0;
                
                // Length check
                if (password.length >= 8) strength += 1;
                if (password.length >= 12) strength += 1;
                
                // Complexity checks
                if (/[a-z]/.test(password)) strength += 1; // Lowercase
                if (/[A-Z]/.test(password)) strength += 1; // Uppercase
                if (/[0-9]/.test(password)) strength += 1; // Numbers
                if (/[^A-Za-z0-9]/.test(password)) strength += 1; // Special chars
                
                // Update strength meter
                const strengthPercent = (strength / 6) * 100;
                passwordStrength.style.width = strengthPercent + '%';
                
                // Set color based on strength
                if (strength <= 2) {
                    passwordStrength.style.backgroundColor = '#dc3545'; // Red
                } else if (strength <= 4) {
                    passwordStrength.style.backgroundColor = '#ffc107'; // Yellow
                } else {
                    passwordStrength.style.backgroundColor = '#198754'; // Green
                }
                
                return strength >= 4; // Minimum strength required
            }
            
            // Validate password confirmation
            function validatePasswordConfirm() {
                const errorElement = document.getElementById('passwordConfirmError');
                
                if (passwordInput.value !== passwordConfirmInput.value) {
                    passwordConfirmInput.classList.add('is-invalid');
                    errorElement.textContent = 'Passwords do not match';
                    return false;
                }
                
                passwordConfirmInput.classList.remove('is-invalid');
                errorElement.textContent = '';
                return true;
            }
            
            // Event listeners for real-time validation
            nameInput.addEventListener('input', () => {
                validateInput(nameInput, patterns.name, 'nameError', 
                    'Please enter a valid name (2-50 characters, letters and spaces only)');
            });
            
            emailInput.addEventListener('input', () => {
                validateInput(emailInput, patterns.email, 'emailError', 
                    'Please enter a valid email address');
            });
            
            passwordInput.addEventListener('input', () => {
                const isValid = validateInput(passwordInput, patterns.password, 'passwordError', 
                    'Minimum 8 characters, at least one uppercase, one lowercase, one number and one special character');
                checkPasswordStrength(passwordInput.value);
                return isValid;
            });
            
            passwordConfirmInput.addEventListener('input', validatePasswordConfirm);
            
            // Form submission
            form.addEventListener('submit', function(event) {
                let isValid = true;
                
                // Validate all fields
                isValid = validateInput(nameInput, patterns.name, 'nameError', 
                    'Please enter a valid name (2-50 characters, letters and spaces only)') && isValid;
                
                isValid = validateInput(emailInput, patterns.email, 'emailError', 
                    'Please enter a valid email address') && isValid;
                
                isValid = validateInput(passwordInput, patterns.password, 'passwordError', 
                    'Minimum 8 characters, at least one uppercase, one lowercase, one number and one special character') && isValid;
                
                // Check password strength
                if (isValid && !checkPasswordStrength(passwordInput.value)) {
                    document.getElementById('passwordError').textContent = 'Please choose a stronger password';
                    isValid = false;
                }
                
                isValid = validatePasswordConfirm() && isValid;
                
                // Prevent form submission if validation fails
                if (!isValid) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    // Sanitize all inputs before submission
                    nameInput.value = sanitizeInput(nameInput.value);
                    emailInput.value = sanitizeInput(emailInput.value);
                }
            });
            
            // Initial password strength check
            checkPasswordStrength('');
        });
    </script>
</body>
</html>
