<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PlayerVoter extends Voter
{
    public const PLAYER_INDEX = 'playerIndex';
    public const PLAYER_CREATE = 'playerCreate';
    public const PLAYER_DISPLAY = 'playerDisplay';
    public const PLAYER_MODIFY = 'playerModify';
    public const PLAYER_DELETE = 'playerDelete';

    private const ATTRIBUTES = array(
        self::PLAYER_CREATE,
        self::PLAYER_DISPLAY,
        self::PLAYER_INDEX,
        self::PLAYER_MODIFY,
        self::PLAYER_DELETE,
    );

    protected function supports(string $attribute, $subject): bool
    {
        if (null !== $subject) {
            return $subject instanceof Player && in_array($attribute, self::ATTRIBUTES);
        }
        return in_array($attribute, self::ATTRIBUTES);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case self::PLAYER_CREATE:
                return $this->canCreate($token, $subject);

            case self::PLAYER_DISPLAY:

            case self::PLAYER_MODIFY:
                return $this->canModify($token, $subject);

            case self::PLAYER_DELETE:
                return $this->canDelete($token, $subject);
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

    # Checks if is allowed to delete
    private function canDelete($token, $subject)
    {
        return true;
    }
}
