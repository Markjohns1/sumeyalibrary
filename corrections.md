# PHP Login Error Analysis

Here is exactly what is happening and why, step-by-step:

### 1. The Variable Name Mismatch
In your `config/db.php` file on line 8, you initialize the database connection and assign it to a variable called `$conn`:
```php
$conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
```

However, in `auth/login.php` on line 28, you try to use a variable called `$pdo` to prepare your SQL statement:
```php
$stmt = $pdo->prepare(
    'SELECT id, username, password FROM users WHERE username = ?'
);
```

**Why this fails:** Because `$pdo` was never defined in `login.php` or `db.php`, PHP treats it as `null`. You cannot call the `prepare()` method on `null`, which causes the script to crash immediately (Fatal Error).

**How to fix it:** You need to make sure the variable names match. Either change `$conn` to `$pdo` in `config/db.php`, OR change `$pdo` to `$conn` in `auth/login.php`.

### 2. Missing `exit()` after Redirects
In `auth/login.php`, when the login is successful, you have:
```php
header('Location: ../dashboard/index.php');
exit;
```
You should always use `exit;` or `die();` immediately after a `header()` redirect so the rest of the script doesn't continue executing. 

### What you should do next:
1. Open `auth/login.php` and `config/db.php`.
2. Decide whether you want your database connection variable to be named `$conn` or `$pdo`.
3. Update one of the files so they both use the exact same variable name!
