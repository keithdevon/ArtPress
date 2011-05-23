<?php
// TODO reorder attributes in alphabetical order

/** Generic HTML generating functions */
/* Generic HTML attribute functions */
function attribute($name, $value) { 
    if ( $value == '' ) return null;
    else return ' ' . $name . '="' . $value . '"'; } 
// TODO if $value is null should this function return null? 
function attr_id ($value)         { return attribute('id', $value); }
function attr_name ($value)       { return attribute('name', $value); }
function attr_class ($value)      { return attribute('class', $value); }
function attr_value ($value)      { return attribute('value', $value); }
function attr_type ($value)       { return attribute('type', $value); }
function attr_alt ($value)        { return attribute('alt', $value); }
function attr_src ($value)        { return attribute('src', $value); }

function attr_style ($value)      { 
    if( $value==true ) return attribute('style', $value);
    else return ''; 
}
function attr_valign ($value)     { return attribute('valign', $value); }
function attr_size ($value)       { return attribute('size', $value); }
function attr_href ($value)       { return attribute('href', $value); }
function attr_label ($value)      { return attribute('label', $value); }
function attr_checked ($value)    {
    if( $value == true ){
        return attribute('checked', 'checked');
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

function td($content, $attributes ="") { return ot('td', $attributes) . $content . ct('td'); }
function tr($content, $attributes ="") { return ot('tr', $attributes) . $content . ct('tr'); }
function table($content, $attributes ="") { return ot('table', $attributes) . $content . ct('table'); }
function alink($href, $content, $attributes) { return ot('a', attr_href($href) . $attributes ) . $content . ct('a');}
function div($content, $attributes ="") { return ot('div', $attributes) . $content . ct('div'); }
function optgroup($label, $options) { return ot('optgroup', attr_label($label)) . $options . ct('optgroup'); }

?>