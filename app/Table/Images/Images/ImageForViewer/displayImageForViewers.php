<?php


namespace app\Table\Images\Images\ImageForViewer;


class displayImageForViewers
{
    public function __construct($imgObj){
        $this->id       = $imgObj->pk_idimage;
        $this->iduser   = $imgObj->fk_iduser;
        $this->idpost   = $imgObj->fk_idpost;
        $this->name     = $imgObj->name;
        $this->title    = $imgObj->title;
        $this->description = $imgObj->description;
        $this->index    = $imgObj->index;
        $this->date     = $imgObj->date;
        $this->slug     = $imgObj->slug;
    }

    public function showBody()
    {
        return '<img src="inc/img/imgview.php?imgname='. $this->slug .'&p='. $this->idpost .'&u='. $this->iduser .'">';
    }

    public function show()
    {
        return $this->showBody();
    }
}