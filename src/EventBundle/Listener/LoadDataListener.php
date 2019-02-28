<?php
namespace EventBundle\Listener;
use AncaRebeca\FullCalendarBundle\Event\CalendarEvent;
use AncaRebeca\FullCalendarBundle\Model\Event;
use AncaRebeca\FullCalendarBundle\Model\FullCalendarEvent;
use EventBundle\Entity\Evenement;
use UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use DateTime;
class LoadDataListener
{
    /**
     * @var EntityManager
     */
    private $em;
    public function __construct(EntityManagerInterface $em,Security $security)
    {
        $this->em = $em;
        $this->security=$security;
    }
    /**
     * @param CalendarEvent $calendarEvent
     *
     * @return FullCalendarEvent[]
     */
    public function loadData(CalendarEvent $calendarEvent)
    {
        // You can retrieve information from the event dispatcher (eg, You may want which day was selected in the calendar):
        // $startDate = $calendarEvent->getStart();
        // $endDate = $calendarEvent->getEnd();
        // $filters = $calendarEvent->getFilters();
        // You may want do a custom query to populate the events
        // $currentEvents = $repository->findByStartDate($startDate);
        /**@var User $user*/
        $user=$this->security->getUser();
        //  $responsable = $user->getResponsable();

        $repository = $this->em->getRepository('EventBundle:Evenement')->findAll();
        //  $schedules = $repository->findBy(array('responsable'=>$responsable));
        // You may want to add an Event into the Calendar view.
        /** @var Evenement $schedule */
        foreach ($repository as $schedule) {
            /** affichage fil caendar**/
            $result = $schedule->getDateFin()->format('Y-m-d H:i:s');
            $datetime = new DateTime($result);
            $datetime->modify('+1 day');

       // $schedule->setDateFin($date) ;
            $event = new Event($schedule->getNomEvenement(), $schedule->getDateDebut()  );
//            $event->setStartDate($schedule->getDateDebut());

            $event->setEndDate( $datetime );
            $event->setEditable($user==$schedule->getResponsable() ? true : false);
            $event->setStartEditable($user==$schedule->getResponsable() ? true : false);
            $event->setId($schedule->getId());
            $color ="";
            if($schedule->getType()=='Culturel') {$color='#00FF00';}
                elseif ($schedule->getType()=='Aventure'){ $color='light blue' ;}
                    else {$color='#FF0000';}
            $event->setColor($color);

            $event->setDurationEditable($user==$schedule->getResponsable() ? true : false);


            $calendarEvent->addEvent($event);
        }
    }
}