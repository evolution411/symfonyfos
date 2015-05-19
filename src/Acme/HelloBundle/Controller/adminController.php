<?php
namespace Acme\HelloBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Acme\HelloBundle\Entity\Markers;
use Acme\HelloBundle\Entity\Location;
use Acme\HelloBundle\Entity\Userinfo;
use Acme\HelloBundle\Entity\Siteinfo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Acme\HelloBundle\Entity\Area;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DependencyInjection\ContainerAware;
class adminController extends Controller{
    
    //put your code here
public function usermanager(){
    return $userManager = $this->get('fos_user.user_manager');
}
public function AdministratorAction(Request $request) {
        $users = $this->getusers();
        $userManager = $this->get('fos_user.user_manager');
        if($request->getMethod()== "POST"){
            $username=$_POST['srchname'];
            $email=$_POST['srchemail'];
            if($email){
            $userinfo = $userManager->findUserByEmail($email);
            }
            else{
            $userinfo = $userManager->findUserByUsername($username);
            }
           //$this->getRquest()->setUser('user',$userinfo);
            //$this->getUser->setAttribute('user',$userinfo);
           
            //$this->forward('AcmeHelloBundle:admin:showusers');
            //$response = $this->forward('AcmeHelloBundle:admin:showusers',array('user'=>$userinfo));
            $this->getRequest()->setUser('user', $userinfo);
            $this->forward('AcmeHelloBundle:admin:showusers');
            //$id = $userinfo->getId();
           // $useremail = $userinfo['email'];
            //echo $id;
          // die();
           //return $this->redirect($this->get('adminusers_page')->generateUrl('adminusers_page'),array('user' =>$userinfo));
        // return $this->render('AcmeHelloBundle:Default:adminusers.html.twig',array('user'=>$userinfo));
        
            //return $response;
            }
	return $this->render('AcmeHelloBundle:Default:administrator.html.twig',array('users' =>$users));
}

public function getallprices(){
    
    		$price = $this->getDoctrine()
				->getRepository('Acme\HelloBundle\Entity\price')
				->findAll();
                return $price;

}
public function newposterAction(Request $request){
    $price = $this->getallprices();
    $area = $this->getAreas();
    $Location = new location();        
    $em = $this->getDoctrine()->getEntityManager();
    if($request->getMethod()== "POST")
		{
                    
			$contact = $request->get('contact');
                        if(!$this->isValid( 'phone',$contact )) 
			{
				return new Response('Please enter the correct format of contact info.');        
                         }
                         else
			{
                                //$zipcode = $request->get('zipcode');
				if(!preg_match("/^([0-9]{5})(-[0-9]{4})?$/i",$request->get('zipcode')))
				{
					echo "invalid zipcode";
				}
				else
				{
                                        $posterId =  $this->getsession();
					$renttype=$_POST['renttype'];
                                        $area=$_POST['area'];
					$Location->setStreet($request->get('street'));  
					$Location->setDescription($request->get('description'));
					$Location->setTitle($request->get('title'));
					$Location->setAvenue($request->get('avenue'));
					$Location->setContact($request->get('contact'));    
					$Location->setPosterId($posterId);
					$Location->setRenttype($renttype);
                                        $Location->setArea($area);
					$Location->setZipcode($request->get('zipcode'));
					//added by masum
					$Location->setNumbath($request->get('bathtxt'));
					//$Location->setRentamount($request->get('pricetxt'));
					$cd=$_POST['pricetxt'];
					$rntamount = preg_replace('/\s+/', '', $cd); // removing all white space, example: $ 500 - $ 900 will be = $500-$900
					$Location->setRentamount($rntamount); // saving into rentamount column
					$Location->setLaundryfacility($request->get('londrytxt'));
					
					$date = new \DateTime();
					//$date = date("m-d-Y");
					$Location->setPostdate($date);
					$Location->setEditdate($date);
					//$Location->setMakersId('15');
					$em->persist($Location);
					$em->flush();
					$id = $Location->getId();
					$locationId[] = array('Location'=>$id);
					 
					//return new Response('Form has been Sucessfully added');
					$msg = "addlocation";
					//return new Response($posterId);
					return $this->render('AcmeHelloBundle:Default:newaddmsg.html.twig', array('Lid'=>$locationId,'msg' => $msg));
				}
			}
		}
    return $this->render('AcmeHelloBundle:Default:adminnewposter.html.twig',array('area'=>$area,'ranze'=>$price));
}

public function getAreas(){
        $area = $this->getDoctrine()
                        ->getRepository('Acme\HelloBundle\Entity\area')
                        ->findAll();
        return $area;
    }
    
public function getusers() {
    //access user manager services 

    $userManager = $this->get('fos_user.user_manager');
    $users = $userManager->findUsers();

    return $users;
    //return $this->render('AnnuaireAdminBundle:Admin:users.html.twig', array('users' =>   $users));
}

    public function showadsAction(Request $request){
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
        
        if($request->getMethod()== "POST"){
            $date = strtotime("-15 day");
            
            echo $date;
            die();
        }
        return $this->render('AcmeHelloBundle:Default:backend.html.twig', array('address' => $addresslist));
    }
    public function showmapsAction(Request $request){
        $em = $this->getDoctrine()->getEntityManager();
        $info= $this->getDoctrine()
                ->getRepository('Acme\HelloBundle\Entity\Siteinfo')
                ->findAll();
        $Infomation = new siteinfo();
        if (!$info) {
            throw $this->createNotFoundException('No Address found');
        }
        
        if($request->getMethod()== "POST"){
            
            $Infomation->setSiteemail($request->get('email')); 
            $Infomation->setSitephone($request->get('phone'));
            $Infomation->setAboutus($request->get('aboutus'));
            $Infomation->setMission($request->get('mission'));
            $em->merge($Infomation);
            $em->flush();
            
        }
        foreach ($info as $item) {
            $email = $item->getsiteemail();
            $phone = $item->getsitephone();
            $aboutus = $item->getaboutus();
            $mission = $item->getmission();
           // $type= $item->getType();
           // $mapid = $item->getid();
            $siteinfo[] = array( 'email' => $email, 'phone' => $phone,'aboutus'=>$aboutus,'mission'=>$mission);
        }
        return $this->render('AcmeHelloBundle:Default:adminmaps.html.twig', array('info' => $siteinfo));
    }
    public function showusersAction(Request $request){
        //$userManager = $this->get('fos_user.user_manager');
        //$userinfo = $userManager->findUserByEmail($email);
        $user = $this->getRequest()->getUser('user');
        //$user = $this->getRequest()->getUser('user');
         return $this->render('AcmeHelloBundle:Default:adminusers.html.twig',array('user'=>$user));
    }
    public function renderLogin(array $data) {
        $requestAttributes = $this->container->get('request')->attributes;
        if ($requestAttributes->get('_route') == 'admin_login') {
            $template = sprintf('TwsAdminBundle:Security:login.html.twig');
        } else {
            $template = sprintf('FOSUserBundle:Security:login.html.twig');
        }
        return $this->container->get('templating')->renderResponse($template, $data);
    }
    /*
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
    }*/
    public function gencode($min,$max,$quantity){
        //generate 10 random numbers
    $numbers = range($min, $max);
    shuffle($numbers);
    return array_slice($numbers, 0, $quantity);
    }
}

?>
