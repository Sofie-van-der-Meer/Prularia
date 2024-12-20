<!DOCTYPE html>
<html lang="nl">

<head>
    <?php include_once 'App/Views/components/head.php'; ?>
    <link rel="stylesheet" href="./public/css/login.css">
    <title><?= $title; ?></title>
</head>

<body>

    <?php require_once 'App/Views/components/header.php'; ?>


    <div class="login-container">
        <h1>Login to Prularia</h1>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required
                    value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="primary-button">Login</button>
        </form>
        <div class="action-links">
            <a href="#" class="secondary-button">Wachtwoord vergeten?</a>
            <a href="registrationController.php" class="secondary-button">Registreren</a>
        </div>
    </div>
    <!-- Footer -->
    <?php require_once 'App/Views/components/footer.php'; ?>
</body>

</html>