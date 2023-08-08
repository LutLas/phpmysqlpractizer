<a class="navmaster2" href="/category/edit">Add Category</a>

<?php foreach ($categories as $category): ?>
  <blockquote class="blockquoter">
  <?=htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8')?>
  
  <div style="margin-top: 10px;">
        <a class="navmasterYellow" href="/category/edit/<?= $category->id?>">Edit</a>
  </div>
  <div style="margin-top: 8px;padding-bottom: 8px;">
    <form action="/category/delete" method="post">
      <input type="hidden" name="id" value="<?=$category->id?>">
      <input class="navmasterRed" type="submit" name="submit" value="Delete">
    </form>
  </div>
</blockquote>

<?php endforeach; ?>
