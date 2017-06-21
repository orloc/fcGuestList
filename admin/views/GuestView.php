<?php

class GuestView {

    private static $TABLE_NAME = 'guest';
    private static $postUri = '/wp/wp-admin/admin-post.php';
    private static $pageUri = '/wp/wp-admin/admin.php?page=fcGuestList/guest-listPlugin.phpguests';
    
    public static function handlePost(){

        global $wpdb;


        list($a,$email, $role, $event) = array_values($_POST);

        $tableName = $wpdb->prefix . self::$TABLE_NAME;
        $guestQuery = $wpdb->prepare("select count(*) as count FROM $tableName where  email= %s and event_id = %d and deleted_at is null", $email, $role);
        
        $res = $wpdb->get_results($guestQuery);
        $item = boolval(array_pop($res)->count);

        if (!$item){
            Database::insert(self::$TABLE_NAME, [
                'email' => $email,
                'event_id' => $event,
                'role_id' => $role
            ]);

            wp_redirect(self::$pageUri, 200);
            exit;
        }

        wp_redirect(self::$pageUri.'&exists=true', 200);
    }

    public static function handleEdit(){
        list($a,$id, $email) = array_values($_POST);
        $item = Database::hasItem(intval($id), 'id', self::$TABLE_NAME);
        if ($item){
            Database::update(self::$TABLE_NAME, [
                'email' => $email
            ], [ 'id' => $id]);
            wp_redirect(self::$pageUri, 200);
        }
        wp_redirect(self::$pageUri.'&notExists=true', 200);
    }

    public static function handleDelete(){
        list($a,$id) = array_values($_POST);
        $item = Database::hasItem($id, 'id', self::$TABLE_NAME);
        if ($item){
            Database::update(self::$TABLE_NAME, [
                'deleted_at' => current_time('mysql', false)
            ], [ 'id' => $id]);
            wp_redirect(self::$pageUri, 200);
        }
        wp_redirect(self::$pageUri.'&notExists=true', 200);
    }

    public static function getView(){
        global $wpdb;
        $tableName = $wpdb->prefix . 'guest';
        $guestQuery = "select t.*, count(tt.id)  as additions FROM $tableName t 
                       left join guest_additions tt on t.id=tt.guest_id 
                       where t.deleted_at is null
                       group by t.id";

        $results = $wpdb->get_results($guestQuery);
        $roleList = Database::all('member_type');
        $events = Database::all('event');
        
        $index = [
            'events' => [], 'roles' => []
        ];
        
        foreach ($roleList as $r){
            $index['roles'][$r->id] = $r->name;
        }

        foreach ($events as $e){
            $index['events'][$e->id] = $e->name;
        }
        
        ?>
        <div class="wrap" ng-app="admin" ng-controller="listCtrl">
        <h1>Guest List</h1>
        <button  style="float: right" class="page-title-action aria-button-if-js" ng-click="toggleNew()" ng-show="show_edit !== true">New</button>
        <button  style="float: right" class="page-title-action aria-button-if-js" ng-click="toggleNew()" ng-show="show_edit === true">Hide Form</button>

        <div ng-show="open_edit_box === true" style="width: 80%; margin: 0 auto;">
            <h4>Editing</h4>
            <a ng-click="closeEdit()">X Close</a>
            <form action="/wp/wp-admin/admin-post.php" name="guestEdit" method="POST">
                <input type="hidden" name="action" value="edit_guest">
                <input type="hidden" name="id" value="{{ currently_editing.id }}">
                <table class="form-table">
                    <tr class="form-field form-required">
                        <td><label for="role">Email</label></td>
                        <td><input type="email" value="{{ currently_editing.email }}" required name="email"></td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" class="button-primary" value="Update Guest"/>
                </p>
            </form>
        </div>

        <div ng-show="show_edit === true" style="width: 80%; margin: 0 auto;">
            <form action="<?php echo self::$postUri ?>" name="guestNew" method="POST">
                <p class="submit">
                    <input type="submit" class="button-primary" value="Add new Guest"/>
                </p>
            </form>
        </div>
        <table class="widefat">
            <thead>
            <th>id</th>
            <th>Email</th>
            <th>Role</th>
            <th>Additions</th>
            <th>Event</th>
            <th>Responded</th>
            <th>Created At</th>
            <th></th>
            </thead>
            <tbody>
            <?php
            if (!count($results)){
                echo "<tr> <td colspan='4'>No Results Founds</td></tr>";
                return;
            }
            foreach($results as $r) {
                $date = new \DateTime($r->created_at);
                $formatted = $date->format('m/d/Y h:i:s A');
                $responded = $r->responded ? 'Yes' : 'No';
                echo "<tr>
                        <td>$r->id</td>
                        <td>
                            $r->email
                        </td>
                        <td>
                            {$index['roles'][$r->role_id] }
                        </td>
                        <td>
                            $r->additions 
                        </td>
                        <td>
                            {$index['events'][$r->event_id] }
                        </td>
                        <td>
                            $responded
                        </td>
                        <td>
                             $formatted
                        </td>
                        <td>
                            <form name='delete{$r->id}' method='post' action='/wp/wp-admin/admin-post.php'>
                                <input type='hidden' name='action' value='delete_guest'>
                                <input type='hidden' name='id' value='{$r->id}'>
                                <input type='submit' style='float: right' class='button-secondary delete' value ='Archive'/>
                            </form>
                            <button class='button-primary' ng-click='openEdit({ email: \"{$r->email}\", id: {$r->id}, role: {$r->role_id}})'>Edit</button>
                        </td>
                    </tr>";
            }
            ?>
            </tbody>
        </table>
        <?php
    }

}
