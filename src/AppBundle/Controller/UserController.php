<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\User;

use AppBundle\Form\Type\UserType;
use AppBundle\Form\Type\UserUpdateType;
use AppBundle\Form\Type\UserParameterType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @Route("/user/list", name="user_list")
     */
    public function listAction(Request $request)
    {
		$this->denyAccessUnlessGranted('ROLE_ADMIN');
		$em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')->findAll();
        // replace this example code with whatever you need
        return $this->render('user/list.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
			'users' => $users
        ]);
    }
	
	public function createAction(Request $request)
    {
		$this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = new User();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if($request->getMethod()=='POST'){
            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();
                $redirectRoute = 'user_list';
                
				$role = 'ROLE_USER';
                $user->addRole($role);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $this->addFlash('success','Enregistré avec succès');
                return $this->redirectToRoute($redirectRoute);
            }else{
               //dump($form->getErrors());die;
            }
        }

        return $this->render('user/create.html.twig', array(
            'form' => $form->createView(),

        ));

    }
	
	public function updateAction($id,Request $request)
    {
		$this->denyAccessUnlessGranted('ROLE_ADMIN');
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($id);
        try {
           $id = $user->getId();
        } catch (\Doctrine\ORM\EntityNotFoundException $ex) {
            echo "Exception Found - " . $ex->getMessage() . "<br/>";
        }
        $form = $this->createForm(UserUpdateType::class, $user);
		
        
        $form->handleRequest($request);
        if($request->getMethod()=='POST'){
            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();
                $redirectRoute ='user_list';
                
                 $em = $this->getDoctrine()->getManager();
                 $em->persist($user);
                 $em->flush();
                $this->addFlash('success','Enregistré avec succès');
                return $this->redirectToRoute($redirectRoute); //$redirectRoute
            }else{
				$errors = $form->getErrors(true);
				//print_r($errors);
			}
        }
        return $this->render('user/update.html.twig', array(
            'form' => $form->createView(),
            'user' => $user

        ));

    }
	
	public function deleteAction( $id)
    {
		$this->denyAccessUnlessGranted('ROLE_ADMIN');
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($id);
        $em->remove($user);
        $em->flush();
        $this->addFlash("success", "L'utilisateur a bien été supprimée");
        $url = $this->generateUrl('user_list');
        return $this->redirect($url);

    }
	
	public function updateParameterAction( Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        
        $form = $this->createForm(UserParameterType::class, $user);
		
        
        $form->handleRequest($request);
        if($request->getMethod()=='POST'){
            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();
                $redirectRoute ='homepage';
                
                 $em = $this->getDoctrine()->getManager();
                 $em->persist($user);
                 $em->flush();
                $this->addFlash('success','Enregistré avec succès');
                return $this->redirectToRoute($redirectRoute); //$redirectRoute
            }else{
				$errors = $form->getErrors(true);
				//print_r($errors);
			}
        }
        return $this->render('user/updateParameter.html.twig', array(
            'form' => $form->createView(),
            'user' => $user

        ));

    }
}
