<?php require 'config.php';
if (empty($_SESSION['onboarding']['personal'])) { header('Location: step2_personal.php'); exit; }
$currentStep=4;
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $pets = [];
  foreach (($_POST['pets'] ?? []) as $idx => $pet) {
    $petName = trim($pet['pet_name'] ?? '');
    $breed = trim($pet['breed'] ?? '');
    $age = trim($pet['age'] ?? '');
    if ($petName === '' && $breed === '' && $age === '') continue;
    $uploaded = save_uploaded_image($_FILES['pet_photos_' . $idx] ?? null, UPLOAD_DIR_PETS, 'pet_');
    $pets[] = ['pet_name'=>$petName,'breed'=>$breed,'age'=>$age,'pet_photo'=>$uploaded ?: ''];
  }
  $_SESSION['onboarding']['pets'] = $pets;
  header('Location: step5_confirm.php'); exit;
}
$pets = $_SESSION['onboarding']['pets'] ?? [['pet_name'=>'','breed'=>'','age'=>'','pet_photo'=>'']];
$pageTitle='Step 4 - Pet Info'; include 'header.php'; include 'wizard_nav.php'; ?>
<div class="card shadow-sm"><div class="card-body p-4">
<h2 class="h4">Step 4: Pet Info</h2>
<form method="post" enctype="multipart/form-data">
  <div id="petRows">
    <?php foreach ($pets as $i=>$pet): ?>
    <div class="row g-2 border rounded p-2 mb-2 bg-light">
      <div class="col-md-3"><input class="form-control" name="pets[<?= $i ?>][pet_name]" placeholder="Pet name" value="<?= e($pet['pet_name']??'') ?>"></div>
      <div class="col-md-3"><input class="form-control" name="pets[<?= $i ?>][breed]" placeholder="Breed" value="<?= e($pet['breed']??'') ?>"></div>
      <div class="col-md-2"><input class="form-control" type="number" min="0" name="pets[<?= $i ?>][age]" placeholder="Age" value="<?= e($pet['age']??'') ?>"></div>
      <div class="col-md-3"><input class="form-control" type="file" accept="image/*" name="pet_photos_<?= $i ?>"></div>
      <div class="col-md-1 d-grid"><button type="button" class="btn btn-outline-danger" onclick="this.closest('.row').remove()">×</button></div>
      <?php if (!empty($pet['pet_photo'])): ?><div class="col-12"><img class="pet-thumb" src="uploads/pets/<?= e($pet['pet_photo']) ?>"></div><?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>
  <button type="button" class="btn btn-outline-primary mb-3" onclick="addPetRow()">+ Add Another Pet</button>
  <div class="d-flex justify-content-between"><a class="btn btn-outline-secondary" href="step3_profile_photo.php">Previous</a><button class="btn btn-primary">Next</button></div>
</form></div></div>
<?php include 'footer.php'; ?>
