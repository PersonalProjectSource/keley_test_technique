<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Handler\UserHandler;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation as Doc;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class DefaultController extends FOSRestController
{
    /**
     * @param Request $request
     *
     * @Rest\Get("/users", name="user_list", options={"expose"=true})
     *
     * @Rest\View(statusCode=200)
     *
     * @Doc\ApiDoc(
     *      section="Users",
     *      description="get users",
     *      statusCodes={
     *          200="Returned if users displayed",
     *      }
     * )
     * @return Response
     */
    public function usersListAction(Request $request)
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $view = new View();
        $view->setData($users);
        $view->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * @param Request $request
     *
     * @Rest\Get("/users/{id}", name="get_user", options={"expose"=true})
     *
     * @Rest\View(statusCode=200)
     *
     *
     * @Doc\ApiDoc(
     *      section="User",
     *      description="get users",
     *      statusCodes={
     *          200="Returned if users displayed",
     *      }
     * )
     * @return Response
     */
    public function userAction(Request $request, $id)
    {
        $user = $this
            ->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $id, 'actif' => true]);
        $view = new View();
        $view->setData($user);
        $view->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * @param Request $request
     *
     * @Rest\Post("/users/{id}", name="user_edit", options={"expose"=true})
     *
     * @Rest\View(statusCode=200)
     *
     *
     * @Doc\ApiDoc(
     *      section="User",
     *      description="get users",
     *      statusCodes={
     *          200="Returned if users displayed",
     *      }
     * )
     * @return Response
     */
    public function editUserAction(Request $request, $id)
    {
        try{
            $user = $this->get(UserHandler::class)->handle($request);
        } catch (NotFoundResourceException $e) {
            $message = sprintf("Un probleme est survenu : %s", $e->getMessage());
            $user = $message;
        }

        // View creation for Json stream.
        $view = View::create();
        $view->setData([
            'user' => $user
        ]);
        $view->setFormat('json');

        return $this->handleView($view);
    }
}
