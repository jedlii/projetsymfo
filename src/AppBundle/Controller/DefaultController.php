<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Categorie;
use AppBundle\Entity\Produit;

class DefaultController extends Controller
{
    /**
    * @Route("/", name="homepage")
    */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
    	$repo = $em->getRepository(Categorie::class);
    	$list = $repo->findAll();
    	return $this->render("default/index.html.twig",
    		['categories' => $list]);
    }   
}
