<?php

namespace MusicBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use MusicBundle\Entity\Playlist;
use MusicBundle\Entity\Songs;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use getID3;
use getid3_lib;

class MusicController extends Controller
{


    public function indexAction()
    {
        return $this->render('MusicBundle:Default:index.html.twig');
    }


    public function CreateAction(Request $request)
    {


        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $sn = $this->getDoctrine()->getManager();
        $playlist = new Playlist();
        $now = new\DateTime('now');
        $form = $this->createFormBuilder($playlist)
            ->add('name', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('songs', FileType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['songs']->getData();
//            $file = $playlist->getSongs();

            $songs = new Songs();
            // Generate a unique name for the file before saving it

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $name = $file->getClientOriginalName();
            // Move the file to the directory where brochures are stored
            $songdir = $this->container->getParameter('kernel.root_dir') . '/../web/uploads/music';
            $file->move($songdir, $fileName);

            $remotefilename = 'C:/xampp/htdocs/Pi-Dev-Web/web/uploads/music/' . $fileName;
            if ($fp_remote = fopen($remotefilename, 'rb')) {
                $localtempfilename = tempnam('C:/xampp/htdocs/Pi-Dev-Web/web/uploads/tmp', 'getID3');
                if ($fp_local = fopen($localtempfilename, 'wb')) {
                    while ($buffer = fread($fp_remote, 8192)) {
                        fwrite($fp_local, $buffer);
                    }
                    fclose($fp_local);
                    // Initialize getID3 engine
                    $getID3 = new getID3();
                    $songInfo = $getID3->analyze($localtempfilename);
                    getid3_lib::CopyTagsToComments($songInfo);
                    // Delete temporary file


                    $songs->setName($name);
                    $songs->setCname($fileName);
                    $songs->setArtist('comments_html');
                    $songs->setDuration((int)$songInfo['playtime_seconds']);
                    $songs->setAddDate($now);
                    $songs->setPlaylist(array($playlist));
                    $sn->persist($songs);

                    $name = $form['name']->getData();
                    $playlist->setName($name);
                    $playlist->setSongs(array($songs));
                    $playlist->setDateCreation($now);
                    $playlist->setOwner($user);

                    $sn->persist($playlist);
                    $sn->flush();


                    unlink($localtempfilename);

                }
                fclose($fp_remote);
            }


            unset($file);
        }
        $pl = $this->getDoctrine()->getRepository('MusicBundle:Playlist')->findByOwner($user);
        $other = $this->getDoctrine()->getRepository('MusicBundle:Playlist')->Others($user);


        return $this->render('@Music/Default/index.html.twig', array('pl' => $pl, 'other' => $other,

            'form' => $form->createView()

        ));


//
//
//        $user= $this->container->get('security.token_storage')->getToken()->getUser();
//        $sn = $this->getDoctrine()->getManager();
//        $playlist = new Playlist();
//
//        $now = new\DateTime('now');
//        $form = $this->createFormBuilder($playlist)
//            ->add('name', TextType::class, array('attr' => array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
//            ->add('songs', FileType::class, array('data_class' => null,'attr'=>array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
//            ->getForm();
//
//        if($form->isSubmitted() && $form->isValid()){
////            $songColl = new ArrayCollection();
//
//            $file = $form['songs']->getData();
//            $songdir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/music';
//
////            $file = $playlist->getSongs();
//
//                    $songs = new Songs();
//                    // Generate a unique name for the file before saving it
//                    $name = $file->getClientOriginalName();
//
//                    $fileName = md5(uniqid()).'.'.$file->guessExtension();
//
//                    $file->move($songdir, $fileName);
//
//                    $remotefilename = 'C:/xampp/htdocs/Pi-Dev-Web/web/uploads/music/'.$fileName;
//                    if ($fp_remote = fopen($remotefilename, 'rb')) {
//                        $localtempfilename = tempnam('C:/xampp/htdocs/Pi-Dev-Web/web/uploads/tmp', 'getID3');
//                        if ($fp_local = fopen($localtempfilename, 'wb')) {
//                            while ($buffer = fread($fp_remote, 8192)) {
//                                fwrite($fp_local, $buffer);
//                            }
//                            fclose($fp_local);
//                            // Initialize getID3 engine
//                            $getID3 = new getID3();
//                            $songInfo = $getID3->analyze($localtempfilename);
//                            getid3_lib::CopyTagsToComments($songInfo);
//
//                            $songs->setName($name);
//                            $songs->setCname($fileName);
//                            $songs->setArtist('comments_html');
//                            $songs->setDuration((int) $songInfo['playtime_seconds']);
//                            $songs->setAddDate($now);
//                            $songs->setPlaylist(array($playlist));
//                            $sn->persist($songs);
//
//                            $name = $form['name']->getData();
//                            $playlist->setName($name);
//                            $playlist->setSongs($songs);
//                            $playlist->setDateCreation($now);
//                            $playlist->setOwner($user);
//
//                            $sn->persist($playlist);
//                            $sn->flush();
//
//
//                            unlink($localtempfilename);
//
//                        }
//                        fclose($fp_remote);
//                    }
//
//
//                    unset($file);
//
//
//        }
//
//        $pl = $this->getDoctrine()->getRepository('MusicBundle:Playlist')->findByOwner($user);
//        $other = $this->getDoctrine()->getRepository('MusicBundle:Playlist')->Others($user);
//
//
//        return $this->render('@Music/Default/index.html.twig', array('pl'=>$pl,'other'=>$other,
//
//            'form'=>$form->createView()
//
//        ));

    }


    public function deleteAction($id)
    {

        $sn = $this->getDoctrine()->getManager();
        $playlist = $sn->getRepository('MusicBundle:Playlist')->find($id);
        $sn->remove($playlist);
        $sn->flush();

        return $this->redirectToRoute('music_homepage');

    }

    public function deleteSongAction($id)
    {

        $sn = $this->getDoctrine()->getManager();
        $song = $sn->getRepository('MusicBundle:Songs')->find($id);
        $sn->remove($song);
        $sn->flush();

        return $this->redirectToRoute('music_homepage');

    }


    public function addSongAction(Request $request2, $id)
    {


        $now = new\DateTime('now');
        $sn = $this->getDoctrine()->getManager();

        $playlist = $sn->getRepository('MusicBundle:Playlist')->find($id);

        $form2 = $this->createFormBuilder($playlist)
            ->add('songs', FileType::class, array(
                'data_class' => null, 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->getForm();

        $form2->handleRequest($request2);

        if ($form2->isSubmitted() && $form2->isValid()) {


            $file = $form2['songs']->getData();

            $songs = new Songs();
            // Generate a unique name for the file before saving it
            $name = $file->getClientOriginalName();

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
//                array_push ($this->paths, $fileName);
            // Move the file to the directory where brochures are stored
            $songdir = $this->container->getParameter('kernel.root_dir') . '/../web/uploads/music';
            $file->move($songdir, $fileName);

            $remotefilename = 'C:/xampp/htdocs/Pi-Dev-Web/web/uploads/music/' . $fileName;
            if ($fp_remote = fopen($remotefilename, 'rb')) {
                $localtempfilename = tempnam('C:/xampp/htdocs/Pi-Dev-Web/web/uploads/tmp', 'getID3');
                if ($fp_local = fopen($localtempfilename, 'wb')) {
                    while ($buffer = fread($fp_remote, 8192)) {
                        fwrite($fp_local, $buffer);
                    }
                    fclose($fp_local);
                    // Initialize getID3 engine
                    $getID3 = new getID3();
                    $songInfo = $getID3->analyze($localtempfilename);
                    getid3_lib::CopyTagsToComments($songInfo);
                    // Delete temporary file


                    $songs->setName($name);
                    $songs->setCname($fileName);
                    $songs->setArtist('comments_html');
                    $songs->setDuration((int)$songInfo['playtime_seconds']);
                    $songs->setAddDate($now);
                    $songs->setPlaylist(array($playlist));
                    $sn->persist($songs);

                    $songsArray = Array($playlist->getSongs(), $songs);

                    $playlist->setSongs($songsArray);


                    $sn->persist($playlist);
                    $sn->flush();


                    unlink($localtempfilename);

                }
                fclose($fp_remote);
            }


            unset($file);

//            return $this->redirectToRoute('todo_list');
        }

        return $this->render('@Music/Default/addSong.html.twig', array(

            'form2' => $form2->createView()

        ));


//        $sn = $this->getDoctrine()->getManager();
//        $playlist = $sn->getRepository('MusicBundle:Playlist')->find($id);
//
//        $now = new\DateTime('now');
//        $form2 = $this->createFormBuilder($playlist)
//            ->add('songs', FileType::class, array('data_class' => null,'attr'=>array('class'=>'form-control', 'style'=>'margin-bottom:15px')))
//            ->getForm();
//
//        $form2 -> handleRequest($request2);
//        if($form2->isSubmitted() && $form2->isValid()){
//            $song = $form2['songs']->getData();
//            $file = $playlist->getSongs();
//
//            $songs = new Songs();
//            // Generate a unique name for the file before saving it
//            $name = $file->getClientOriginalName();
//
//            $fileName = md5(uniqid()).'.'.$file->guessExtension();
////                array_push ($this->paths, $fileName);
//            // Move the file to the directory where brochures are stored
//            $songdir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/music';
//            $file->move($songdir, $fileName);
//
//            $remotefilename = 'C:/xampp/htdocs/Pi-Dev-Web/web/uploads/music/'.$fileName;
//            if ($fp_remote = fopen($remotefilename, 'rb')) {
//                $localtempfilename = tempnam('C:/xampp/htdocs/Pi-Dev-Web/web/uploads/tmp', 'getID3');
//                if ($fp_local = fopen($localtempfilename, 'wb')) {
//                    while ($buffer = fread($fp_remote, 8192)) {
//                        fwrite($fp_local, $buffer);
//                    }
//                    fclose($fp_local);
//                    // Initialize getID3 engine
//                    $getID3 = new getID3();
//                    $songInfo = $getID3->analyze($localtempfilename);
//                    getid3_lib::CopyTagsToComments($songInfo);
//                    // Delete temporary file
//
//
//
//
//                    $songs->setName($name);
//                    $songs->setCname($fileName);
//                    $songs->setArtist('comments_html');
//                    $songs->setDuration((int) $songInfo['playtime_seconds']);
//                    $songs->setAddDate($now);
//                    $songs->setPlaylists(array($playlist));
//                    $sn->persist($songs);
//                    $sn->flush();
//
//
//
//
//                    unlink($localtempfilename);
//
//                }
//                fclose($fp_remote);
//            }
//
//
//            unset($file);
//
//            $playlist->setName($playlist->getName());
//            $playlist->setSongs(array($songs));
//            $playlist->setDateCreation($now);
//            $playlist->setOwner($playlist->getOwner());
//
//            $sn->persist($playlist);
//            $sn->flush();
//
//
//
//
////            return $this->redirectToRoute('todo_list');
//        }
//
//        return $this->render('@Music/Default/addSong.html.twig', array(
//
//            'form2'=>$form2->createView()
//
//        ));


    }


}
