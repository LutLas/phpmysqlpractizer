<?php if ($user): ?>

    <div class="centermaster">
    <h3>Hello, <?=$user->name?></h3>
    <p>You have access to the MasteredSite Music Database</p>
    </div>

<?php else: ?>

    <div class="centermaster">
    <h3>Hello, There!</h3>
    <p>Welcome to the MasteredSite Music Database</p>
    </div>

<?php endif; ?>