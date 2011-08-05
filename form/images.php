<?php

class Images_Tab extends Main_Tab {
    function __construct($members=null) {
        if ( null == $members ) {
            $i1 = new Image('image 1');
            $i2 = new Image('image 2');
            $i3 = new Image('image 3');
            $members[] = new Option_Group('Images',  array($i1, $i2, $i3));
        } 
        parent::__construct('images', 'artpress_options', $members);
        $this->form_enctype = "multipart/form-data";
    }  
}

class Image extends CSS_Text_Input { // TODO this should probably be deleted
    private static $global_image_instances = array();
    
    function __construct($display_name, $value='') {
        parent::__construct('color', $display_name, $value);
        self::$global_image_instances[] = $this;   
        $this_class = get_class($this);
        $number_of_global_image_instances = sizeof(self::$global_image_instances);
        $name = $this_class . '__' . $number_of_global_image_instances;
        $this->set_name( $name );
    }
    function validate($value) {
        //TODO
    }
    static function get_dropdown_image_options() {
        $options = array();
        foreach (self::$global_image_instances as $image) {
            $v = $image->get_value();
            $options[] = $v;
        }
        return $options;       
    }
    function get_html() {
        $options = get_option('ap_options');
        $name = $this->get_name();
        $path ='';
        if(array_key_exists('images', $options)) {
            $img_file_paths = $options['images'];
        }
        $files = $_FILES;
        // TODO un hard code hardcoded name
        $input = "<input type='file' name='ap_options[images][{$name}]' size='40' value='{$path}'/>";
        return $input;
    }
    function set_value($value) {
        
    }
}
abstract class CSS_Image_Dropdown extends CSS_Dropdown_Input {
    static $options;

    function __construct($css_property, $display_name, $value=0) {
        global $post;
        parent::__construct($css_property, $display_name, null, $value);
        if(!self::$options) { 
            self::$options[0] = '';
            if($option = get_option('ap_images') ){
                if( isset($option['images']) && $images = $option['images'] ) {
                    foreach( array_keys($images) as $aid ) {
                        $url = wp_get_attachment_url($aid);
                        self::$options[$aid] = $url;
                    }
                }
            }         
        }     
    }    
    function get_opts() {
        return self::$options;
    }
    
    function validate($value) {
        return (in_array($value, array_keys(self::$options)));
    }
    function get_css_declaration() {
        $options = self::$options;
        $value = $this->get_value();
        $file = $options[$value];
        if($file) {      
            $dec = "\nbackground-image:url('{$file}');";
            return $dec;
        }
    }
}
class Background_Image_Dropdown extends CSS_Image_Dropdown {
    function __construct($value=0) {
        parent::__construct('background-image:url', 'image select', $value);
    }
}
class Logo_Image_Dropdown extends CSS_Image_Dropdown {
    function __construct($value=0) {
        parent::__construct('background-image:url', 'Logo image select', $value);  
    }
}