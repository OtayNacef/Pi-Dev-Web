<?php

namespace MusicBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AudioLengthExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('sectotime', [$this, 'secondsToTime']),
        ];
    }

//    public function getFilters()
//    {
//        return array(
//            new \Twig_Filter('sectotime', array($this, 'secondsToTime')),
//        );
//    }

    public function secondsToTime($seconds)
    {
        return gmdate("i:s", (int)$seconds);;
    }
}