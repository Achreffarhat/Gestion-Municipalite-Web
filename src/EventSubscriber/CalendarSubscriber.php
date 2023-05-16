<?php

namespace App\EventSubscriber;

use App\Repository\RendezVousRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class CalendarSubscriber implements EventSubscriberInterface
{
    private $RendezVousRepository;
    private $router;

    public function __construct(
        RendezVousRepository $RendezVousRepository,
        UrlGeneratorInterface $router
    ) {
        $this->RendezVousRepository = $RendezVousRepository;
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar)
  {
    $start = $calendar->getStart();
    // $end = $calendar->getEnd();
    $filters = $calendar->getFilters();

    
    $filters['property-index'];

    // Modify the query to fit to your entity and needs
    // Change booking.beginAt by your start date property
    $RendezVouss = $this->RendezVousRepository
        ->createQueryBuilder('RendezVous')
        ->select('RendezVous.id, RendezVous.description , RendezVous.date_ren, COUNT(RendezVous.id) AS RendezVous_count')
        ->where('RendezVous.date_ren BETWEEN :start and :end ')
        ->setParameter('start', $start->format('Y-m-d H:i:s'))
        // ->setParameter('end', $end->format('Y-m-d H:i:s'))
        ->groupBy('RendezVous.date_ren')
        ->getQuery()
        ->getResult()
    ;

    foreach ($RendezVouss as $RendezVous) {
        // Count the number of bookings for the current day
        $dayRendezVouss = $this->RendezVousRepository
            ->createQueryBuilder('RendezVous')
            ->select('COUNT(RendezVous.id) AS RendezVous_count')
            ->where('RendezVous.date_ren = :date_ren')
            ->setParameter('date_ren', $RendezVous['date_ren']->format('Y-m-d'))
            // ->setParameter('day_end', $booking['beginAt']->format('Y-m-d 23:59:59'))
            ->getQuery()
            ->getSingleScalarResult()
        ;

        // this create the events with your data (here booking data) to fill calendar
        $RendezVoussEvent = new Event(
                $RendezVous->getDescription(),
                $RendezVous->getDateRen(),
                
            // $RendezVous['description'] . ' (' . $dayRendezVouss . ')', // Add the booking count to the title
            // $RendezVous['date_ren'],
            // $RendezVous['endAt'] // If the end date is null or not defined, a all day event is created.
        );

        /*
         * Add custom options to events
         *
         * For more information see: https://fullcalendar.io/docs/event-object
         * and: https://github.com/fullcalendar/fullcalendar/blob/master/src/core/options.ts
         */

        $RendezVoussEvent->setOptions([
            'backgroundColor' => 'bleu',
            'borderColor' => 'bleu',
            'textColor' => 'black'
        ]);
        $RendezVoussEvent->addOption(
            'url',
            $this->router->generate('app_rendez_vous_show', [
                'id' => $RendezVous['id'],
            ])
        );
        $RendezVoussEvent->addOption(
            'RendezVous_count',
            $dayRendezVouss // Add the number of bookings for the current day as a custom option
        );

        // finally, add the event to the CalendarEvent to fill the calendar
        $calendar->addEvent($RendezVoussEvent);
    }
  }
}