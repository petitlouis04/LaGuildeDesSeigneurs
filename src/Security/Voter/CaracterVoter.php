<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use LogicException;
use App\Entity\Caracter;

class CaracterVoter extends Voter
{
    public const CHARACTER_INDEX = 'characterIndex';
    public const CHARACTER_CREATE = 'characterCreate';
    public const CHARACTER_DISPLAY = 'characterDisplay';
    public const CHARACTER_MODIFY = 'characterModify';

    private const ATTRIBUTES = array(
        self::CHARACTER_CREATE,
        self::CHARACTER_DISPLAY,
        self::CHARACTER_INDEX,
        self::CHARACTER_MODIFY,
    );

    # Checks if is allowed to display
    private function canDisplay($token, $subject)
    {
        return true;
    }

    protected function supports(string $attribute, $subject): bool
    {
        if (null !== $subject) {
                        return $subject instanceof Caracter && in_array($attribute, self::ATTRIBUTES);
                    }
                    return in_array($attribute, self::ATTRIBUTES);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        switch ($attribute) {
            case self::CHARACTER_CREATE:
                return $this->canCreate($token, $subject);
                break;

            case self::CHARACTER_DISPLAY:
            case self::CHARACTER_INDEX:
                return $this->canDisplay($token, $subject);
                break;
            case self::CHARACTER_MODIFY:
                return $this->canModify($token, $subject);
                break;
        }

        throw new LogicException('Invalid attribute: ' . $attribute);
    }

    # Checks if is allowed to create
    private function canCreate($token, $subject)
    {
        return true;
    }

    # Checks if is allowed to modify
    private function canModify($token, $subject)
   {
       return true;
    }
}
