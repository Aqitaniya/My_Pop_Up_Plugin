<?php
/*
Plugin Name: My Pop Up Plugin
Description: Создание Pop Up
Version:  1.0
Author: Stacey
*/


// ------------------------------------------------
// Enqueue styles and scripts
// ------------------------------------------------
//
function mpup_add_wp_enqueue_styles_scripts()
{
    wp_enqueue_style('plugin_style', plugin_dir_url('') . 'my_pop_up_plugin/css/style.css');

    wp_enqueue_script('mpup_js_script', plugin_dir_url('') . '/my_pop_up_plugin/js/mpup_form.js', array('jquery', 'jquery-ui-draggable', 'backbone','underscore'));
    mpup_send_settins_to_js();
}

add_action('wp_enqueue_scripts', 'mpup_add_wp_enqueue_styles_scripts');


function mpup_add_admin_enqueue_styles_scripts()
{

    wp_enqueue_style('admin_style', plugin_dir_url('') . '/my_pop_up_plugin/css/admin.css');

    wp_enqueue_script('admin-functions', plugin_dir_url('') . '/my_pop_up_plugin/js/admin-functions.js');
}

add_action('admin_enqueue_scripts', 'mpup_add_admin_enqueue_styles_scripts');

// ------------------------------------------------
//  PopUp structure
// ------------------------------------------------
//
function mpup_popup_stucture(){?>
    <script id="popup-template" type="text/template">
        <div class="overlay">
            <div class="popup">
                <div class="popup-close">
                    <span class="dashicons dashicons-dismiss"></span>
                </div>
                <div class="title">
                    <h1><%= title %></h1>
                </div>
                <div class="content">
                    <h3><%= content %></h3>
                </div>
            </div>
        </div>
</script><?php
}
add_action( 'wp_head', 'mpup_popup_stucture');

// ------------------------------------------------
// Set default Pop Up settings
// ------------------------------------------------
//

function default_popup_settings(){
    $default=array('header_text' => 'Default header text',
                    'main_text' => 'Default content',
                    'delay_before_popup' => '10',
                    'time_display_popup' => '10',
                    'existence_close' => '1',
                    'close_clicking_esc' => '1',
                    'close_clicking_overlay' => '1',
    );
    add_option( 'popup_settings',$default);
}
register_activation_hook(__FILE__, 'default_popup_settings');

// ------------------------------------------------
// Delete Pop Up settings
// ------------------------------------------------
//

function delete_popup_settings()
{
    delete_option('popup_settings');
}
register_deactivation_hook(__FILE__, 'delete_popup_settings');

// ------------------------------------------------
// Create custom plugin settings menu
// ------------------------------------------------
//

function mpup_add_plugin_page() {

    add_menu_page('Pop UP configuration', 'Pop UP', 'administrator', __FILE__, 'popup_settings_page', 'dashicons-welcome-view-site', 81);

}
add_action('admin_menu', 'mpup_add_plugin_page');

