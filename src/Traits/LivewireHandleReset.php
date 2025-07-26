<?php

namespace Shankar\LaravelBasicSetting\Traits;

use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionProperty;
use Shankar\LaravelBasicSetting\Attributes\Unresetable;

trait LivewireHandleReset
{
    public function reset(...$properties)
    {
        $unresetable = $this->getUnresetableProperties();

        if (empty($properties)) {
            $properties = collect((new \ReflectionObject($this))->getProperties(\ReflectionProperty::IS_PUBLIC))
                ->filter(fn($prop) => !$unresetable->contains($prop->getName()))
                ->filter(fn($prop) => !str_starts_with($prop->getName(), '__'))
                ->map(fn($prop) => $prop->getName())
                ->toArray();
        }

        parent::reset(...$properties);
    }

    protected function getUnresetableProperties(): Collection
    {
        $reflection = new ReflectionClass($this);
        return collect($reflection->getProperties(\ReflectionProperty::IS_PUBLIC))
            ->filter(fn($property) => !empty($property->getAttributes(Unresetable::class)))
            ->map(fn($prop) => $prop->getName());
    }
}
