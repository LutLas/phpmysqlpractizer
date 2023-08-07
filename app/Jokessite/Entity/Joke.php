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

    public function __construct(private DatabaseTable $authorsTable, private DatabaseTable $jokeCategoriesTable) {
    }

    public function getAuthor() {
        
        if (empty($this->author)) {
          $this->author = $this->authorsTable->findGeneric('id', $this->authorid)[0];
        }
      
        return $this->author;
    }
    
    public function addCategory($categoryId) {
      $jokeCat = ['jokeid' => $this->id, 'categoryid' => $categoryId];
    
      $this->jokeCategoriesTable->saveGeneric($jokeCat);
    }

    public function hasCategory($categoryId):bool {
      $jokeCategories = $this->jokeCategoriesTable->findGeneric('jokeid', $this->id);
    
      foreach ($jokeCategories as $jokeCategory) {
        if ($jokeCategory->categoryid == $categoryId) {
          return true;
        }
      }
      return false;
    }
}