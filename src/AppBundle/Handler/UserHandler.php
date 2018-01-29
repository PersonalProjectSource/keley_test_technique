<?php

namespace AppBundle\Handler;

use AppBundle\Form\UserType;
use AppBundle\Manager\UserManager;
use AppBundle\Repository\UserRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\CssSelector\Parser\Handler\HandlerInterface;
use Symfony\Component\Translation\Exception\NotFoundResourceException;



class UserHandler implements CoreHandlerInterface
{
    private $manager;
    private $formFactory;
    private $repository;

    public function __construct(UserManager $manager, FormFactoryInterface $factory, $repository)
    {
        $this->manager = $manager;
        $this->formFactory = $factory;
        $this->repository = $repository;
    }

    /**
     * Persist and flush a tenant entity.
     *
     * @param Request $request
     */
    public function handle(Request $request)
    {
        $data = $request->request->all();
        $user = $this->repository->findOneBy(['id' => $request->get('id')]);
        $userForm = $this->formFactory->create(UserType::class, $user);
        $userForm->submit($data);

        return $this->manager->save($user);
    }
}