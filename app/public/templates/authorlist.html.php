<?php if ($user): ?>
  <?php foreach ($authors as $author) : ?>
    <blockquote class="blockquoter">
              <div style="margin-top: 2px;">
                <?=htmlspecialchars($author->name, ENT_QUOTES, 'UTF-8')?>
              </div>
              <div>
              <a href="mailto:<?php
                echo htmlspecialchars($author->email, ENT_QUOTES,'UTF-8'); ?>">
                <?php
                echo htmlspecialchars($author->email, ENT_QUOTES,'UTF-8'); ?></a>
              </div>
              <?php if ($user->hasPermission(\Jokessite\Entity\Author::EDIT_USER_ACCESS)): ?>
                    <div style="margin-top: 8px;padding-bottom: 10px;">
                        <a class="navmasterYellow" href="/author/permissions/<?= $author->id?>">Edit Permissions</a>
                    </div>
              <?php endif; ?>
    </blockquote>
  <?php endforeach; ?>
<?php endif; ?>