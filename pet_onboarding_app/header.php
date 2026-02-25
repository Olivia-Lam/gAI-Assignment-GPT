<?php if (!isset($pageTitle)) $pageTitle = 'Pet Lovers Community'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand" href="index.php">🐾 Pet Lovers Community</a>
    <div class="ms-auto d-flex gap-2">
      <?php if (!empty($_SESSION['user_id'])): ?>
        <a class="btn btn-light btn-sm" href="dashboard.php">Dashboard</a>
        <a class="btn btn-outline-light btn-sm" href="logout.php">Logout</a>
      <?php else: ?>
        <a class="btn btn-light btn-sm" href="login.php">Login</a>
        <a class="btn btn-outline-light btn-sm" href="step1_account.php">Join Now</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
<div class="container pb-5">
<?php if ($msg = flash('success')): ?><div class="alert alert-success"><?= e($msg) ?></div><?php endif; ?>
<?php if ($msg = flash('error')): ?><div class="alert alert-danger"><?= e($msg) ?></div><?php endif; ?>
