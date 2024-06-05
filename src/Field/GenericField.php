<?php

namespace Plugin\Field;


abstract class GenericField
{
    public const string PREFIX = '_CField_';
    private String $metaKey;
    private String $name;
    private ?String $label = null;
    private ?String $cssClass = null;
    private bool $required = true;

    public function getName(): String
    {
        return $this->name;
    }
    public function setName($name): static
    {
        $this->name = $name;
        return $this;
    }
    public function getMetaKey(): String
    {
        return $this->metaKey;
    }
    private function setMetaKey(string $key): static
    {
        $this->metaKey = self::PREFIX . (preg_replace('/^(_|-)+/i', '', $key));
        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }
    public function setLabel(String $label = null): static
    {
        $this->label = $label;
        return $this;
    }
    public function setCssClass(String $class): static
    {

        $this->cssClass = $class ;
        return $this;
    }
    public function getCssClass(): ?String
    {
        return $this->cssClass;
    }
    public function notRequired(): static
    {
        $this->required = !$this->required;
        return $this;
    }
    public function isRequired(): ?bool
    {
        return $this->required;
    }

    public function getType(): string
    {
        return strtolower(preg_filter('/.*\\\(\w+)Field$/', '$1' ,static::class));
    }

    final public static function new(String $name, ?String $label = null) :static
    {
        return (new static())
            ->setMetaKey($name)
            ->setName($name)
            ->setLabel($label);
    }

    final public function renderField(\WP_Post $post): void{

        $value = get_post_meta($post->ID, $this->getMetaKey(), true);

        var_dump($value);
        die();

        echo '<div class="box__field" style="padding-bottom: 1rem">';
        if (!empty($this->getLabel())){
            echo '<div class="box__label" style="padding-bottom: .5rem">' . $this->createLabel() . '</div>';
        }
        echo '<div class="box__wrapper" >' . $this->createField($value) . '</div>';
        echo '</div>';
    }

    final public static function getField(String $name, \WP_Post $post, bool $single = true): String|array
    {
        return !empty(get_post_meta($post->ID, self::PREFIX . $name, $single))? get_post_meta($post->ID, self::PREFIX . $name, $single) : false ;
    }

    abstract protected function createLabel():String;
    abstract protected function createField(String $value):String;

}