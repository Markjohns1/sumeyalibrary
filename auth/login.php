<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../config/db.php';

// If already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: ../dashboard/index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {

        $error = 'Please enter both a username and a password.';

    } else {

        $stmt = $pdo->prepare(
            'SELECT id, username, password FROM users WHERE username = ?'
        );

        $stmt->execute([$username]);

        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {

            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            header('Location: ../dashboard/index.php');
            exit;

        } else {

            $error = 'Incorrect username or password.';
        }
    }
}

require '../includes/header.php';
?>

<div class="login-wrapper">

    <form class="login-box" method="POST" action="login.php">

        <h1>ABC Community Library</h1>

        <p class="subtitle">Librarian Login</p>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Log In</button>

    </form>

</div>

