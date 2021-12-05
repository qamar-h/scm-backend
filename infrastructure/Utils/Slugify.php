<?php

namespace Infrastructure\Utils;

use Symfony\Component\String\Slugger\SluggerInterface;

class Slugify implements SlugifyInterface
{
    public function __construct(private SluggerInterface $slugger)
    {
    }

    public function execute(string $element, string $separator = '-'): string
    {
        return $this->slugger->slug($element, $separator);
    }
}
