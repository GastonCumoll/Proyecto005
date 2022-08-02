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
        //dd("post persist");
        //aca la logica de enviar mail
        //$args= la entidad
        


        

    }

    public function onFlush(OnFlushEventArgs $eventArgs)
    {   
        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();
        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            

            if ($entity instanceof Consulta){
                $emailPersona = $entity->getEmail();
                $asunto = $entity->getTipoConsulta()->getNombre();
                $emailEncargado = $entity->getTipoConsulta()->getEmail();
                $texto = $entity->getTexto();
                $from=$this->params->get('ws_mail_noreply');
                
                //dd($from);
                $email=(new Email())
                ->from($from)
                ->to($emailEncargado)
                ->replyTo($emailPersona)
                ->subject($asunto)
                ->text($texto);
                // $email->SMTPOptions = [
                //     'ssl' => [
                //         'verify_peer' => false,
                //         'verify_peer_name' => false,
                //         'allow_self_signed' => true
                //     ]
                //     ];

                $this->mailer->send($email);

            }

        }
    }


}