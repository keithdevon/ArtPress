<?php
require_once 'heart-theme-form-functions.php';
//require_once 'background-image-uploader.php';//code to add image uploader - this will be used for background images and needs to be added to the ArtPress options page

add_action( 'admin_init', 'theme_options_init' );
add_action( 'admin_menu', 'theme_options_add_page' );
add_action( 'admin_init', 'artpress_options_load_scripts' );

// Load our scripts
function artpress_options_load_scripts() {

    wp_enqueue_script('farbtastic', get_bloginfo('template_url') .
        '/scripts/farbtastic/farbtastic.js', array('jquery'));
    wp_register_style( 'ArtPressOptionsStylesheet', get_bloginfo('template_url') . 
        '/scripts/farbtastic/farbtastic.css' );
    wp_enqueue_style( 'ArtPressOptionsStylesheet' );
        
}

/**
 * Init plugin options to white list our options
 */
function theme_options_init(){
    register_setting( 'artpress_options', 'artpress_theme_options', 'artpress_options_validate' );
    $options = get_option('artpress_theme_options');
    $padded_with_default_options = artpress_options_validate($options);
    update_option('artpress_theme_options', $padded_with_default_options);   
}

//        update_option('artpress_theme_options', get_default_options());

function get_default_options() {
    // TODO include theme version variable?
    $options = array(
    	'theme_version' => 0.1,
    	'base_text_size' => '1em',
        'color1' => '#ff0000',
        'color2' => '#00ff00',
        'color3' => '#0000ff',
        'color4' => '#888888'
                     );
    return $options; 
}

/**
 * Load up the menu page
 */
function theme_options_add_page() {
        add_theme_page( __( 'ArtPress Options' ), __( 'ArtPress Options' ), 'edit_theme_options', 'theme_options', 'artpress_options_do_page' );
}

/**
 * Create arrays for our select and radio options
 */
$select_options = array('0' => array('value' =>         '0', 'label' => __( 'Zero'  )),
                        'Baskerville' => array('value' =>       'Baskerville', 'label' => __( 'Baskerville__'   )),
                        '2' => array('value' => '2', 'label' => __( 'Two'   )),
                        '3' => array('value' => '3', 'label' => __( 'Three' )),
                        '4' => array('value' => '4', 'label' => __( 'Four'  )),
                        '5' => array('value' => '5', 'label' => __( 'Five'  )));

$radio_options = array(         'blue' => array('value' => 'blue', 'label' => __( 'Blue' )),
                        'red' => array('value' => 'red', 'label' => __( 'Red' )),
                        'green' => array('value' => 'green', 'label' => __( 'Green' )));

$num_colors = 4;
$num_fonts  = 3;


/** style element creator functions */
function create_element_options($color, $background_color, $font_size, $font_family, $padding) {
    
}
function create_style_element($element_name, $element_selector, $element_options) {
    
}

/**
 * Create the options page
 */
