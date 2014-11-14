<?php
	function getData($field){
		global $post;
		$post_meta = @get_post_meta($post->ID, 'wp_simple_association_manager', true);
		return !empty($post_meta) && isset($post_meta[$field]) ? $post_meta[$field] : '';
	}
?>
<input type="hidden" name="post_title" id="title" value="<?php echo getData('wp_simple_association_manager_surname')." ".getData('wp_simple_association_manager_name'); ?>" />
<div class="field">
	<label for="wp_simple_association_manager_name"><?php echo __( 'Name', 'wp-simpl-adm' )?></label>
	<input type="text" id="wp_simple_association_manager_name" name="wp_simple_association_manager_name" value="<?php echo getData('wp_simple_association_manager_name'); ?>" />
</div>
<div class="field">
	<label for="wp_simple_association_manager_surname"><?php echo __( 'Surname', 'wp-simpl-adm' )?></label>
	<input type="text" id="wp_simple_association_manager_surname" name="wp_simple_association_manager_surname" value="<?php echo getData('wp_simple_association_manager_surname');  ?>" />
</div>
<div class="field">
	<label for="wp_simple_association_manager_email"><?php echo __( 'Email', 'wp-simpl-adm' )?></label>
	<input type="text" id="wp_simple_association_manager_email" name="wp_simple_association_manager_email" value="<?php echo getData('wp_simple_association_manager_email'); ?>" />
</div>
<div class="field">
	<label for="wp_simple_association_manager_phone"><?php echo __( 'Phone', 'wp-simpl-adm' )?></label>
	<input type="text" id="wp_simple_association_manager_phone" name="wp_simple_association_manager_phone" value="<?php echo getData('wp_simple_association_manager_phone'); ?>" />
</div>
<div class="field">
	<label for="wp_simple_association_manager_mobile"><?php echo __( 'Mobile', 'wp-simpl-adm' )?></label>
	<input type="text" id="wp_simple_association_manager_mobile" name="wp_simple_association_manager_mobile" value="<?php echo getData('wp_simple_association_manager_mobile'); ?>" />
</div>
<div class="field">
	<label for="wp_simple_association_manager_fax"><?php echo __( 'Fax', 'wp-simpl-adm' )?></label>
	<input type="text" id="wp_simple_association_manager_fax" name="wp_simple_association_manager_fax" value="<?php echo getData('wp_simple_association_manager_fax'); ?>" />
</div>
<div class="field">
	<label for="wp_simple_association_manager_in_activity"><?php echo __( 'In activity', 'wp-simpl-adm' )?></label>
	<input type="checkbox" id="wp_simple_association_manager_in_activity" name="wp_simple_association_manager_in_activity" value="1" <?php echo getData('wp_simple_association_manager_in_activity') == 1 ? 'checked="checked"' : ''  ?> />
</div>
<div class="field">
	<label for="wp_simple_association_manager_in_study"><?php echo __( 'In preparation', 'wp-simpl-adm' )?></label>
	<input type="checkbox" id="wp_simple_association_manager_in_study" name="wp_simple_association_manager_in_study" value="1" <?php echo getData('wp_simple_association_manager_in_study') == 1 ? 'checked="checked"' : ''  ?> />
</div>