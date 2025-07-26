<?php

namespace Shankar\LaravelBasicSetting\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]

class Unresetable
{
    public function __construct() {}
}
