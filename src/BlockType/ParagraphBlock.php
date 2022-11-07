<?php

namespace WordPressBlockTheme\BlockType;

class ParagraphBlock extends BlockBase {

    public $type = 'paragraph';

    public function omit(\simple_html_dom_node $item) {
        $item->innertext = str_replace('--newline--', '</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph  -->
<p>', trim($item->innertext()));

        return parent::omit($item);
    }


    public function type(\simple_html_dom_node $item) {


        return $this->type;
    }
}
