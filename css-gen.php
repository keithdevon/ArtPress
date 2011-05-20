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

/** css property options*/
$ht_css_repeat = array('no-repeat', 'repeat', 'repeat-x', 'repeat-y', 'inherit');
$ht_css_attachment = array('scroll', 'fixed', 'inherit' );

$ht_css_font_style = array('normal', 'italic', 'oblique');

$ht_css_text_transform = array('none', 'uppercase', 'lowercase', 'capitalize');
$ht_css_text_align = array('left', 'right', 'center', 'justify');
$ht_css_text_decoration = array('none', 'underline', 'overline', 'line-through', 'blink');

$ht_css_border_style = array('none', 'hidden', 'dotted', 'dashed', 'solid', 'double', 'groove', 'ridge', 'inset', 'outset', 'inherit');

$ht_css_list_style_position = array('inherit', 'inside', 'outside');
$ht_css_list_style_type = array('circle', 'decimal', 'decimal-leading-zero', 'disc', 'lower-alpha', 'lower-roman', 'none', 'square', 'upper-alpha', 'upper-roman');

$ht_css_font_weight = array('normal', 'bold', 'bolder', 'lighter', '100', '200', '300', '400', '500', '600', '700', '800', '900'  );

$ht_css_font_family = array(
	array('Arial, “Helvetica Neue”, Helvetica, sans-serif','paragraph or title'),
	'Cambria, Georgia, Times, “Times New Roman”, serif',
	'“Century Gothic”, “Apple Gothic”, sans-serif',
	'Consolas, “Lucida Console”, Monaco, monospace',
	'“Copperplate Light”, “Copperplate Gothic Light”, serif',
	'“Courier New”, Courier, monospace',
	'“Franklin Gothic Medium”, “Arial Narrow Bold”, Arial, sans-serif',
	'Futura, “Century Gothic”, AppleGothic, sans-serif',
	'Impact, Haettenschweiler, “Arial Narrow Bold”, sans-serif',
	'“Lucida Sans”, “Lucida Grande”, “Lucida Sans Unicode”, sans-serif',
	'Times, “Times New Roman”, Georgia, serif', 
	array('Baskerville, “Times New Roman”, Times, serif','paragraph'),
	'Garamond, “Hoefler Text”, Times New Roman, Times, serif',
	'Geneva, “Lucida Sans”, “Lucida Grande”, “Lucida Sans Unicode”, Verdana, sans-serif',
	'Georgia, Palatino,” Palatino Linotype”, Times, “Times New Roman”, serif',
	'“Gill Sans”, Calibri, “Trebuchet MS”, sans-serif',
	'“Helvetica Neue”, Arial, Helvetica, sans-serif',
	'Palatino, “Palatino Linotype”, Georgia, Times, “Times New Roman”, serif',
	'Tahoma, Geneva, Verdana',
	'“Trebuchet MS”, “Lucida Sans Unicode”, “Lucida Grande”,” Lucida Sans”, Arial, sans-serif',
	'Verdana, Geneva, Tahoma, sans-serif',
	array('Baskerville, Times, “Times New Roman”, serif','title'),
	'Garamond, “Hoefler Text”, Palatino, “Palatino Linotype”, serif',
	'Geneva, Verdana, “Lucida Sans”, “Lucida Grande”, “Lucida Sans Unicode”, sans-serif',
	'Georgia, Times, “Times New Roman”, serif',
	'“Gill Sans”, “Trebuchet MS”, Calibri, sans-serif',
	'Helvetica, “Helvetica Neue”, Arial, sans-serif',
	'Palatino, “Palatino Linotype”, “Hoefler Text”, Times, “Times New Roman”, serif',
	'Tahoma, Verdana, Geneva',
	'“Trebuchet MS”, Tahoma, Arial, sans-serif',
	'Verdana, Tahoma, Geneva, sans-serif'
	);
