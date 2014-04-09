<?php
namespace Acme\HelloBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Acme\HelloBundle\Entity\Markers;
use Acme\HelloBundle\Entity\Location;
use Acme\HelloBundle\Entity\Userinfo;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Mapping as ORM;
class adminController extends Controller{
    
    //put your code here
    public function showadsAction(){
        $ads= $this->getDoctrine()
                ->getRepository('Acme\HelloBundle\Entity\Location')
                ->findAll();
        if (!$ads) {
            throw $this->createNotFoundException('No Address found');
        }
        foreach ($ads as $item) {
            $title = $item->getTitle();
            $street = $item->getStreet();
            $avenue = $item->getAvenue();
            $description = $item->getDescription();
            $contact = $item->getContact();
            $addressid = $item->getid();
            $addresslist[] = array( 'id' => $addressid,'title' => $title,'street'=>$street,'avenue'=>$avenue,'description'=>$description,'contact'=>$contact);
        }
        return $this->render('AcmeHelloBundle:Default:backend.html.twig', array('address' => $addresslist));
    }
    public function showmapsAction(){
        $maps= $this->getDoctrine()
                ->getRepository('Acme\HelloBundle\Entity\Markers')
                ->findAll();
        if (!$maps) {
            throw $this->createNotFoundException('No Address found');
        }
        foreach ($maps as $item) {
            $name = $item->getname();
            $address = $item->getaddress();
            $lat = $item->getlat();
            $lng = $item->getlng();
            $type= $item->getType();
            $mapid = $item->getid();
            $maplist[] = array( 'id' => $mapid, 'name' => $name,'address'=>$address,'lat'=>$lat,'lng'=>$lng,'type'=>$type);
        }
        return $this->render('AcmeHelloBundle:Default:adminmaps.html.twig', array('map' => $maplist));
    }
    
    public function showusersAction(Request $request){
       ;
        $em = $this->getDoctrine()->getEntityManager();
        $users= $this->getDoctrine()
                ->getRepository('Acme\HelloBundle\Entity\Userinfo')
                ->findAll();
        $min = 1000;
        $max = 9999;
        $quantity = 5;
        if($request->getMethod()== "POST"){
            $code = $this->gencode($min,$max,$quantity);
            
            //foreach($code as $c){
               
               for($i=0;$i<$quantity;$i++){
                   $accesscode = $this->getDoctrine()
                    ->getRepository('Acme\HelloBundle\Entity\Userinfo')
                    ->findOneByCode($code[$i]);
                  if(!$accesscode){
                  $user = new userinfo();
                  $user->setcode($code[$i]);
                  $user->setactive(0);
                  $em->persist($user);
                  $em->flush();
                  }
                  else{
                  return new Response("Duplicated Code, please try again");
                  }
               
                
               }
               
            //}
            
            
        }
       if(!$users){
            return $this->render('AcmeHelloBundle:Default:adminusers.html.twig',array('user'=>'noresult'));
       }
        foreach ($users as $item) {
            $code = $item->getcode();
            $id = $item->getid();
            $password= $item->getpassword();
            $locationId = $item->getlocationId();
            $active = $item->getActive();
            
            $userinfo[] = array( 'id' => $id, 'code' => $code,'password'=>$password,'locationId'=>$locationId,'active'=>$active);
        }
        return $this->render('AcmeHelloBundle:Default:adminusers.html.twig', array('user' => $userinfo));
    }
    public function gencode($min,$max,$quantity){
        //generate 10 random numbers
    $numbers = range($min, $max);
    shuffle($numbers);
    return array_slice($numbers, 0, $quantity);
    }
}

?>
