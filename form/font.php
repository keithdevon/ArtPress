<?php
require_once 'form.php';

// FONT
class Global_Font_Family extends CSS_Dropdown_Input { 
    function __construct($parent, $value) { 
        parent::__construct('font-family', 'font family', $parent, $value); 
        self::set_options(array(
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
                    	));
    }    
}
/**
 * Class to represent a selector of one of the preselected font
 * @author jsd
 *
 */
class Section_Font extends CSS_Dropdown_Input {
    function __construct($parent, $value, $group_arr) {
        parent::__construct('font-family', 'font select', $parent, $value); 
        self::set_options($group_arr);
    }    
}
class Font_Style extends CSS_Dropdown_Input { 
    function __construct($parent, $value) { 
        parent::__construct('font-style', 'font style', $parent, $value); 
        self::set_options(array('normal', 'italic', 'oblique'));
    }    
}
class Font_Weight extends CSS_Dropdown_Input { 
    function __construct($parent, $value) { 
        parent::__construct('font-weight', 'font weight', $parent, $value); 
        self::set_options(array('normal', 'bold', 'bolder', 'lighter', '100', '200', '300', '400', '500', '600', '700', '800', '900'  ));
    }    
}