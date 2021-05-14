<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="user_list")
     */
    public function listAction(UserRepository $repository)
    {
        $this->denyAccessUnlessGranted('USER_LIST', $this->getUser());

        return $this->render('user/list.html.twig', ['users' => $repository->findAll()]);
    }

    /**
     * @Route("/users/create", name="user_create")
     */
    public function createAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $this->denyAccessUnlessGranted('USER_CREATE', $this->getUser());

        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/users/{id}/edit", name="user_edit")
     */
    public function editAction(User $user, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $this->denyAccessUnlessGranted('USER_EDIT', $this->getUser());

        $form = $this->createForm(UserType::class, $user, array('create' => false));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (isset($_POST['user']['password']['first']) && $_POST['user']['password']['first'] !== '') {
                $newPassword = $_POST['user']['password']['first'];
                $password = $encoder->encodePassword($user, $newPassword);
                $user->setPassword($password);
            }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
