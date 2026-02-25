<?php require 'config.php';
if (!empty($_SESSION['user_id'])) { header('Location: dashboard.php'); exit; }
$pageTitle='Welcome'; include 'header.php'; ?>
<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <h1 class="h3">Join our Pet Lovers Community 🐶🐱</h1>
        <p class="text-muted">A simple onboarding wizard built with PHP + Bootstrap + CSV flat-file storage.</p>
        <div class="d-flex gap-2">
          <a href="step1_account.php" class="btn btn-primary">Start Onboarding</a>
          <a href="login.php" class="btn btn-outline-secondary">Login</a>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>
