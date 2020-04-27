<?php

namespace App\Security\Voter;

use App\Entity\Mission;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class MissionVoter extends Voter
{
    private $security;

    const UPDATE = 'MISSION_UPDATE';
    const VIEW = 'MISSION_VIEW';
    const DELETE = 'MISSION_DELETE';

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::VIEW, self::UPDATE, self::DELETE])
            && $subject instanceof Mission;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var User $user */
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::VIEW:
                return $this->canView($user, $subject);
            case self::UPDATE:
                return $this->canUpdate($user, $subject);
            case self::DELETE:
                return $this->canDelete($user, $subject);
        }

        return false;
    }

    private function canView(User $user, Mission $mission): bool
    {
        if ($this->security->isGranted([User::ROLE_ADMIN])) {
            return true;
        }

        if ($this->security->isGranted([User::ROLE_CLIENT])) {
            return $user->ownsMission($mission);
        }

        return false;
    }

    private function canUpdate(User $user, Mission $mission): bool
    {
        if ($this->security->isGranted([User::ROLE_CLIENT]) &&
            $user->ownsMission($mission)
        ) {
            return true;
        }

        return false;
    }

    private function canDelete(User $user, Mission $mission): bool
    {
        if ($this->security->isGranted([User::ROLE_ADMIN])) {
            return true;
        }

        if ($this->security->isGranted([User::ROLE_CLIENT])) {
            return $user->ownsMission($mission);
        }

        return false;
    }
}
