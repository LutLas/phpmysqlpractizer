<?php
namespace Jokessite\Entity;
use Generic\DatabaseTable;

class Category {
    public $id;
    public $name;

    public function __construct(private ?DatabaseTable $jokesTable, private ?DatabaseTable $jokeCategoriesTable) {
    }

    public function getJokes(int $limit = 0, int $offset = 0) {
        $jokeCategories = $this->jokeCategoriesTable->findGeneric('categoryid', $this->id, null, $limit);

        $jokes = [];

        foreach ($jokeCategories as $jokeCategory) {
            $joke = $this->jokesTable->findGeneric('id', $jokeCategory->jokeid)[0] ?? null;
            if ($joke) {
                $jokes[] = $joke;
            }
        }

        usort($jokes, [$this, 'sortJokes']);

        return $jokes;
    }

    public function getNumJokes() {
        $queryData = [
            'categoryid' => $this->id
        ];
        return $this->jokeCategoriesTable->totalGeneric($queryData);
    }
    
    private function sortJokes($a, $b) {
        $aDate = new \DateTime($a->jokedate);
        $bDate = new \DateTime($b->jokedate);
    
        if ($aDate->getTimestamp() == $bDate->getTimestamp()) {
        return 0;
        }
    
        return $aDate->getTimestamp() > $bDate->getTimestamp() ? -1 : 1;
    }
}