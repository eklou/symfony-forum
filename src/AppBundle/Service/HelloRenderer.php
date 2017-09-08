<?php

namespace AppBundle\Service;


class HelloRenderer
{

    public function render($text){
        return "<h3> $text </h3>";
    }

}