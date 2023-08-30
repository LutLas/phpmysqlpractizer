<?php
namespace Jokessite\Controllers;
//use Generic\Authentication;
class Disclaimer {
    public function __construct() {

    }

    public function index() {

      $miniheading = "Just So You Know";
      
     $disclaimerTexts = [
      "The audio content avaliable on this website belong's to the named or titled authors, artists, producers or contributors respectively. www.MasteredSite.com does not claim ownership of any licenced or unlicenced entity whatsoever used without permission.",
     "Under no circumstances shall www.MasteredSite.com, it's affiliates or partners be liable for any indirect, incidental, consequential, special or exemplary damages arising out of or in connection with access or use of or inability to access or use this website and any third party content and services, whether or not the damages were foreseeable and whether or not this website owner was advised of the possibility of such damages. The foregoing limitations will apply even if applied remedies fail of it's essential purpose.",
     "This website is not responsible for any errors or omissions, or for the results obtained from the use of this information. All information on this website is provided \"as is\", with no guarantee of completeness, accuracy, timeliness or of the results obtained from the use of this information. None of the authors, contributors, administrators, vandals, or anyone else connected with this website, in any way whatsoever, can be responsible for your use of the information contained in or linked to www.MasteredSite.com.",
     "The content on this website is distributed without profit to those who have expressed a prior interest in receiving the included information for liesure purposes, however individuals interested in purchasing said content may try to do so by contacting the respective owner.",
     "We thank you for your support, please use this website accordingly"
    ];

        return ['template' => 'disclaimer.html.php',
          'title' => 'Disclaimer',
          'heading' => 'Disclaimer',
          'variables' => [
            'miniheading' => $miniheading,
            'disclaimerTexts' =>  $disclaimerTexts
          ]
        ];
    } 
}