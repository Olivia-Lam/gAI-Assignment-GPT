<?php require 'config.php';
if (empty($_SESSION['onboarding']['account']) || empty($_SESSION['onboarding']['personal'])) { header('Location: step1_account.php'); exit; }
$currentStep=5;
$onb = $_SESSION['onboarding'];
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $users = read_csv_assoc(USERS_CSV);
  if (find_user_by_username($onb['account']['username'])) {
    flash('error','That username was taken while you were onboarding. Please choose another.');
    header('Location: step1_account.php'); exit;
  }
  $userId = next_id($users);
  $now = date('c');
  $users[] = [
    'id' => $userId,
    'username' => $onb['account']['username'],
    'password_hash' => password_hash($onb['account']['password'], PASSWORD_DEFAULT),
    'full_name' => $onb['personal']['full_name'] ?? '',
    'email' => $onb['personal']['email'] ?? '',
    'phone' => $onb['personal']['phone'] ?? '',
    'profile_photo' => $onb['profile_photo'] ?? '',
    'created_at' => $now,
    'updated_at' => $now
  ];
  write_csv_assoc(USERS_CSV, $users);

  $petsCsv = read_csv_assoc(PETS_CSV);
  foreach (($onb['pets'] ?? []) as $pet) {
    $petsCsv[] = [
      'id' => next_id($petsCsv),
      'user_id' => $userId,
      'pet_name' => $pet['pet_name'] ?? '',
      'breed' => $pet['breed'] ?? '',
      'age' => $pet['age'] ?? '',
      'pet_photo' => $pet['pet_photo'] ?? ''
    ];
  }
  write_csv_assoc(PETS_CSV, $petsCsv);

  unset($_SESSION['onboarding']);
  $_SESSION['user_id'] = $userId;
  flash('success', 'Welcome! Your profile has been created.');
  header('Location: dashboard.php'); exit;
}
$pageTitle='Step 5 - Confirm'; include 'header.php'; include 'wizard_nav.php'; ?>
<div class="card shadow-sm"><div class="card-body p-4">
<h2 class="h4">Step 5: Confirmation and Save</h2>
<ul class="list-group mb-3">
  <li class="list-group-item"><strong>Username:</strong> <?= e($onb['account']['username']) ?></li>
  <li class="list-group-item"><strong>Name:</strong> <?= e($onb['personal']['full_name'] ?? '') ?></li>
  <li class="list-group-item"><strong>Email:</strong> <?= e($onb['personal']['email'] ?? '') ?></li>
  <li class="list-group-item"><strong>Phone:</strong> <?= e($onb['personal']['phone'] ?? '') ?></li>
  <li class="list-group-item"><strong>Profile Photo:</strong> <?= !empty($onb['profile_photo']) ? 'Uploaded' : 'Not uploaded' ?></li>
  <li class="list-group-item"><strong>Pets:</strong> <?= count($onb['pets'] ?? []) ?></li>
</ul>
<?php if (!empty($onb['pets'])): ?>
<div class="table-responsive mb-3"><table class="table table-sm">
  <thead><tr><th>Pet Name</th><th>Breed</th><th>Age</th><th>Photo</th></tr></thead><tbody>
  <?php foreach ($onb['pets'] as $pet): ?><tr>
    <td><?= e($pet['pet_name'] ?? '') ?></td><td><?= e($pet['breed'] ?? '') ?></td><td><?= e($pet['age'] ?? '') ?></td>
    <td><?php if (!empty($pet['pet_photo'])): ?><img class="pet-thumb" src="uploads/pets/<?= e($pet['pet_photo']) ?>"><?php endif; ?></td>
  </tr><?php endforeach; ?>
  </tbody></table></div>
<?php endif; ?>
<form method="post">
  <div class="d-flex justify-content-between">
    <a class="btn btn-outline-secondary" href="step4_pet_info.php">Previous</a>
    <button class="btn btn-success">Save Profile</button>
  </div>
</form>
</div></div>
<?php include 'footer.php'; ?>
