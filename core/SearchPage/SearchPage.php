<?php

namespace core\SearchPage;

use app\Displays\SearchPage\displaySearchResults;
use core\Session\Session;

class SearchPage
{
    private $currentUser;

    public function __construct()
    {
        $this->currentUser = Session::getInstance()->read('auth');
    }

    public function getSearchBar()
    {
        $display = new displaySearchResults();
        return $display->showSearchBar();
    }
}