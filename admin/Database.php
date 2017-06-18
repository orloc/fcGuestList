<?php

class Database {
    public static function doQuery($query, $table){
        global $wpdb;
        $tableName = $wpdb->prefix . $table;
        $guestQuery = "$query FROM $tableName";
        return $wpdb->get_results($guestQuery);
    }

    public static function initGuestDb(){
        global $wpdb;

        $tableName = $wpdb->prefix . 'guest_list';
        $tableName2 = $wpdb->prefix . 'member_type';
        $tableName3 = $wpdb->prefix . 'event';

        $sql = "CREATE TABLE $tableName (
          id mediumint(9) not null auto_increment,
          created_at datetime default NOW() not null,
          email varchar(255) not null,
          full_name varchar(255),
          event_id mediumint(9) not null,
          role_id mediumint(9) not null,
          responded tinyint(1) not null,
          responded_at datetime not null,
          PRIMARY KEY (id)
        )";

        $sql2 = "CREATE TABLE $tableName2 (
          id mediumint(9) not null auto_increment,
          created_at datetime default NOW() not null,
          name varchar(255) not null,
          price int(9) not null,
          PRIMARY KEY (id)
        )";

        $sql3 = "CREATE TABLE $tableName3 (
          id mediumint(9) not null auto_increment,
          created_at datetime default NOW() not null,
          name varchar(255) not null,
          PRIMARY KEY (id)
        )";

        require_once (ABSPATH . 'wp-admin/upgrade.php');
        dbDelta($sql);
        dbDelta($sql2);
        dbDelta($sql3);

        self::addDefaultRoles();
        self::addDefaultUser();
    }

    private static function addDefaultRoles(){
        global $wpdb;
        $name = $wpdb->prefix.'member_type';

        $query = "delete from $name";
        $wpdb->query($query);

        $wpdb->insert($name, [
            'name' => 'Family Office',
            'price' => 150
        ]);
        $wpdb->insert($name, [
            'name' => 'RIA',
            'price' => 500
        ]);
    }

    private static function addDefaultUser(){
        global $wpdb;
        $name = $wpdb->prefix.'guest';

        $query = "delete from $name";
        $wpdb->query($query);

        $wpdb->insert($name, [
            'full_name' => 'Grant Tepper',
            'role_id' => 9,
            'event_id' =>  '',
            'email' => 'grant.tepper@gmail.com'
        ]);
    }
}
