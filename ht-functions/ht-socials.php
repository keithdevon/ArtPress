<?php 

add_action( 'show_user_profile', 'ht_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'ht_show_extra_profile_fields' );

function ht_show_extra_profile_fields( $user ) { ?>

	<h3>Social Info</h3>

	<table class="form-table">

		<tr>
			<th><label for="twitter">Twitter</label></th>

			<td>
				<input type="text" name="twitter" id="twitter" value="<?php echo esc_attr( get_the_author_meta( 'twitter', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter your Twitter username.</span>
			</td>
		</tr>
		
		
		<tr>
			<th><label for="flickr">Flickr</label></th>

			<td>
				<input type="text" name="flickr" id="flickr" value="<?php echo esc_attr( get_the_author_meta( 'flickr', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter your Flickr username.</span>
			</td>
		</tr>
		
		
		<tr>
			<th><label for="linkedin">LinkedIn URL</label></th>

			<td>
				<input type="text" name="linkedin" id="linkedin" value="<?php echo esc_attr( get_the_author_meta( 'linkedin', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter your LinkedIn public URL.</span>
			</td>
		</tr>
		
		
		<tr>
			<th><label for="facebook">Facebook</label></th>

			<td>
				<input type="text" name="facebook" id="facebook" value="<?php echo esc_attr( get_the_author_meta( 'facebook', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter your Facebook username.</span>
			</td>
		</tr>
	</table>
	
	 <h3>Address</h3>
	     
    <table class="form-table">

		<tr>
			<th><label for="company-name">Company/Organisation Name</label></th>

			<td>
				<input type="text" name="company-name" id="company-name" value="<?php echo esc_attr( get_the_author_meta( 'company-name', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter your company/organisation name.</span>
			</td>
		</tr>
		
		
		<tr>
			<th><label for="address-line-1">Line 1</label></th>

			<td>
				<input type="text" name="address-line-1" id="address-line-1" value="<?php echo esc_attr( get_the_author_meta( 'address-line-1', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter the first line of your address.</span>
			</td>
		</tr>
		
		
		<tr>
			<th><label for="address-line-2">Line 2</label></th>

			<td>
				<input type="text" name="address-line-2" id="address-line-2" value="<?php echo esc_attr( get_the_author_meta( 'address-line-2', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter the second line of your address.</span>
			</td>
		</tr>
		
		
		<tr>
			<th><label for="address-city">City</label></th>

			<td>
				<input type="text" name="address-city" id="address-city" value="<?php echo esc_attr( get_the_author_meta( 'address-city', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter your city/town.</span>
			</td>
		</tr>
		
		
		<tr>
			<th><label for="address-county">County/State</label></th>

			<td>
				<input type="text" name="address-county" id="address-county" value="<?php echo esc_attr( get_the_author_meta( 'address-county', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter your county/state.</span>
			</td>
		</tr>
		
		
		<tr>
			<th><label for="address-code">Zip/Post code</label></th>

			<td>
				<input type="text" name="address-code" id="address-code" value="<?php echo esc_attr( get_the_author_meta( 'address-code', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter your zip/post code.</span>
			</td>
		</tr>
		
		
		<tr>
			<th><label for="address-country">Country</label></th>

			<td>
				<input type="text" name="address-country" id="address-country" value="<?php echo esc_attr( get_the_author_meta( 'address-country', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter your country.</span>
			</td>
		</tr>

	</table>

	
   <?php }


add_action( 'personal_options_update', 'ht_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'ht_save_extra_profile_fields' );

function ht_save_extra_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	/* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
	update_user_meta( $user_id, 'twitter', $_POST['twitter'] );
	update_user_meta( $user_id, 'flickr', $_POST['flickr'] );
	update_user_meta( $user_id, 'linkedin', $_POST['linkedin'] );
	update_user_meta( $user_id, 'facebook', $_POST['facebook'] );
	update_user_meta( $user_id, 'company-name', $_POST['company-name'] );
	update_user_meta( $user_id, 'address-line-1', $_POST['address-line-1'] );
	update_user_meta( $user_id, 'address-line-2', $_POST['address-line-2'] );
	update_user_meta( $user_id, 'address-city', $_POST['address-city'] );
	update_user_meta( $user_id, 'address-county', $_POST['address-county'] );
	update_user_meta( $user_id, 'address-code', $_POST['address-code'] );
	update_user_meta( $user_id, 'address-country', $_POST['address-country'] );
}