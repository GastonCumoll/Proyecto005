<?php

namespace App\EventSubscriber;

use DateTime;
use Doctrine\ORM\Events;
use App\Entity\Consulta;
use App\Entity\TipoConsulta;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class ConsultaSubscriber implements EventSubscriberInterface 
{
    private $mailer;
    private $params;
    //$params para iyectar los paramentros de la carpeta services.yaml en config
    public function __construct(MailerInterface $mailer,ParameterBagInterface $params){
        $this->params = $params;
        $this->mailer = $mailer;
    }
    // this method can only return the event names; you cannot define a
    // custom method name to execute when each event triggers
    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::onFlush,
        ];
    }
    public function postPersist(LifecycleEventArgs $args): void
    {   
    }

    public function onFlush(OnFlushEventArgs $eventArgs)
    {   
        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();
        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            
            //pregunto si se envio una consulta
            if ($entity instanceof Consulta){
                //cada vez qaue un usuario crea una consulta, se crea un Objeto Consulta.
                //Con ese objeto, obtengo los datos, tanto del usuario que la acaba de realizar
                //como los de tipo de consulta(y su mail respectivamente)
                $emailPersona = $entity->getEmail();
                $asunto = $entity->getTipoConsulta()->getNombre();
                $emailEncargado = $entity->getTipoConsulta()->getEmail();
                $texto = $entity->getTexto();
                $from=$this->params->get('ws_mail_noreply');
                
                $email=(new Email())
                ->from($from)
                ->to($emailEncargado)
                ->replyTo($emailPersona)
                ->subject($asunto)
                ->text($texto);

                $this->mailer->send($email);

            }

        }
    }


}