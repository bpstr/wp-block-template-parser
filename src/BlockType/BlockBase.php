<?php

namespace WordPressBlockTheme\BlockType;

abstract class BlockBase {

    public $attributes;

    public $type;
    public $class;

    public $preserve = ['class' => 'className', 'style' => 'style', 'id' => ''];

    public function __construct($attributes = []) {
        $this->attributes = $attributes;
    }

    public function type(\simple_html_dom_node $item) {
        if ($this->class) {
            $item->addClass($this->class);
        }

        return $this->type;
    }

    public function omit(\simple_html_dom_node $item) {
        if (empty($item->innertext) && empty($item->children())) {
            return true;
        }

        return false;
    }


    public function prepend(\simple_html_dom_node $item) {

        foreach ($item->getAllAttributes() ?? [] as $name => $attribute) {
            if(isset($this->preserve[$name])) {
                if (empty($this->preserve[$name])) {
                    continue;
                }
                $this->attributes[$this->preserve[$name]] = $attribute;
                continue;
            }

            if (!array_key_exists($name, $this->preserve)) {
                $item->removeAttribute($name);
            }
        }

        return sprintf('<!-- wp:%s %s -->', $this->type($item), empty($this->attributes) ? '' : json_encode($this->attributes));
    }

    public function append(\simple_html_dom_node $item) {
        $type = $this->type($item);
        return "<!-- /wp:$type -->";
    }


}
