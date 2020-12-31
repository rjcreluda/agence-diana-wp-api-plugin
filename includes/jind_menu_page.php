<div class="wrap">
	<h1><?php echo get_admin_page_title(); ?> </h1><br>
	<form action="" method="post" id="ads_form" enctype="multipart/form-data">

		<div id="titlewrap" style="margin-bottom: 30px;">
			<input type="text" name="ads_title" value="<?php echo $this->post_title; ?>" id="title" spellcheck="true" placeholder="Ad title" style="display: inline-block; width: 100%; padding: 5px; font-size: 20px;">
		</div>

		<div class="row">
			<div class="col-md-9">
				<div class="my-3">
					<h4>French Ad</h4>
					<?php
					$content = $this->post_content['french'];
					wp_editor( $content, 'ads_french', array( 'theme_advanced_buttons1' => 'bold, italic, ul, pH, pH_min', "media_buttons" => true, "textarea_rows" => 10, "tabindex" => 4 ) );
					?>
				</div><!-- ./ row -->
				<div class="my-3">
					<h4>English Ad</h4>
					<?php
					$content = $this->post_content['english'];
					wp_editor( $content, 'ads_english', array( 'theme_advanced_buttons1' => 'bold, italic, ul, pH, pH_min', "media_buttons" => true, "textarea_rows" => 10, "tabindex" => 4 ) );
					?>
				</div><!-- ./ row -->
			</div><!-- ./col -->

			<div class="col-md-3">
				<h4 class="my-4">Publish the Ad to</h4>
				<div class="d-flex flex-column">
					<?php foreach($this->sites as $site => $info) :?>
						<div>
							<input type="checkbox" id="<?php echo '_'.$site; ?>" name="sites[]" value="<?php echo $site; ?>">
							<label for="<?php echo '_'.$site; ?>" class="mr-1"><?php echo strtoupper( $site ); ?> </label>
						</div>
					<?php endforeach; ?>
				</div>
				<hr>
				<h4 class="my-4">Categories</h4>
				<div id="category">
					<p>Select a site above to show its category</p>
					<img id="load" src="<?php echo $plugin_url . '/assets/loading.gif';?>" alt="load" class="img-fluid" alt="">
				</div>
			</div><!-- ./col -->

		</div><!-- ./row -->

		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label for="price">Price (€)</label>
					<input type="text" name="price" id="price" class="form-control" value="<?php echo $_POST['price'] ?? '' ?>">
				</div>
				<div class="form-group">
					<label for="type">Type</label>
					<select name="type" id="type" class="form-control">
						<option value="sale">Sale</option>
						<option value="rent">Rent</option>
					</select>
				</div>
				<div class="form-group">
					<label for="address">Address</label>
					<input type="text" name="address" id="address" class="form-control" value="<?php echo $_POST['address'] ?? '' ?>">
				</div>
				<div class="form-group">
					<label for="province">Province</label>
					<input type="text" name="province" id="province" class="form-control" value="<?php echo $_POST['province'] ?? '' ?>">
				</div>
				<div class="form-group">
					<label for="ref">Ad Reference</label>
					<input type="text" name="ref" id="ref" class="form-control" value="<?php echo $_POST['ref'] ?? '' ?>">
				</div>
				<div class="form-group">
					<label for="email">Email</label>
					<input type="text" name="email" id="email" class="form-control" value="<?php echo $_POST['email'] ?? '' ?>">
				</div>
			</div><!-- ./ col -->
			<div class="col-md-8">
				<h5>Ad Media</h5>
				<div class="d-flex flex-column">
					<div class="my-3">
						<h6>Diego</h6>
						<div>
							<input type="file" name="diego[]" id="diego_img" multiple>
							<div class="img_preview d-flex justify-content-start">
							</div>
						</div>
					</div>
					<div class="my-3">
						<h6>Mahajanga</h6>
						<div>
							<input type="file" name="mahajanga[]" id="mahajanga_img" multiple>
							<div class="img_preview d-flex justify-content-start">
							</div>
						</div>
					</div>
					<div class="my-3">
						<h6>Real</h6>
						<div>
							<input type="file" name="real[]" id="real_img" multiple>
							<div class="img_preview d-flex justify-content-start">
							</div>
						</div>
					</div>
					<div class="my-3">
						<h6>Property</h6>
						<div>
							<input type="file" name="property[]" id="property_img" multiple>
							<div class="img_preview d-flex justify-content-start">
							</div>
						</div>
					</div>
					<div class="my-3">
						<h6>AIM</h6>
						<div>
							<input type="file" name="aim[]" id="aim_img" multiple>
							<div class="img_preview d-flex justify-content-start">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		
		<div class="mt-5 d-flex flex-row justify-content-center">
			<div class="p-1">
				<button type="reset" class="btn btn-outline-info"> Reset everything </button>
			</div>
			<div class="p-1"><button type="submit" class="btn btn-info"> Go ahead and save :) </button></div>
		</div>
	</form>
</div><!--end wrap -->