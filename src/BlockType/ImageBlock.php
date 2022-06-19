<?php

namespace WordPressBlockTheme\BlockType;

class ImageBlock extends BlockBase {

    public $type = 'image';
    public $preserve = ['src' => null, 'alt' => 'alt'];


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
