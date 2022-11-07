<?php

namespace WordPressBlockTheme\BlockType;

class EmbedBlock extends BlockBase {

    public $type = 'embed';
    public $preserve = ['src' => 'url'];

    public function omit(\simple_html_dom_node $item) {
        return false;
    }


    public function prepend(\simple_html_dom_node $item) {
        if ($item->parentNode()->tag == 'figure') {
            return '';
        }

        return parent::prepend($item) . PHP_EOL . '<figure class="wp-block-embed"><div class="wp-block-embed__wrapper">';
    }

    public function append(\simple_html_dom_node $item) {
        if ($item->parentNode()->tag == 'figure') {
            return '';
        }

        return '</div></figure>' . PHP_EOL . parent::append($item);
    }

}
