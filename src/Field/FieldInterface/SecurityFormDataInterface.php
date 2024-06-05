<?php

namespace Plugin\Field\FieldInterface;

interface SecurityFormDataInterface
{
    public function sanitize($data): mixed;
}