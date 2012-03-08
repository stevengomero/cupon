<?php

namespace Cupon\OfertaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
   public function portadaAction($ciudad)
   {
       if (null == $ciudad) {
	   $ciudad = $this->container->getParameter('cupon.ciudad_por_defecto');
	   return new RedirectResponse($this
		   ->generateUrl('portada', array('ciudad' => $ciudad)));
       }
       
	$em = $this->getDoctrine()->getEntityManager();
	$oferta = $em->getRepository('OfertaBundle:Oferta')->findOfertaDelDia(array(
	    'ciudad' => $ciudad));
	
	if (!$oferta) {
		throw $this->createNotFoundException(
		'No se ha encontrado la oferta del día en la ciudad seleccionada');
	}
	return $this->render('OfertaBundle:Default:portada.html.twig',
		array('oferta' => $oferta));
   }
}
