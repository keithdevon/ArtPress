<?php
//require_once 'form.php';

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

class Image extends CSS_Text_Input {
    private static $global_image_instances = array();
    
    function __construct($display_name, $value='') {
        parent::__construct('color', $display_name, $value);
        self::$global_image_instances[] = $this;   
        $this_class = get_class($this);
        $number_of_global_image_instances = sizeof(self::$global_image_instances);
        $name = $this_class . '__' . $number_of_global_image_instances;
        $this->set_name( $name );
    }
    static function validate($value) {
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
    function get_name() { return $this->name; }
    
    function get_html() {
        $options = get_option('ap_options');
        $name = $this->get_name();
        $path ='';
        if(array_key_exists('images', $options)) {
            $img_file_paths = $options['images'];
        }
        $input = "<input type='file' name='{$name}' size='40' value='{$path}'/>";
        return $input;
    }
    function set_value($value) {
        $files = $_FILES;
        
    }
}