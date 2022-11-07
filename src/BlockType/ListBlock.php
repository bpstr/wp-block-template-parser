<?php

namespace WordPressBlockTheme\BlockType;

use voku\helper\HtmlDomParser;

class ListBlock extends BlockBase {

    public $type = 'list';


    public function prepend(\simple_html_dom_node $item) {
        $this->attributes['templateLock'] = false;
        $contents = implode(PHP_EOL, $item->children());
        $item->innertext = $contents;

        return parent::prepend($item);
    }

}