function artpress_options_do_page() {
    global $num_colors, $num_fonts;
    global $select_options, $radio_options;

    if ( ! isset( $_REQUEST['updated'] ) )
        $_REQUEST['updated'] = false;

    ?>
    <div class="wrap">
        <?php 
        screen_icon(); echo "<h2>" . get_current_theme() . __( ' Options' ) . "</h2>"; 
        if ( false !== $_REQUEST['updated'] ) : ?>
                        <div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
        <?php endif; ?>

        <form method="post" action="options.php">
            <?php 
            settings_fields( 'artpress_options' ); 
            $options = get_option( 'artpress_theme_options' ); ?>
            <h3>Global settings</h3>
            <table class="form-table">
                <?php echo ht_form_text_field('Base text size', 'base_text_size', esc_attr( $options['base_text_size']), __( "example options: '16px', '1em' or '100%'" ), '5');
                $cells = '';
                for ($i = 1; $i <= $num_colors; $i++) {
                    $id = 'color' . $i;
                    $cells .= ht_form_cell($id,    'colorwell', esc_attr( $options[$id] ), '7', __( 'Color ' . $i ));
                }
                echo ht_form_field('Colors',
                    table(
                        tr(
                              td(div('', attr_id('picker') . attr_style('float: right;')))
                            . $cells
                        ),    
                        attr_valign('top')
                    )
                ); 
                $cells = '';
                for ($i = 1; $i <= $num_fonts; $i++) {
                    $id = 'font' . $i;
                    $cells .= ht_form_cell($id,    'fontfamily', esc_attr( $options[$id] ), '7', __( 'Font ' . $i ));
                }              
                echo ht_form_field('Fonts',
                    table(
                        tr($cells),    
                        attr_valign('top')
                    )
                );
                /* BODY FONT */
                //echo ht_form_text_field(__( 'Body Font' ), "artpress_theme_options[sometext]", esc_attr( $options['sometext'] ), __( 'type the font family name here' ));                               

                /*  TITLE FONT */
                //echo ht_form_text_field(__( 'Title Font' ), "artpress_theme_options[title-font]", esc_attr( $options['title-font'] ), __( 'type the font family name here' ));
                                
                /* PAGE WIDTH */
                echo ht_form_text_field('Page Width', 'page_width', esc_attr( $options['page_width']), __( "example options: '1000px', '30em' or '100%'" ), '6');
                                /* Color Pickers */ ?>
                        <script>
                    jQuery(document).ready(function() {
                        var f = jQuery.farbtastic('#picker');
                        var p = jQuery('#picker').css('opacity', 0.25);
                        var selected;
                        jQuery('.colorwell')
                          .each(function () { f.linkTo(this); jQuery(this).css('opacity', 0.75); })
                          .focus(function() {
                            if (selected) {
                              jQuery(selected).css('opacity', 0.75).removeClass('colorwell-selected');
                            }
                            f.linkTo(this);
                            p.css('opacity', 1);
                            jQuery(selected = this).css('opacity', 1).addClass('colorwell-selected');
                          });
                      });
                 </script>


                <?php
                /**
                * Logo + Title Colors 
                */
                                                 
                $artpress_colors = array(
                        'primary' =>    array('value' => 'primary',    'label' => __( 'Primary' )),
                        'secondary' =>  array('value' => 'secondary',  'label' => __( 'Secondary' )),
                        'tertiary' =>   array('value' => 'tertiary',   'label' => __( 'Tertiary' )),
                        'background' => array('value' => 'background', 'label' => __( 'Background' ))
                                        );
                                        ?>
                </table>
                <p class="submit"><input type="submit" class="button-primary" value="<?php _e( 'Save Options' ); ?>" /></p>

                <h3>Site part settings</h3>
                <table class="form-table">
                    <?php
                    
                    
                    $part = 'body_';
                    
                    $cells = '';
                    $id = $part . 'font';
                    $checked = esc_attr( $options[$id] );
                    for ($i = 1; $i <= $num_fonts; $i++) {
                        $cells .= ht_form_cell_radio($id, $i, ($i == $checked) ? $i : false, '7', __( 'Font ' . $i ));
                    }  
                	echo ht_form_field('Body font', 
                        table(
                            tr($cells),    
                            attr_valign('top')
                        )
                    );    

                    $cells = '';
                    $id =  $part . 'color';
                    $checked = esc_attr( $options[$id] );
                    for ($i = 1; $i <= $num_colors; $i++) {
                        $cells .= ht_form_cell_radio($id, $i, ($i == $checked) ? $i : false, '7', __( 'Color ' . $i ));
                    }  
                	echo ht_form_field('Body color', 
                        table(
                            tr($cells),    
                            attr_valign('top')
                        )
                    );
                    
                    $cells = '';
                    $id =  $part . 'backgroundcolor'; 
                    $checked = esc_attr( $options[$id] );
                    for ($i = 1; $i <= $num_colors; $i++) {
                        $cells .= ht_form_cell_radio($id, $i, ($i == $checked) ? $i : false, '7', __( 'Color ' . $i ));
                    }  
                	echo ht_form_field('Body background color', 
                        table(
                            tr($cells),    
                            attr_valign('top')
                        )
                    );
                    $id = $part . 'fontsize';
                    echo ht_form_text_field('Body font size', $id, esc_attr( $options[$id] ), '');
                    $id = $part . 'padding';                    
                    echo ht_form_text_field('Body padding', $id, esc_attr( $options[$id] ), 'internal space between the element\'s content and its border');
                    $id = $part . 'margin';
                    echo ht_form_text_field('Body margin', $id, esc_attr( $options[$id] ), 'external space between the element\'s border and other elements');
                     
                	?>
                </table>
                <p class="submit"><input type="submit" class="button-primary" value="<?php _e( 'Save Options' ); ?>" /></p>
            </form>
        </div>
        <?php
}
function ends_with($str, $suffix) {
    $suffix_start = strlen($str) - strlen($suffix);
    return (substr($str, $suffix_start) == $suffix);
}
function get_prefix($str, $suffix) {
    return substr($str, 0, strlen($str) - strlen($suffix));
}
function is_suffix_string($str, $suffix) {
    if (ends_with($str, $suffix)) {
        $prefix = get_prefix($str, $suffix);
        return is_numeric($prefix);
    }
}
function is_em_string($str) {
    return is_suffix_string($str, 'em');
}
function is_px_string($str) {
    return is_suffix_string($str, 'px');
}
function is_percent_string($str) {
    return is_suffix_string($str, '%');
}
function is_valid_size_string($str) {
    return (is_em_string($str) || is_px_string($str) || is_percent_string($str));
}
/**
 * Sanitize and validate input. Accepts an array, return a sanitized array.
 */
function artpress_options_validate( $input ) {
    global $select_options, $radio_options, $artpress_colors;
    
    if (!(isset( $input['base_text_size']) 
          && is_valid_size_string($input['base_text_size'])))      
        $input['base_text_size'] = '1em'; 
    
    if ( !isset( $input['color1'] ) ) {
        $input['color1'] = '#00ff00';        
    }
    
    if ( !isset( $input['color2'] ) ) {
        $input['color2'] = '#222222';        
    }
    
//    // Our checkbox value is either 0 or 1
//    if ( ! isset( $input['option1'] ) ) {
//        $input['option1'] = null;
//        $input['option1'] = ( $input['option1'] == 1 ? 1 : 0 );        
//    }
    
    // Say our text option must be safe text with no HTML tags
    $input['sometext'] = wp_filter_nohtml_kses( $input['sometext'] );
    
    // Our select option must actually be in our array of select options
    if ( ! array_key_exists( $input['selectinput'], $select_options ) )
        $input['selectinput'] = null;
    
    // Our radio option must actually be in our array of radio options
    if ( ! isset( $input['radioinput'] ) )
        $input['radioinput'] = null;
    if ( ! array_key_exists( $input['radioinput'], $radio_options ) )
        $input['radioinput'] = null;
    
    // Say our textarea option must be safe text with the allowed tags for posts
    $input['sometextarea'] = wp_filter_post_kses( $input['sometextarea'] );
    
    return $input;
}