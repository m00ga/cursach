<?php

class View
{

    function generate($content_view, $basic_view = null, $data = null)
    {
        if(empty(pathinfo($content_view, PATHINFO_EXTENSION))) {
            $content_view = $content_view.".php";
        }
        if($basic_view !== null) {
            include "src/Views/".$basic_view; 
        }else{
            include "src/Views/".$content_view;
        }
    }
}
