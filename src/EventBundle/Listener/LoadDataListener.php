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

class LoadDataListener
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
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
        /**@var User $user */
        $user = $this->security->getUser();
        //  $responsable = $user->getResponsable();

        $repository = $this->em->getRepository('EventBundle:Evenement')->findAll();
        //  $schedules = $repository->findBy(array('responsable'=>$responsable));
        // You may want to add an Event into the Calendar view.
        /** @var Evenement $schedule */
        foreach ($repository as $schedule) {
            /** affichage fil caendar**/

            $event = new Event($schedule->getNomEvenement(), $schedule->getDateDebut());
//            $event->setStartDate($schedule->getDateDebut());
            $event->setEndDate($schedule->getDateFin());
            $event->setEditable(true);
            $event->setStartEditable(true);
            $event->setId($schedule->getId());
            $event->setDurationEditable(true);


            $calendarEvent->addEvent($event);
        }
    }
}