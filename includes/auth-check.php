<?php
/**
 * Protects a page from being viewed by anyone who is not logged in.
 *
 * Usage: put this at the very top of every protected page, BEFORE
 * any HTML is printed:
 *
 *     require '../includes/auth_check.php';
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    // Not logged in - send them to the login page instead.
    header('Location: /library-system/auth/login.php');
    exit; // always exit after a redirect, or the rest of the page still runs
}
