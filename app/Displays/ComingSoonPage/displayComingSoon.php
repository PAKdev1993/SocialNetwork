<?php
namespace app\Displays\ComingSoonPage;

use app\Table\AppDisplay;

class displayComingSoon extends AppDisplay
{
   public function __construct()
   {
        parent::__construct();
   }

    public function showBody()
    {

    }

    public function show()
    {
        return $this->showComingSoon($this->showBody());
    }
}