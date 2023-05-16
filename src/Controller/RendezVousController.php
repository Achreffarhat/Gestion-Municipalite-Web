<?php

namespace App\Controller;

// use twilio\Rest\Client as Client;

use App\Entity\RendezVous;
use App\Form\RendezVousType;
use App\Repository\RendezVousRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;
use Psr\Log\LoggerInterface;
use Twilio\Http\GuzzleClient;
use Twilio\TwiML\Voice\Client as VoiceClient;

// require __DIR__ . '/vendor/autoload.php';





#[Route('/rendez/vous')]
class RendezVousController extends AbstractController
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
                // $RendezVous->getDescription(),
                // $RendezVous->getDateRen(),
                
            $RendezVous['description'] . ' (' . $dayRendezVouss . ')', // Add the booking count to the title
            $RendezVous['date_ren'],
            $RendezVous['endAt'] // If the end date is null or not defined, a all day event is created.
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
    #[Route('/', name: 'app_rendez_vous_index', methods: ['GET'])]
    public function index(RendezVousRepository $rendezVousRepository): Response
    {
        return $this->render('rendez_vous/index.html.twig', [
            'rendez_vouses' => $rendezVousRepository->findAll(),
        ]);
    }
    #[Route('/listrendez', name: 'app_rendez_listrendez', methods: ['GET'])]
    public function listrendez(RendezVousRepository $rendezVousRepository): Response
    {
        return $this->render('rendez_vous/listrendez.html.twig', [
            'rendez_vouses' => $rendezVousRepository->findAll(),
        ]);
    }
    #[Route('/calendar', name: 'app_calendar', methods: ['GET'])]
    public function calendar(EntityManagerInterface $em, RendezVousRepository $rendezVou ): Response
    {
        $events = $rendezVou->findAll();
        $idvt = [];

        foreach ($events as $event) {
            $idvt [] = [
                'title' => $event->getDescription(),
                'start' => $event->getDateRen()->format('Y-m-d H:i:s'),
            ];

            
        }

        $data = json_encode($idvt);

        return $this->render('rendez_vous/calender.html.twig', compact('data')
        );
    }



    #[Route('/new', name: 'app_rendez_vous_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RendezVousRepository $rendezVousRepository,LoggerInterface $logger, EntityManagerInterface $entityManager): Response
    {
        $rendezVous = new RendezVous();
        $form = $this->createForm(RendezVousType::class, $rendezVous);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        
            $entityManager->persist($rendezVous);
            $entityManager->flush();
            // $accountSid='AC025be9524098572a965a5ed4e46f96fa';
            // $authToken='25953fe51db972e34cdac60b25f3c09f';
            // $twilio= new Client($accountSid,$authToken);
            // $message = $twilio->messages->create('+21690318552',array( 'from'=>'+15674093858','body'=>'A new meeting was detected!',));

            
            // $sid = 'AC025be9524098572a965a5ed4e46f96fa';
            // $token = '25953fe51db972e34cdac60b25f3c09f';
            // $client = new Client('AC025be9524098572a965a5ed4e46f96fa', '25953fe51db972e34cdac60b25f3c09f');



            // $client->messages->create(
            //     // the number you'd like to send the message to
            //     '+21690318552',
            //     [
            //         // A Twilio phone number you purchased at twilio.com/console
            //         'from' => '+15674093858',
            //         // the body of the text message you'd like to send
            //         'body' => 'Hey Jenny! Good luck on the bar exam!'
            //     ]
            // );


            


            // Send an SMS notification to a specific phone number
            // $sms = new SmsMessage(
            //     '+21690318552', // Replace with the phone number to send the SMS message to
            //     'A new appointment has been added!'
            // );
            // $texter->send($sms);
            


            return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rendez_vous/new.html.twig', [
            'rendez_vous' => $rendezVous,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_rendez_vous_show', methods: ['GET'])]
    public function show(RendezVous $rendezVou): Response
    {
        return $this->render('rendez_vous/show.html.twig', [
            'rendez_vou' => $rendezVou,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_rendez_vous_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RendezVous $rendezVou, RendezVousRepository $rendezVousRepository): Response
    {
        $form = $this->createForm(RendezVousType::class, $rendezVou);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rendezVousRepository->save($rendezVou, true);

            return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rendez_vous/edit.html.twig', [
            'rendez_vou' => $rendezVou,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rendez_vous_delete', methods: ['POST'])]
    public function delete(Request $request, RendezVous $rendezVou, RendezVousRepository $rendezVousRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rendezVou->getId(), $request->request->get('_token'))) {
            $rendezVousRepository->remove($rendezVou, true);
        }

        return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
    }
}
