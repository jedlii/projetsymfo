<?php
namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Categorie;
use AppBundle\Entity\Produit;

/**
 * @Route("shop")
 */
class FrontController extends Controller
{

    /**
     * @Route("/category/{id}", name="eshop_category")
     */
    public function categoryAction(Categorie $category)
    {
    	// Get product of given category
    	$em = $this->getDoctrine()->getManager();
    	$repo = $em->getRepository(Produit::class);
    	$products = $repo->findByCategorie($category->getId());

    	return $this->render("default/products.html.twig",
    		['products' => $products, 'category' => $category]);
    }

}