<?php
$str = "<div class='categorydiv' id='taxonomy-".Association_Activity::POST_TYPE."'>\n";
$str .= '<input type="hidden" name="wp_simple_association_manager_groups_box_noncename" id="wp_simple_association_manager_groups_box_noncename" value="' .
wp_create_nonce( 'wp_simple_association_manager_groups_box_'.$post->ID ) . '" />';
$str .= '<input type="hidden" name="tax_input['.Association_Activity::POST_TYPE.'][]" value="0">';
// Get all theme taxonomy terms
$activities = get_terms(Association_Activity::POST_TYPE,  array("parent" => 0, 'hide_empty' => 0));
$str .= "<ul class='categorychecklist'>";
$names = wp_get_object_terms($post->ID, Association_Activity::POST_TYPE);

foreach ($activities as $activity) {
	$checked = "";$checkedClass = " class='dashicons-before dashicons-minus'";
	foreach ($names as $name) {
		if (!is_wp_error($names) && !empty($names) && !strcmp($activity->slug, $name->slug)){
			$checked = " checked='checked'";
			$checkedClass = " class='dashicons-before dashicons-yes'";
			break;
		}
	}

	$str .= "<li id='".Association_Activity::POST_TYPE."-".$activity->term_id."'>\n";

	$subactivities = get_categories(array(
		'taxonomy'		=> ($activity -> taxonomy),
		'parent'		=> ($activity -> term_id),
		'hide_empty'	=> 0
	));
	if (count($subactivities) == 0){
		$str .= "<label>";
		$str .= "<input type='checkbox' name='tax_input[".Association_Activity::POST_TYPE."][]' id='in-".Association_Activity::POST_TYPE."-" . $activity->term_id ."' value='" . $activity->term_id ."'". $checked." />";
	}else{
		$str .= "<span".$checkedClass.">";
		$str .= "<input type='checkbox' name='tax_input[".Association_Activity::POST_TYPE."][]' id='in-".Association_Activity::POST_TYPE."-" . $activity->term_id ."' value='" . $activity->term_id ."'". $checked." class='hidden' />";
	}
	$str .= $activity->name;
	if (count($subactivities) == 0) $str .= "</label>\n";
	else  $str .= "</span>\n";
	if (count($subactivities) >= 0){
		$str .= "<ul class='children'>";
		foreach ($subactivities as $subactivity) {
			$checked = "";
			foreach ($names as $name) {
				if (!is_wp_error($names) && !empty($names) && !strcmp($subactivity->slug, $name->slug)){
					$checked = " checked='checked'";
					break;
				}
			}
			$str .= "<li id='".Association_Activity::POST_TYPE."-".$subactivity->term_id."'>\n<label>";
			$str .= "<input type='checkbox' name='tax_input[".Association_Activity::POST_TYPE."][]' id='in-".Association_Activity::POST_TYPE."-" . $subactivity->term_id ."' value='" . $subactivity->term_id ."'". $checked." />";
			$str .= $subactivity->name;
			$str .= "</label>\n</li>\n";
		}
		$str .= "</ul>";
	}
	$str .= "</li>\n";
}
$str .= "</ul></div>";
echo $str;
?>