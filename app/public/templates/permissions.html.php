<?php if ($author): ?>
<form action="" method="post">
<input type="hidden" name="authorid" value="<?= $author->id ?? ''?>">
  <?php foreach ($permissions as $name => $value): ?>
  <div>
    <input name="permissions[]" type="checkbox" value="<?=$value?>" <?php if ($author->hasPermission($value)): echo 'checked'; endif; ?> />
    <label><?=ucwords(strtolower(str_replace('_',' ',$name)))?>
  </div>
  <?php endforeach; ?>

  <input style="margin-top: 8px;" type="submit" value="Submit" />
</form>
<?php else: ?>
    <p>Failed To Load Page</p>
<?php endif; ?>