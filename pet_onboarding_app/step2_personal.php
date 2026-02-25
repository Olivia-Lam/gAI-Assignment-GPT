<?php require 'config.php';
if (empty($_SESSION['onboarding']['account'])) { header('Location: step1_account.php'); exit; }
$currentStep=2;
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $_SESSION['onboarding']['personal'] = [
    'full_name' => trim($_POST['full_name'] ?? ''),
    'email' => trim($_POST['email'] ?? ''),
    'phone' => trim($_POST['phone'] ?? '')
  ];
  header('Location: step3_profile_photo.php'); exit;
}
$data = $_SESSION['onboarding']['personal'] ?? [];
$pageTitle='Step 2 - Personal Info'; include 'header.php'; include 'wizard_nav.php'; ?>
<div class="card shadow-sm"><div class="card-body p-4">
<h2 class="h4">Step 2: Personal Info</h2>
<form method="post">
  <div class="mb-3"><label class="form-label">Full Name</label><input name="full_name" class="form-control" required value="<?= e($data['full_name']??'') ?>"></div>
  <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required value="<?= e($data['email']??'') ?>"></div>
  <div class="mb-3"><label class="form-label">Phone</label><input name="phone" class="form-control" value="<?= e($data['phone']??'') ?>"></div>
  <div class="d-flex justify-content-between"><a class="btn btn-outline-secondary" href="step1_account.php">Previous</a><button class="btn btn-primary">Next</button></div>
</form></div></div>
<?php include 'footer.php'; ?>
