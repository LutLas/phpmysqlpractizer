<?php
namespace Jokessite\Controllers;
use Generic\DatabaseTable;
class Category {
    public function __construct(private DatabaseTable $categoriesTable) {

    }

    public function edit(?string $id = null) {

        if (isset($id)) {
          $category = $this->categoriesTable->findGeneric('id', $id)[0];
        }
      
        return ['template' => 'editcategory.html.php',
          'title' =>  'Edit Category',
          'heading' => 'Category Modification Page',
          'variables' => [
            'category' => $category ?? null
          ]
        ];
    }
    
    public function editSubmit() {
        $category = $_POST['category'];
      
        $this->categoriesTable->saveGeneric($category);
      
        header('location: /category/list');
    }

    public function list() {
        return ['template' => 'categories.html.php',
          'title' => 'Joke Categories',
          'heading' => 'List of Categories',
          'variables' => [
            'categories' => $this->categoriesTable->findAllGeneric()
          ]
        ];
    }

    public function deleteSubmit() {
        $this->categoriesTable->deleteGeneric('id', $_POST['id']);
      
        header('location: /category/list');
    }      
}