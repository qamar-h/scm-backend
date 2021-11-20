<?php

namespace App\Middleware;

use ApiPlatform\Core\Bridge\Symfony\Messenger\RemoveStamp;
use SCM\User\Entity\User;
use SCM\User\Message\{UserPersist, UserRemove};
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

class UserMiddleware implements MiddlewareInterface
{
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if ($envelope->getMessage() instanceof User) {
            $envelope = new Envelope(
                new UserPersist($envelope->getMessage())
            );

            if ($envelope->last(RemoveStamp::class)) {
                $envelope = new Envelope(
                    new UserRemove($envelope->getMessage()->getId())
                );
            }
        }

        return $stack->next()->handle($envelope, $stack);
    }
}
