<?php require 'config.php'; require_login();
$me = current_user();
if (!$me) { session_destroy(); header('Location: login.php'); exit; }

if ($_SERVER['REQUEST_METHOD']==='POST') {
  $action = $_POST['action'] ?? 'save';
  if ($action === 'delete') {
    $users = array_values(array_filter(read_csv_assoc(USERS_CSV), fn($u)=>(string)$u['id'] !== (string)$me['id']));
    write_csv_assoc(USERS_CSV, $users);
    $pets = read_csv_assoc(PETS_CSV);
    $pets = array_values(array_filter($pets, fn($p)=>(string)$p['user_id'] !== (string)$me['id']));
    write_csv_assoc(PETS_CSV, $pets);
    session_destroy();
    flash('success','Profile deleted.');
    header('Location: index.php'); exit;
  }

  $users = read_csv_assoc(USERS_CSV);
  foreach ($users as &$u) {
    if ((string)$u['id'] === (string)$me['id']) {
      $u['full_name'] = trim($_POST['full_name'] ?? '');
      $u['email'] = trim($_POST['email'] ?? '');
      $u['phone'] = trim($_POST['phone'] ?? '');
      if (!empty($_POST['new_password'])) {
        $u['password_hash'] = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
      }
      $uploaded = save_uploaded_image($_FILES['profile_photo'] ?? null, UPLOAD_DIR_PROFILES, 'profile_');
      if ($uploaded) $u['profile_photo'] = $uploaded;
      $u['updated_at'] = date('c');
      break;
    }
  }
  unset($u);
  write_csv_assoc(USERS_CSV, $users);

  $allPets = array_values(array_filter(read_csv_assoc(PETS_CSV), fn($p)=>(string)$p['user_id'] !== (string)$me['id']));
  foreach (($_POST['pets'] ?? []) as $idx=>$pet) {
    $petName = trim($pet['pet_name'] ?? '');
    $breed = trim($pet['breed'] ?? '');
    $age = trim($pet['age'] ?? '');
    $existingPhoto = $pet['existing_photo'] ?? '';
    if ($petName === '' && $breed === '' && $age === '') continue;
    $uploadedPet = save_uploaded_image($_FILES['pet_photos_' . $idx] ?? null, UPLOAD_DIR_PETS, 'pet_');
    $allPets[] = [
      'id' => next_id($allPets),
      'user_id' => $me['id'],
      'pet_name' => $petName,
      'breed' => $breed,
      'age' => $age,
      'pet_photo' => $uploadedPet ?: $existingPhoto
    ];
  }
  write_csv_assoc(PETS_CSV, $allPets);

  flash('success','Profile updated.');
  header('Location: edit_profile.php'); exit;
}

$me = current_user();
$myPets = get_pets_by_user_id($me['id']);
$pageTitle='Edit Profile'; include 'header.php'; ?>
<div class="card shadow-sm"><div class="card-body p-4">
  <h1 class="h4 mb-3">Edit My Profile</h1>
  <form method="post" enctype="multipart/form-data">
    <div class="row g-3">
      <div class="col-md-6"><label class="form-label">Username (locked)</label><input class="form-control" value="<?= e($me['username']) ?>" disabled></div>
      <div class="col-md-6"><label class="form-label">New Password (optional)</label><input type="password" name="new_password" class="form-control"></div>
      <div class="col-md-6"><label class="form-label">Full Name</label><input name="full_name" class="form-control" value="<?= e($me['full_name']) ?>"></div>
      <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?= e($me['email']) ?>"></div>
      <div class="col-md-6"><label class="form-label">Phone</label><input name="phone" class="form-control" value="<?= e($me['phone']) ?>"></div>
      <div class="col-md-6"><label class="form-label">Profile Photo</label><input type="file" name="profile_photo" accept="image/*" class="form-control"></div>
      <?php if (!empty($me['profile_photo'])): ?><div class="col-12"><img src="uploads/profiles/<?= e($me['profile_photo']) ?>" class="profile-avatar"></div><?php endif; ?>
    </div>

    <hr class="my-4">
    <h2 class="h5">Pet Info</h2>
    <div id="petRows">
      <?php if (!$myPets) $myPets=[['pet_name'=>'','breed'=>'','age'=>'','pet_photo'=>'']]; ?>
      <?php foreach ($myPets as $i=>$pet): ?>
      <div class="row g-2 border rounded p-2 mb-2 bg-light">
        <div class="col-md-3"><input class="form-control" name="pets[<?= $i ?>][pet_name]" placeholder="Pet name" value="<?= e($pet['pet_name'] ?? '') ?>"></div>
        <div class="col-md-3"><input class="form-control" name="pets[<?= $i ?>][breed]" placeholder="Breed" value="<?= e($pet['breed'] ?? '') ?>"></div>
        <div class="col-md-2"><input class="form-control" type="number" min="0" name="pets[<?= $i ?>][age]" placeholder="Age" value="<?= e($pet['age'] ?? '') ?>"></div>
        <div class="col-md-3"><input class="form-control" type="file" accept="image/*" name="pet_photos_<?= $i ?>"></div>
        <div class="col-md-1 d-grid"><button type="button" class="btn btn-outline-danger" onclick="this.closest('.row').remove()">×</button></div>
        <input type="hidden" name="pets[<?= $i ?>][existing_photo]" value="<?= e($pet['pet_photo'] ?? '') ?>">
        <?php if (!empty($pet['pet_photo'])): ?><div class="col-12"><img class="pet-thumb" src="uploads/pets/<?= e($pet['pet_photo']) ?>"></div><?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
    <button type="button" class="btn btn-outline-primary mb-3" onclick="addPetRow()">+ Add Another Pet</button>

    <div class="d-flex gap-2">
      <button name="action" value="save" class="btn btn-primary">Save Changes</button>
      <a href="dashboard.php" class="btn btn-outline-secondary">Back</a>
      <button name="action" value="delete" class="btn btn-outline-danger ms-auto" onclick="return confirm('Delete your profile permanently?')">Delete Profile</button>
    </div>
  </form>
</div></div>
<?php include 'footer.php'; ?>
