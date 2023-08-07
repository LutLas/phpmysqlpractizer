<table class="tablemaster">
  <thead>
  </thead>

  <tbody>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Edit</th>
    </tr>
    <?php foreach ($authors as $author): ?>
    <tr>
      <td><?=$author->name;?></td>
      <td><?=$author->email;?></td>
      <td><a class="navmasterYellow" href="/author/permissions/<?=$author->id;?>">Edit Permissions</a></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>