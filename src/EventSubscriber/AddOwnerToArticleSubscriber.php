<?php
// api/src/EventSubscriber/AddOwnerToArticleSubscriber.php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class AddOwnerToArticleSubscriber implements EventSubscriberInterface
{

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    private $manager;

    public function __construct(ManagerRegistry $manager, TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
        $this->manager = $manager->getManager();
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['attachOwner', EventPriorities::PRE_WRITE],
        ];
    }

    public function attachOwner(GetResponseForControllerResultEvent $event)
    {

        $article = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$article instanceof Comment || Request::METHOD_POST !== $method) {

            // Only handle Article entities (Event is called on any Api entity)
            return;
        }


        // maybe these extra null checks are not even needed
        $token = $this->tokenStorage->getToken();
        if (!$token) {
            return;
        }



        $owner = $token->getUser();
        if (!$owner instanceof User) {
            return;
        }



        // Attach the user to the not yet persisted Article
        $article->setCreator($owner);

        $article->setCreatedAt(new \DateTime());

        $user = $token->getUser();

        $points = $user->getPoints();
        if($points == null) $points = 0;
        $user->setPoints($points + 1);
        $this->manager->persist($user);
        $this->manager->flush();

    }
}