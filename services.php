<?php
declare(strict_types=1);

require_once realpath(__DIR__) . '/vendor/autoload.php';

use Mow\Admin\AdminPanel;
use Mow\Field\GenericField as Field;
use Mow\Field\MetaBox;
use Mow\Field\Type\Primitive\UrlField;

new class(){
    public function __construct()
    {
        $admin = new AdminPanel(
            "prova",
            "edit_dashboard",
            "admin"
        );

//        MetaBox::new('Google Maps')
//            ->setTemplateName('contact')
//            ->setFields(
//                UrlField::new("gmaps")
//            )
//        ;
    }
};

function getCustomField(String $name, int|null|\WP_Post $post = null , bool $single = true): String|array
{
    $post = get_post($post);

    if ( !Field::getField($name, $post, $single) && WP_DEBUG){
        throw new InvalidArgumentException('The field "' . $name . '" not found in the database');
    }else{
        return Field::getField($name, $post, $single);
    }
}