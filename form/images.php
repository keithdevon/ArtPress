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
        if(isset($options[$value]) && $file = $options[$value]) {
            $dec = "\nbackground-image:url('{$file}');";
            return $dec;
        }
    }
}
class Background_Image_Dropdown extends CSS_Image_Dropdown implements IToggle_Group {
    function __construct($children, $value=null) {
        parent::__construct('background-image:url', 'image select', $value);
        foreach ($children as $child) {
            $this->add_child($child);
        }
    }
    function get_html() {
        /* The following slightly odd behaviour is necessary for now.
         * Because this is a composite setting, it needs to generate
         * its own tabular html code. However the containing object's html method
         * isn't expecting this. 
         * Therefore we first need to close the currently open cell and row, 
         * create our own rows and cells and then make sure
         * we don't close the last cell and row and the containing object will
         * do that for us. */
        $parent_html = parent::get_html() . ct('td') .  ct('tr')  ;
        $children_html = '';
        $children = parent::get_children();
        if ( null != $children ) {
            $size = sizeof( $children );
            for( $i = 0; $i < $size; $i++ ) {
                $children_html .= ot('tr');
                $children_html .= td( $children[$i]->get_display_name());
                $children_html .= ot('td');
                $children_html .= $children[$i]->get_html();
                if($i != ($size - 1) ) {
                    $children_html .= ct('td') . ct('tr');
                }
            }
            
        }
        return $parent_html . $children_html;
    }
    function is_on() {
        if ($this->get_value()) return true;
        else return false;
    }
}
class Logo_Image_Dropdown extends CSS_Image_Dropdown {
    function __construct($value=0) {
        parent::__construct('background-image:url', 'Logo image select', $value);
    }
    function get_html(){
        return 
            table(
                row( 
                    td($this->get_display_name() ) 
                    . td( parent::get_html( attr_class('globalSetting') ) )
                ),
                attr_class('form-table')  
            );
    }
}
