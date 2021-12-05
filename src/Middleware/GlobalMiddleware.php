<?php

namespace App\Middleware;

use ApiPlatform\Core\Bridge\Symfony\Messenger\RemoveStamp;
use SCM\Global\Message\EntityRemove;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

class GlobalMiddleware implements MiddlewareInterface
{
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $this->enveloppe = $envelope;
        $entity = $this->enveloppe->getMessage();

        if ($this->enveloppe->last(RemoveStamp::class) !== null) {
            $message = new EntityRemove(
                get_class($envelope->getMessage()),
                $envelope->getMessage()->getId()
            );

            if ($this->hasMessages() && isset($entity::MESSAGES['remove'])) {
                $messages = $entity::MESSAGES;
                $message = new $messages['remove']($envelope->getMessage()->getId());
            }

            $envelope = new Envelope($message);
        }

        if ($this->hasMessages() && $this->forPersist($entity::MESSAGES)) {
            $messages = $entity::MESSAGES;
            $envelope = new Envelope(
                new $messages['persist']($entity)
            );
        }

        return $stack->next()->handle($envelope, $stack);
    }

    private function hasMessages(): bool
    {
        $className = get_class($this->enveloppe->getMessage());

        return defined($className . '::MESSAGES')
            && is_array($this->enveloppe->getMessage()::MESSAGES);
    }

    private function forPersist(array $messages): bool
    {
        return $this->enveloppe->last(RemoveStamp::class) === null
            && isset($messages['persist']);
    }
}
