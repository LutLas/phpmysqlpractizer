<?php if (empty($joke->id) || $user->id == $joke->authorid || $user->hasPermission(\Jokessite\Entity\Author::EDIT_JOKE)): ?>
<section class="form-container">
<form class="centermaster larger" action="" method="post" enctype="multipart/form-data">
  <input type="hidden" name="joke[id]" value="<?= $joke->id ?? $joke['id'] ?? '' ?>">

<label for="joketitle">Song Title:</label>
<input style="background-color: #a0be9d;" type="text" id="joketitle" name="joke[joketitle]" value="<?= $joke->joketitle ?? $joke['joketitle'] ?? '' ?>" REQUIRED>

<label for="artistname">Artist Name:</label>
<input style="background-color: #a0be9d;" type="text" id="artistname" name="joke[artistname]" value="<?= $joke->artistname ?? $joke['artistname'] ?? '' ?>" REQUIRED>

<label for="producername">Producer Name:</label>
<input style="background-color: #a0be9d;" type="text" id="producername" name="joke[producername]" value="<?= $joke->producername ?? $joke['producername'] ?? '' ?>" REQUIRED>

<label for="albumname">Album Name:</label>
<input style="background-color: #a0be9d;" type="text" id="albumname" name="joke[albumname]" value="<?= $joke->albumname ?? $joke['albumname'] ?? '' ?>" REQUIRED>

<label for="tracknumber">Track Number:</label>
<input style="background-color: #a0be9d;" type="number" id="tracknumber" min="1" max="100" name="joke[tracknumber]" value="<?= $joke->tracknumber ?? $joke['tracknumber'] ?? '' ?>" REQUIRED>

<label for="datetimepublished">UTC Date/Time Published:</label>
<input style="background-color: #a0be9d;" type="datetime-local" id="datetimepublished" name="joke[datetimepublished]" value="<?= $joke->datetimepublished ?? $joke['datetimepublished'] ?? '' ?>" REQUIRED>

<label for="joketext">Song Description:</label>
<textarea style="background-color: #a0be9d;" id="joketext" name="joke[joketext]" rows="3" cols="30" REQUIRED><?= $joke->joketext ?? $joke['joketext'] ?? '' ?></textarea>

<?php if (!empty($user)) :?>
    <label for="albumcover">Album/Song Image Upload(<small style="color: blue;">Max Size 16MB</small>):</label>
    <input type="file" id="albumcover" name="joke[]" value="<?= $joke->albumcover ?? $joke['albumcover'] ?? '' ?>" accept="image/*" multiple="multiple" REQUIRED>

    <?php if ($user->hasPermission(\Jokessite\Entity\Author::APPROVE_JOKE)): ?> 
        <label for="song">Admin Audio Upload (<small style="color: blue;">Max Size 42MB</small>):</label>
        <input type="file" id="song" name="joke[]" value="<?= $joke->song ?? $joke['song'] ?? '' ?>" REQUIRED>
    <?php else: ?>
        <label for="song">Audio File Upload(<small style="color: blue;">Max Size 42MB</small>):</label>
        <input type="file" id="song" name="joke[]" value="<?= $joke->song ?? $joke['song'] ?? '' ?>" accept="audio/*" REQUIRED>
    <?php endif; ?>

    <?php if ($user->hasPermission(\Jokessite\Entity\Author::APPROVE_JOKE) && !empty($joke)): ?> 
        <span><label  for="approved">Song Approved: </label><input type="checkbox" name="joke[approved]" id="approved"  <?= $joke->approved ? 'checked' : '' ?> /></span>
    <?php else: ?>
        <input type="hidden"  name="joke[approved]" id="approved" value="" />
    <?php endif; ?>
<?php endif;?>
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