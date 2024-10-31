<?php
/*
Plugin Name: Pure-CSS Emoticons
Plugin URI: http://www.anthonymontalbano.com/software/general/pure-css-emotions-wordpress-plugin/
Description: Replace emoticons with a pure-CSS method.  Instead of using the traditional image embeds, you know have more control over the look and feel of your site's emoticons with this pure CSS method.  Inspired by a good friend and developer, Steve Schwartz of Alpha Jango.  See: <a href='http://www.alfajango.com/blog/jquery-css-emoticons-plugin/'>http://www.alfajango.com/blog/jquery-css-emoticons-plugin/</a>
Version: 0.1.2
Author: Anthony Montalbano
Author URI: http://www.anthonymontalbano.com

Copyright 2010 Anthony Montalbano (me@anthonymontalbano.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

//define plugin urls
if ( !defined('WP_CONTENT_URL') )
    define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if ( !defined('CSS_EMOTICONS_DIR') )
    define( 'CSS_EMOTICONS_DIR',WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__)));
if ( !defined('CSS_EMOTICONS_CSS') )
    define( 'CSS_EMOTICONS_CSS', CSS_EMOTICONS_DIR.'/css/');
if ( !defined('CSS_EMOTICONS_JS') )
    define( 'CSS_EMOTICONS_JS', CSS_EMOTICONS_DIR.'/js/');

//add wp actions
if(function_exists('add_action'))
	add_action('admin_menu', 'add_pure_css_emoticons_menu');

//add jquery js and css
function add_pure_css_emoticons_reqs() {
	wp_enqueue_script('jquery');
    wp_enqueue_script('pce-js', CSS_EMOTICONS_JS.'jquery.cssemoticons.min.js', 'jquery');
	wp_enqueue_script('pce-js-run', CSS_EMOTICONS_JS.'pce-run.js', 'pce-js');
	wp_enqueue_style('pce-css', CSS_EMOTICONS_CSS.'jquery.cssemoticons.css');
}    

//installing pure-CSS emoticons
register_activation_hook(__FILE__, 'install_pure_css_emoticons');
register_deactivation_hook(__FILE__, 'uninstall_pure_css_emoticons');
add_action('init', 'add_pure_css_emoticons_reqs');

//add wp filters
$pce_options = get_option("pce_options");
if($pce_options['postAndPageBody'])
	add_filter('the_content', 'enable_pce');

if($pce_options['postAndPageTitle'])
	add_filter('the_title', 'enable_pce');
	
if($pce_options['comments'])
	add_filter('comment_text', 'enable_pce');

function install_pure_css_emoticons() {
	update_option("use_smilies", "");
}
function uninstall_pure_css_emoticons() {
	update_option("use_smilies", "1");
}

//add the options to wp admin menu
function add_pure_css_emoticons_menu() {
	add_options_page('Pure-CSS Emoticons', 'Pure-CSS Emoticons', 8, 'cssemoticons', 'pure_css_options');
}
  
//show admin menu
function pure_css_options() {
	global $wpdb;
	
	if(isset($_REQUEST['savePCESettings']) && $_REQUEST['savePCESettings']) {
		$newSettings = array("postAndPageBody" => $_POST['postAndPageBody'], 
							 "comments" => $_POST['comments']
							 );
		update_option("pce_options", $newSettings);
		pce_message('Pure-CSS Emoticon Settings Saved!');
	}
	$pce_options = get_option("pce_options");
	if(get_option("use_smilies")==1)
			  	_e('<div id="message" class="error fade"><p>YIKES! WordPress emoticons are enabled!  You must disable them for Pure-CSS Emoticons to work properly. Disable them here: <a href="options-writing.php">Settings &rarr; General</a></p></div>');
?>
<div class="wrap">
  <h2>Pure-CSS Emoticon Settings</h2>
  <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
    <div id="poststuff" style="width:650px;">
    <div class="postbox">
      <h3>Options</h3>
      <div class="inside">
        <table class="form-table" style="width:600px;">
          <tr>
            <td colspan="2"><strong>Where do you want to apply the pure-CSS emoticons?</strong><br />
              <?php _e('Please note: This plugin automatically disables WordPress emoticons. If WordPress emoticons are turned on Pure-CSS Emoticons will not render.'); ?>
              </td>
          </tr>
          <tr>
            <td valign="top"><strong>Posts &amp; Pages:</strong></td>
            <td><input type="checkbox" value="true" name="postAndPageBody" <?php if($pce_options['postAndPageBody']) { print "checked='checked'"; } ?> /></td>
          </tr>
          <tr>
            <td valign="top"><strong>Comments:</strong></td>
            <td><input type="checkbox" value="true" name="comments" <?php if($pce_options['comments']) { print "checked='checked'"; } ?> /></td>
          </tr>
        </table>
      </div>
    </div> 
    <div class="postbox">
      <h3>Preview your emoticons</h3>
      <div class="inside">
        <table class="form-table" style="width:600px;">
          <tr>
            <td colspan="2">
             <?php _e('Hover your mouse over each emoticon to see how to create them in your posts.<br><br>'); ?>
            <div class='pce-enabled-section'>:-) :) :o) :c) :^) :-D :-( :-9 ;-) :-P :-p :-b :-O :-/ :-X :-# :'( B-) 8-) :-\ ;*( :-*  :] :> =] =) 8) :} :D 8D XD xD =D :( :< :[ :{ =( ;) ;] ;D :P :p =P =p :b :O 8O :/ =/ :S :# :X B) O:) :{) <3 ;( >:) >;) >:( O_o O_O o_o 0_o T_T ^_^ ?-)</div></td>
          </tr>
        </table>
      </div>
    </div> 
    <input type="submit" name="savePCESettings" id="submitter" value="<?php _e("Save Settings"); ?>" class="button-primary"/>
  </form>
</div>
<?php
	print "<div align=right><br><br><small>Pure-CSS Emoticons is created by <a href=http://www.anthonymontalbano.com target=_blank>Anthony Montalbano</a> &copy; 2010</small></div></div>";

}

function enable_pce($content) {
	if(get_option("use_smilies")!=1)
		return "<div class='pce-enabled-section'>".$content."</div>";
	else
		return $content;
}

//displays necessary CSS for message
function pce_message($message) {
	echo "<div id=\"message\" class=\"updated fade\"><p>$message</p></div>\n";
}
?>