<?php require_once __DIR__ . '/../controllers/resetPasswordController.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | Rodencia Hospital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/bootstrap-icons.css">
    <style>
        body { background-color: #f5f5f5; }
        .bg-green-primary { background-color: #2e7d32 !important; color: white !important; }
        .bg-green-light { background-color: #e8f5e9 !important; }
        .text-green-primary { color: #2e7d32 !important; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-green-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="../utils/index.php">Rodencia Hospital</a>
        </div>
    </nav>

    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-green-primary text-white">
                        <h5 class="mb-0">Reset Your Password</h5>
                    </div>
                    <div class="card-body p-4">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger small"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>
                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success small"><?php echo htmlspecialchars($success); ?></div>
                        <?php endif; ?>

                        <?php if (empty($success)): ?>
                            <p class="text-muted small mb-3">Choose a strong password for your account. Passwords must be at least 8 characters long and include uppercase, lowercase, numbers, and special characters.</p>
                            <form method="POST" action="reset-password.php">
                                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">New Password</label>
                                    <input type="password" name="password" class="form-control" required autocomplete="new-password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}" title="At least 8 characters including uppercase, lowercase, number, and special character.">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Confirm Password</label>
                                    <input type="password" name="confirm_password" class="form-control" required autocomplete="new-password">
                                </div>
                                <button type="submit" class="btn bg-green-primary text-white w-100 py-2 fw-bold">Reset Password</button>
                            </form>
                        <?php endif; ?>

                        <div class="mt-4 text-center">
                            <a href="../utils/index.php#auth-section" class="text-green-primary small">Back to sign in</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
