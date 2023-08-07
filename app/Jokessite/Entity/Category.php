<?php
namespace Jokessite\Entity;
use Generic\DatabaseTable;

class Category {
    public $id;
    public $name;

    public function __construct(private ?DatabaseTable $jokesTable, private ?DatabaseTable $jokeCategoriesTable) {
    }

    public function getJokes() {
        $jokeCategories = $this->jokeCategoriesTable->findGeneric('categoryid', $this->id);

        $jokes = [];

        foreach ($jokeCategories as $jokeCategory) {
            $joke = $this->jokesTable->findGeneric('id', $jokeCategory->jokeid)[0] ?? null;
            if ($joke) {
                $jokes[] = $joke;
            }
        }

        return $jokes;
    }
}