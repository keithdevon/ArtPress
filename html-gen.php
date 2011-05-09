<?php
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
function attr_style ($value)      { return attribute('style', $value); }
function attr_valign ($value)     { return attribute('valign', $value); }
function attr_size ($value)       { return attribute('size', $value); }
function attr_checked ($value)    {
    if( $value == true ){
        return attribute('checked', 'checked');
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

function div($content, $attributes ="") { return ot('div', $attributes) . $content . ct('div'); }

?>