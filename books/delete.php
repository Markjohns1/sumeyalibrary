<?php
require '../includes/auth_check.php';
require '../config/db.php';
require '../includes/functions.php';

$id = (int)($_GET['id'] ?? 0);

// Don't allow deleting a book that's currently borrowed - it still
// has an open borrow_records row that depends on it.
$check = $pdo->prepare("SELECT status FROM books WHERE id = ?");
$check->execute([$id]);
$book = $check->fetch();

if ($book && $book['status'] === 'borrowed') {
    redirect_with_message(
        'list.php',
        'Cannot delete this book - it is currently borrowed.',
        'error'
    );
}

$stmt = $pdo->prepare('DELETE FROM books WHERE id = ?');
$stmt->execute([$id]);

redirect_with_message('list.php', 'Book deleted.');
