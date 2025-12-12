<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../config/db.php';

// Protect user
/* if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: /FoodOrderingApp/public/login.php");
    exit;
}
*/

// Fetch current user info
$stmt = $conn->prepare("SELECT name, email , password FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $current = trim($_POST['password1']);
    $password = $_POST['password'];
    if($current === $user['password']){
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $email, $hash, $_SESSION['user_id']);
        $stmt->execute();

        $success = "Profile updated successfully!";
    }else{
        $success = 'password mismatch';  
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/../components/nav.php'; ?>  
<div class="container my-5">
    <h2>Edit Profile</h2>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="post" action="edit_profile.php">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name"
                   value="<?php echo htmlspecialchars($user['name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email"
                   value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label" >current Password</label>
            <input type = "password" class = "form-control" id = "password" name = "password1">
        </div>
          <div class="mb-3">
            <label for="password" class="form-label" > New Password</label>
            <input type = "password" class = "form-control" id = "password" name = "password">
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </form>
</div>
</body>
</html>
