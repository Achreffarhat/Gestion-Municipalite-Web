<?php

namespace App\Form;


use App\Entity\RendezVous;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Doctrine\ORM\EntityManagerInterface;


class RendezVousType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('date_ren', DateTimeType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'constraints' => [
                    new Callback([$this, 'validateDateRen']),
                    new Callback([$this, 'validateDate'])
                    
                    
                ],
                
            
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RendezVous::class,
        ]);
    }
    public function validateDateRen($date_ren, ExecutionContextInterface $context= null): void
    {
        $hour = $date_ren->format('G');

        if ($hour < 8 || $hour > 17) {
            $context->buildViolation('The meeting hour must be between 8 am and 5 pm.')
                ->atPath('date_ren')
                ->addViolation();
        }
    }
    // public function validateDate($date_ren, ExecutionContextInterface $context)
    // {
    //     $dateTime = new \DateTimeImmutable($date_ren->format('Y-m-d H:i:s'));
    //     $existingRendezVous = $this->entityManager->getRepository(RendezVous::class)->findOneBy(['date_ren' => $dateTime]);

    //     if ($existingRendezVous) {
    //         $context->addViolation('This date and time are already booked.');
    //     }
    // }
    public function validateDate($date_ren, ExecutionContextInterface $context)
    {
    $dateTime = new \DateTimeImmutable($date_ren->format('Y-m-d H:i:s'));
    $oneHourLater = $dateTime->modify('+1 hour');

    $existingRendezVous = $this->entityManager->getRepository(RendezVous::class)
        ->createQueryBuilder('r')
        ->where('r.date_ren BETWEEN :selectedDate AND :oneHourLater')
        ->setParameter('selectedDate', $dateTime)
        ->setParameter('oneHourLater', $oneHourLater)
        ->getQuery()
        ->getOneOrNullResult();

    if ($existingRendezVous) {
        $context->addViolation('This date and time are already booked.');
    }
    }
    
}
