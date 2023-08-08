<?php if ($author): ?>
<form action="" method="post">
<input type="hidden" name="authorid" value="<?= $author->id ?? ''?>">

<div style="margin-top:8px;">
  <?php foreach ($permissions as $name => $value): ?>
  <div>
    <input name="permissions[]" type="checkbox" value="<?=$value?>" <?php if ($author->hasPermission($value)): echo 'checked'; endif; ?> />
    <label><?=ucwords(strtolower(str_replace('_',' ',$name)))?>
  </div>
  <?php endforeach; ?>
  </div>
<div style="margin-top:8px;">
  <input class="navmaster2" type="submit" value="Submit" />
</div>
</form>
<?php else: ?>
    <p>Failed To Load Page</p>
<?php endif; ?>