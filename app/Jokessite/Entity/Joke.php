<?php
namespace Jokessite\Entity;
use Generic\DatabaseTable;
use Jokessite\Entity\Author;

class Joke {
    public int $id;
    public int $authorid;
    public string $jokedate;
    public string $joketext;
    private ?Author $author;

    public function __construct(private DatabaseTable $authorsTable) {
    }

    public function getAuthor() {
        
        if (empty($this->author)) {
          $this->author = $this->authorsTable->findGeneric('id', $this->authorid)[0];
        }
      
        return $this->author;
    }
}