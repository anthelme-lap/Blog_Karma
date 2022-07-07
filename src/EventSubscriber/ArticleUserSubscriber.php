<?php

namespace App\EventSubscriber;

use App\Entity\Article;
use App\Entity\Comment;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;

class ArticleUserSubscriber implements EventSubscriberInterface
{
    //  $security;

    public function __construct(private Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setArticleUser']
        ];
    }

    public function setArticleUser(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Comment)) {
            return;
        }

        $user = $this->security->getUser();
        // $entity->setFkuser($user);
        
    }
}