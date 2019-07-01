<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\GameSuggest;
use App\Entity\User;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class AddOwnerToSuggestGame implements EventSubscriberInterface
{

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    private $manager;

    public function __construct(ManagerRegistry $manager, TokenStorageInterface $tokenStorage)
    {
        $this->manager = $manager->getManager();
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['attachOwnerGame', EventPriorities::PRE_WRITE],
        ];
    }

    public function attachOwnerGame(GetResponseForControllerResultEvent $event)
    {

        $article = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$article instanceof GameSuggest || Request::METHOD_POST !== $method) {
            return;
        }

        $token = $this->tokenStorage->getToken();
        if (!$token) {
            return;
        }

        $owner = $token->getUser();
        if (!$owner instanceof User) {
            return;
        }

        $article->setAuthor($owner);
        $article->setCreatedAt(new \DateTime());

        $user = $token->getUser();

        $points = $user->getPoints();
        if($points == null) $points = 0;
        $user->setPoints($points + 5);
        $this->manager->persist($user);
        $this->manager->flush();

    }

}