<?php
require '../includes/auth_check.php';
require '../config/db.php';

// If a search term was submitted, filter by it. Otherwise show all books.
$search = trim($_GET['search'] ?? '');

if ($search !== '') {
    // LIKE with % wildcards matches the search term anywhere in the
    // title or author. Still a prepared statement - the search box
    // is user input, and user input is never trusted directly in SQL.
    $stmt = $pdo->prepare(
        "SELECT books.*, categories.name AS category_name
         FROM books
         JOIN categories ON books.category_id = categories.id
         WHERE books.title LIKE ? OR books.author LIKE ?
         ORDER BY books.title ASC"
    );
    $likeTerm = '%' . $search . '%';
    $stmt->execute([$likeTerm, $likeTerm]);
} else {
    $stmt = $pdo->query(
        "SELECT books.*, categories.name AS category_name
         FROM books
         JOIN categories ON books.category_id = categories.id
         ORDER BY books.title ASC"
    );
}

$books = $stmt->fetchAll();

require '../includes/header.php';
?>

<h1>Books</h1>
<a href="add.php" class="btn">+ Add Book</a>

<form method="GET" action="list.php" class="search-form">
    <input type="text" name="search" placeholder="Search by title or author..."
           value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit" class="btn">Search</button>
    <?php if ($search !== ''): ?>
        <a href="list.php" class="btn btn-secondary">Clear</a>
    <?php endif; ?>
</form>

<table class="data-table">
    <thead>
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Category</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($books) === 0): ?>
            <tr><td colspan="5">No books found.</td></tr>
        <?php endif; ?>

        <?php foreach ($books as $book): ?>
            <tr>
                <td><?php echo htmlspecialchars($book['title']); ?></td>
                <td><?php echo htmlspecialchars($book['author']); ?></td>
                <td><?php echo htmlspecialchars($book['category_name']); ?></td>
                <td>
                    <span class="status status-<?php echo htmlspecialchars($book['status']); ?>">
                        <?php echo htmlspecialchars($book['status']); ?>
                    </span>
                </td>
                <td>
                    <a href="edit.php?id=<?php echo (int)$book['id']; ?>">Edit</a>
                    |
                    <a href="delete.php?id=<?php echo (int)$book['id']; ?>"
                       onclick="return confirm('Delete this book?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require '../includes/footer.php'; ?>
