<?php
require '../includes/auth_check.php';
require '../config/db.php';
require '../includes/functions.php';

// Categories are needed to fill the dropdown on the form.
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);

    if ($title === '' || $author === '' || $category_id === 0) {
        $error = 'Please fill in all fields, including a category.';
    } else {
        $stmt = $pdo->prepare(
            'INSERT INTO books (title, author, category_id, status) VALUES (?, ?, ?, "available")'
        );
        $stmt->execute([$title, $author, $category_id]);

        redirect_with_message('list.php', 'Book added successfully.');
    }
}

require '../includes/header.php';
?>
<h1>Add Book</h1>

<?php if ($error): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<?php if (count($categories) === 0): ?>
    <div class="alert alert-error">
        You need at least one category before adding a book.
        <a href="../categories/add.php">Add one here</a>.
    </div>
<?php else: ?>

<form method="POST" action="add.php" class="data-form">
    <label for="title">Title</label>
    <input type="text" id="title" name="title" required
           value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">

    <label for="author">Author</label>
    <input type="text" id="author" name="author" required
           value="<?php echo htmlspecialchars($_POST['author'] ?? ''); ?>">

    <label for="category_id">Category</label>
    <select id="category_id" name="category_id" required>
        <option value="">-- Select a category --</option>
        <?php foreach ($categories as $category): ?>
            <option value="<?php echo (int)$category['id']; ?>">
                <?php echo htmlspecialchars($category['name']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit" class="btn">Save Book</button>
    <a href="list.php" class="btn btn-secondary">Cancel</a>
</form>

<?php endif; ?>

<?php require '../includes/footer.php'; ?>
