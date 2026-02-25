<?php require 'config.php';
$currentStep=1;
$_SESSION['onboarding'] = $_SESSION['onboarding'] ?? [];
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';
  $confirm  = $_POST['confirm_password'] ?? '';
  if ($username==='' || $password==='') flash('error','Username and password are required.');
  elseif ($password !== $confirm) flash('error','Passwords do not match.');
  elseif (find_user_by_username($username)) flash('error','Username already exists.');
  else {
    $_SESSION['onboarding']['account'] = ['username'=>$username,'password'=>$password];
    header('Location: step2_personal.php'); exit;
  }
}
$pageTitle='Step 1 - Account'; include 'header.php'; include 'wizard_nav.php';
$data = $_SESSION['onboarding']['account'] ?? []; ?>
<div class="card shadow-sm"><div class="card-body p-4">
<h2 class="h4">Step 1: Username & Password</h2>
<form method="post">
  <div class="mb-3"><label class="form-label">Username</label><input name="username" class="form-control" required value="<?= e($data['username']??'') ?>"></div>
  <div class="mb-3"><label class="form-label">Initial Password</label><input type="password" name="password" class="form-control" required></div>
  <div class="mb-3"><label class="form-label">Confirm Password</label><input type="password" name="confirm_password" class="form-control" required></div>
  <div class="d-flex justify-content-end"><button class="btn btn-primary">Next</button></div>
</form></div></div>
<?php include 'footer.php'; ?>
