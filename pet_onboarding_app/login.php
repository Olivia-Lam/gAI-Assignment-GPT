<?php require 'config.php';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';
  $user = find_user_by_username($username);
  if ($user && password_verify($password, $user['password_hash'])) {
    $_SESSION['user_id'] = $user['id'];
    header('Location: dashboard.php'); exit;
  }
  flash('error', 'Invalid username or password.');
}
$pageTitle='Login'; include 'header.php'; ?>
<div class="row justify-content-center"><div class="col-md-6">
  <div class="card shadow-sm"><div class="card-body p-4">
    <h2 class="h4 mb-3">Login</h2>
    <form method="post">
      <div class="mb-3"><label class="form-label">Username</label><input name="username" class="form-control" required></div>
      <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
      <button class="btn btn-primary">Login</button>
      <a href="step1_account.php" class="btn btn-link">New user? Start onboarding</a>
    </form>
  </div></div>
</div></div>
<?php include 'footer.php'; ?>
