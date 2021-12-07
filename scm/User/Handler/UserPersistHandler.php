<?php

namespace SCM\User\Handler;

use Infrastructure\Action\HandlerInterface;
use Infrastructure\ORM\ManagerInterface;
use Infrastructure\Security\{ UserPasswordEncoder, SecurityInterface };
use SCM\User\Entity\User;
use SCM\User\Exception\PasswordEmptyException;
use SCM\User\Message\UserPersist;
use DateTimeImmutable;

class UserPersistHandler implements HandlerInterface
{
    public function __construct(
        private UserPasswordEncoder $encoder,
        private ManagerInterface $em
    ) {
    }

    public function __invoke(UserPersist $message): void
    {
        $user = $message->getUser();

        if ($this->givePassword($user)) {
            throw new PasswordEmptyException('Password is empty');
        }

        if ($user->getPlainPassword() !== '') {
            $password = $this->encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
        }

        if ($user->getId() === null) {
            $user->setAvatar('/default-avatar.jpg');
        }

        $this->em->persist($user);
        $this->em->flush();
    }

    private function givePassword(User $user): bool
    {
        return $user->getPlainPassword() == '' && $user->getId() === null;
    }
}
