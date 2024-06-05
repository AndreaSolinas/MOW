<?php

namespace Mow\Field\FieldInterface;

interface SecurityFormDataInterface
{
    public function sanitize($data): mixed;
}