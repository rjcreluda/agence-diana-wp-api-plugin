<?php

if( !defined('ABSPATH') ){
	exit;
}

if( isset( $_POST['ads_french'] ) && !empty( $_POST['ads_french'] ) && isset( $_POST['ads_english'] ) && !empty( $_POST['ads_english'] ) ){
	$this->post_content = array(
		'french' => str_replace("\'", "'", $_POST['ads_french']), // texte en français
		'english' => str_replace("\'", "'", $_POST['ads_english']) // text en anglais
	);
	//var_dump($_POST['ads_french']);
	//var_dump(str_replace("\'", "'", $_POST['ads_french']));
	//wp_die();
}
if( isset( $_POST['ads_title'] ) && !empty( $_POST['ads_title'] ) ){
	$this->post_title = sanitize_text_field( $_POST['ads_title'] );
}

if( isset($_POST['sites']) && is_array($_POST['sites']) ){
	$sites =  $_POST['sites'];

	$forDiego = in_array('diego', $sites); // verifi si annonce origine de Diego
	
	foreach($sites as $name){
		$url = $this->sites[$name]['url'];
		$login = $this->sites[$name]['login'];
		$post_type = $this->sites[$name]['post_type'];

		$jind_post = new Jind_Post();
		$jind_post->title = $this->post_title;

		$french_text = $this->post_content['french'];
		$english_text = $this->post_content['english'];
		
		if( !$forDiego ){ // If Ads is not originally for Diego Suarez, add social network link at the bottom
			$french_text .= Jind_Constant::french_footer_text();
			$english_text .= Jind_Constant::english_footer_text();
		}

		if( $name == 'real' or $name == 'property' ) // English text first for real estate, property
			$jind_post->content = $english_text . '<br>' . $french_text;
		else
			$jind_post->content = $french_text . '<br>' . $english_text;

		$jind_post->slug = sanitize_title($this->post_title);
		$jind_post->type = $post_type;

		if( $name == 'mahajanga' )
			$jind_post->status = 'publish';

		/* Need to work on taxonomy of different sites */
		$jind_post->taxonomy =  $this->sites[$name]['taxonomy'];

		/* Set meta fields according to the site */
		switch( $name ){
			case 'mahajanga':
				$meta = array(
					$this->sites[$name]['meta_keys']['prix'] => esc_html( $_POST['price'] ),
					$this->sites[$name]['meta_keys']['street'] => 'Mahajanga',
					$this->sites[$name]['meta_keys']['address'] => esc_html( $_POST['address'] ),
					$this->sites[$name]['meta_keys']['province'] => 'Mahajanga',
					$this->sites[$name]['meta_keys']['email'] => 'info@mahajanga-immobilier.com',
				);
				/*$jind_post->yoast_meta = array(
					'yoast_wpseo_metadesc'	=> 'lorem ipsum description hight here'
				);*/
				break;
			case 'diego':
				$meta = array(
					$this->sites[$name]['meta_keys']['prix']	=> esc_html( $_POST['price'] ),
					$this->sites[$name]['meta_keys']['adresse'] => esc_html( $_POST['address'] ),
					$this->sites[$name]['meta_keys']['ref']	=> '',
					$this->sites[$name]['meta_keys']['province'] => 'Diego-Suarez',
					$this->sites[$name]['meta_keys']['email'] => 'info@diego-suarez-immobilier.com',
				);
				break;
			case 'real':
				$meta = array(
					$this->sites[$name]['meta_keys']['prix']	=> esc_html( $_POST['price'] ),
					$this->sites[$name]['meta_keys']['address'] => esc_html( $_POST['address'] ),
					$this->sites[$name]['meta_keys']['ref']	=> esc_html( $_POST['ref'] ),
					$this->sites[$name]['meta_keys']['province'] => esc_html( $_POST['province'] ),
					$this->sites[$name]['meta_keys']['email'] => esc_html( $_POST['email'] ),
				);
				break;
			case 'property':
				$meta = array(
					$this->sites[$name]['meta_keys']['salerent']	=> esc_html( $_POST['type'] ),
					$this->sites[$name]['meta_keys']['price'] => esc_html( $_POST['price'] ),
					$this->sites[$name]['meta_keys']['address']	=> esc_html( $_POST['address'] ),
					$this->sites[$name]['meta_keys']['reference'] => esc_html( $_POST['ref'] ),
					$this->sites[$name]['meta_keys']['email'] => esc_html( $_POST['email'] ),
					$this->sites[$name]['meta_keys']['province'] => esc_html( $_POST['province'] ),
				);
				$jind_post->image_array = array(
					array(
						'name'			=> 'DSCN1698.jpg',
						'type'			=> 'image/jpeg',
						'tmp_name'		=> '/tmp/php2LDUNQ',
						'postID'		=> 22778,
						'size'			=> '141.49 KiB',
						'src'			=> '"https://property-madagascar.com/wp-content/uploads/2020/10/DSCN1698.jpg"',
						'thumbnail'		=> 'https://property-madagascar.com/wp-content/uploads/2020/10/DSCN1698-300x225.jpg',
						'filepath'		=> '/home/agencediana/property-madagascar.com/wp-content/uploads/2020/10/DSCN1698.jpg',
						'id'			=> '22788',
						'default'		=> false,
						'order'			=> 0,
					)
				);
				break;
			case 'aim':
				$meta = array(
					$this->sites[$name]['meta_keys']['price'] => esc_html( $_POST['price'] ),
					$this->sites[$name]['meta_keys']['type']	=> esc_html( $_POST['type'] ),
					$this->sites[$name]['meta_keys']['address'] => esc_html( $_POST['address'] ),
					$this->sites[$name]['meta_keys']['status'] => 'Libre',
				);
				break;
			default:
				break;
		}

		$jind_post->meta = $meta;

		if( isset($_POST['category']) && is_array($_POST['category']) && !empty($_POST['category']) ){
			$jind_post->category = $_POST['category'];
		}

		$response = Jind_Api::post($url, $login, $jind_post);
		if( isset($response->id) ){
			$post_id = $response->id;
			?>
			<div id='message' class='updated fade'>
					<p>The post #<?php echo $post_id; ?> 
					<?php echo $response->title->rendered; ?> has been created successfully</p>
			</div>
			<?php
			// Processing Image Upload and Attaching to the post
			// Begin Upload
			if( isset($_FILES[$name]['tmp_name'][0]) && !empty($_FILES[$name]['tmp_name'][0]) ){
				$extensions = array('jpg', 'jpeg', 'png');
				$temp_dir = get_temp_dir();
				for($i = 0; $i < sizeof($_FILES[$name]['tmp_name']); $i++){
					$temp = $_FILES[$name]['tmp_name'][$i]; // temp name with full path
					$filename = $_FILES[$name]['name']; // array
					$newname = sanitize_title($this->post_title) . '-' . ($i+1);
					$ext = pathinfo($filename[$i], PATHINFO_EXTENSION);
					$filepath = get_temp_dir() . $newname . '.' . $ext;
					move_uploaded_file($temp, $filepath);

					/*echo 'Site: ' . $name . '<br>';
					echo $filepath.' [Ext: '. $ext .']<br>';
					wp_die();*/

					// Uploading image
					$api_media_response = Jind_Api::upload($url, $login, $filepath);

					if( !is_wp_error( $api_media_response ) ){
						$body_resp = json_decode( $api_media_response['body'], true );
						$media_id = $body_resp['id'];
						
						// Update post parent id
						$host = $url . '/wp-json/wp/v2/media/'. $media_id;
						$arg = array( 'post' => $post_id, 'alt_text' => basename($filepath) );
						$response = wp_remote_post( $host, array(
							'headers' => array(
								'Authorization' => 'Basic ' . base64_encode( $login ),
							),
							'body' => $arg
						) );
						if( is_wp_error( $response ) ){
							$err_msg = $response->get_error_message();
							echo '<p>Error Updating Media Info: '. $err_msg .'</p>';
							//wp_die();
						}
					}
					else{
						$error_message = $api_media_response->get_error_message();
						echo '<p>Error Uploading Media: '. $error_message .'</p>';
						//wp_die();
					}
					unlink( $filepath );
				} // End for
			} // End Upload
		}
		else{
			?>
			<div id='message' style="color: red;">
				Error saving post: <?php print_r($response->message); ?>
			</div>
			<?php
		}
	} // End sites loop
}