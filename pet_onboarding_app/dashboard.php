<?php require 'config.php'; require_login();
$me = current_user();
$allUsers = read_csv_assoc(USERS_CSV);
$pageTitle='Dashboard'; include 'header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Community Members</h1>
  <a class="btn btn-primary" href="edit_profile.php">Edit My Profile</a>
</div>
<div class="row g-3">
<?php foreach ($allUsers as $u): $pets = get_pets_by_user_id($u['id']); ?>
  <div class="col-md-6 col-lg-4">
    <div class="card h-100 shadow-sm">
      <div class="card-body">
        <div class="d-flex align-items-center gap-3 mb-2">
          <?php if (!empty($u['profile_photo'])): ?>
            <img src="uploads/profiles/<?= e($u['profile_photo']) ?>" class="avatar-thumb" alt="avatar">
          <?php else: ?><div class="avatar-thumb d-flex align-items-center justify-content-center">🐾</div><?php endif; ?>
          <div>
            <h2 class="h5 mb-0"><?= e($u['full_name'] ?: $u['username']) ?></h2>
            <div class="text-muted small">@<?= e($u['username']) ?></div>
          </div>
        </div>
        <div class="small mb-2">Contact: <?= e($u['email']) ?><?= $u['phone'] ? ' · '.e($u['phone']) : '' ?></div>
        <div><strong>Pets (<?= count($pets) ?>)</strong></div>
        <ul class="mb-0 ps-3 small">
          <?php foreach ($pets as $p): ?><li><?= e($p['pet_name']) ?><?= $p['breed'] ? ' ('.e($p['breed']).')' : '' ?><?= $p['age'] !== '' ? ', age '.e($p['age']) : '' ?></li><?php endforeach; ?>
          <?php if (!$pets): ?><li>No pets listed yet.</li><?php endif; ?>
        </ul>
      </div>
    </div>
  </div>
<?php endforeach; ?>
</div>
<?php include 'footer.php'; ?>
