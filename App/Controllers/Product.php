<?php

namespace App\Controllers;

use App\Models\Articles;
use App\Utility\Upload;
use \Core\View;

/**
 * Product controller
 */
class Product extends \Core\Controller
{

    /**
     * Affiche la page d'ajout
     * @return void
     */

    /**
     * @OA\Post(
     *     path="/product",
     *     @OA\Response(response="200", description="Méthode post du produit")
     * )
     */
    public function index()
    {
        if (isset($_POST['submit'])) {
            try {
                $f = $_POST;

                $errors = $this->validateFormData($f);
                if (count($errors) === 0) {
                    $f['user_id'] = $_SESSION['user']['id'];
                    $id = Articles::save($f);

                    $pictureName = Upload::uploadFile($_FILES['picture'], $id);

                    Articles::attachPicture($id, $pictureName);

                    header('Location: /product/' . $id);
                    exit;
                } else {
                    $_SESSION['form_errors'] = $errors;
                    header('Location: /product');
                    exit;
                }
            } catch (\Exception $e) {
                var_dump($e);
            }
        }

        View::renderTemplate('Product/Add.html');
    }

    private function validateFormData($formData): array
    {
        $errors = [];

        if (empty($formData['name'])) {
            $errors['name'] = 'Name is required.';
        }

        if (empty($formData['description'])) {
            $errors['description'] = 'Description is required.';
        }

        return $errors;
    }

    /**
     * Affiche la page d'un produit
     * @return void
     */

    /**
     * @OA\Get(
     *     path="/product/{id}",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the product to return",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response="200", description="On récupère un produit spécifique par son identifiant")
     * )
     */

    public function show()
    {
        $id = $this->route_params['id'];

        try {
            Articles::addOneView($id);
            $suggestions = Articles::getSuggest();
            $article = Articles::getOne($id);
        } catch (\Exception $e) {
            var_dump($e);
        }

        View::renderTemplate('Product/Show.html', [
            'article' => $article[0],
            'suggestions' => $suggestions
        ]);
    }
}
