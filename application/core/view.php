<?php

class View
{
    public function generate($view, $data=true)
    {
         include_once Q_PATH.'/application/views/template.php';
    }
}