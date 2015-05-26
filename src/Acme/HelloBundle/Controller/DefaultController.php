<?php

namespace Acme\HelloBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Acme\HelloBundle\Controller\adminController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Acme\HelloBundle\Entity\Markers;
use Acme\HelloBundle\Entity\Location;
use Acme\HelloBundle\Entity\Userinfo;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerAware;
//use Acme\HelloBundle\Entity\Picture;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\EventDispatcher\EventDispatcher,
    Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken,
    Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Mapping as ORM;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Acme\HelloBundle\Entity\Document;
use Acme\HelloBundle\Entity\UploadFileMover;
use Acme\HelloBundle\Entity\Images; //images entity for saving data into images table
use Acme\HelloBundle\Entity\Videos;
use Acme\HelloBundle\Entity\admin;
use Acme\HelloBundle\Entity\User;
use Acme\HelloBundle\Entity\Area;
use Acme\HelloBundle\Entity\Comment;

class DefaultController extends Controller {

    public function indexAction($name) {
        return $this->render('AcmeHelloBundle:Default:index.html.twig', array('name' => $name));
    }

    public function getsession() {
        if ($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // authenticated (NON anonymous)
            $usr = $this->get('security.context')->getToken()->getUser();
            $posterId = $usr->getId();
            return $posterId;
        } else {
            $router = $this->container->get('router');
            return new RedirectResponse($router->generate('fos_user_security_login'), 307);
        }
    }

    //admin session start need to fix this :
    public function setadsession($uname) {
        $adsession = new Session();
        $adsession->start();
        $adsession->set('adminlogin', $uname);
    }

    public function getadsession() {
        $adsession = $this->getRequest()->getAdsession();
        return $adsession->get('adminlogin');
    }

    /////////////////////////////////
    public function blogAction($name) {
        return new Response('<html><body>BLOG ' . $name . '~</body></html>');
    }

    public function newmapAction(Request $request, $locationId) {
        $map = new Markers();
        $em = $this->getDoctrine()->getEntityManager();
        if ($request->getMethod() == "POST") {
            $housenum = $request->get('housenum');
            $street = $request->get('street');
            $city = $request->get('city');
            $state = $request->get('state');
            $zipcode = $request->get('zipcode');
            $locationId = $_POST['locationId'];
            $address = $housenum . " " . $street . " " . $city . " " . $state . " " . $zipcode;
            $map->setAddress($address);
            $latlng = $this->lookup($address);
            $lat = $latlng['latitude'];
            $lng = $latlng['longitude'];
            $map->setLat($lat);
            $map->setLng($lng);
            $map->setLocationId($locationId);
            $em->persist($map);
            $em->flush();
            $msg = 'addmap';
            //return $this->redirect($this->generateUrl('newaddress_page'));
            return $this->render('AcmeHelloBundle:Default:newaddmsg.html.twig', array('msg' => $msg));
            //return $this->redirect($this->generateUrl('newaddmsg',array('street'=>$street)));
        }
        $form = $this->createFormBuilder($map)
                ->add('address', 'text')
                ->getForm();
        //$form->handleRequest($request);
        //if($form->isValid()){
        return $this->render('AcmeHelloBundle:Default:addmap.html.twig', array('form' => $form->createView(), 'locationId' => $locationId));
    }

    public function getsiteinfo() {
        $em = $this->getDoctrine()->getEntityManager();
        $info = $this->getDoctrine()
                ->getRepository('Acme\HelloBundle\Entity\Siteinfo')
                ->findAll();

        if (!$info) {
            throw $this->createNotFoundException('No Address found');
        }


        foreach ($info as $item) {
            $email = $item->getsiteemail();
            $phone = $item->getsitephone();
            $aboutus = $item->getaboutus();
            $mission = $item->getmission();
            // $type= $item->getType();
            // $mapid = $item->getid();
            $siteinfo[] = array('email' => $email, 'phone' => $phone, 'aboutus' => $aboutus, 'mission' => $mission);
        }
        return $siteinfo;
    }

//add a Poster
    public function newaddressAction(Request $request) {
        $Location = new location();
        $em = $this->getDoctrine()->getEntityManager();

        $images = $this->getDoctrine()->getEntityManager()
                ->createQuery('select imgtbl.id,imgtbl.posterid,imgtbl.imgName,imgtbl.path,imgtbl.status from AcmeHelloBundle:Images imgtbl WHERE imgtbl.status=:sts')
                ->setParameter('sts', '1')
                ->SetMaxResults(10)
                ->getResult();

        $videos = $this->getDoctrine()->getEntityManager()
                ->createQuery('select vdotbl.id,vdotbl.posterid,vdotbl.path,vdotbl.status, vdotbl.idcode from AcmeHelloBundle:Videos vdotbl WHERE vdotbl.status=:sts and vdotbl.idcode=:idkod')
                ->setParameter('sts', '1')
                ->setParameter('idkod', '2')
                ->SetMaxResults(10)
                ->getResult();
        if ($request->getMethod() == "POST") {

            $contact = $request->get('contact');
            if (!$this->isValid('phone', $contact)) {
                return new Response('Please enter the correct format of contact info.');
            } else {
                //$zipcode = $request->get('zipcode');
                if (!preg_match("/^([0-9]{5})(-[0-9]{4})?$/i", $request->get('zipcode'))) {
                    echo "invalid zipcode";
                } else {
                    $posterId = $this->getsession();
                    $renttype = $_POST['renttype'];
                    $area = $_POST['area'];
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
                    $cd = $_POST['pricetxt'];
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
                    $locationId[] = array('Location' => $id);

                    //return new Response('Form has been Sucessfully added');
                    $msg = "addlocation";
                    //return new Response($posterId);
                    return $this->render('AcmeHelloBundle:Default:newaddmsg.html.twig', array('Lid' => $locationId, 'msg' => $msg));
                }
            }
        }
        $count_per_page = 12;
        $count = $this->getTotalPosters();
        $total_pages = ceil($count / $count_per_page);
        // $current_page = 1;
        $page = $request->get('page');
        if (!is_numeric($page)) {
            $page = 1;
        } else {
            $page = floor($page);
        }
        if ($count <= $count_per_page) {
            $page = 1;
        }
        if (($page * $count_per_page) > $count) {
            $page = $total_pages;
        }
        $offset = 0;
        if ($page > 1) {
            $offset = $count_per_page * ($page - 1);
        }
        $page_per_poster = $this->showall($offset, $count_per_page);
        $form = $this->createFormBuilder($Location)
                ->add('street', 'text')
                ->add('avenue', 'text')
                ->add('description', 'text')
                ->add('title', 'text')
                ->add('contact', 'text')
                ->add('zipcode', 'text')
                ->getForm();
        //$addresslist = $this->showall();
        $price = $this->getallprices();
        $area = $this->getAreas();

        //$form->handleRequest($request);
        //if($form->isValid()){


        /* $bath = $this->getDoctrine()
          ->getRepository('Acme\HelloBundle\Entity\Bath')
          ->findAll(); */

        /* $price = $this->getDoctrine()
          ->getRepository('Acme\HelloBundle\Entity\Price')
          ->findAll();
         */
        /* $laundry = $this->getDoctrine()
          ->getRepository('Acme\HelloBundle\Entity\laundry')
          ->findAll(); */
        $siteinfo = $this->getsiteinfo();
        return $this->render('AcmeHelloBundle:Default:newaddress.html.twig', array('area' => $area, 'ranze' => $price, 'page_per_poster' => $page_per_poster, 'total_pages' => $total_pages, 'address' => $page_per_poster, 'current_page' => $page, 'images' => $images, 'videos' => $videos, 'info' => $siteinfo));
    }

