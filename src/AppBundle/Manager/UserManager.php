<?php

namespace AppBundle\Manager;


use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserManager
{
    private $em;
    private $repository;

    public function __construct(EntityManagerInterface $em,  $repository)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * Save entity from request data.
     *
     * @param User $user
     * @throws \Exception
     * @internal param Request $request
     */
    public function save(User $user)
    {
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}