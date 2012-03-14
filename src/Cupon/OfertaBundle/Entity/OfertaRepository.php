<?php

/*
 * (c) Javier Eguiluz <javier.eguiluz@gmail.com>
 *
 * This file is part of the Cupon sample application.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Este archivo pertenece a la aplicaciÃ³n de prueba Cupon.
 * El cÃ³digo fuente de la aplicaciÃ³n incluye un archivo llamado LICENSE
 * con toda la informaciÃ³n sobre el copyright y la licencia.
 */

namespace Cupon\OfertaBundle\Entity;

use Doctrine\ORM\EntityRepository;

class OfertaRepository extends EntityRepository
{
    /**
     * Encuentra la oferta cuyo slug y ciudad coinciden con los indicados
     *
     * @param string $ciudad El slug de la ciudad
     * @param string $slug El slug de la oferta
     */
    public function findOferta($ciudad, $slug)
    {
        $em = $this->getEntityManager();
        
        $consulta = $em->createQuery('SELECT o, c, t FROM OfertaBundle:Oferta o JOIN o.ciudad c JOIN o.tienda t WHERE o.revisada = true AND o.slug = :slug AND c.slug = :ciudad');
        $consulta->setParameter('slug', $slug);
        $consulta->setParameter('ciudad', $ciudad);
        $consulta->setMaxResults(1);
        
        return $consulta->getSingleResult();
    }
    
    /**
     * Encuentra la oferta del dÃ­a en la ciudad indicada
     *
     * @param string $ciudad El slug de la ciudad
     */
    public function findOfertaDelDia($ciudad)
    {
        $em = $this->getEntityManager();
        
        $consulta = $em->createQuery('SELECT o, c, t FROM OfertaBundle:Oferta o 
	    JOIN o.ciudad c JOIN o.tienda t 
	    WHERE o.revisada = true 
		AND o.fecha_publicacion < :fecha 
		AND c.slug = :ciudad 
	    ORDER BY o.fecha_publicacion DESC');
        $consulta->setParameter('fecha', new \DateTime('now'));
        $consulta->setParameter('ciudad', $ciudad);
	//$consulta->setParameter('ciudad', 'madrid');
        $consulta->setMaxResults(1);
        
        return $consulta->getSingleResult();
    }
    
    /**
     * Encuentra la oferta del dÃ­a de maÃ±ana en la ciudad indicada
     *
     * @param string $ciudad El slug de la ciudad
     */
    public function findOfertaDelDiaSiguiente($ciudad)
    {
        $em = $this->getEntityManager();
        
        $consulta = $em->createQuery('SELECT o, c, t FROM OfertaBundle:Oferta o JOIN o.ciudad c JOIN o.tienda t WHERE o.revisada = true AND o.fecha_publicacion < :fecha AND c.slug = :ciudad ORDER BY o.fecha_publicacion DESC');
        $consulta->setParameter('fecha', new \DateTime('tomorrow'));
        $consulta->setParameter('ciudad', $ciudad);
        $consulta->setMaxResults(1);
        
        return $consulta->getSingleResult();
    }
    
    /**
     * Encuentra las cinco ofertas mÃ¡s recuentes de la ciudad indicada
     *
     * @param string $ciudad_id El id de la ciudad
     */
    public function findRecientes($ciudad_id)
    {
        $em = $this->getEntityManager();
        
        $consulta = $em->createQuery('SELECT o, t FROM OfertaBundle:Oferta o JOIN o.tienda t WHERE o.revisada = true AND o.fecha_publicacion < :fecha AND o.ciudad = :id ORDER BY o.fecha_publicacion DESC');
        $consulta->setMaxResults(5);
        $consulta->setParameter('id', $ciudad_id);
        $consulta->setParameter('fecha', new \DateTime('today'));
        $consulta->useResultCache(true, 600);
        
        return $consulta->getResult();
    }
    
    /**
     * Encuentra las cinco ofertas mÃ¡s cercanas a la ciudad indicada
     *
     * @param string $ciudad El slug de la ciudad
     */
    public function findCercanas($ciudad)
    {
        $em = $this->getEntityManager();
        
        $consulta = $em->createQuery('SELECT o, c FROM OfertaBundle:Oferta o JOIN o.ciudad c WHERE o.revisada = true AND o.fecha_publicacion <= :fecha AND c.slug != :ciudad ORDER BY o.fecha_publicacion DESC');
        $consulta->setMaxResults(5);
        $consulta->setParameter('ciudad', $ciudad);
        $consulta->setParameter('fecha', new \DateTime('today'));
        $consulta->useResultCache(true, 600);
        
        return $consulta->getResult();
    }
    
    /**
     * Encuentra todas las ventas de la oferta indicada
     *
     * @param string $oferta El id de la oferta
     */
    public function findVentasByOferta($oferta)
    {
        $em = $this->getEntityManager();
        
        $consulta = $em->createQuery('SELECT v, o, u FROM OfertaBundle:Venta v JOIN v.oferta o JOIN v.usuario u WHERE o.id = :id ORDER BY v.fecha DESC');
        $consulta->setParameter('id', $oferta);
        
        return $consulta->getResult();
    }
}
