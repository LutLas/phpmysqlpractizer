<?php if (empty($joke->id) || $user->id == $joke->authorid || $user->hasPermission(\Jokessite\Entity\Author::EDIT_JOKE)): ?>
<form style="align-items: center; justify-content: center; display: flex;" action="" method="post">
  <input type="hidden" name="joke[id]" value="<?= $joke->id ?? ''?>">
    <table >
        <thead>
            <th>
            </th>
        </thead>
        <tbody>
            <tr>
                <tr>
                    <th>
                <label for="joketext">Edit Your Joke Here:</label>
                    </th>
                </tr>
            <td>
                <textarea id="joketext" name="joke[joketext]" rows="3" cols="30"><?= $joke->joketext ?? ''?></textarea>
            </td>
                <tr>
            <td>
                <p>Select Categories For This Joke:</p>

                <?php foreach ($categories as $category): ?>
                    <?php if ($joke && $joke->hasCategory($category->id)): ?>
                        <input type="checkbox" checked name="category[]" value="<?= $category->id ?>" /> 
                    <?php else: ?>
                        <input type="checkbox" name="category[]" value="<?=$category->id?>" /> 
                    <?php endif; ?>
                    
                        <label><?= $category->name ?></label>
                <?php endforeach; ?>
            </td>
                </tr>
                <tr style="margin-bottom: 8px;">
                    <td>
                        <small></small>
                    </td>
                </tr>
        </tbody>
        <tfoot>
            <td>
                <input type="submit" name="submit" value="Save">
            </td>
        </tfoot>
    </table>
</form>
<?php else: ?>

<p>You may only edit jokes that you posted.</p>

<?php endif; ?>