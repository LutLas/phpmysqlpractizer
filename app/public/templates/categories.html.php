<a href="/category/edit">Add A New Category</a>

<?php foreach ($categories as $category): ?>
  <blockquote style="align-items: center; justify-content: center; display: flex; border-bottom: 1px solid #ccc;">
  <p>
  <?=htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8')?>
  
  <div>
        <a style="margin-left: 12px;" href="/category/edit/<?= $category->id?>">Edit</a>
  </div>
  <form action="/category/delete" method="post">
    <input type="hidden" name="id" value="<?=$category->id?>">
    <input style="margin-left: 8px;" type="submit" name="submit" value="Delete">
  </form>
  </p>
</blockquote>

<?php endforeach; ?>
