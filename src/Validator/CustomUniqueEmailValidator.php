<?php

namespace App\Validator;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CustomUniqueEmailValidator extends ConstraintValidator
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($fullEmailAddress, Constraint $constraint)
    {
        // Check if a proper constraint instance is passed
        if (!$constraint instanceof CustomUniqueEmail) {
            throw new UnexpectedTypeException($constraint, CustomUniqueEmail::class);
        }

        // Check if there's anything for us to actually validate
        if (null === $fullEmailAddress || '' === $fullEmailAddress) {
            return;
        }

        // Check if an account with this email already exists
        $email = self::getEmail($fullEmailAddress);
        $existingAccount =
            $this->entityManager
            ->getRepository(User::class)
            ->loadUserByEmailAddress($email);

        if ($existingAccount) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ fullEmail }}', $fullEmailAddress)
                ->addViolation();
        }
    }

    private static function getEmail($fullEmailAddress): string
    {
        $parts = explode('+', $fullEmailAddress);
        // Return the 2nd part of the email, otherwise return the original,
        // since no subaddress was found
        return $parts[1] ?? $parts[0];
    }
}
