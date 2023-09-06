
<div class="noticejoke">
  <div>
    <form action="/joke/search" method="post">
            <input type="hidden" name="pagequery" id="pagequery" value="musiclist" REQUIRED>
            <input type="text" name="songquery" id="songquery" value="<?=$songquery ?? '' ?>" REQUIRED>
      <button class="navmasterJoke fas fa-search" type="submit" name="submit">
    </form>
  </div>
        <?=$jokesnav?>

</div>


        
<div id="ruffle-player-container" class="centermaster ruffle-player-box">
      <div class="ruffle-player-box-close" id="close"></div>
      <div class="ruffle-player-box-title" id="ruffle-player-title"></div>
      <div class="ruffle-player-box-artist" id="ruffle-player-album"></div>
      <div class="ruffle-player-box-name" id="ruffle-player-artist"></div>
      <img id="ruffle-player-cover" style="height: 90px; width:90px;" src="/../assets/images/favicon.png" alt="song's album art cover">
      <audio controls controlsList="noplaybackrate nodownload" id="ruffle-player-embed" class="ruffle-player"></audio>
</div>

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
              'UTF-8'); ?>(prod.<?= htmlspecialchars($joke->producername, ENT_QUOTES,
              'UTF-8'); ?>)</div>

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

          (by <a href="mailto:<?php if (!empty($user) && !empty($user->verified)): ?><?php echo htmlspecialchars($joke->getAuthor()->email, ENT_QUOTES,
              'UTF-8'); ?>
              <?php endif; ?>"><?php
            echo htmlspecialchars($joke->getAuthor()->name, ENT_QUOTES,
              'UTF-8'); ?></a> on <?php
              $date = new DateTime($joke->jokedate);
              
              echo $date->format('jS F Y H:i:s');
              ?> UTC)
          <?php if ($user): ?>
                <?php if (($user->id == $joke->authorid && $user->hasPermission(\Jokessite\Entity\Author::EDIT_JOKE)) || $user->hasPermission(\Jokessite\Entity\Author::APPROVE_JOKE)): ?>
                      <div style="margin-top: 10px;">
                          <a class="navmasterYellow" href="/joke/edit/<?= $joke->id?>">Edit</a>
                      </div>
                <?php endif; ?>
                <?php if ($user->hasPermission(\Jokessite\Entity\Author::DELETE_JOKE)): ?>
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
        </div>
      </div>
    <?php endif; ?>
  <?php endforeach; ?>

</div>