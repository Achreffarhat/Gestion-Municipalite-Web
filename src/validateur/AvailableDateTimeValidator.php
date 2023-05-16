<?php 

// src/Validator/AvailableDateTimeValidator.php

namespace App\Validator;

use App\Repository\RendezVousRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AvailableDateTimeValidator extends ConstraintValidator
{
    private $rendezVousRepository;

    public function __construct(RendezVousRepository $rendezVousRepository)
    {
        $this->rendezVousRepository = $rendezVousRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof \DateTimeInterface) {
            return;
        }

        $existingAppointment = $this->rendezVousRepository->findOneBy([
            'date_ren' => $value,
        ]);

        if ($existingAppointment) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