function popup_settings_page(){
    ?>
    <div class="wrap">
        <h2><?php echo get_admin_page_title() ?></h2>

        <form action="options.php" method="POST">
            <?php settings_fields( 'popup_group' ); ?>
            <?php do_settings_sections(__FILE__); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// ------------------------------------------------
// Register settings
// ------------------------------------------------
//

function mpup_plugin_settings(){
    // параметры: $option_group, $option_name, $sanitize_callback
    register_setting( 'popup_group', 'popup_settings', 'mpup_validation_settings' );

    // параметры: $id, $title, $callback, $page
    add_settings_section( 'popup_main', 'Main settings', 'eg_setting_section_callback_function', __FILE__ );

    // параметры: $id, $title, $callback, $page, $section, $args
    add_settings_field('header_text', 'Header text', 'mpup_header_text', __FILE__, 'popup_main' );
    add_settings_field('main_text', 'Main text', 'mpup_main_text', __FILE__, 'popup_main' );
    add_settings_field('delay_before_popup', 'Delay before displaying the popup', 'mpup_delay_before_popup', __FILE__, 'popup_main' );
    add_settings_field('time_display_popup', 'Popup display time', 'mpup_time_display_popup', __FILE__, 'popup_main' );
    add_settings_field('existence_close', 'The existence of the "close"', 'mpup_existence_close', __FILE__, 'popup_main' );
    add_settings_field('close_clicking_esc', 'Close the popup by clicking on the button `Esc`', 'mpup_close_clicking_esc', __FILE__, 'popup_main' );
    add_settings_field('close_clicking_overlay', 'Close the popup by clicking on the overlay', 'mpup_close_clicking_overlay', __FILE__, 'popup_main' );
}
add_action('admin_init', 'mpup_plugin_settings');

// ------------------------------------------------
// Fill settings
// ------------------------------------------------
//

function eg_setting_section_callback_function() {
    echo '<p>List of settings popup menu</p>';
}

function mpup_header_text(){

    $val = get_option('popup_settings');

    ?>
    <input type="text" name="popup_settings[header_text]" value="<?php echo esc_attr( $val['header_text'] ) ?>" />
    <?php
}

function mpup_main_text(){

    $val = get_option('popup_settings');

    ?>
    <textarea type="text" name="popup_settings[main_text]" placeholder = "" rows="4" cols="50" ><?php echo esc_attr( $val['main_text'] ) ?></textarea>
    <?php

}

function mpup_delay_before_popup(){

    $val = get_option('popup_settings');

    ?>
    <input type="text" name="popup_settings[delay_before_popup]" value="<?php echo esc_attr( $val['delay_before_popup'] ) ?>" />
    <?php
}

function mpup_time_display_popup(){

    $val = get_option('popup_settings');

    ?>
    <input type="text" name="popup_settings[time_display_popup]" value="<?php echo esc_attr(  $val['time_display_popup']) ?>" />
    <?php
}

function mpup_existence_close(){

    $val = get_option('popup_settings');

    if(!isset( $val['existence_close']))
        $val['existence_close']='';

    ?>
    <input type="checkbox" name="popup_settings[existence_close]" value="1" <?php checked( 1, $val['existence_close'] ) ?> />
    <?php
}

function mpup_close_clicking_esc(){

    $val = get_option('popup_settings');

    if(!isset( $val['close_clicking_esc']))
        $val['close_clicking_esc']='';

    ?>
   <input type="checkbox" name="popup_settings[close_clicking_esc]" value="1" <?php checked( 1, $val['close_clicking_esc'] ) ?> />
    <?php
}

function mpup_close_clicking_overlay(){

    $val = get_option('popup_settings');

    if(!isset( $val['close_clicking_overlay']))
        $val['close_clicking_overlay']='';

    ?>
    <input type="checkbox" name="popup_settings[close_clicking_overlay]" value="1" <?php checked( 1, $val['close_clicking_overlay'] ) ?> />
    <?php
}

// ------------------------------------------------
// Validation settings
// ------------------------------------------------
//

function mpup_validation_settings($popup_settings){

    $val = get_option('popup_settings');
    $message = $type = null;

    if(empty($popup_settings['header_text'])){

        $type = 'error';
        $message = 'Field "Header text" can not be empty';

        add_settings_error( 'popup_setting_error', 'popup_header_text', $message, $type );
        $popup_settings['header_text'] = $val['header_text'];

    }else{
        $popup_settings['header_text']= sanitize_text_field($popup_settings['header_text']);
    }

    if(empty($popup_settings['main_text'])){

        $type = 'error';
        $message = 'Field "Main text" can not be empty';

        add_settings_error( 'popup_setting_error', 'popup_main_text', $message, $type );
        $popup_settings['main_text'] = $val['main_text'];

    }else{
        $popup_settings['main_text']= sanitize_text_field($popup_settings['main_text']);
    }

    if(empty($popup_settings['delay_before_popup'])){

        $type = 'error';
        $message = 'Field "Delay before displaying the popup" can not be empty';

        add_settings_error( 'popup_setting_error', 'popup_delay_before_popup', $message, $type );
        $popup_settings['delay_before_popup'] = $val['delay_before_popup'];

    } elseif( !is_numeric($popup_settings['delay_before_popup']) ){

        $type = 'error';
        $message = 'field "Delay before displaying the popup" must contain numbers';

        add_settings_error( 'popup_setting_error', 'popup_delay_before_popup', $message, $type );
        $popup_settings['delay_before_popup'] = $val['delay_before_popup'];
    }
    elseif($popup_settings['delay_before_popup']<0 || $popup_settings['delay_before_popup']>100){

        $type = 'error';
        $message = 'field "Delay before displaying the popup" should be in the range from 0 to 100 seconds';

        add_settings_error( 'popup_setting_error', 'popup_delay_before_popup', $message, $type );
        $popup_settings['delay_before_popup'] = $val['delay_before_popup'];
    }

    if(empty($popup_settings['time_display_popup'])){

        $type = 'error';
        $message = 'Field "Delay before displaying the popup" can not be empty';

        add_settings_error( 'popup_setting_error', 'popup_time_display_popup', $message, $type );
        $popup_settings['time_display_popup'] = $val['time_display_popup'];

    } elseif(! is_numeric( $popup_settings['time_display_popup']) ){

        $type = 'error';
        $message = 'Field "Popup display time" can not contain digits';

        add_settings_error( 'popup_setting_error', 'popup_time_display_popup', $message, $type );
        $popup_settings['time_display_popup'] = $val['time_display_popup'];
    }
    elseif($popup_settings['time_display_popup'] < 0 || $popup_settings['time_display_popup'] > 50){

        $type = 'error';
        $message = 'Field "Popup display time" should be in the range from 0 to 50 seconds';

        add_settings_error( 'popup_setting_error', 'popup_time_display_popup', $message, $type );
        $popup_settings['time_display_popup'] = $val['time_display_popup'];
    }

    return $popup_settings;
}

function mpup_admin_notices_action() {
    settings_errors( 'popup_setting_error');
}
add_action( 'admin_notices', 'mpup_admin_notices_action' );

// ------------------------------------------------
// Send settings to js
// ------------------------------------------------
//

function mpup_send_settins_to_js()
{
    $val = get_option('popup_settings');
    wp_localize_script('mpup_js_script', 'popup_settings', $val);

}