<?php

declare(strict_types=1);

namespace PhpTui\Term\InformationProvider;

use PhpTui\Term\InformationProvider;
use PhpTui\Term\TerminalInformation;

class AggregateInformationProvider implements InformationProvider
{
    /**
     * @param InformationProvider[] $providers
     */
    public function __construct(private readonly array $providers)
    {
    }

    public function for(string $classFqn): ?TerminalInformation
    {
        foreach ($this->providers as $provider) {
            $information = $provider->for($classFqn);
            if (null !== $information) {
                return $information;
            }
        }

        return null;
    }

    /**
     * @param InformationProvider[] $providers
     */
    public static function new(array $providers): self
    {
        return new self($providers);
    }
}
