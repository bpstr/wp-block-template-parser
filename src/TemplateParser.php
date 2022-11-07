<?php

namespace WordPressBlockTheme;

use voku\helper\HtmlDomParser;
use voku\helper\SimpleHtmlDomInterface;
use WordPressBlockTheme\BlockType\EmbedBlock;
use WordPressBlockTheme\BlockType\GroupBlock;
use WordPressBlockTheme\BlockType\HeadingBlock;
use WordPressBlockTheme\BlockType\ImageBlock;
use WordPressBlockTheme\BlockType\ListBlock;
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
        $this->html = $this->parseSource($source);
        static::$tag_mapping['p'] = new ParagraphBlock();
        static::$tag_mapping['h1'] = new HeadingBlock(['level' => 1]);
        static::$tag_mapping['h2'] = new HeadingBlock(['level' => 2]);
        static::$tag_mapping['h3'] = new HeadingBlock(['level' => 3]);
        static::$tag_mapping['h4'] = new HeadingBlock(['level' => 4]);
        static::$tag_mapping['h5'] = new HeadingBlock(['level' => 5]);
        static::$tag_mapping['h6'] = new HeadingBlock(['level' => 6]);
        static::$tag_mapping['div'] =  new GroupBlock();
        static::$tag_mapping['header'] = new GroupBlock(['tagName' => 'header']);
        static::$tag_mapping['span'] = new GroupBlock(['tagName' => 'span']);
        static::$tag_mapping['img'] = new ImageBlock();
        static::$tag_mapping['ul'] = new ListBlock();
        static::$tag_mapping['iframe'] = new EmbedBlock();
    }

    public function parseSource($source) {
        return $source instanceof \simple_html_dom || $source instanceof \simple_html_dom_node ? $source : file_get_html($source);
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

    public function output() {
        $wrapper = $this->content ?? $this->html->find('body', 0) ?? $this->html;
        $this->convert($wrapper);
        if ($this->content) {
            return $this->content->innertext;
        }

        return (string) $this->html;
    }

    public function generate($output) {
        $wrapper = $this->content ?? $this->html->find('body', 0);
        $this->convert($wrapper);
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
