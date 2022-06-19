<?php

namespace WordPressBlockTheme\BlockType;

class GroupBlock extends BlockBase {

    public $type = 'group';
    public $class = 'wp-block-group';

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

        if (empty($item->children())) {
            $item->innertext = sprintf('<!-- wp:paragraph --><p>%s</p><!-- /wp:paragraph -->', $item->innertext());
        }

        return parent::prepend($item);
    }


}
