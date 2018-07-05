<?php
namespace app\Displays\Header\Viewer;

use app\Table\ProfileViewers\displayProfileViewers;

class displayUmViewer
{
    private $myself;

    private $profileViewers;

    public function __construct($profileViewers, $myself)
    {
        $this->myself =         $myself;
        $this->profileViewers = $profileViewers;
    }

    public function showViewerPart()
    {
        $display = new displayProfileViewers($this->profileViewers, 'UmViewers');
        return $display->show();
    }

    public function show()
    {
        return $this->showViewerPart();
    }
}