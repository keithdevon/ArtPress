<?php
/** css declaration */
function dec($property, $value) { // TODO include validation
    if ($value) return "\n" . $property . ": " . $value . ";";
    else return '';
}
/** css declaration block */
function decblock($declarations) {
    return "{" . $declarations . "}\n";
}
/** css rule */
function rule($selectors, $declaration_block) { // TODO validate selector
    return $selectors . ' ' . $declaration_block . "\n";
}

/** background image repeat options*/
$ht_css_repeat = array('no-repeat', 'repeat', 'repeat-x', 'repeat-y', 'inherit');
$ht_css_attachment = array('scroll', 'fixed', 'inherit' );