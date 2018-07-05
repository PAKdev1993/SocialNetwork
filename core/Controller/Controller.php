<?php

namespace core\Controller;



class Controller
{
    /**
     *  Defines by AppController
     */
    protected $langFile;
    protected $we_langsArray;
    protected $langErrorFiles;
    protected $langFooter;

    protected $viewPath;
    protected $viewPathAdmin;

    protected $displayPath;
    protected $displayPathAdmin;

    protected $defaultMyTemplate;
    protected $defaultTemplate;
    protected $adminTemplate;

    protected $myself;
    protected $UserToDisplay;

    protected function render($view, $variables = [], $temp)
    {
        ob_start();
        extract($variables);

        if($temp == $this->adminTemplate)
        {
            require_once($this->viewPathAdmin . $view . "index.php");
        }
        if($temp == $this->defaultTemplate)
        {
            //var_dump($temp . $view . "index.php");
            //die('ok');
            require_once($this->viewPath . $view . "index.php");
        }

        $content = ob_get_clean();

        require_once($temp . $view . "index.php");
    }
}