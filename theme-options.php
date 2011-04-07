<?php

add_action( 'admin_init', 'theme_options_init' );
add_action( 'admin_menu', 'theme_options_add_page' );
add_action('admin_init', 'artpress_options_load_scripts');

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
$select_options = array('0' => array('value' =>	'0', 'label' => __( 'Zero'  )),
			'Baskerville' => array('value' =>	'Baskerville', 'label' => __( 'Baskerville__'   )),
			'2' => array('value' => '2', 'label' => __( 'Two'   )),
			'3' => array('value' => '3', 'label' => __( 'Three' )),
			'4' => array('value' => '4', 'label' => __( 'Four'  )),
			'5' => array('value' => '5', 'label' => __( 'Five'  )));

$radio_options = array(	'blue' => array('value' => 'blue', 'label' => __( 'Blue' )),
			'red' => array('value' => 'red', 'label' => __( 'Red' )),
			'green' => array('value' => 'green', 'label' => __( 'Green' )));

/** Generic HTML generating functions */
/* Generic HTML attribute functions */
function attribute($name, $value) { return ' ' . $name . '="' . $value . '"'; }
function attr_id ($value)         { return attribute('id', $value); }
function attr_name ($value)       { return attribute('name', $value); }
function attr_class ($value)      { return attribute('class', $value); }
function attr_value ($value)      { return attribute('value', $value); }
function attr_type ($value)       { return attribute('type', $value); }
function attr_valign ($value)     { return attribute('valign', $value); }
function attr_checked ($value)    {
	// TODO include type checking
	if($value == true) 
		return attribute('checked', 'checked');
	else 
		//return attribute ('checked', '');
		return '';
}

/* Generic HTML element functions */
function bt($tag_name, $attributes) { return '<' . $tag_name . $attributes . ' />'; } // bacherlor tag eg: <tag />
function ot($tag_name, $attributes) { return '<' . $tag_name . $attributes . '>'; }   // opening tag   eg: <tag>
function ct($tag_name)              { return '</' . $tag_name . '>'; }                // closing tag   eg: </tag>

function td($content, $attributes ="") { return ot('td', $attributes) . $content . ct('td'); }

/** Heart Theme specific functions */
function ht_label($class, $for, $text) { 
	return ot('label', attr_class($class)	. attribute('for', $for)) . $text . ct('label');
}
function ht_th($value, $scope = "")       { 
	return ot('th', attribute('scope', $scope)) . $value . ct('th'); 
}
function ht_input_text ($id, $class, $value, $size) {
	return bt('input', attr_id($id) . attr_class($class) . attr_type('text') . attr_name($id) . attr_value($value) );	
}
function ht_input_checkbox ($field_name, $id, $is_checked, $value, $field_blurb) {
	return bt('input', attr_id($id) . attr_type('checkbox') . attr_name($id) . attr_value($value) . attr_checked($is_checked ) );
}
function ht_form_text_field($field_name, $id, $value, $field_blurb) {
	echo  ot( 'tr', attr_valign('top') )
		. ht_th($field_name, "row")
	    . td(  ht_input_text($id, 'regular-text', $value) 
			. ht_label('description', $id, $field_blurb) )
		. ct('tr');
}
function ht_form_checkbox($field_name, $id, $value, $is_checked, $field_blurb) {
	echo ot( 'tr', attr_valign('top') );
	echo ht_th( $field_name, "row" );
	echo td(  ht_input_checkbox($field_name, $id, $is_checked, $value, $field_blurb)
			. ht_label ('description', $id, $field_blurb) );
	echo ct( 'tr' );
}
//<td>
//		<label class="description" for="artpress_theme_options[primarycolor]"><?php _e( 'Primary' ); ?/></label>
//		<input id = 'artpress_theme_options[primarycolor]' 
//				type = 'text' 
//				class='colorwell'
//				name = 'artpress_theme_options[primarycolor]' 
//				value = "</?php esc_attr_e( $options['primarycolor'] ); ?/>" 
//				size = "7" />
//
//</td>
function ht_color_chooser_cell ( $id, $class, $value, $size, $field_blurb) {
	echo td(  ht_label('description', $id, $field_blurb)
			. bt('br')
			. ht_input_text($id, $class, $value, $size)
	);
}

