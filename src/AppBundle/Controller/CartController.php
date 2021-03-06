<?php
// src/AppBundle/Controller/CartController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Session\Session;

use AppBundle\Entity\Produit;
/**
 * @Route("/eshop")
 */
class CartController extends Controller
{
     /**
      * @Route("/cart/add/{id}", name="eshop_add_to_cart")
      */
     function addToCart(Produit $prd) {     	
     	$session = new Session();
     	@$cart = $session->get('sess_cart');
     	@$cart[$prd->getId()] ++ ;
     	$session->set('sess_cart', $cart);
     	
     	// Retrouner à la page précédente
     	$cat  = $prd->getCategorie();
     	return $this->redirectToRoute('eshop_category', ['id'=> $cat->getId()]);
     }

     // Renvoi le nbr total des produits du panier
     function cartCount() {
     	$session = new Session();
     	$cart = $session->get('sess_cart');
     	$total = 0;
     	if ($cart)
	     	foreach ($cart as $id => $qty) 
	     		$total += $qty;
	     	
     	return new Response($total);
     }

     /**
      * @Route("/cart/", name="eshop_cart")
      */
     function cart() {
     	$session = new Session();
     	$cart = $session->get('sess_cart');
     	$products = array();

     	$em = $this->getDoctrine()->getManager();
     	$repo = $em->getRepository(Produit::class);

     	$total_ht = 0;

          // Calcul du total HT, mnt TVA et total TTC
          // On ne doit pas laisser la vue faire le calcul
          if ($cart)
          	foreach ($cart as $product_id => $qty) {
          		$prd = $repo->find($product_id);
          		$products[] = $prd;
          		$total_ht += $prd->getPrix() * $qty;
          	}

     	$mnt_tva = $total_ht * 10/100;
     	$total_ttc = $total_ht + $mnt_tva;

     	return $this->render("default/cart.html.twig",
     		[
     			'products' => $products,
     			'cart'	 => $cart,
     			'total_ht' => $total_ht,
     			'mnt_tva'  => $mnt_tva,
     			'total_ttc'=> $total_ttc
     		]
     	);
     }

     /**
      * @Route("/cart/remove/{id}", name="eshop_remove_from_cart")
      */
     function removeFromCart($id) {      
          $session = new Session();
          $cart = $session->get('sess_cart');
          unset($cart[$id]); // Remove item from row
          $session->set('sess_cart', $cart);
          
          // Retrouner au panier
          return $this->redirectToRoute('eshop_cart');
     }

     /**
      * @Route("/cart/clear/", name="eshop_clear_cart")
      */
     function clearCart() {      
          $session = new Session();
          $session->clear('sess_cart');
          // Retrouner au panier
          return $this->redirectToRoute('eshop_cart');
     }     
}
