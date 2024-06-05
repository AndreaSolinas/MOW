<?php

namespace Mow\Field;

use WP_Screen;

class MetaBox
{
    private const POST_TYPE = ['post', 'page'];
    protected const BASE_TEMPLATE = "template/";

    private null|String|array|WP_Screen $postType = null;

    private String $title;

    private ?String $templateName = null;

    private array $fields;

    protected function __construct(){}

    public static function new(String $title, String|array|WP_Screen $postType = null) :static
    {
        return (new static())
            ->setTitle($title)
            ->setPostType($postType)
        ;
    }

    public function getPostType(): string
    {
        return $this->postType;
    }

    public function setPostType(?String $postType): static
    {
        $this->postType = $postType;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getTemplateName(): string
    {
        return $this->templateName;
    }

    public function setTemplateName(string $templateName, string $path = null, string $extension = 'php'): static
    {
        $this->templateName = ($path ?? self::BASE_TEMPLATE) . $templateName . '.' . $extension;
        return $this;
    }

    private function hookRenderBox(): static
    {
        add_action(
            'add_meta_boxes',
            function () {
                if ($this->templateName === null ||
                    ($this->templateName === get_page_template_slug()))
                {
                    add_meta_box(
                        uniqid(), // only to identify in admin pannel
                        $this->title,
                        [$this, 'populateBox'],
                        (empty($this->templateName) ? // if
                            ($this->postType ?? self::POST_TYPE) :
                            'page' // else
                        ),
                        'advanced',
                    );
                }
            }
        );
        return $this;
    }

    private function hookSavingBox():static
    {
        add_action(
            'save_post',
            function ($post_id)
            {
                if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                    return;
                }
                if (!empty($this->fields)) {
                    foreach ($this->fields as $field) {
                        if (!empty($_POST) && !empty($_POST[$field->getName()])) {
                            update_post_meta($post_id, $field->getMetaKey(), $field->sanitize($_POST[$field->getName()]));
                        } else {
                            continue;
                            // TODO: gestisci l'errore del salvataggio (se non sono validi ecc)
                        }
                    }
                }
            }
            , 999);
        return $this;
    }

    public function populateBox(): void
    {
        global $post;
        echo '<section class="box__wrapper">';
        if (!empty($this->fields)) {
            foreach ($this->fields as $field) {
                $field->renderField($post);
            }
        }
        echo '</section>';
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @throws \ErrorException
     */
    public function setFields(GenericField ...$fields): static
    {
        foreach ($fields as $field) {
            if (!is_subclass_of($field, GenericField::class) && WP_DEBUG){
                throw new \ErrorException('The only allowed arguments are instances of the classes that extend ' . GenericField::class );
            }
            $this->fields[] = $field;
        }
        return $this;
    }

    final public function __destruct()
    {
        $this->hookSavingBox();
        $this->hookRenderBox(); // Aggiunge il metabox
    }
}