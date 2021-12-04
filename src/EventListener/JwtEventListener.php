<?php

namespace App\EventListener;

use SCM\User\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class JwtEventListener
{
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event): void
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof User) {
            return;
        }

        $expiration = new \DateTime('+' . $this->expirtation() . ' minutes');

        $data['email'] = $user->getEmail();
        $data['lastname'] = $user->getPerson()->getLastname();
        $data['firstname'] = $user->getPerson()->getFirstname();
        $data['fullname'] = $user->getPerson()->getFullname();
        $data['gender'] = $user->getPerson()->getGender();
        $data['ui'] = $user->getId();
        $data['exp'] = $expiration->getTimestamp();
        $data['avatar'] = $user->getAvatar();
        $data['age'] = $user->getPerson()->getAge();

        $event->setData($data);
    }

    /**
     * @return int
     */
    private function expirtation(): int
    {
        $time = 3600;
        if (isset($_ENV['JWT_TOKEN_TTL'])) {
            $time = intval($_ENV['JWT_TOKEN_TTL']);
        }
        return ($time / (60));
    }
}
