<?php
namespace Jokessite\Controllers;
use Generic\DatabaseTable;
class Author {
    public function __construct(private DatabaseTable $authorsTable) {

    }
}