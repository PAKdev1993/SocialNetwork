<?php

namespace app\Displays\SearchPage;

use app\Displays\SearchPage\searchBar\displaySearchBar;
use app\Displays\SearchPage\Results\displayResultUser;
use app\Table\AppDisplay;

class displaySearchResults extends AppDisplay
{
    protected $pageName;

    private $searchString;
    private $todisplay; //= searchPage: display search page results || = searchBar: display search bar result
    private $results;

    public function __construct($todisplay = false, $results = array(), $searchString = false)
    {
        $this->pageName = 'searchpage';
        parent::__construct(false, $this->pageName);
        $this->searchString =   $searchString;
        $this->todisplay =      $todisplay;
        $this->results =        $results;
    }

    public function showSearchBar()
    {
        $display = new displaySearchBar($this->searchString);
        return $display->show();
    }

    public function showBody()
    {
        $content = '';
        if(!empty($this->results))
        {
            foreach($this->results['users'] as $result)
            {
                $display = new displayResultUser($result, $this->todisplay);
                $content = $content . $display->show();
            }
        }
        return $content;
    }

    public function showBodyEmpty()
    {
        return '<div class="bloc-container empty-result">
                     <div class="empty-title-container col-md-12">
                         <h1>'. $this->langFileHeader->text_nothing_found .'</h1>
                     </div>   
                </div>';
    }

    public function showBodySearchBarEmpty()
    {
        return '<div class="searchbar-result col-md-12">
                    <div class="empty-result-container">
                        <p>'. $this->langFile[$this->pageName]->text_nothing_found .'</p>
                    </div>
                </div>';
    }

    public function show()
    {
        if($this->todisplay == 'searchPage')
        {
            if($this->results)
            {
                return $this->showSearchPageResults($this->showBody());
            }
            else{
                return $this->showSearchPageResultsEmpty($this->showBodyEmpty());
            }
        }
        if($this->todisplay == 'searchBar')
        {
            if($this->results)
            {
                return $this->showSearchBarResults($this->showBody());
            }
            else{
                return $this->showSearchBarResultsEmpty($this->showBodySearchBarEmpty());
            }
        }
    }
}