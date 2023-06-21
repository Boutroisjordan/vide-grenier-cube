<?php

namespace App\Controllers;

use App\Models\Articles;
use App\Models\Cities;
use \Core\View;
use Exception;

/**
 * API controller
 */
/**
 * @OA\Info(
 *   title="Vide Grenier API",
 *   version="1.0",
 *   description="API pour le site web Vide Grenier",
 *   termsOfService="http://swagger.io/terms/",
 *   @OA\Contact(
 *     email="contact@mysite.com",
 *   ),
 *   @OA\License(
 *     name="Apache 2.0",
 *     url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *   )
 * )
 */
class Api extends \Core\Controller
{

    /**
     * Affiche la liste des articles / produits pour la page d'accueil
     *
     * @throws Exception
     */

    /**
     * @OA\Get(
     *     path="/product",
     *     @OA\Response(response="200", description="On récupère tous les produits/articles pour la page d'accueil")
     * )
     */
    public function ProductsAction()
    {
        $query = $_GET['sort'];

        $articles = Articles::getAll($query);

        header('Content-Type: application/json');
        echo json_encode($articles);
    }

    /**
     * Recherche dans la liste des villes
     *
     * @throws Exception
     */

/**
 * @OA\Get(
 *     path="/api/cities",
 *     @OA\Parameter(
 *         name="query",
 *         in="query",
 *         description="Requête de recherche",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(response="200", description="On recherche dans la liste des villes")
 * )
 */
    public function Cities()
    {
        $cities = Cities::search($_GET['query']);

        header('Content-Type: application/json');
        // echo json_encode($cities);
        return $cities;
    }
}
