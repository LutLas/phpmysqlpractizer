<?php 
namespace Jokessite\Entity;
use Generic\DatabaseTable;

class Author {
  const EDIT_JOKE = 1;
  const LIST_CATEGORIES = 2;
  const EDIT_CATEGORY = 4;
  const APPROVE_CATEGORY = 8;
  const APPROVE_JOKE = 16;
  const DELETE_CATEGORY = 32;
  const DELETE_JOKE = 64;
  const EDIT_USER_ACCESS = 128;
  public int $id;
  public string $name;
  public string $email;
  public string $password;
  public int $permissions;
  public bool $verified;

  public function __construct(private DatabaseTable $jokesTable) {

  }

  public function getJokes() {
      return $this->jokesTable->findGeneric('authorid', $this->id);
  }

  public function addJoke(array $joke) {
    // set the `authorId` in the new joke to the id stored in this instance
    $joke['authorid'] = $this->id;
  
    return $this->jokesTable->saveGeneric($joke);
  }

  public function hasPermission(int $permission) {
    return $this->permissions >= $permission;
  }
}