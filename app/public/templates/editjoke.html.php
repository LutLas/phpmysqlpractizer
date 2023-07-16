<form style="align-items: center; justify-content: center; display: flex;" action="" method="post">
  <input type="hidden" name="jokeid" value="<?=$joke['id'] ?? ''?>">
    <table >
        <thead>
            <th>
                <label for="joketext">Edit your joke here:</label>
            </th>
        </thead>
        <tbody>
            <td>
                <textarea id="joketext" name="joketext" rows="3" cols="30"><?= $joke['joketext'] ?? ''?></textarea>
            </td>
        </tbody>
        <tfoot>
            <td>
                <input type="submit" name="submit" value="Save">
            </td>
        </tfoot>
    </table>
</form>
