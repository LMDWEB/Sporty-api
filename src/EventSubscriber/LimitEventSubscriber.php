<?php
namespace App\EventSubscriber;
use ApiPlatform\Core\EventListener\EventPriorities;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class LimitEventSubscriber implements EventSubscriberInterface
{
    private $manager;
    private $tokenStorage;

    public function __construct(ManagerRegistry $manager, TokenStorageInterface $storage)
    {
        $this->manager = $manager->getManager();
        $this->tokenStorage = $storage;
    }
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['createPlane', EventPriorities::PRE_RESPOND],
        ];
    }
    public function createPlane(GetResponseForControllerResultEvent $event)
    {
        $data_event = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        $token = $this->tokenStorage->getToken();

        //La on recupere le user qui a fait la requete
        $user = $token->getUser();

        $nb_requests = $user->getNbRequests();

        if ($user->getNbRequestMax() == $nb_requests ) {
            //header('Content-type: application/json');
            //return json_encode(['access-refusé' => $user->getNbRequests()  ]);
            die('forfait depaasé');
        }

        else {
            $user->setNbRequests( $nb_requests + 1);

            $this->manager->persist($user);
            $this->manager->flush();
        }

        //La y a son username, on peut faire une requete à partir de ca, on a déjà le manager avec $this->manager
        //die(dump($user->getUsername()));


    }
}