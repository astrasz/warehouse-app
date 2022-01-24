<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
 
    /**
     * @Route("/users", name="admin_users", methods={"GET"})
     */

    public function showUsers(EntityManagerInterface $em, Request $request, PaginatorInterface $paginator): Response
    {

        $usersData = $em->getRepository(User::class)->findAll();

        $users = $paginator->paginate(
            $usersData,
            $request->query->getInt('page', 1),
            10
        );


        return $this->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }



    /**
     * @Route("/delete-user/{id}",name="admin_delete_user", methods={"DELETE"})
     */

     public function deleteUser(EntityManagerInterface $em, int $id)
     {

        $user = $em->getRepository(User::class)->find($id);

        $em->remove($user);
        $em->flush();

        return new Response('success');

     }


     /**
      * @Route("/edit-user/{id}",name="admin_edit_user", methods={"POST", "GET"})
      */

      public function editUser(EntityManagerInterface $em, int $id, Request $request): Response
      {
        $user = $em->getRepository(User::class)->find($id);

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);



        if($form->isSubmitted() && $form->isValid())
        {
            $this->addFlash(
                'notice-edit-user',
                'Changes successfully saved'
            );

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('admin_users');
        }




        return $this->render('admin/edit_user.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);

      }

    /**
     * @Route("/change-status/{id}",name="admin_change_status", methods={"POST"})
     */

      public function changeUserStatus(EntityManagerInterface $em, int $id, Request $request): Response
      {

        $user = $em->getRepository(User::class)->find($id);

        if($user->getIsEnabled())
        {
            $user->setIsEnabled(false);
        } else 
        {
            $user->setIsEnabled(true);
        }

        $em->persist($user);
        $em->flush();

        return new Response('success');


      }


}
