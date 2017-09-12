<?php
/**
 * Created by PhpStorm.
 * User: formation
 * Date: 08/09/2017
 * Time: 16:11
 */

namespace AppBundle\Twig;


class ElapsedTimeFilter extends \Twig_Extension
{

    private $intervalFormat =[
        "y" => "an",
        "m" => "mois",
        "d" => "jour",
        "h" => "heure",
        "i" => "minuites",
        "s" => "seconde",
    ];
    public function getName(){
        return "elapsedTimeFilter";
    }

    public function getFilters(){
        return [
            new \Twig_SimpleFilter("elapsed", [$this, "elapsed"])
        ];
    }

    public function elapsed($date){
        $now = new \DateTime();

        $interval = $now->diff($date);

        $format = "";
        foreach ($this->intervalFormat as $key=>$val){
            $value = $interval->$key;
            if($value>0){
                $format .= "%{$key} $val";
            }
        }

        return  $interval->format($format);
    }
}