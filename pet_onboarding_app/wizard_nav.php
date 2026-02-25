<?php
$stepLabels = [1=>'Account',2=>'Personal Info',3=>'Profile Photo',4=>'Pet Info',5=>'Confirm'];
?>
<div class="wizard-steps d-flex gap-2 mb-4">
  <?php foreach($stepLabels as $n=>$label): ?>
    <div class="step <?= ($currentStep==$n?'active':'') ?>"><?= $n ?>. <?= e($label) ?></div>
  <?php endforeach; ?>
</div>
