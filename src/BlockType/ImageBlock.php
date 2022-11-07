<?php

namespace WordPressBlockTheme\BlockType;

class ImageBlock extends BlockBase {

    public $type = 'image';
    public $preserve = ['src' => null, 'alt' => 'alt', 'class' => 'className'];


    public function type(\simple_html_dom_node $item) {
        $item->src = 'http://rogos.localhost:8080/wp-content/uploads/' . $item->src;

        return parent::type($item);
    }


    public function omit(\simple_html_dom_node $item) {
        if (empty($item->src) && empty($item->getAttribute('data-src'))) {
            return true;
        }

        return false;
    }

    public function prepend(\simple_html_dom_node $item) {
        if ($item->parentNode()->tag == 'figure') {
            return '';
        }

        return parent::prepend($item) . PHP_EOL . '<figure class="wp-block-image">';
    }

    public function append(\simple_html_dom_node $item) {
        if ($item->parentNode()->tag == 'figure') {
            return '';
        }

        return '</figure>' . PHP_EOL . parent::append($item);
    }


}
