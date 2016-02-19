<?php

class mpup_Settings_bd{
    static function mpup_install() {
        global $wpdb;
        $wpdb->query("CREATE TABLE `".$wpdb->prefix."popup_settings` (
                                                                        `ID` INT(10) UNSIGNED NULL AUTO_INCREMENT,
                                                                        `header_text` VARCHAR(50) DEFAULT 'title Pop Up',
                                                                        `main_text` VARCHAR (200) DEFAULT 'content Pop Up',
                                                                        `delay_before_popup` INT DEFAULT '10',
                                                                        `time_display_popup` INT DEFAULT '10',
                                                                        `existence_close` VARCHAR(10) DEFAULT 'checked',
                                                                        `close_clicking_esc` VARCHAR(10) DEFAULT 'checked',
                                                                        `close_clicking_overlay` VARCHAR(10) DEFAULT 'checked',
                                                                        PRIMARY KEY (`ID`))");
        //$wpdb->insert( $wpdb->prefix.'popup_settings',
        //    array( 'header_text' => 'title Pop Up',),
        //    array('%s')
        //);
        $wpdb->query( $wpdb->prepare(
            "INSERT INTO `".$wpdb->prefix."popup_settings` (header_text) VALUES (%s)",
            array('title Pop Up')
        ));

    }

    static function mpup_uninstall() {
        global $wpdb;
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}popup_settings");
    }

    static function mpup_update() {
        global $wpdb;
        $wpdb->update( $wpdb->prefix.'popup_settings',
            array( 'header_text' =>  $_POST['header_text'],
                   'main_text' => $_POST['main_text'],
                   'delay_before_popup' => $_POST['delay_before_popup'],
                   'time_display_popup' => $_POST['time_display_popup'],
                   'existence_close' => $_POST['existence_close'],
                   'close_clicking_esc' => $_POST['close_clicking_esc'],
                   'close_clicking_overlay' => $_POST['close_clicking_overlay'],
                ),
            array( 'ID' => 1 ),
            array( '%s','%s', '%d', '%d', '%s', '%s', '%s' ),
            array( '%d' )
        );
    }

    static function mpup_select() {
        global $wpdb;
        $select_popup_array = $wpdb->get_row("SELECT * FROM `".$wpdb->prefix."popup_settings` WHERE ID = 1", ARRAY_A);
        return $select_popup_array;
    }
}
?>
