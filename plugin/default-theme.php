<?php
/*
Plugin Name: Default Theme
Plugin URI: http://premium.wpmudev.org/project/default-theme
Description: Allows you to easily select a new default theme for new blogs
Author: Andrew Billits and Aaron Edwards
Version: 1.0.1
WDP ID: 48
*/

/* 
Copyright 2007-2010 Incsub (http://incsub.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License (Version 2 - GPLv2) as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

//------------------------------------------------------------------------//
//---Hook-----------------------------------------------------------------//
//------------------------------------------------------------------------//

add_action('wpmu_options', 'default_theme_site_admin_options');
add_action('update_wpmu_options', 'default_theme_site_admin_options_process', 1);
add_action('wpmu_new_blog', 'default_theme_switch_theme', 1, 1);

//------------------------------------------------------------------------//
//---Functions------------------------------------------------------------//
//------------------------------------------------------------------------//

function default_theme_switch_theme($blog_ID) {

	$default_theme = get_site_option('default_theme');
  $themes = get_themes();

  //we have to go through all this to handle child themes, otherwise it will throw errors
  foreach( (array) $themes as $key => $theme ) {
		$stylesheet = wp_specialchars($theme['Stylesheet']);
		$template = wp_specialchars($theme['Template']);
		if ($default_theme == $stylesheet || $default_theme == $template) {
      $new_stylesheet = $stylesheet;
      $new_template = $template;
		}
	}

  //activate it
  switch_to_blog( $blog_ID );
	switch_theme( $new_template, $new_stylesheet );
	restore_current_blog();
}

function default_theme_site_admin_options_process() {
	update_site_option( 'default_theme' , $_POST['default_theme']);
}

//------------------------------------------------------------------------//
//---Output Functions-----------------------------------------------------//
//------------------------------------------------------------------------//

function default_theme_site_admin_options() {

	$themes = get_themes();

	$default_theme = get_site_option('default_theme');
	if ( empty( $default_theme ) ) {
		$default_theme = 'default';
	}
?>
		<h3><?php _e('Theme Settings') ?></h3>
		<table class="form-table">
            <tr valign="top"> 
            <th scope="row"><?php _e('Default Theme') ?></th> 
            <td><select name="default_theme">
            <?php
				foreach( $themes as $key => $theme ) {
					$theme_key = wp_specialchars( $theme['Stylesheet'] );
                    echo '<option value="' . $theme_key . '"' . ($theme_key == $default_theme ? ' selected' : '') . '>' . $key . '</option>' . "\n";
				}
            ?>
            </select>
            <br /><?php _e('Default theme applied to new blogs.'); ?></td> 
            </tr>
		</table>
<?php
}

//------------------------------------------------------------------------//
//---Page Output Functions------------------------------------------------//
//------------------------------------------------------------------------//

?>
