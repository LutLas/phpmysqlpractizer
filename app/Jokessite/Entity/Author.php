<?php 
namespace Jokessite\Entity;
use Generic\DatabaseTable;

class Author {
  public int $id;
  public string $name;
  public string $email;
  public string $password;

  public function __construct(private DatabaseTable $jokesTable) {

  }

  public function getJokes() {
      return $this->jokesTable->findGeneric('authorid', $this->id);
  }

  public function addJoke(array $joke) {
    // set the `authorId` in the new joke to the id stored in this instance
    $joke['authorid'] = $this->id;
  
    $this->jokesTable->saveGeneric($joke);
  }
}