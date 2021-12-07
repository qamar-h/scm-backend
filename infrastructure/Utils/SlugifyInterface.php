<?php

namespace Infrastructure\Utils;

interface SlugifyInterface
{
    public function execute(string $element, string $separator = '-'): string;
}
