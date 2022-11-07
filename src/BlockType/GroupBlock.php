<?php

namespace WordPressBlockTheme\BlockType;

class GroupBlock extends BlockBase {

    public $type = 'group';
    public $class = 'wp-block-group';

    public function omit(\simple_html_dom_node $item) {
        return false;
    }

    public function type(\simple_html_dom_node $item) {
        if ($item->hasClass('row')) {
            $item->addClass('wp-block-columns');
            return 'columns';
        }

        if (strpos($item->getAttribute('class'), 'col-') !== false) {
            $item->addClass('wp-block-column');
            return 'column';
        }

        return parent::type($item);
    }

    public function prepend(\simple_html_dom_node $item) {
//        $this->attributes['templateLock'] = 'all';
//        $this->attributes['spacing'] = ['margin' => false];


        if (empty($item->children()) && !empty($item->innertext())) {
            $item->innertext = sprintf('<!-- wp:paragraph --><p>%s</p><!-- /wp:paragraph -->', $item->innertext());
        }

        return parent::prepend($item);
    }


}
