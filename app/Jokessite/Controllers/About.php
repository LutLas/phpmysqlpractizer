<?php
namespace Jokessite\Controllers;
use Generic\Authentication;
class About {
    public function __construct(private Authentication $authentication) {

    }

    public function index() {

      $miniheading = "About Us";

     $aboutTexts = [
      "Currently www.MasteredSite.com is The Ultimate Music Database designed for use by anyone.",
      "This website allows artists, producers and contributors to manage their content for free.",
      "The only requirement is that a user signs up, otherwise Music accessing will be resricted to streaming only."
    ];
    

        return ['template' => 'about.html.php',
          'title' => 'Thank You For Your Support',
          'heading' => 'About MasteredSite',
          'variables' => [
            'miniheading' => $miniheading,
            'aboutTexts' =>  $aboutTexts
          ]
        ];
    } 
}