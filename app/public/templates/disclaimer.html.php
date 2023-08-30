<div class="centermaster">
    <p>---</p>
    <h3><?= $miniheading ?? '' ?></h3>

    <?php foreach ($disclaimerTexts as $key => $value):?> 
        <p style="padding: 8px;"><?= $value ?? '' ?></p>
        <p>---</p>
    <?php endforeach; ?>
</div>