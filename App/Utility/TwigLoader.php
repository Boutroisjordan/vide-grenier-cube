<?php
namespace App\Utility;

use Twig\Environment;

class TwigLoader {
    private static $twigEnvironment;

    public static function setTwigEnvironment(Environment $twig): void {
        self::$twigEnvironment = $twig;
    }

    public static function getTwigEnvironment(): Environment {
        return self::$twigEnvironment;
    }
}