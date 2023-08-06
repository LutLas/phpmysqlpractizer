<?php if (empty($joke->id) || $userId == $joke->authorid): ?>
<form style="align-items: center; justify-content: center; display: flex;" action="" method="post">
  <input type="hidden" name="joke[id]" value="<?= $joke->id ?? ''?>">
    <table >
        <thead>
            <th>
                <label for="joketext">Edit your joke here:</label>
            </th>
        </thead>
        <tbody>
            <td>
                <textarea id="joketext" name="joke[joketext]" rows="3" cols="30"><?= $joke->joketext ?? ''?></textarea>
            </td>
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
