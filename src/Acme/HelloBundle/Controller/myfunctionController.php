<?php
namespace Acme\HelloBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Acme\HelloBundle\Entity\Location;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Query;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerAware;
class myfunctionController extends Controller {
    public function __construct($entityManager) {
    $this->entityManager = $entityManager;
}
    public function newaddmsgAction()
    {
    //$id = $request->request->get('locationId');
         return $this->render('AcmeHelloBundle:Default:newaddmsg.html.twig');
    }
    
    public function showall() {
        //$em = $this->getDoctrine()->getEntityManager();
        //$em = $this->entityManager;
         $ads= $this->entityManager
                ->getRepository('Acme\HelloBundle\Entity\Location')
                ->findBy(array(),array('postdate'=>'DESC'));
        if (!$ads) {
            throw $this->createNotFoundException('No Address found');
        }
        
        foreach ($ads as $item) {
            $position = 15;
            $title = $item->getTitle();
            $street = $item->getStreet();
            $avenue = $item->getAvenue();
            $description = $item->getDescription();
            $newdes = mb_substr($description, 0, $position,'UTF-8'); 
            $contact = $item->getContact();
            $addressid = $item->getid();
             
            $showdate = $item->getPostdate()->format('m-d-Y');
            
            $addresslist[] = array( 'id' => $addressid,'title' => $title,'street'=>$street,'avenue'=>$avenue,'description'=>$description,'contact'=>$contact,'newdes'=>$newdes,'showdate'=>$showdate);
        }
        return $addresslist;
        //return $this->render('AcmeHelloBundle:Default:address.html.twig', array('address' => $addresslist));
    }
    public function findAll() {
        return $this->getEntityManager()
                        ->createQuery('select latitude,longitude from AcmeHelloBundle:Markers')
                        ->getResult();
    }
}

?>
