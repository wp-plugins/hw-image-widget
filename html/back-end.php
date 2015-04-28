<style type="text/css">
	.customize-control .hwim input[type="text"].small-text {
		width: 50px;
	}
	div.hwim .text-preview {
		height: 100px;
		overflow: scroll;
		resize: vertical;
		width: 100%;
		display: inline-block;
		cursor: text;
		border-color: #dfdfdf;
	}
	#customize-controls div.hwim .text-preview {
		height: 200px;
	}
	.hwim .text-preview {
		border-color: #ddd;
		box-shadow: inset 0 1px 2px rgba(0,0,0,.07);
		box-sizing: border-box;
		border-width: 1px;
		border-style: solid;
		padding: 5px;
	}
</style>
<div id="<?php echo $div_id; ?>" class="<?php esc_attr_e( $this->widget_id ); ?>">
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'hwim' ); ?>
			<input class="widefat title"
				id="<?php echo $this->get_field_id( 'title' ); ?>"
				name="<?php echo $this->get_field_name( 'title' ); ?>"
				type="text"
				value="<?php esc_attr_e( $instance['title'] ); ?>" />
		</label>
	</p>
	<div class="form-padding">
		<label><?php _e( 'Text:', 'hwim' ); ?></label>
		<div class="text-preview widefat edit-text"
			 onClick="hwim.openTextEditor('#<?php echo $div_id; ?>');"><?php echo $content_cleaned; ?></div>
		<input type="hidden" id="<?php echo $this->get_field_id( 'text' ); ?>"
			   class="text"
			   name="<?php echo $this->get_field_name( 'text' ); ?>"
			   value="<?php esc_attr_e( $instance['text'] ); ?>" />
	</div>
	<p>
		<label for="<?php echo $this->get_field_id( 'src' ); ?>"><?php _e( 'Image:', 'hwim' ); ?><br />
			<a id="<?php echo $this->get_field_id( 'img-button' ); ?>" class="select-image" onClick="hwim.selectImage('#<?php echo $div_id; ?>');"><?php esc_html_e( 'Select image', 'hwim' ); ?></a><span class="remove-image-link"<?php if ( $instance['src'] == '') { echo ' style="display: none;"'; } ?>>, <a class="remove-image" onClick="hwim.removeImage('#<?php echo $div_id; ?>');"><?php esc_html_e( 'Remove image', 'hwim' ); ?></a></span>
			<div id="<?php echo $this->get_field_id( 'img-thumb' ); ?>" class="img-thumb">
			<?php
				if ( $instance['src'] != '') {
					echo '<img src="' . $instance['src'] . '" style="max-width: 100%;">';
				}
			?>
			</div>
			<input class="src"
				id="<?php echo $this->get_field_id( 'src' ); ?>"
				name="<?php echo $this->get_field_name( 'src' ); ?>"
				type="hidden"
				value="<?php esc_attr_e( $instance['src'] ); ?>">
		</label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'display_size' ); ?>"><?php _e( 'Display size:', 'hwim' ); ?>
			<select class="widefat display-size"
				id="<?php echo $this->get_field_id( 'display_size' ); ?>"
				name="<?php echo $this->get_field_name( 'display_size' ); ?>"
				type="text"
				value="<?php esc_attr_e( $instance['display_size'] ); ?>"
				onChange="hwim.displaySize('#<?php echo $div_id; ?>', this.value);">
				<?php
				$targets = $this->get_display_sizes();
				foreach ( $targets as $value => $display ) {
					if ( $instance['display_size'] == $value ) {
						echo '<option value="' . esc_attr( $value ) . '" selected="selected">' . esc_html( $display ) . '</option>';
					} else {
						echo '<option value="' . esc_attr( $value ) . '">' . esc_html( $display ) . '</option>';
					}
				}
				?>
			</select>
		</label>
		<div class="relative-size" style="margin-left: 20px;<?php if ( $instance['display_size'] == 'fixed' ) { echo ' display: none;'; } ?>">
			<input class="checkbox fill_width"
				   type="checkbox"
				   id="<?php echo $this->get_field_id( 'fill_width' ); ?>"
				   name="<?php echo $this->get_field_name( 'fill_width' ); ?>"
				   value="1"
					<?php checked( $instance['fill_width'], true ); ?>
				   onChange="hwim.keepAspectRatio('#<?php echo $div_id; ?>');" />
			<label for="<?php echo $this->get_field_id( 'fill_width' ); ?>"><?php _e( 'Fill width.', 'hwim' ); ?></label>
		</div>
		<div class="fixed-size" style="margin-left: 20px;<?php if ( $instance['display_size'] == 'responsive' ) { echo ' display: none;'; } ?>">
			<p style="margin-top: 0;">
				<label for="<?php echo $this->get_field_id( 'display_height' ); ?>"><?php _e( 'Size:', 'hwim' ); ?></label>
				<input class="small-text display-width"
					id="<?php echo $this->get_field_id( 'display_width' ); ?>"
					name="<?php echo $this->get_field_name( 'display_width' ); ?>"
					type="text"
					value="<?php esc_attr_e( $instance['display_width'] ); ?>"
					onChange="hwim.calcAspectRatio('#<?php echo $div_id; ?>', 'x');" />
				x
				<input class="small-text display-height"
					id="<?php echo $this->get_field_id( 'display_height' ); ?>"
					name="<?php echo $this->get_field_name( 'display_height' ); ?>"
					type="text"
					value="<?php esc_attr_e( $instance['display_height'] ); ?>"
					onChange="hwim.calcAspectRatio('#<?php echo $div_id; ?>', 'y');" />
				px
				<input class="original-width"
					id="<?php echo $this->get_field_id( 'original_width' ); ?>"
					name="<?php echo $this->get_field_name( 'original_width' ); ?>"
					type="hidden"
					value="<?php esc_attr_e( $instance['original_width'] ); ?>">
				<input class="original-height"
					id="<?php echo $this->get_field_id( 'original_height' ); ?>"
					name="<?php echo $this->get_field_name( 'original_height' ); ?>"
					type="hidden"
					value="<?php esc_attr_e( $instance['original_height'] ); ?>">
			</p>
			<p style="margin-bottom: 0;">
				<input class="checkbox keep-aspect-ratio"
					   type="checkbox"
					   id="<?php echo $this->get_field_id( 'keep_aspect_ratio' ); ?>"
					   name="<?php echo $this->get_field_name( 'keep_aspect_ratio' ); ?>"
					   value="1"
						<?php checked( $instance['keep_aspect_ratio'], true ); ?>
					   onChange="hwim.keepAspectRatio('#<?php echo $div_id; ?>');" />
				<label for="<?php echo $this->get_field_id( 'keep_aspect_ratio' ); ?>"><?php _e( 'Keep aspect ratio.', 'hwim' ); ?></label>
			</p>
		</div>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'alt' ); ?>"><?php _e( 'Alt:', 'hwim' ); ?>
			<input class="widefat alt"
				id="<?php echo $this->get_field_id( 'alt' ); ?>"
				name="<?php echo $this->get_field_name( 'alt' ); ?>"
				type="text"
				value="<?php esc_attr_e( $instance['alt'] ); ?>" />
		</label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'url' ); ?>"><?php _e( 'URL:', 'hwim' ); ?>
			<input class="widefat"
				id="<?php echo $this->get_field_id( 'url' ); ?>"
				name="<?php echo $this->get_field_name( 'url' ); ?>"
				type="text"
				value="<?php esc_attr_e( $instance['url'] ); ?>" />
		</label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'target_option' ); ?>"><?php _e( 'Target:', 'hwim' ); ?>
			<select class="widefat target-option"
				id="<?php echo $this->get_field_id( 'target_option' ); ?>"
				name="<?php echo $this->get_field_name( 'target_option' ); ?>"
				type="text"
				value="<?php esc_attr_e( $instance['target_option'] ); ?>"
				onChange="hwim.target('#<?php echo $div_id; ?>');" >
				<?php
				$targets = $this->get_targets();
				foreach ( $targets as $value => $display ) {
					if ( $instance['target_option'] == $value ) {
						echo '<option value="' . esc_attr( $value ) . '" selected="selected">' . esc_html( $display ) . '</option>';
					} else {
						echo '<option value="' . esc_attr( $value ) . '">' . esc_html( $display ) . '</option>';
					}
				}
				?>
			</select>
		</label>
	</p>
	<p>
		<input class="widefat target-name"
			id="<?php echo $this->get_field_id( 'target_name' ); ?>"
			name="<?php echo $this->get_field_name( 'target_name' ); ?>"
			type="text"
			value="<?php esc_attr_e( $instance['target_name'] ); ?>"
			<?php if ( $instance['target_option'] != 'other' ) echo 'style="display: none;"'; ?> />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'rel_option' ); ?>"><?php _e( 'Rel:', 'hwim' ); ?>
			<select class="widefat rel-option"
				id="<?php echo $this->get_field_id( 'rel_option' ); ?>"
				name="<?php echo $this->get_field_name( 'rel_option' ); ?>"
				type="text"
				value="<?php esc_attr_e( $instance['rel_option'] ); ?>"
				onChange="hwim.rel('#<?php echo $div_id; ?>');" >
				<?php
				$rels = $this->get_rels();
				foreach ( $rels as $value => $display ) {
					if ( $instance['rel_option'] == $value ) {
						echo '<option value="' . esc_attr( $value ) . '" selected="selected">' . esc_html( $display ) . '</option>';
					} else {
						echo '<option value="' . esc_attr( $value ) . '">' . esc_html( $display ) . '</option>';
					}
				}
				?>
			</select>
		</label>
	</p>
	<p>
		<input class="widefat rel-name"
			id="<?php echo $this->get_field_id( 'rel_name' ); ?>"
			name="<?php echo $this->get_field_name( 'rel_name' ); ?>"
			type="text"
			value="<?php esc_attr_e( $instance['rel_name'] ); ?>"
			<?php if ( $instance['rel_option'] != 'other' ) echo 'style="display: none;"'; ?> />
	</p>
	<p class="help-text"><a href="https://wordpress.org/support/view/plugin-reviews/hw-image-widget" target="_blank">Please support by rating this open source plugin.</a></p>
</div>