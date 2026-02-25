<?php require 'config.php';
if (empty($_SESSION['onboarding']['personal'])) { header('Location: step2_personal.php'); exit; }
$currentStep=3;
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $existing = $_SESSION['onboarding']['profile_photo'] ?? null;
  $uploaded = save_uploaded_image($_FILES['profile_photo'] ?? null, UPLOAD_DIR_PROFILES, 'profile_');
  $_SESSION['onboarding']['profile_photo'] = $uploaded ?: $existing;
  header('Location: step4_pet_info.php'); exit;
}
$pageTitle='Step 3 - Profile Photo'; include 'header.php'; include 'wizard_nav.php';
$photo = $_SESSION['onboarding']['profile_photo'] ?? null; ?>
<div class="card shadow-sm"><div class="card-body p-4">
<h2 class="h4">Step 3: Profile Photo</h2>
<form method="post" enctype="multipart/form-data">
  <div class="mb-3"><label class="form-label">Upload Profile Photo (optional)</label><input type="file" name="profile_photo" accept="image/*" class="form-control"></div>
  <?php if ($photo): ?><img class="profile-avatar mb-3" src="uploads/profiles/<?= e($photo) ?>" alt="Profile photo"><?php endif; ?>
  <div class="d-flex justify-content-between"><a class="btn btn-outline-secondary" href="step2_personal.php">Previous</a><button class="btn btn-primary">Next</button></div>
</form></div></div>
<?php include 'footer.php'; ?>
