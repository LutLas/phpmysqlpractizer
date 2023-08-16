<?php if (empty($joke->id) || $user->id == $joke->authorid || $user->hasPermission(\Jokessite\Entity\Author::EDIT_JOKE)): ?>
<section class="form-container">
<form class="centermaster" action="" method="post">
  <input type="hidden" name="joke[id]" value="<?= $joke->id ?? ''?>">
                <label for="joketext">Edit Your Joke Here:</label>
                <textarea style="background-color: #a0be9d;" id="joketext" name="joke[joketext]" rows="3" cols="30"><?= $joke->joketext ?? ''?></textarea>
                <?php if (!empty($categories)): ?> 
                    <p>Select Genre</p>
                    <?php foreach ($categories as $category): ?>
                        <?php if ($joke && $joke->hasCategory($category->id)): ?>
                            <input type="checkbox" checked name="category[]" value="<?= $category->id ?>" /> 
                        <?php else: ?>
                            <input type="checkbox" name="category[]" value="<?=$category->id?>" /> 
                        <?php endif; ?>
                        
                            <label><?= $category->name ?></label>
                    <?php endforeach; ?>
                <?php endif; ?>
                <div style="margin-top: 8px;">
                    <input class="navmaster2" type="submit" name="submit" value="Save">
                </div>
</form>
</section>
<?php else: ?>

<p>You may only edit jokes that you posted.</p>

<?php endif; ?>