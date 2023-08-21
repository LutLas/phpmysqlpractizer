<div style="margin-top: 10px;">
    <?php foreach ($categories as $category): ?>
      <a class="navmaster2" href="/joke/list/<?=$category->id?>"><?=$category->name?></a>
    <?php endforeach; ?>
<div>

<div class="box-container">

  <?php foreach ($jokes as $joke) : ?>
    <?php if ($joke->approved && !$joke->archived): ?>
      <div class="box">
        
        <div class="title"><?= htmlspecialchars($joke->joketitle, ENT_QUOTES,
              'UTF-8'); ?></div>

          <?php if (!empty($joke->albumcover)): ?>
          <img src="<?= $joke->albumcover; ?>" alt="" class="album">
          <?php else: ?>
          <img src="/../assets/images/disc.png" alt="" class="album">
          <?php endif; ?>
        
          <div class="name"><?= htmlspecialchars($joke->artistname, ENT_QUOTES,
              'UTF-8');?></div>
          <div class="artist"><?= htmlspecialchars($joke->albumname, ENT_QUOTES,
              'UTF-8'); ?></div>

          <?= (new \Generic\Markdown($joke->joketext))->toHtml();?>

          (by <a href="mailto:<?php
            echo htmlspecialchars($joke->getAuthor()->email, ENT_QUOTES,
              'UTF-8'); ?>"><?php
            echo htmlspecialchars($joke->getAuthor()->name, ENT_QUOTES,
              'UTF-8'); ?></a> on <?php
              $date = new DateTime($joke->jokedate);
              
              echo $date->format('jS F Y H:i:s');
              ?> UTC)
          <?php if ($user): ?>
                <?php if (empty($joke) || $user->id == $joke->authorid || $user->hasPermission(\Jokessite\Entity\Author::EDIT_JOKE)): ?>
                      <div style="margin-top: 10px;">
                          <a class="navmasterYellow" href="/joke/edit/<?= $joke->id?>">Edit</a>
                      </div>
                <?php endif; ?>
                <?php if ($user->id == $joke->authorid || $user->hasPermission(\Jokessite\Entity\Author::DELETE_JOKE)): ?>
                  <div style="margin-top: 8px;padding-bottom: 8px;">
                      <form action="/joke/delete" method="post">
                        <input hidden type="text" name="jokeid" id="jokeid" value="<?= $joke->id ?>">
                        <input class="navmasterRed" type="submit" name="submit" value="Delete">
                      </form>
                </div>
                <?php endif; ?>
          <?php endif; ?>
          <div class="flex">
          <div class="play" data-src="<?= $joke->song; ?>"><i class="fas fa-play"></i><span>play</span></div>
          <!-- <a href="<?= $joke->song; ?>" download><i class="fas fa-download"></i><span>download</span></a> -->
        </div>
      </div>
    <?php endif; ?>
  <?php endforeach; ?>

</div>

Select Page:
<?php
// Calculate the number of pages
$numPages = ceil($totalJokes/10);

for ($i = 1; $i <= $numPages; $i++):
  if ($i == $currentPage):?>
    <a class="navmaster2" href="/joke/list?page=<?=$i?>"><?=$i?></a>
  <?php else: ?>
    <a class="navmasterBlue" href="/joke/list/<?=$categoryId?>/<?=$i?>"><?=$i?></a>
  <?php endif; ?>
<?php endfor; ?>