    public function getTotalPosters() {
        $number_of_poster = $this->getDoctrine()->getEntityManager()->createQueryBuilder()
                ->select('COUNT(loc.posterid)')
                ->from('AcmeHelloBundle:Location', 'loc')
                ->getQuery();
        $total_poster = $number_of_poster->getSingleScalarResult();
        return $total_poster;
    }

    public function getallprices() {

        $price = $this->getDoctrine()
                ->getRepository('Acme\HelloBundle\Entity\Price')
                ->findAll();
        return $price;
    }

    public function newaddmsgAction() {
        //$id = $request->request->get('locationId');
        return $this->render('AcmeHelloBundle:Default:newaddmsg.html.twig');
    }

    public function showall($offset, $count_per_page) {
        /* $ads= $this->getDoctrine()
          ->getRepository('Acme\HelloBundle\Entity\Location')
          ->findBy(array(),array('postdate'=>'DESC')); */
        $ads = $this->getDoctrine()->getEntityManager()->createQueryBuilder()
                ->select('loc')
                ->from('AcmeHelloBundle:Location', 'loc')
                ->setFirstResult($offset)
                ->setMaxResults($count_per_page)
                ->orderBy('loc.postdate', 'DESC')
                ->getQuery();
        $page_per_poster = $ads->getArrayResult();

        //echo $page_per_poster;
        /* if (!$ads) {
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
          //return $this->render('AcmeHelloBundle:Default:address.html.twig', array('address' => $addresslist)); */
        return $page_per_poster;
    }

    public function findAll() {
        return $this->getEntityManager()
                        ->createQuery('select latitude,longitude from AcmeHelloBundle:Markers')
                        ->getResult();
    }

    public function mapaddAction($id, Request $request) {
        //I was using this following block to display images on the map page left side
        //$postr_id = $id;
        $price = $this->getallprices();
        $area = $this->getAreas();
        $searchresult = $this->getDoctrine()->getEntityManager()
                ->createQuery('select imgtbl.id,imgtbl.posterid,imgtbl.imgName,imgtbl.path,imgtbl.status from AcmeHelloBundle:Images imgtbl WHERE imgtbl.status=:sts and imgtbl.posterid=:pstrid')
                ->setParameter('sts', '1')
                ->setParameter('pstrid', $id)
                ->getResult();

        $qryforutube = $this->getDoctrine()->getEntityManager()
                ->createQuery('select vdotbl.id,vdotbl.posterid,vdotbl.path,vdotbl.status, vdotbl.idcode from AcmeHelloBundle:Videos vdotbl WHERE vdotbl.status=:sts and vdotbl.posterid=:pstrid and vdotbl.idcode=:idkod')
                ->setParameter('sts', '1')
                ->setParameter('pstrid', $id)
                ->setParameter('idkod', '2')
                ->getResult(); //idkode 2 for youtube vdo
        //echo $qryforutube.path;
        //masum added
        $vidoemsg = "";
        $ytube = 2;
        $st = 1;
        $vdocode = $this->getDoctrine()
                ->getRepository('Acme\HelloBundle\Entity\Videos')
                ->findby(array('idcode' => $ytube, 'posterid' => $id, 'status' => $st));
        if ($vdocode != null || $vdocode != 0) {
            $vidarray = array();
            foreach ($vdocode as $v) {
                //$path = $video->getPath();
                $path = $v->getPath();
                if ($this->isValidYoutubeURL($path) == true) {
                    $vidoemsg = "youtube";
                    $videopath = $this->getYoutubeIdFromUrl($path);

                    $vidarray [] = $videopath;
                } else {
                    //$vidoemsg= "0";
                    $vidarray [] = $path;
                }
            }
        } else {
            $vidarray [] = "";
            //$vidoemsg= "0";
        }
        //Marker is used for showing the address MAP.
        $address = $this->getDoctrine()
                ->getRepository('Acme\HelloBundle\Entity\Markers')
                ->findOneBylocationId($id);
        //displaying poster information
        $addresslist = $this->getDoctrine()
                ->getRepository('Acme\HelloBundle\Entity\Location')
                ->findOneById($id);

        if (!$address) {
            // return new Response("No map found");
            $latlng = "nomap";
            $latlnglist = array('lat' => "nomap", 'lng' => "nomap");
        } else {
            $latlng = $address->getaddress();
            $newaddress = $this->lookup($latlng);
            $latlnglist = array('lat' => $newaddress['latitude'], 'lng' => $newaddress['longitude']);
        }

        //masum added
        $qryresult = $this->getDoctrine()->getEntityManager()
                ->createQuery('select vdotbl.id,vdotbl.posterid,vdotbl.path,vdotbl.status from AcmeHelloBundle:Videos vdotbl WHERE vdotbl.status=:sts and vdotbl.posterid=:pstrid and vdotbl.idcode=:idkod')
                ->setParameter('sts', '1')
                ->setParameter('pstrid', $id)
                ->setParameter('idkod', '3')
                ->getResult(); //idkode 3 for uploaded vdo
        //count number of pic that a user posted in his account
        $number_of_pic = $this->getDoctrine()->getEntityManager()->createQueryBuilder()
                ->select('COUNT(loc.posterid)')
                ->from('AcmeHelloBundle:Images', 'loc')
                ->where('loc.posterid = :id')
                ->setParameter('id', $id)
                ->getQuery();
        $total_pic = $number_of_pic->getSingleScalarResult();

        $ac = 1;
        $flag = 0;
        $single_img = $this->getDoctrine()
                ->getRepository('Acme\HelloBundle\Entity\Images')
                ->findOneByPosterid($id);

        //comment method
        if ($request->getMethod() == "POST") {
            $em = $this->getDoctrine()->getEntityManager();
            $Comment = new Comment();
            $datetime = date('Y/m/d H:i:s');
            $mycomment = $request->get('comment');
            $userid = $this->getsession();
            $status = '1';
            if ($mycomment != "") {
                $Comment->setComment($mycomment);
                $Comment->setDate($datetime);
                $Comment->setLocationid($id);
                $Comment->setUserid($userid);
                $Comment->setStatus($status);
                $em->persist($Comment);
                $em->flush();
            }
        }


        if ($single_img != null || $single_img != 0) {
            $flag = 1;
            return $this->render('AcmeHelloBundle:Default:map.html.twig', array('srchlst' => $searchresult, 'address' => $latlnglist, 'name' => $latlng, 'posterinfo' => $addresslist,
                        'area' => $area, 'vmsg' => $vidoemsg, 'qutube' => $vidarray, 'uploadedvdolist' => $qryresult, 'pth' => $single_img, 'flag' => $flag, 'comment' => $Comment, 'ranze' => $price));
        } else {
            if ($single_img == 0 OR $single_img == null) {
                $single_img = "No image found";
                $flag = 2;
                return $this->render('AcmeHelloBundle:Default:map.html.twig', array('srchlst' => $searchresult, 'address' => $latlnglist, 'name' => $latlng, 'posterinfo' => $addresslist,
                            'area' => $area, 'vmsg' => $vidoemsg, 'qutube' => $vidarray, 'uploadedvdolist' => $qryresult, 'pth' => $single_img, 'flag' => $flag, 'comment' => $Comment, 'ranze' => $price));
            }
        }
    }

    //Comment Posting Function
    public function commentPosting($locationid) {
        $Comment = new Comment();
        $datetime = date('Y/m/d H:i:s');
        $mycomment = $request->get('comment');
        $userid = $this->getsession();
        $status = '1';
        if ($mycomment != "") {
            $Comment->setComment($mycomment);
            $Comment->setDate($datetime);
            $Comment->setLocationid($locationid);
            $Comment->setUserid($userid);
            $Comment->setStatus($status);
            return $mycomment;
        }
    }

