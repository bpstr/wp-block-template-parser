<?php

namespace WordPressBlockTheme;

use voku\helper\HtmlDomParser;
use voku\helper\SimpleHtmlDomInterface;
use WordPressBlockTheme\BlockType\GroupBlock;
use WordPressBlockTheme\BlockType\HeadingBlock;
use WordPressBlockTheme\BlockType\ImageBlock;
use WordPressBlockTheme\BlockType\ParagraphBlock;

class TemplateParser {

    /**
     * @var HtmlDomParser
     */
    protected $html;

    /**
     * @var SimpleHtmlDomInterface
     */
    protected $content;

    public static $tag_mapping = [];

    public function __construct($source) {
        $this->html = file_get_html($source);
        static::$tag_mapping['p'] = new ParagraphBlock();
        static::$tag_mapping['h1'] = new HeadingBlock(['level' => 1]);
        static::$tag_mapping['h2'] = new HeadingBlock(['level' => 2]);
        static::$tag_mapping['h3'] = new HeadingBlock(['level' => 3]);
        static::$tag_mapping['h4'] = new HeadingBlock(['level' => 4]);
        static::$tag_mapping['h5'] = new HeadingBlock(['level' => 5]);
        static::$tag_mapping['h6'] = new HeadingBlock(['level' => 6]);
        static::$tag_mapping['div'] =  new GroupBlock();
        static::$tag_mapping['header'] = new GroupBlock(['tagName' => 'header']);
        static::$tag_mapping['img'] = new ImageBlock();
    }

    public function setContent($selector) {
        $this->content = $this->html->find($selector, 0);
    }

    public function replace($selector, $innertext) {
        $this->html->find($selector, 0)->innertext = $innertext;
    }

    public function convert($tag) {
        $this->explore($tag);
    }

    public function generate($output) {
        $this->convert($this->content ?? $this->html->find('body', 0));
        if ($this->content) {
            file_put_contents($output, $this->content->innertext);
            return;
        }

        $this->html->save($output);
    }

    protected function explore(\simple_html_dom_node $node) {

        $inner = [];

        if (empty($node->children())) {
            return;
        }

        /** @var \simple_html_dom_node $item */
        foreach ($node->children() as $key => $item) {
            $this->explore($item);
            $inner[$key] = $this->process($item);
        }

        $node->innertext = implode(PHP_EOL, $inner);

    }

    protected function process(\simple_html_dom_node $node) {
        $block = $this->resolveBlock($node);
        if (!$block) {
            return '';
            return "<!-- unknown $node -->";
        }

        if ($block->omit($node)) {
            return '';
        }

        return PHP_EOL . $block->prepend($node) . PHP_EOL . $node . PHP_EOL . $block->append($node) . PHP_EOL;
    }

    protected function resolveBlock(\simple_html_dom_node $node) {
        $tag = $node->tag;

        return static::$tag_mapping[$tag] ?? false;
    }

}
