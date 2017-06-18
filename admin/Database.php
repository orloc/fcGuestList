<?php

class Database {
    public static function all($table){
        global $wpdb;
        $tableName = $wpdb->prefix . $table;
        $guestQuery = "select * FROM $tableName";
        return $wpdb->get_results($guestQuery);
    }
    
    public static function insert($table, $data){
        global $wpdb;

        $tableName = $wpdb->prefix . $table;
        $wpdb->insert($tableName, $data);
    }

    public static function update($table, $data, $where){
        global $wpdb;

        $tableName = $wpdb->prefix . $table;
        $wpdb->update($tableName, $data, $where);
    }
    
    public static function hasItem($identifier, $field, $table) {
        global $wpdb;
        $tableName = $wpdb->prefix . $table;
        $guestQuery = $wpdb->prepare("select count(*) as count FROM $tableName where $field = %s", $identifier);
        $res = $wpdb->get_results($guestQuery);
        return boolval(array_pop($res)->count);
        
    }

    public static function init(){
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
          responded tinyint(1) default 0 not null ,
          deleted_at datetime,
          responded_at datetime,
          PRIMARY KEY (id)
        )";

        $sql2 = "CREATE TABLE $tableName2 (
          id mediumint(9) not null auto_increment,
          created_at datetime default NOW() not null,
          name varchar(255) not null,
          deleted_at datetime,
          price int(9) not null,
          PRIMARY KEY (id)
        )";

        $sql3 = "CREATE TABLE $tableName3 (
          id mediumint(9) not null auto_increment,
          deleted_at datetime,
          created_at datetime default NOW() not null,
          name varchar(255) not null,
          PRIMARY KEY (id)
        )";

        require_once (ABSPATH . 'wp-admin/upgrade.php');
        dbDelta($sql);
        dbDelta($sql2);
        dbDelta($sql3);

        self::addDefaultRoles();
        self::addDefaultEvent();
    }
    
    private static function addDefaultEvent(){
        global $wpdb;
        $name = $wpdb->prefix.'event';

        $query = "delete from $name";
        $wpdb->query($query);

        self::insert('event', [
            'name' => 'Test Event'
        ]);
    }

    private static function addDefaultRoles(){
        global $wpdb;
        $name = $wpdb->prefix.'member_type';

        $query = "delete from $name";
        $wpdb->query($query);

        self::insert('member_type', [
            'name' => 'Family Office',
            'price' => 150
        ]);
        self::insert('member_type', [
            'name' => 'RIA',
            'price' => 500
        ]);
    }
}