    public function mypostAction(Request $request) {
        //$session = $this->getRequest()->getSession();
        // $poster = $session->get('login');
        $poster = $this->getsession();
        $price = $this->getallprices();
        $area = $this->getAreas();
        if ($poster == "" || $poster == null) {
            return $this->redirect($this->generateUrl('login'));
        } else {
            $addresslist = $this->getDoctrine()
                    ->getRepository('Acme\HelloBundle\Entity\Location')
                    ->findByPosterid($poster);

            return $this->render('AcmeHelloBundle:Default:mypost.html.twig', array('myposterinfo' => $addresslist, 'ranze' => $price, 'area' => $area));
        }
    }

    public function getAreas() {
        $area = $this->getDoctrine()
                ->getRepository('Acme\HelloBundle\Entity\Area')
                ->findAll();
        return $area;
    }

    public function addposterAction(Request $request) {
        $Location = new location();
        $em = $this->getDoctrine()->getEntityManager();
        if ($_POST['btnnewposter']) {

            $contact = $request->get('contact');
            if (!$this->isValid('phone', $contact)) {
                return new Response('Please enter the correct format of contact info.');
            } else {
                // $zipcode = $request->get('zipcode');
                if (!preg_match("/^([0-9]{5})(-[0-9]{4})?$/i", $request->get('zipcode'))) {
                    echo "invalid zipcode";
                } else {
                    $posterId = $this->getsession();
                    $renttype = $_POST['renttype'];
                    $area = $_POST['area'];
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
                    //$Location->setNumbath($request->get('bathtxt'));
                    //$Location->setRentamount($request->get('pricetxt'));
                    $cd = $_POST['pricetxt'];
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
                    $locationId[] = array('Location' => $id);

                    //return new Response('Form has been Sucessfully added');
                    $msg = "addlocation";
                    //return new Response($posterId);
                    return $this->render('AcmeHelloBundle:Default:newaddmsg.html.twig', array('Lid' => $locationId, 'msg' => $msg));
                }
            }
        }
        $form = $this->createFormBuilder($Location)
                ->add('street', 'text')
                ->add('avenue', 'text')
                ->add('description', 'text')
                ->add('title', 'text')
                ->add('contact', 'text')
                ->add('zipcode', 'text')
                ->getForm();
        //$addresslist = $this->showall();
        $area = $this->getAreas();
        $price = $this->getallprices();
        return $this->render('AcmeHelloBundle:Default:usermenu.html.twig', array('ranze' => $price, 'area' => $area, 'form' => $form->createView()));
    }

