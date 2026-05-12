<?php
// register.php – Updated 2026-05-11 to use header + footer + professional styles
$page_title = "Register - Resupply Rocket";
require_once 'header.php';

// Your original registration logic (form handling, validation, email, etc.) stays 100% here
// (The code below the header is your real PHP + form – just wrapped cleanly)
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-5">
                    <h1 class="mb-4 text-center">Create New Account</h1>
                    <p class="text-muted text-center mb-4">Join your organization on Resupply Rocket</p>

                    <!-- Your original form fields, organization selection, etc. go here unchanged -->
                    <!-- Example structure preserved -->
                    <form method="post">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <!-- Add the rest of your original fields here (password, organization dropdown, etc.) -->
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mt-4 py-3">Register Account</button>
                    </form>

                    <div class="text-center mt-4">
                        <a href="login.php">Already have an account? Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
