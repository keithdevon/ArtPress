<?php

/** Generic HTML generating functions */
function attribute($name, $value) {
    if ( $value == '' ) return null;
    else return ' ' . $name . '="' . $value . '"';
}
function attr_action        ($value) { return attribute('action',      $value);     }
function attr_alt           ($value) { return attribute('alt',         $value);     }
function attr_class         ($value) { return attribute('class',       $value);     }
function attr_enctype       ($value) { return attribute('enctype',     $value);     }
function attr_for           ($value) { return attribute('for',         $value);     }
function attr_href          ($value) { return attribute('href',        $value);     }
function attr_id            ($value) { return attribute('id',          $value);     }
function attr_label         ($value) { return attribute('label',       $value);     }
function attr_method        ($value) { return attribute('method',      $value);     }
function attr_name          ($value) { return attribute('name',        $value);     }
function attr_on_blur       ($value) { return attribute('onblur',      $value);     }
function attr_on_change     ($value) { return attribute('onchange',    $value);     }
function attr_on_click      ($value) { return attribute('onclick',     $value);     }
function attr_on_load       ($value) { return attribute('onload',      $value);     }
function attr_on_focus      ($value) { return attribute('onfocus',     $value);     }
function attr_on_mouse_down ($value) { return attribute('onmousedown', $value);     }
function attr_on_mouse_over ($value) { return attribute('onmouseover', $value);     }
function attr_readonly      ()       { return attribute('readonly',    'readonly'); }
function attr_size          ($value) { return attribute('size',        $value);     }
function attr_src           ($value) { return attribute('src',         $value);     }
function attr_title         ($value) { return attribute('title',       $value);     }
function attr_type          ($value) { return attribute('type',        $value);     }
function attr_valign        ($value) { return attribute('valign',      $value);     }
function attr_value         ($value) { return attribute('value',       $value);     }
function attr_width         ($value) { return attribute('width',       $value);     }

function attr_style ($value)      {
    if( $value==true ) return attribute('style', $value);
    else return '';
}
function attr_checked ($value)    {
    if( $value == true ){
        return attribute('checked', 'checked');
    } else {
        return '';
    }
}
function attr_disabled      ($value) { 
    if( $value == true ){
        return attribute('disabled',    'disabled'); 
    } else {
        return '';
    }
}
function attr_selected ($value)    {
    if( $value == true ){
        return attribute('selected', 'selected');
    } else {
        return '';
    }
}

/* Generic HTML element functions */
function bt($tag_name, $attributes='') { return '<' . $tag_name . $attributes . ' />'; } // bacherlor tag eg: <tag />
function ot($tag_name, $attributes='') { return '<' . $tag_name . $attributes . '>'; }   // opening tag   eg: <tag>
function ct($tag_name)                 { return '</' . $tag_name . '>'; }                // closing tag   eg: </tag>

/* Specific HTML element functions */
function alink($href, $content, $attributes="") { return ot('a', attr_href($href) . $attributes ) . $content . ct('a');}
function checkbox($name, $checked, $attributes="") { return bt('input', attr_type('checkbox') . attr_name($name) . attr_checked($checked) . $attributes); }
function div($content, $attributes ="")         { return ot('div', $attributes)   . $content . ct('div'); }
function form($method, $action, $content, $enctype, $attributes='') {
    $o = ot('form',
        attr_method($method) . attr_action($action) . attr_enctype($enctype) . $attributes) ;
    $o .= $content;
    return $o . ct('form');
}
function h1($content, $attributes ="")          { return ot('h1', $attributes)    . $content . ct('h1'); }
function h2($content, $attributes ="")          { return ot('h2', $attributes)    . $content . ct('h2'); }
function h3($content, $attributes ="")          { return ot('h3', $attributes)    . $content . ct('h3'); }
function h4($content, $attributes ="")          { return ot('h4', $attributes)    . $content . ct('h4'); }
function h5($content, $attributes ="")          { return ot('h5', $attributes)    . $content . ct('h5'); }
function h6($content, $attributes ="")          { return ot('h6', $attributes)    . $content . ct('h6'); }
function img($src, $attributes="")              { return bt('img', attr_src($src) . $attributes); }
function input($type, $attributes ="")          { return bt('input', attr_type($type) . $attributes); }
function label($for, $content, $attributes ="") { return ot('label', attr_for($for)  . $attributes) . $content . ct('label'); }
function li($content, $attributes ="")          { return ot('li', $attributes)    . $content . ct('li'); }
function optgroup($label, $options)             { return ot('optgroup', attr_label($label)) . $options . ct('optgroup'); }
function option($value, $content, $attributes=""){ return ot('option', attr_value($value) . $attributes) . $content . ct('option'); }
function p($content, $attributes ="")           { return ot('p', $attributes)      . $content . ct('p'); }
function select($name, $content, $attributes=""){ return ot('select', attr_name($name) . $attributes ) . $content . ct('select'); }
function span($content, $attributes ="")         { return ot('span', $attributes)   . $content . ct('span'); }
function table($content, $attributes ="")       { return ot('table', $attributes)  . $content . ct('table'); }
function td($content, $attributes ="")          { return ot('td', $attributes)     . $content . ct('td'); }
function th($content, $attributes ="")          { return ot('th', $attributes)     . $content . ct('th'); }
function tr($content, $attributes ="")          { return ot('tr', $attributes)     . $content . ct('tr'); }
function ul($content, $attributes='')           { return ot('ul', $attributes)     . $content . ct('ul'); }

function col($attributes='') {
    return bt('col', $attributes);
}
function colgroup($number_of_cols) {
    $cols = '';
    for( $i=0; $i<$number_of_cols; $i++ ) {
        $cols .= col();
    }
    return ot('colgroup') . $cols . ct('colgroup');
}
?>