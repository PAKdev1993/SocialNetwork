<?php

namespace app\Displays\SearchPage\searchBar;

use app\Displays\SearchPage\displaySearchResults;

class displaySearchBar extends displaySearchResults
{
    private $searchString;
    protected $langFile;

    public function __construct($searchString = false)
    {
        parent::__construct();
        //cas ou la recherche est submit par appui sur la touche entrée
        if(empty($_POST)) $this->searchString = $_GET['tosearch'];
        //cas ou la recherche est lancée par show more...
        else{
            //cas ou la recherche est faite depuis la version PC
            if(isset($_POST["keyword_searchbar_header"])) $this->searchString = $_POST["keyword_searchbar_header"];
            //cas ou la recherche est faite depuis la version MOBILE
            else $this->searchString = $_POST["keyword_searchbar_header_mobile"];
        }
    }

    public function showLeftPart()
    {
        return '<div class="search-bar-left-part">                    
                </div>';
    }

    public function showRightPart()
    {
        return "<div class='search-bar-right-part'>
                     <input type='text' name='keyword-searchbar' placeholder='". $this->langFile[$this->pageName]->placeholder_keyword ."' class='input' value='". $this->searchString ."'/>
                </div>";
    }

    public function showBody()
    {
        return '<div class="aside-left-bloc bloc search-bar">
                    '. $this->showLeftPart() .'
                    '. $this->showRightPart() .'
                </div>';
    }

    public function show()
    {
        return $this->showBody();
    }
}