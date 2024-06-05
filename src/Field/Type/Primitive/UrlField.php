<?php

namespace Plugin\Field\Type\Primitive;

use Plugin\Field\FieldInterface\SecurityFormDataInterface;
use Plugin\Field\GenericField;

final class UrlField Extends GenericField implements SecurityFormDataInterface
{

    protected function createLabel(): string
    {
        return '<label for="_' . $this->getName() . '">' . $this->getLabel() . '</label>';
    }

    protected function createField($value): string
    {
        return '<input type="url" class="' . esc_attr($this->getCssClass()) . '" name="' . esc_attr($this->getName()) .
            '" id="_' . esc_attr($this->getName()) . '" value="' .  esc_attr($value) .
            '" ' . (!$this->isRequired() ? '' : 'required') . ' >';
    }


    public function sanitize($url): mixed
    {

    }
}