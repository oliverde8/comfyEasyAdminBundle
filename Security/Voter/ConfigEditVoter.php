<?php

namespace oliverde8\ComfyEasyAdminBundle\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ConfigEditVoter extends Voter
{
    const ACTION_NEW = 'edit';

    const ACTIONS = [self::ACTION_NEW];

    /**
     * @inheritDoc
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, self::ACTIONS) && $subject instanceof \oliverde8\ComfyBundle\Model\ConfigInterface;
    }

    /**
     * @inheritDoc
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        return true;
    }
}
