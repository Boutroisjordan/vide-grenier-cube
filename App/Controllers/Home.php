<?php

namespace App\Controllers;

use App\Models\Articles;
use \Core\View;
use Exception;

/**
 * Home controller
 */
class Home extends \Core\Controller
{

    /**
     * Affiche la page d'accueil
     *
     * @return void
     * @throws \Exception
     */
    public function index()
    {
        var_dump("azdazfdazf");
        var_dump("azdazfdazf");
        View::renderTemplate('Home/index.html', []);
    }
}
