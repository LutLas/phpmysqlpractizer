<?php if (empty($category->id) || !empty($category->id)): ?>
<form style="align-items: center; justify-content: center; display: flex;" action="" method="post">
  <input type="hidden" name="category[id]" value="<?= $category->id ?? ''?>">
    <table >
        <thead>
            <th>
                <label for="name">Edit Category Name Here:</label>
            </th>
        </thead>
        <tbody>
            <td>
                <input id="name" name="category[name]" value="<?= $category->name ?? ''?>" />
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

<p>Only Admins Can Edit Categories.</p>

<?php endif; ?>