    public function searchAction(Request $request) {
        $price = $this->getallprices();
        $st = "Please start over and select criteria from given category";
        $area = $this->getAreas();

        if ($request->getMethod() == "POST") {
            $searcharea = $_POST['area'];
            $priceselected = $_POST['pricetxt'];
            $apttype = $_POST['apttype'];
            //$abcd = $_POST['selectoption'];
            $searchtext = $_POST['searchtext'];
            if (is_int($priceselected) && is_int($apttype) && $searchtext != "") {
                $adv_srch = $this->getDoctrine()->getEntityManager()
                        ->createQuery('select x.id, x.title,x.street, x.avenue, x.numbath, x.renttype,x.description, x.laundryfacility, x.rentamount, x.zipcode
										   from AcmeHelloBundle:Location x where x.area =:area AND x.rentamount=:rentamnt AND x.zipcode=:zip AND x.renttype=:apttype')
                        ->setParameter('rentamnt', $priceselected)
                        ->setParameter('zip', $searchtext)
                        ->setParameter('apttype', $apttype)
                        ->setParameter('area', $searcharea)
                        ->getResult();
            } elseif (is_int($priceselected) && is_int($apttype)) {
                $adv_srch = $this->getDoctrine()->getEntityManager()
                        ->createQuery('select x.id, x.title,x.street, x.avenue, x.numbath, x.renttype,x.description, x.laundryfacility, x.rentamount, x.zipcode
										   from AcmeHelloBundle:Location x where x.area =:area AND x.rentamount=:rentamnt AND x.renttype=:apttype')
                        ->setParameter('rentamnt', $priceselected)
                        ->setParameter('apttype', $apttype)
                        ->setParameter('area', $searcharea)
                        ->getResult();
            } elseif (is_int($priceselected) && $searchtext && !(is_int($apttype))) {
                $adv_srch = $this->getDoctrine()->getEntityManager()
                        ->createQuery('select x.id, x.title,x.street, x.avenue, x.numbath, x.renttype,x.description, x.laundryfacility, x.rentamount, x.zipcode
										   from AcmeHelloBundle:Location x where x.area =:area AND x.rentamount=:rentamnt AND x.zipcode=:zip')
                        ->setParameter('rentamnt', $priceselected)
                        ->setParameter('area', $searcharea)
                        ->setParameter('zip', $searchtext)
                        ->getResult();
            } elseif (is_int($apttype) && $searchtext && !(is_int($priceselected))) {
                $adv_srch = $this->getDoctrine()->getEntityManager()
                        ->createQuery('select x.id, x.title,x.street, x.avenue, x.numbath, x.renttype,x.description, x.laundryfacility, x.rentamount, x.zipcode
										   from AcmeHelloBundle:Location x where x.area =:area AND x.zipcode=:zip AND x.renttype=:apttype')
                        ->setParameter('zip', $searchtext)
                        ->setParameter('area', $searcharea)
                        ->setParameter('apttype', $apttype)
                        ->getResult();
            } else {
                $adv_srch = $this->getDoctrine()->getEntityManager()
                        ->createQuery('select x.id, x.title,x.street, x.avenue, x.numbath, x.renttype,x.description, x.laundryfacility, x.rentamount, x.zipcode
										   from AcmeHelloBundle:Location x where x.area =:area')
                        ->setParameter('area', $searcharea)
                        ->getResult();
            }

            return $this->render('AcmeHelloBundle:Default:searchresult.html.twig', array('search' => $adv_srch, 'bb' => $priceselected, 'cc' => $searchtext, 'ranze' => $price, 'searcharea' => $searcharea, 'area' => $area, 'st' => ''));
        }
        return $this->render('AcmeHelloBundle:Default:searchresult.html.twig', array('search' => '', 'bb' => '',
                    'cc' => '', 'ranze' => $price, 'area' => $area, 'st' => $st));
    }

    /////////////////////////////////////////
    public function logoutAction(Request $request) {
        $request->getSession()->invalidate();
        return $this->render('AcmeHelloBundle:Default:login.html.twig');
    }

    public function encrypt($password) {
        $encrypt_password = md5($password);
        return $encrypt_password;
    }

    public function checkpassword($password, $repassword) {
        $bool = false;
        if ($password == $repassword) {
            if (strlen($password) >= 7 || strlen($password) <= 13) {
                $bool = true;
                return $bool;
            } else {
                return $bool;
            }
        } else {
            return $bool;
        }
    }

    public function lookup($string) {
        $string = str_replace(" ", "+", urlencode($string));
        $details_url = "http://maps.googleapis.com/maps/api/geocode/json?address=" . $string . "&sensor=false";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $details_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec($ch), true);
        // If Status Code is ZERO_RESULTS, OVER_QUERY_LIMIT, REQUEST_DENIED or INVALID_REQUEST
        if ($response['status'] != 'OK') {
            return null;
        }
        //print_r($response);
        $geometry = $response['results'][0]['geometry'];
        $longitude = $geometry['location']['lat'];
        $latitude = $geometry['location']['lng'];
        $array = array(
            'latitude' => $geometry['location']['lat'],
            'longitude' => $geometry['location']['lng'],
            'location_type' => $geometry['location_type'],
        );
        return $array;
    }

//////////////////////////////////////this function output: delete_this_img_confirmation.html.twig
// this function is not actually deleting record from images table, its just update or set status into 0 for deactivate

    public function Delete_this_image_Action($this_img_id) {
        $em = $this->getDoctrine()->getEntityManager();
        $ps_id = $this_img_id;
        $single_img_row = $this->getDoctrine()
                ->getRepository('Acme\HelloBundle\Entity\Images')
                ->findOneByid($ps_id);

        if (isset($_POST['deleteConfirmed'])) {
            $id = $_POST['id'];
            $single_img_row = $this->getDoctrine()
                    ->getRepository('Acme\HelloBundle\Entity\Images')
                    ->findOneByid($ps_id);

            $id = $_POST['id'];
            $pstr_id = $_POST['posteridtxt'];
            $imgnm = $_POST['imgNametxt'];
            $path = $_POST['pathtxt'];
            $status = $_POST['statustxt'];

            $single_img_row->setPosterid($pstr_id);
            $single_img_row->setImgName($imgnm);
            $single_img_row->setPath($path);
            $single_img_row->setStatus($status);


            $em->merge($single_img_row);
            $em->flush();

            return $this->redirect($this->generateUrl('imageDisplay_page', array('id' => $pstr_id)));
        } elseif (isset($_POST['cancel-or-not-deletebtn'])) {
            $id = $_POST['id'];
            $single_img_row = $this->getDoctrine()
                    ->getRepository('Acme\HelloBundle\Entity\Images')
                    ->findOneByid($ps_id);
            $pstr_id = $_POST['posteridtxt'];
            return $this->redirect($this->generateUrl('imageDisplay_page', array('id' => $pstr_id)));
        }
        return $this->render('AcmeHelloBundle:Default:delete_this_img_confirmation.html.twig', array('piclist' => $single_img_row, 'pid' => $ps_id));
    }

//////////////////////////////////////this function output: delete_this_vdo_confirmation.html.twig
// this function is not actually deleting record from images table, its just update or set status into 0 for deactivate

    public function deletevdoAction($id) {
        $em = $this->getDoctrine()->getEntityManager();
        $vdo_id = $id;
        $allvdo = $this->getDoctrine()->getRepository('Acme\HelloBundle\Entity\Videos')->findOneByid($vdo_id);

        if (isset($_POST['delcon'])) {

            $id = $id;
            $vdolist = $this->getDoctrine()
                    ->getRepository('Acme\HelloBundle\Entity\Videos')
                    ->findOneByid($id);
            $activetxt = 0;
            $vdolist->setStatus($activetxt);
            $em->merge($vdolist);
            $em->flush();

            $srch = $this->getDoctrine()
                    ->getRepository('Acme\HelloBundle\Entity\Videos')
                    ->findby(array('id' => $id));

            foreach ($srch as $v) {

                $psid = $v->getPosterid();
            }
            //
            return $this->redirect($this->generateUrl('vdolist__page', array('id' => $psid)));
        } elseif (isset($_POST['cancelout'])) {
            $id = $_POST['id'];
            $vdo_lst = $this->getDoctrine()
                    ->getRepository('Acme\HelloBundle\Entity\Videos')
                    ->findOneByid($vdo_id);
            $pstr_id = $_POST['posteridtxt'];
            return $this->redirect($this->generateUrl('vdolist__page', array('id' => $pstr_id)));
        }
        return $this->render('AcmeHelloBundle:Default:delete_this_vdo_confirmation.html.twig', array('piclist' => $allvdo, 'pid' => $vdo_id));
    }

// display full image
    public function original_size_image_Action($img_id) {
        $ps_id = $img_id;
        $img_lst = $this->getDoctrine()
                ->getRepository('Acme\HelloBundle\Entity\Images')
                ->findByid($ps_id);


        return $this->render('AcmeHelloBundle:Default:whole_img_display.html.twig', array('piclist' => $img_lst, 'pid' => $ps_id));
    }

// display all images where status =1
    public function display_all_images_forAction($id) {
        $postr_id = $id;
        //$img_list = $this->getDoctrine()->getRepository('Acme\HelloBundle\Entity\Images')->findByPosterid($postr_id);

        $searchresult = $this->getDoctrine()->getEntityManager()
                ->createQuery('select imgtbl.id,imgtbl.posterid,imgtbl.imgName,imgtbl.path,imgtbl.status from AcmeHelloBundle:Images imgtbl WHERE imgtbl.status=:sts and imgtbl.posterid=:pstrid')
                ->setParameter('sts', '1')
                ->setParameter('pstrid', $id)
                ->getResult();
        return $this->render('AcmeHelloBundle:Default:display_image.html.twig', array('piclist' => $searchresult));
    }

// working version by Yan for picture upload to server and saving data to images table:
    public function uploadAction($id) {
        $image = new Images();
        $em = $this->getDoctrine()->getEntityManager();
        if (isset($_POST['submit'])) {
            $allowedExts = array("gif", "jpeg", "jpg", "png");
            $name = $_FILES['file']['name'];
            $temp = explode(".", $name);
            $orignalname = pathinfo($name);
            $filename = $orignalname['filename'] . '_' . 'id' . $this->encrypt($id);
            $ext = $orignalname['extension'];

            $type = $_FILES['file']['type'];
            $size = $_FILES['file']['size'];
            $extension = end($temp);

            if ((($type == "image/gif") || ($type == "image/jpeg") ||
                    ($type == "image/jpg") || ($type == "image/pjpeg") ||
                    ($type == "image/x-png") || ($type == "image/png")) && ($size < 200000000000) && in_array($extension, $allowedExts)
            ) {
                if ($_FILES["file"]["error"] > 0) {
                    echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
                } else {
                    $path = '%kernel.root_dir%/../uploads/';
                    $filepath = $path . $filename . '.' . $ext;
                    $name = basename($filepath);
                    $index = rand(1, 10000);

                    if (file_exists($filepath)) {
                        $name = $filename . $index . '.' . $ext;
                    }

                    move_uploaded_file($_FILES["file"]["tmp_name"], $path . $name);


                    //Add to database
                    $image->setPosterid($id);
                    $image->setimgName($name);
                    $image->setPath($filepath);
                    $image->setStatus(1);  //by default set to 1 to display
                    $em->persist($image);
                    $em->flush();
                    // echo "Uploaded!";
                    return $this->redirect($this->generateUrl('mypost_page'));
                }
            } else {
                echo $name;
                echo "Invalid file";
            }
        }
        return $this->render('AcmeHelloBundle:Default:uploadfile.html.twig');
    }

//poster Edit function

    public function editposterAction($id, Request $request) {
        $em = $this->getDoctrine()->getEntityManager();
        $addresslist = $this->getDoctrine()
                ->getRepository('Acme\HelloBundle\Entity\Location')
                ->findOneById($id);
        $poster = $addresslist->getPosterid();
        $myid = $this->getsession();
        if ($poster != $myid) {
            return $this->redirect($this->generateUrl('newaddress_page'));
        } else {
            if (isset($_POST['editpost'])) {
                $id = $_POST['id'];
                $addresslist = $this->getDoctrine()
                        ->getRepository('Acme\HelloBundle\Entity\Location')
                        ->findOneById($id);
                $title = $_POST['title'];
                $street = $_POST['street'];
                $avenue = $_POST['avenue'];
                $description = $_POST['description'];
                $contact = $_POST['contact'];
                $renttype = $_POST['renttype'];

                $addresslist->setTitle($title);
                $addresslist->setDescription($description);
                $addresslist->setStreet($street);
                $addresslist->setAvenue($avenue);
                $addresslist->setContact($contact);
                $addresslist->setRenttype($renttype);

                $date = new \DateTime();
                //$date = date("m-d-Y");
                $addresslist->setEditdate($date);
                $em->merge($addresslist);
                $em->flush();
                return $this->redirect($this->generateUrl('mypost_page'));
                // return $this->render('AcmeHelloBundle:Default:mypost.html.twig');
            } elseif (isset($_POST['delete'])) {
                $id = $_POST['id'];
                $addresslist = $this->getDoctrine()
                        ->getRepository('Acme\HelloBundle\Entity\Location')
                        ->findOneById($id);
                if (!$addresslist) {
                    throw $this->createNotFoundException('No guest found for id ' . $id);
                }
                $em->remove($addresslist);
                $em->flush();
                return $this->redirect($this->generateUrl('mypost_page'));
            }

            return $this->render('AcmeHelloBundle:Default:editposter.html.twig', array('address' => $addresslist));
        }
    }

    public function Delete_this_post_Action($this_post_id) {
        $id = $this_post_id;
        $em = $this->getDoctrine()->getEntityManager();
        $addresslist = $this->getDoctrine()
                ->getRepository('Acme\HelloBundle\Entity\Location')
                ->findOneById($id);

        if (isset($_POST['deleteConfmd'])) {
            $id = $_POST['id'];
            $addresslist = $this->getDoctrine()
                    ->getRepository('Acme\HelloBundle\Entity\Location')
                    ->findOneById($id);
            if (!$addresslist) {
                throw $this->createNotFoundException('No guest found for id ' . $id);
            }
            $em->remove($addresslist);
            $em->flush();
            return $this->redirect($this->generateUrl('mypost_page'));
        } elseif (isset($_POST['cancel'])) {
            //$session = $this->getRequest()->getSession();
            //$poster = $session->get('login');
            $poster = $this->getsession();
            $addresslist = $this->getDoctrine()
                    ->getRepository('Acme\HelloBundle\Entity\Location')
                    ->findByPosterid($poster);
            return $this->render('AcmeHelloBundle:Default:mypost.html.twig', array('myposterinfo' => $addresslist));
        }


        return $this->render('AcmeHelloBundle:Default:delete_post_confirmation.html.twig', array('address' => $addresslist));
    }

////////////////////// to display list of youtube vedios and uploaded vedios to the profile page per poster id

    public function vdolist_Action($id) {
        $postr_id = $id;

        //masum added
        $qryforutube = $this->getDoctrine()->getEntityManager()
                ->createQuery('select vdotbl.id,vdotbl.posterid,vdotbl.path,vdotbl.status,vdotbl.idcode from AcmeHelloBundle:Videos vdotbl WHERE vdotbl.status=:sts and vdotbl.posterid=:pstrid and vdotbl.idcode=:idkod')
                ->setParameter('sts', '1')
                ->setParameter('pstrid', $id)
                ->setParameter('idkod', '2')
                ->getResult(); //idkode 2 for youtube vdo
        // masum added
        $ytube = 2;
        $st = 1;
        $v = 0;
        $videopath = "";
        $vdocode = $this->getDoctrine()
                ->getRepository('Acme\HelloBundle\Entity\Videos')
                ->findby(array('idcode' => $ytube, 'posterid' => $id, 'status' => $st));

        if ($vdocode != null || $vdocode != 0) {
            $vidarray = array();
            foreach ($vdocode as $v) {
                //$path = $video->getPath();
                $path = $v->getPath();
                if ($this->isValidYoutubeURL($path) == true) {
                    $v = "youtube";
                    $videopath = $this->getYoutubeIdFromUrl($path);
                    $vidarray [] = $videopath;
                } else {

                    $videopath = $path;
                }
            }
        } else {
            $videopath = "";
            $v = 0;
        }


        $qryresult = $this->getDoctrine()->getEntityManager()
                ->createQuery('select vdotbl.id,vdotbl.posterid,vdotbl.path,vdotbl.status
									   from AcmeHelloBundle:Videos vdotbl
									   WHERE vdotbl.status=:sts and vdotbl.posterid=:pstrid and vdotbl.idcode=:idkod')
                ->setParameter('sts', '1')
                ->setParameter('pstrid', $id)
                ->setParameter('idkod', '3')
                ->getResult(); //idkode 3 for uploaded vdo

        return $this->render('AcmeHelloBundle:Default:show_all_vdos.html.twig', array('utubelink_id' => $qryforutube, 'piclist' => $qryresult, 'qutube' => $vidarray, 'qtube' => $vdocode, 'vmsg' => $v, 'vedio' => $videopath));
    }

//UPload Video process
    public function uploadvideoAction($id) {
        $idkode = 0; // default value set to 0.
        $video = new Videos();
        $em = $this->getDoctrine()->getEntityManager();
        if (isset($_POST['submit'])) {
            $url = $_POST['url'];
            $name = $_FILES['file']['name'];
            if (($url == "" && !$name ) || ($url != "" && $name != "")) {
                echo "Please select ONE! video file OR  enter a YOUTUBE link. Thank you!";
            } elseif ($url !== "") {
                if ($this->isValidYoutubeURL($url) == true) {
                    //$youtubeid = $this->getYoutubeIdFromUrl($url);
                    $filepath = $url;
                    $idkode = 2;  // idcode is to identify youtube vedio or uploaded vedio. code 2 is for youtube, 3 for uploaded vedio
                    // echo $filepath;
                } else {
                    echo "Invalid Youtube Link";
                }
            } else {
                //defines variables
                $vname = $_FILES['file']['name'];
                $temp = explode(".", $vname);
                $orignalname = pathinfo($vname);
                $filename = $orignalname['filename'] . '_' . 'id' . $this->encrypt($id);
                $ext = $orignalname['extension'];

                $type = $_FILES['file']['type'];
                $size = $_FILES['file']['size'];
                //$tmp_name=$_FILES['file']['tmp_name'];
                $target_path = '%kernel.root_dir%/../vedios/';

                $allowedTypes = array("video/wmv", "video/avi", "video/mpeg", "video/mpg", "video/mp4", "video/mov");
                $is_valid_type = (in_array($_FILES['file']['type'], $allowedTypes)) ? true : false;

                if ($is_valid_type && ($_FILES["file"]["size"] < 30000000000)) {
                    if ($_FILES["file"]["error"] > 0) {
                        echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
                    } else {
                        echo "Upload:&nbsp; " . $_FILES["file"]["name"] . "<br>";
                        echo "Type:&nbsp; " . $_FILES["file"]["type"] . "<br>";
                        echo "Size:&nbsp; " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
                        //echo "Temp file:&nbsp; " . $_FILES["file"]["tmp_name"] . "<br>";



                        if (file_exists($target_path . $_FILES["file"]["name"])) {
                            echo $_FILES["file"]["name"] . " already exists. ";
                        } else {
                            $path = '%kernel.root_dir%/../vedios/';
                            $filepath = $path . $filename . '.' . $ext;
                            $name = basename($filepath);
                            $index = rand(1, 10000);
                            if (file_exists($filepath)) {
                                $name = $filename . $index . '.' . $ext;
                            }
                            move_uploaded_file($_FILES["file"]["tmp_name"], $path . $name);
                            echo "Stored in: " . "vedios/" . $filename;
                            $idkode = 3; // 3 for user uploaded vedio
                        }
                    }
                } else {
                    echo "Invalid file type, please try again. Thank you.";
                }
            }
            //Add to database
            $video->setPosterid($id);
            //$video->setVdoname($name);
            $video->setPath($filepath);
            $video->setStatus(1);  //by default set to 1 to display
            $video->setIdcode($idkode);
            $em->persist($video);
            $em->flush();
            echo "Uploaded!";
            return $this->redirect($this->generateUrl('mypost_page'));
        }
        return $this->render('AcmeHelloBundle:Default:uploadvideo.html.twig');
    }

    //Youtube video Process

    function isValidYoutubeURL($url) {

        // Let's check the host first
        $parse = parse_url($url);
        //echo $url;
        $host = $parse['host'];
        //echo $host;
        if (!in_array($host, array('http://www.youtube.com', 'www.youtube.com'))) {
            return false;
        }

        $ch = curl_init();
        $oembedURL = 'www.youtube.com/oembed?url=' . urlencode($url) . '&format=json';

        curl_setopt($ch, CURLOPT_URL, $oembedURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // Silent CURL execution
        $output = curl_exec($ch);
        unset($output);

        $info = curl_getinfo($ch);

        curl_close($ch);

        if ($info['http_code'] !== 404)
            return true;
        else
            return false;
    }

    public function getYoutubeIdFromUrl($url) {
        $parts = parse_url($url);
        if (isset($parts['query'])) {
            parse_str($parts['query'], $qs);
            if (isset($qs['v'])) {
                return $qs['v'];
            } else if ($qs['vi']) {
                return $qs['vi'];
            }
        }
        if (isset($parts['path'])) {
            $path = explode('/', trim($parts['path'], '/'));
            return $path[count($path) - 1];
        }
        return false;
    }

    function isValid($what, $data) {

        switch ($what) {

            // validate a phone number
            case 'phone':
                $pattern = "/^([1]-)?[0-9]{3}-[0-9]{3}-[0-9]{4}$/i";
                break;

            // validate email address
            case 'email':
                $pattern = "/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/i";
                break;

            default:
                return false;
                break;
        }

        return preg_match($pattern, $data) ? true : false;
    }

// user profile display
    public function user_profile_Action(Request $request) {

        return $this->render('AcmeHelloBundle:Default:user_profile.html.twig');
    }

//change password form

    public function change_user_passwd_Action(Request $request) {

        return $this->render('AcmeHelloBundle:Default:change_user_passwd.html.twig');
    }

// load all criteria from db - Advance filtering added by masum
// add this lines to usermenu to activate below function which was developed by masum for advance search option
//<a href="{{path('advance_search_criteria__page')}}">????</a>
//after else
//<a href="{{path('advance_search_criteria__page')}}">????<br/>
    //<div class="loginlink">Advance Search</div>
    //</a>

    public function advance_search_criteria_Action(Request $request) {

        $bedroom = $this->getDoctrine()
                ->getRepository('Acme\HelloBundle\Entity\bedrooms')
                ->findAll();

        $price = $this->getDoctrine()
                ->getRepository('Acme\HelloBundle\Entity\Price')
                ->findAll();

        $laundry = $this->getDoctrine()
                ->getRepository('Acme\HelloBundle\Entity\laundry')
                ->findAll();

        $bath = $this->getDoctrine()
                ->getRepository('Acme\HelloBundle\Entity\Bath')
                ->findAll();

        $zip_srch = $this->getDoctrine()->getEntityManager()
                ->createQuery('select distinct loc.zipcode from AcmeHelloBundle:Location loc')
                ->getResult();


        if ($request->getMethod() == "POST") {
            $abcd = $_POST['selectoption'];
            $bedcombotxt = $_POST['bedtxt'];
            $bathcombotxt = $_POST['bathtxt'];
            $pricecombotxt = $_POST['pricetxt'];
            $londrycombotxt = $_POST['londrytxt'];
            $zipcombotxt = $_POST['ziptxt'];

            if (!preg_match("/^([0-9]{5})(-[0-9]{4})?$/i", $zipcombotxt)) {
                echo "invalid zipcode";
            } else {
                $minzip = $zipcombotxt - 5;
                $maxzip = $zipcombotxt + 5;

                $zipcode = array(range($minzip, $maxzip));

                // search result by number of bedroom, studio
                $srch_bdrm_renttype_id = $this->getDoctrine()
                        ->getRepository('Acme\HelloBundle\Entity\bedrooms')
                        ->findby(array('bdroomtype' => $bedcombotxt));

                // we can use AND operation here if we need all conditions needs to be true
                if ($abcd == 1) {
                    $adv_srch = $this->getDoctrine()->getEntityManager()
                            ->createQuery('select x.id, x.title, x.numbath, x.renttype, x.laundryfacility, x.rentamount, x.zipcode
										   from AcmeHelloBundle:Location x where (x.numbath =:bath or x.renttype =:bdroom
										   or x.rentamount=:rentamnt or x.laundryfacility =:lndry) or x.zipcode IN (:zip)')
                            ->setParameter('bath', $bathcombotxt)
                            ->setParameter('bdroom', $srch_bdrm_renttype_id)
                            ->setParameter('rentamnt', $pricecombotxt)
                            ->setParameter('lndry', $londrycombotxt)
                            ->setParameter('zip', $zipcode)
                            ->getResult();
                    $youchosed = "Any Word matches";
                }

                if ($abcd == 2) {
                    $adv_srch = $this->getDoctrine()->getEntityManager()
                            ->createQuery('select x.id, x.title, x.numbath, x.renttype, x.laundryfacility, x.rentamount,x.zipcode
										   from AcmeHelloBundle:Location x where (x.numbath =:bath and x.renttype =:bdroom
										   and x.rentamount=:rentamnt and x.laundryfacility =:lndry) OR x.zipcode IN (:zip)')
                            ->setParameter('bath', $bathcombotxt)
                            ->setParameter('bdroom', $srch_bdrm_renttype_id)
                            ->setParameter('rentamnt', $pricecombotxt)
                            ->setParameter('lndry', $londrycombotxt)
                            ->setParameter('zip', $zipcombotxt)
                            ->getResult();
                    $youchosed = "Only Word matches";
                }


                /*
                  $srchres_byzip = $this->getDoctrine()->getEntityManager()
                  ->createQuery('select x.id, x.title, x.numbath, x.renttype, x.laundryfacility, x.rentamount
                  from AcmeHelloBundle:Location x WHERE x.zipcode=:zip')
                  ->setParameter('zip', $zipcombotxt)
                  ->getResult();// this will return ID for apartment type from bdrooms tbl, for exmaple: 1 =studio, 2= 1 bedroom..so on

                 */


                // final return
                return $this->render('AcmeHelloBundle:Default:advanced_search_criteria.html.twig', array('bedrooms' => $bedroom, 'ranze' => $price, 'lndry' => $laundry, 'bath' => $bath, 'bb' => $bedcombotxt,
                            'cc' => $bathcombotxt, 'dd' => $pricecombotxt, 'ee' => $londrycombotxt, 'det' => "..more", 'ff' => $youchosed, 'gg' => $zipcombotxt,
                            'selectedtext' => $bedcombotxt, 'no_of_bath' => $bathcombotxt,
                            'pricerangeselected' => $pricecombotxt,
                            'laundryselected' => $londrycombotxt, 'adv_srch_res' => $adv_srch));
            }
        }
        return $this->render('AcmeHelloBundle:Default:advanced_search_criteria.html.twig', array('bedrooms' => $bedroom, 'ranze' => $price, 'lndry' => $laundry, 'bath' => $bath, 'bb' => '',
                    'cc' => '', 'dd' => '', 'ee' => '', 'ff' => '', 'srchres_forbdroom1' => '', 'det' => '', 'srchresultfor_bath' => '', 'selectedtext' => '',
                    'no_of_bath' => '',
                    'bdrm_byprice' => '', 'pricerangeselected' => '',
                    'bdrm_bylaundry' => '', 'laundryselected' => '', 'adv_srch_res' => '', 'gg' => ''));
    }

    /* admin section started */

    public function AdministratorAction(Request $request) {
        return $this->render('AcmeHelloBundle:Default:administrator.html.twig');
    }

    public function adminAction(Request $request) {
        return $this->render('AcmeHelloBundle:Default:admin.html.twig', array('usr' => '', 'unmmsg' => ''));
    }

    public function AlluserAction(Request $request) {
        $user_list = $this->getDoctrine()->getEntityManager()
                ->createQuery('select x.id, x.code, x.password, x.active
									   from AcmeHelloBundle:Userinfo x
									   where x.active=:sts')
                ->setParameter('sts', 1)
                ->getResult();

        return $this->render('AcmeHelloBundle:Default:alluser.html.twig', array('user_list' => $user_list));
    }

//this function displays, how many poster, picture and other stuff he submitted- access by admin

    public function memberAction($id) {
        $usrid = $id;
        //find user code: ex; 1580
        $kode = 0;
        $srch = $this->getDoctrine()
                ->getRepository('Acme\HelloBundle\Entity\Userinfo')
                ->findby(array('id' => $usrid));

        foreach ($srch as $v) {
            //$path = $video->getPath();
            $kode = $v->getCode();
        }

        //count number of poster that a user posted in his account
        $number_of_poster = $this->getDoctrine()->getEntityManager()->createQueryBuilder()
                ->select('COUNT(loc.posterid)')
                ->from('AcmeHelloBundle:Location', 'loc')
                ->where('loc.posterid = :id')
                ->setParameter('id', $kode)
                ->getQuery();
        $total_poster = $number_of_poster->getSingleScalarResult();

        //display poster numbers : example 45, 47, 56 for user=1580
        $srch_list_ofposter = $this->getDoctrine()->getEntityManager()
                ->createQuery('select x.id, x.posterid
									   from AcmeHelloBundle:location x
									   where x.posterid=:sts')
                ->setParameter('sts', $kode)
                ->getResult();
        //--------------------------------------------------

        return $this->render('AcmeHelloBundle:Default:user.html.twig', array('bb' => $srch, 'aa' => $total_poster, 'de' => $kode, 'poster_id' => $srch_list_ofposter));
    }

//admin: find out list of vedios by poster id
    public function userpostedvediossAction($id) {

        $qryforutube = $this->getDoctrine()->getEntityManager()
                ->createQuery('select vdotbl.id,vdotbl.posterid,vdotbl.path,vdotbl.status,vdotbl.idcode
									   from AcmeHelloBundle:Videos vdotbl
									   WHERE vdotbl.posterid=:pstrid and vdotbl.idcode=:idkod')
                ->setParameter('pstrid', $id)
                ->setParameter('idkod', '2')
                ->getResult(); //idkode 2 for youtube vdo
        // masum added
        $ytube = 2;
        $st = 1;
        $v = 0;
        $videopath = "";
        $vdocode = $this->getDoctrine()
                ->getRepository('Acme\HelloBundle\Entity\Videos')
                ->findby(array('idcode' => $ytube, 'posterid' => $id));

        if ($vdocode != null || $vdocode != 0) {
            $vidarray = array();
            foreach ($vdocode as $v) {
                $path = $v->getPath();
                if ($this->isValidYoutubeURL($path) == true) {
                    $v = "youtube";
                    $videopath = $this->getYoutubeIdFromUrl($path);
                    $vidarray [] = $videopath;
                } else {

                    $videopath = $path;
                }
            }
        } else {
            $videopath = "";
            $v = 0;
        }


        $qryresult = $this->getDoctrine()->getEntityManager()
                ->createQuery('select vdotbl.id,vdotbl.posterid,vdotbl.path,vdotbl.status
									   from AcmeHelloBundle:Videos vdotbl
									   WHERE vdotbl.posterid=:pstrid and vdotbl.idcode=:idkod')
                ->setParameter('pstrid', $id)
                ->setParameter('idkod', '3')
                ->getResult(); //idkode 3 for uploaded vdo


        return $this->render('AcmeHelloBundle:Default:listofvdosbyposterid.html.twig', array('poster_id' => $qryforutube, 'psnum' => $id, 'utubelink_id' => $qryforutube,
                    'piclist' => $qryresult, 'qutube' => $vidarray, 'qtube' => $vdocode, 'vmsg' => $v, 'vedio' => $videopath));
    }

// admin: can activate a videos viewable from public or owner
    public function activate_vdoAction($id) {
        $em = $this->getDoctrine()->getEntityManager();
        $id = $id;
        $vdolist = $this->getDoctrine()
                ->getRepository('Acme\HelloBundle\Entity\Videos')
                ->findOneByid($id);
        $activetxt = 1;
        $vdolist->setStatus($activetxt);

        $em->merge($vdolist);
        $em->flush();

        $srch = $this->getDoctrine()
                ->getRepository('Acme\HelloBundle\Entity\Videos')
                ->findby(array('id' => $id));

        foreach ($srch as $v) {

            $psid = $v->getPosterid();
        }

        return $this->redirect($this->generateUrl('userpostedvedioss_page', array('id' => $psid)));
    }

// admin: can deactivate a vdo viewable from public or owner
    public function deactivate_vdoAction($id) {
        $em = $this->getDoctrine()->getEntityManager();
        $id = $id;
        $vdolist = $this->getDoctrine()
                ->getRepository('Acme\HelloBundle\Entity\Videos')
                ->findOneByid($id);
        $activetxt = 0;
        $vdolist->setStatus($activetxt);

        $em->merge($vdolist);
        $em->flush();

        $srch = $this->getDoctrine()
                ->getRepository('Acme\HelloBundle\Entity\Videos')
                ->findby(array('id' => $id));

        foreach ($srch as $v) {

            $psid = $v->getPosterid();
        }

        return $this->redirect($this->generateUrl('userpostedvedioss_page', array('id' => $psid)));
    }

//admin:find out list of pictures by poster id
    public function userpostedpicturesAction($id) {
        $srch_list_ofpictures = $this->getDoctrine()->getEntityManager()
                ->createQuery('select img.id, img.posterid, img.status, img.path
									   from AcmeHelloBundle:Images img
									   where img.posterid=:sts')
                ->setParameter('sts', $id)
                ->getResult();



        return $this->render('AcmeHelloBundle:Default:listofpicturesbyposterid.html.twig', array('poster_id' => $srch_list_ofpictures, 'psnum' => $id));
    }

// admin: can activate a picture viewable from public or owner
    public function activate_pictureAction($id) {
        $em = $this->getDoctrine()->getEntityManager();
        $id = $id;
        $imglist = $this->getDoctrine()
                ->getRepository('Acme\HelloBundle\Entity\Images')
                ->findOneByid($id);
        $activetxt = 1;
        $imglist->setStatus($activetxt);

        $em->merge($imglist);
        $em->flush();

        $srch = $this->getDoctrine()
                ->getRepository('Acme\HelloBundle\Entity\Images')
                ->findby(array('id' => $id));

        foreach ($srch as $v) {

            $psid = $v->getPosterid();
        }

        return $this->redirect($this->generateUrl('userpostedpictures_page', array('id' => $psid)));
    }

// admin: can deactivate a picture viewable from public or owner
    public function deactivate_pictureAction($id) {
        $em = $this->getDoctrine()->getEntityManager();
        $id = $id;
        $imglist = $this->getDoctrine()
                ->getRepository('Acme\HelloBundle\Entity\Images')
                ->findOneByid($id);
        $activetxt = 0;
        $imglist->setStatus($activetxt);

        $em->merge($imglist);
        $em->flush();

        $srch = $this->getDoctrine()
                ->getRepository('Acme\HelloBundle\Entity\Images')
                ->findby(array('id' => $id));

        foreach ($srch as $v) {

            $psid = $v->getPosterid();
        }

        return $this->redirect($this->generateUrl('userpostedpictures_page', array('id' => $psid)));
    }

//admin: deactive a user means status set to = 0, this will redirect alluser.html.twig page
    public function deactivatedAction($id) {
        $em = $this->getDoctrine()->getEntityManager();
        $id = $id;
        $memberlst = $this->getDoctrine()
                ->getRepository('Acme\HelloBundle\Entity\Userinfo')
                ->findOneByid($id);
        $activetxt = 0;
        $memberlst->setActive($activetxt);

        $em->merge($memberlst);
        $em->flush();

        return $this->redirect($this->generateUrl('Alluser_page'));
    }

//admin: activate a user means status set to :1
    public function activatedAction($id) {
        $em = $this->getDoctrine()->getEntityManager();
        $id = $id;
        $memberlst = $this->getDoctrine()
                ->getRepository('Acme\HelloBundle\Entity\Userinfo')
                ->findOneByid($id);
        $activetxt = 1;
        $memberlst->setActive($activetxt);

        $em->merge($memberlst);
        $em->flush();

        return $this->redirect($this->generateUrl('blockuser_page'));
    }

//admin: block an user
    public function blockuserAction(Request $request) {

        // Only user status is - 0 displaying here

        $user_list = $this->getDoctrine()->getEntityManager()
                ->createQuery('select x.id, x.code, x.active
									   from AcmeHelloBundle:Userinfo x where x.active=:usercode')
                ->setParameter('usercode', 0)
                ->getResult();

        return $this->render('AcmeHelloBundle:Default:blockuser.html.twig', array('user_list' => $user_list));
    }

// ******************admin register

    public function adminregisterAction(Request $request) {
        $em = $this->getDoctrine()->getEntityManager();
        $limit = 1;
        $offset = 0;
        $bool = false;

        if ($request->getMethod() == "POST") {
            $username = $_POST['unametxt'];
            $password = $_POST['password'];
            $repass = $_POST['repassword'];

            $bool = $this->checkpassword($password, $repass);

            if ($bool == true) {

                $username = $this->encrypt($username);
                $password = $this->encrypt($password);
                $dataadmin = new Admin();

                $dataadmin->setAdminname($username);
                $dataadmin->setPassword($password);
                $dataadmin->setStatus(1);

                $em->persist($dataadmin);
                $em->flush();

                $uname = $username;
                //return new Response("Registered!");
                echo "Successfully Registered, you will be redirected automatically in 3 sec!!";
                $this->setsession($uname);
                return $this->redirect($this->generateUrl('Administrator_page'));
            } else {
                return new Response("Please enter your password correctly. Make sure it's betwen 7 to 13 character!");
            }
        }
        return $this->render('AcmeHelloBundle:Default:admin_register.html.twig');
    }

//admin login

    public function adminloginAction(Request $request) {
        //$user = new Userinfo();
        $dataadmin = new Admin();

        if ($request->getMethod() == "POST") {
            $uname = $_POST['usernametxt'];
            $enc_this_uname = $this->encrypt($uname);
            /*
              $data = $this->getDoctrine()
              ->getRepository('Acme\HelloBundle\Entity\admin')
              ->findOneByadminname($enc_this_uname);
             */
            //find user name
            $data = $this->getDoctrine()
                    ->getRepository('Acme\HelloBundle\Entity\admin')
                    ->findby(array('adminname' => $enc_this_uname));
            $psid = '';

            foreach ($data as $v) {

                $psid = $v->getAdminname();
                $pas = $v->getPassword();
            }
            //echo $psid;
            $msgnotfound = "";

            if ($psid == null || $psid == 0)
                $msgnotfound = "Username not Correct/Not found";

            if ($enc_this_uname == $psid) {


                $pass1 = $_POST['password'];
                $password = $this->encrypt($pass1);

                if ($enc_this_uname == $psid AND $pas == $password) {
                    $this->setsession($uname);
                    return $this->redirect($this->generateUrl('Administrator_page'));
                } else {
                    $msgnotfound = "Please enter password/incorrect";
                    //return new Response("Username/Password didn't match, Please type very Carefully");
                }
            }

            return $this->render('AcmeHelloBundle:Default:admin.html.twig', array('usr' => $psid, 'unmmsg' => $msgnotfound));
        }

        return $this->render('AcmeHelloBundle:Default:admin.html.twig', array('usr' => '', 'unmmsg' => ''));
    }

//admin logout

    public function adminlogoutAction(Request $request) {
        $request->getSession()->invalidate();
        return $this->render('AcmeHelloBundle:Default:admin.html.twig', array('usr' => '', 'unmmsg' => ''));
    }

    /*
      public function greetingAction(){
      $request = $this->get('request');
      $name=$request->request->get('formName');
      if($name!=""){//if the user has written his name
      $greeting='Hello '.$name.'. How are you today?';
      $return=array("responseCode"=>200,  "greeting"=>$greeting);
      }
      else{
      $return=array("responseCode"=>400, "greeting"=>"You have to write your name!");
      }
      $return=json_encode($return);//jscon encode the array
      return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type

      } */
    /*
      public function Delete_this_image_Action($this_img_id)
      {
      $em = $this->getDoctrine()->getEntityManager();
      $ps_id = $this_img_id;
      $single_img_row = $this->getDoctrine()->getRepository('Acme\HelloBundle\Entity\Images')->findOneByid($ps_id);

      if(isset($_POST['deleteConfirmed'])){
      $id = $_POST['id'];
      $single_img_row = $this->getDoctrine()
      ->getRepository('Acme\HelloBundle\Entity\Images')
      ->findOneByid($ps_id);

      $id = $_POST['id'];
      $pstr_id = $_POST['posteridtxt'];
      $imgnm = $_POST['imgNametxt'];
      $path = $_POST['pathtxt'];
      $status = $_POST['statustxt'];

      $single_img_row->setPosterid($pstr_id);
      $single_img_row->setImgName($imgnm);
      $single_img_row->setPath($path);
      $single_img_row->setStatus($status);


      $em->merge($single_img_row);
      $em->flush();

      return $this->redirect($this->generateUrl('imageDisplay_page', array('id'=>$pstr_id)));
      }
      elseif(isset($_POST['cancel-or-not-deletebtn']))
      {
      $id = $_POST['id'];
      $single_img_row = $this->getDoctrine()
      ->getRepository('Acme\HelloBundle\Entity\Images')
      ->findOneByid($ps_id);
      $pstr_id = $_POST['posteridtxt'];
      return $this->redirect($this->generateUrl('imageDisplay_page', array('id'=>$pstr_id)));
      }
     */
    /*
      public function registerAction(Request $request){
      $em = $this->getDoctrine()->getEntityManager();
      $limit=1;
      $offset=0;
      $bool = false;
      $code = $this->getDoctrine()->getEntityManager()
      ->createQuery("select u.code from AcmeHelloBundle:Userinfo u WHERE u.active = 0")
      ->setFirstResult($offset)
      ->setMaxResults($limit)
      ->getResult();
      if($request->getMethod()=="POST"){
      $code = $_POST['code'];
      $password = $_POST['password'];
      $repass = $_POST['repassword'];
      $bool =  $this->checkpassword($password,$repass);
      if($bool==true){
      $password = $this->encrypt($password);
      //$user = new Userinfo();
      $data = $this->getDoctrine()
      ->getRepository('Acme\HelloBundle\Entity\Userinfo')
      ->findOneByCode($code);
      $data->setactive(1);
      $data->setpassword($password);
      $em->merge($data);
      $em->flush();
      return new Response("Registered!");
      }

      else{
      return new Response("Please enter your password correctly. Make sure it's betwen 7 to 13 character!");
      }
      }
      return $this->render('AcmeHelloBundle:Default:register.html.twig', array('code' => $code));
      }

      public function loginAction(Request $request){
      $user = new Userinfo();

      if($request->getMethod()== "POST")
      {
      $code = $_POST['code'];
      $data = $this->getDoctrine()
      ->getRepository('Acme\HelloBundle\Entity\Userinfo')
      ->findOneByCode($code);
      $pass = $data->getpassword();
      $pass1 = $_POST['password'];
      $password = $this->encrypt($pass1);
      if($pass==$password)
      {
      $this->setsession($code);
      return $this->redirect($this->generateUrl('newaddress_page'));
      }
      else
      {
      return new Response("password doesn't match");
      }
      }

      $form = $this->createFormBuilder($user)
      ->add('code', 'text')
      ->add('password', 'password')
      ->getForm();

      return $this->render('AcmeHelloBundle:Default:login.html.twig', array('form' => $form->createView()));
      }
     */
/////////////////////end of file
}