/**
 * Create the options page
 */
function artpress_options_do_page() {
	global $select_options, $radio_options;

	if ( ! isset( $_REQUEST['updated'] ) )
		$_REQUEST['updated'] = false;

	?>
	<div class="wrap">
		<?php screen_icon(); echo "<h2>" . get_current_theme() . __( ' Options' ) . "</h2>"; ?>

		<?php if ( false !== $_REQUEST['updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'artpress_options' ); ?>
			<?php $options = get_option( 'artpress_theme_options' ); ?>
			
			<p class="submit"><input type="submit" class="button-primary" value="<?php _e( 'Save Options' ); ?>" />	</p>

			<table class="form-table">
				<?php ht_form_text_field("fieldname", "id", "value", "field_blurb");?>
				
				<?php ht_form_text_field('Base text size', 'artpress_theme_options[base_text_size]', esc_attr( $options['base_text_size']), __( 'Base text size blurb label' )); ?>
				
				<?php
				/**
				 * A sample checkbox option
				 */
				?><!--
				<tr valign="top"><th scope="row"><?php _e( 'A checkbox' ); ?></th>
					<td>
						<input id="artpress_theme_options[option1]" name="artpress_theme_options[option1]" type="checkbox" value="1" <?php checked( '1', $options['option1'] ); ?> />
						<label class="description" for="artpress_theme_options[option1]"><?php _e( 'Sample checkbox' ); ?></label>
					</td>
				</tr>
				--><?php 
//							echo "<p>options['option1']: ". $options['option1'] . "</p>";
//							echo "<p>checked(1 , options['option1'], FALSE): " . checked( '1', $options['option1'], true ) . "</p>";
							echo ht_form_checkbox(	__( 'New Sample checkbox' ), 
										"artpress_theme_options[option1]", 
										"1", 
										$options['option1'],
										 "New Sample checkbox blurb"); 
										?>
				<?php
				/**
				 * A sample text input option
				 */
				//create_text_field("artpress_theme_options[sometext]", "artpress_theme_options[sometext]", "text", esc_attr( $options['sometext'] ), $class)
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Body Font' ); ?></th>
					<td>
						<input id="artpress_theme_options[sometext]" class="regular-text" type="text" name="artpress_theme_options[sometext]" value="<?php esc_attr_e( $options['sometext'] ); ?>" />
						<label class="description" for="artpress_theme_options[sometext]"><?php _e( 'type the font family name here' ); ?></label>
					</td>
				</tr>
				
				
				
				<?php
				/*  TITLE FONT */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Title Font' ); ?></th>
					<td>
						<input id="artpress_theme_options[title-font]" class="regular-text" type="text" name="artpress_theme_options[title-font]" value="<?php esc_attr_e( $options['title-font'] ); ?>" />
						<label class="description" for="artpress_theme_options[title-font]"><?php _e( 'type the font family name here' ); ?></label>
					</td>
				</tr>


				<?php /* Color Pickers */ ?>

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

    			
<tr valign="top"><th scope="row"><?php _e( 'Colors' ); ?></th>

<td>
 
<table id="color-table">
	<tr>
	
		<th>
		Element
		</th>
	
		<?php ht_color_chooser_cell('artpress_theme_options[primarycolor]', 'colorwell', esc_attr( $options['primarycolor'] ), 7, __( 'Primary' )); ?>
		<!--<td>
		<label class="description" for="artpress_theme_options[primarycolor]"><\?php _e( 'Primary' ); ?></label>
		<input id = 'artpress_theme_options[primarycolor]' type = 'text' class='colorwell'
name = 'artpress_theme_options[primarycolor]' 
value = "<\?php esc_attr_e( $options['primarycolor'] ); ?>" 
size = "7" />

		</td>-->
		<?php ht_color_chooser_cell('artpress_theme_options[primarycolor]', 'colorwell', esc_attr( $options['secondarycolor'] ), 7, __( 'Secondary' )); ?>		
		<!--<td>
		<label class="description" for="artpress_theme_options[secondarycolor]"></?php _e( 'Secondary' ); ?/></label>
		<input id = 'artpress_theme_options[secondarycolor]' type = 'text' class='colorwell' 
name = 'artpress_theme_options[secondarycolor]' 
value = "</?php esc_attr_e( $options['secondarycolor'] ); ?/>" 
size = "7" />	

		</td>-->
		<?php ht_color_chooser_cell('artpress_theme_options[primarycolor]', 'colorwell', esc_attr( $options['tertiarycolor'] ), 7, __( 'Tertiary' )); ?>			
		<!--<td>
		<label class="description" for="artpress_theme_options[tertiarycolor]"></?php _e( 'Tertiary' ); ?></label>
		<input id = 'artpress_theme_options[tertiarycolor]' type = 'text' class='colorwell'
name = 'artpress_theme_options[tertiarycolor]' 
value = "</?php esc_attr_e( $options['tertiarycolor'] ); ?>" 
size = "7" />	

		</td>-->
		<?php ht_color_chooser_cell('artpress_theme_options[primarycolor]', 'colorwell', esc_attr( $options['backgroundcolor'] ), 7, __( 'Background' )); ?><!--			
		<td>
		<label class="description" for="artpress_theme_options[backgroundcolor]"></?php _e( 'Background' ); ?></label>
		<input id = 'artpress_theme_options[backgroundcolor]' type = 'text' class='colorwell'
name = 'artpress_theme_options[backgroundcolor]' 
value = "</?php esc_attr_e( $options['backgroundcolor'] ); ?>" 
size = "7"  />	

		</td>
		
	--></tr>
	
	
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

<tr valign="top">
	<th scope="row"><?php _e( 'Logo Color' ); ?></th>
	<fieldset>
			<legend class="screen-reader-text"><span><?php _e( 'Logo Color' ); ?></span></legend>
		
			<?php
			    if ( ! isset( $checked ) )
			    	$checked = '';
			    foreach ( $artpress_colors as $option ) {
			    	$radio_setting = $options['logo-color'];
			
			    	if ( '' != $radio_setting ) {
			    		if ( $options['logo-color'] == $option['value'] ) {
			    			$checked = "checked=\"checked\"";
			    		} else {
			    			$checked = '';
			    		}
			    	}
			    	?>
					<td><label class="description horiz">
						<input 	type="radio" 
								name="artpress_theme_options[logo-color]" 
								value="<?php esc_attr_e( $option['value'] ); ?>" 
								<?php echo $checked; ?> /> 
						<?php echo $option['label']; ?>
						</label>
					</td>
			<?php }	?>
			
	
	</fieldset>
</tr>


				
<tr valign="top">
	<th scope="row"><?php _e( 'Title Color' ); ?></th>
	
	<fieldset>
		<legend class="screen-reader-text"><span><?php _e( 'Title Color' ); ?></span></legend>
		    <?php
		    	if ( ! isset( $checked ) )
		    		$checked = '';
		    	foreach ( $artpress_colors as $option ) {
		    		$radio_setting = $options['title-color'];
		
		    		if ( '' != $radio_setting ) {
		    			if ( $options['title-color'] == $option['value'] ) {
		    				$checked = "checked=\"checked\"";
		    			} else {
		    				$checked = '';
		    			}
		    		}
			?>
					<td><label class="description"><input type="radio" name="artpress_theme_options[title-color]" value="<?php esc_attr_e( $option['value'] ); ?>" <?php echo $checked; ?> /> <?php echo $option['label']; ?></label></td>
								<?php
							}
						?>
	</fieldset>					
</tr>



<tr valign="top">
	<th scope="row"><?php _e( 'Page Background' ); ?></th>
	
	<fieldset>
		<legend class="screen-reader-text"><span><?php _e( 'Page Background' ); ?></span></legend>
		    <?php
		    	if ( ! isset( $checked ) )
		    		$checked = '';
		    	foreach ( $artpress_colors as $option ) {
		    		$radio_setting = $options['page-bg-color'];
		
		    		if ( '' != $radio_setting ) {
		    			if ( $options['page-bg-color'] == $option['value'] ) {
		    				$checked = "checked=\"checked\"";
		    			} else {
		    				$checked = '';
		    			}
		    		}
			?>
					<td><label class="description"><input type="radio" name="artpress_theme_options[page-bg-color]" value="<?php esc_attr_e( $option['value'] ); ?>" <?php echo $checked; ?> /> <?php echo $option['label']; ?></label></td>
								<?php
							}
						?>
	</fieldset>					
</tr>
	
	
</table>   			

</td>
</tr>
    			
    			


				
				<?php
				/**
				 * A sample select input option
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Select input' ); ?></th>
					<td>
						<select name="artpress_theme_options[selectinput]">
							<?php
								$selected = $options['selectinput'];
								$p = '';
								$r = '';

								foreach ( $select_options as $option ) {
									$label = $option['label'];
									if ( $selected == $option['value'] ) // Make default first in list
										$p = "\n\t<option style=\"padding-right: 10px;\" selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";
									else
										$r .= "\n\t<option style=\"padding-right: 10px;\" value='" . esc_attr( $option['value'] ) . "'>$label</option>";
								}
								echo $p . $r;
							?>
						</select>
						<label class="description" for="artpress_theme_options[selectinput]"><?php _e( 'Current setting:' ); echo $options['selectinput'];?></label>
					</td>
				</tr>
				
				
							
				
				
				
				

				<?php
				/**
				 * A sample of radio buttons
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Radio buttons' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Radio buttons' ); ?></span></legend>
						<?php
							if ( ! isset( $checked ) )
								$checked = '';
							foreach ( $radio_options as $option ) {
								$radio_setting = $options['radioinput'];

								if ( '' != $radio_setting ) {
									if ( $options['radioinput'] == $option['value'] ) {
										$checked = "checked=\"checked\"";
									} else {
										$checked = '';
									}
								}
								?>
								<label class="description"><input type="radio" name="artpress_theme_options[radioinput]" value="<?php esc_attr_e( $option['value'] ); ?>" <?php echo $checked; ?> /> <?php echo $option['label']; ?></label><br />
								<?php
							}
						?>
						</fieldset>
					</td>
				</tr>
				
				

				<?php
				/**
				 * A sample textarea option
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'A textbox' ); ?></th>
					<td>
						<textarea id="artpress_theme_options[sometextarea]" class="large-text" cols="50" rows="10" name="artpress_theme_options[sometextarea]"><?php echo stripslashes( $options['sometextarea'] ); ?></textarea>
						<label class="description" for="artpress_theme_options[sometextarea]"><?php _e( 'Sample text box' ); ?></label>
					</td>
				</tr>
			</table>

			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e( 'Save Options' ); ?>" />
			</p>
			
			<div id="picker" style="float: right;"></div>
			
			
		</form>
	</div>
	<?php
}

/**
 * Sanitize and validate input. Accepts an array, return a sanitized array.
 */
function artpress_options_validate( $input ) {
	global $select_options, $radio_options, $artpress_colors;

	if ( ! isset( $input['base_text_size'] ) )
		$input['base_text_size'] = 1;
	//$input['base_text_size'] =  $input['base_text_size'];
	
//	if ( ! isset( $input['primarycolor'] ) )
//		$input['primarycolor'] = '#ff0000';
//		
//	if ( ! isset( $input['secondarycolor'] ) )
//		$input['secondarycolor'] = '#0000ff';
//		
//	if ( ! isset( $input['tertiarycolor'] ) )
//		$input['tertiarycolor'] = '#00ff00';
//		
//	if ( ! isset( $input['backgroundcolor'] ) )
//		$input['backgroundcolor'] = '#ffffff';
	
	// Our checkbox value is either 0 or 1
	if ( ! isset( $input['option1'] ) )
		$input['option1'] = null;
	$input['option1'] = ( $input['option1'] == 1 ? 1 : 0 );

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

