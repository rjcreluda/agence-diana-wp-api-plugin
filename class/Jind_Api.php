<?php

class Jind_Api
{
	
	/*
	 * Create Remote Post using API
	 * Return array of a post if succeed else return false
	 */
	public static function post($url, $login, $post){
		if( !is_object( $post ) )
			return;
		$headers = array(
				'Authorization' => 'Basic ' . base64_encode( $login )
			);
		$body = array(
			    	'title'   	=> $post->title,
					'status'  	=> $post->status,
					'content' 	=> $post->content,
					'excerpt' 	=> $post->excerpt,
					'slug' 		=> $post->slug,
					'meta'		=> $post->meta,
					$post->taxonomy => $post->category,
				);
		if( is_array($post->image_array) ){
			$body['image_array'] = $post->image_array;
		}
		$api_response = wp_remote_post( $url . '/wp-json/wp/v2/' . $post->type, 
			array(
					'headers' => $headers,
					'body' => $body )						
		);
	 
		/*if( wp_remote_retrieve_response_message( $api_response ) === 'Created' )
			return $body;
		else
			return false;*/
		if( is_wp_error( $api_response )){
			return $api_response->get_error_message();
		}
		else{
			$body = json_decode( $api_response['body'] );
			return $body;
		}
	}

	public static function upload($url, $login, $file){

		$image_file_path = $file;
		$image = file_get_contents( $image_file_path );
		$mime = mime_content_type( $image_file_path );

		$response = wp_remote_post( $url . '/wp-json/wp/v2/media', array(
			'headers' => array(
				'Authorization' => 'Basic ' . base64_encode( $login ),
				'Content-Disposition' => 'attachment; filename='.basename($image_file_path).'',
				'Content-Type' => $mime
			),
			'body' => $image
		) );

		return $response;
	}

	public static function update($login, $url, $type, $id, $data){
		$host = $url . '/wp-json/wp/v2/' . $type . '/' . $id;
		$response = wp_remote_post( $host, array(
			'headers' => array(
				'Authorization' => 'Basic ' . base64_encode( $login ),
			),
			'body' => $data
		) );
		return $response;
	}


	/*
	 * Get Category List of a custom post
	 * Return an array of terms object
	 */
	public static function get_terms( $url, $taxonomy ){
		$url = $url . '/wp-json/wp/v2/' . $taxonomy . '?per_page=35';
		$response = wp_remote_get( $url );
		if( is_array( $response ) && !is_wp_error( $response ) ){
			$headers = $response['headers'];
			return json_decode( $response['body'] );
		}
		else{
			return false;
		}
	}

	/*
	 * Get a remote post
	 * Return an array of a post
	 */
	public static function get_json($url){
		$response = wp_remote_get( $url );
	    if( is_wp_error( $response ) ){
	        return sprintf('Could not retrieve the URL %1s', $url);
	    }
	    $data = wp_remote_retrieve_body( $response );
	    return json_decode( $data );
	}

	/*
	 * Get a remote taxonomy on a term
	 * Return html form
	 */
	public static function term_form($url, $taxonomy){
		$terms = Jind_Api::get_terms($url, $taxonomy);
		if( $terms ):
			$parents_cat = []; // 1 2 3 4
			$childs_cat = []; //
			foreach($terms as $term){
				if( $term->parent == '0' )
					$parents_cat[$term->id] = $term;
				else
					$childs_cat[] = $term;
			}
			foreach($parents_cat as $parent): ?>
			<div class="row mb-1 no-gutters">
				<div class="col-md-12">
					<h6>
						<input type="checkbox" name="category[]" id="<?php echo $parent->slug; ?>" value="<?php echo $parent->id; ?>" class="form-control">
						<label for="<?php echo $parent->slug; ?>" class="mr-1">
							<?php echo $parent->name; ?> 
						</label>
					</h6>
					
				</div>
				<?php if( strpos($parent->slug, 'special') === false && strpos($parent->slug, 'immobilier') === false ): ?>
					<div class="col-md-10">
						<div class="row no-gutters" style="margin-left: 30px; max-height: 220px; overflow-y: auto;">
							<?php
							foreach($childs_cat as $category){
								$parent_id = $parents_cat[$category->parent]->id;
								if( $parent_id ==  $parent->id ): ?>
									<div class="col-md-12">
										<input type="checkbox" name="category[]" id="<?php echo $category->slug; ?>" value="<?php echo $category->id; ?>" class="form-control">
										<label for="<?php echo $category->slug; ?>"> 
											<span class="small"><?php echo $category->name; ?></span>
										</label>
									</div>
								<?php endif;
							} ?>
						</div>
					</div>
				<?php endif; ?>
			</div><!-- end row -->
			<?php endforeach;
		else:
			echo '<span class="text-danger">Could not retrieve categories</span>';
		endif;
	}
}