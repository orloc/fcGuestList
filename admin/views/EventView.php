<?php

class EventView {

    private static $TABLE_NAME = 'event';
    private static $postUri = '/wp/wp-admin/admin-post.php';
    private static $pageUri = '/wp/wp-admin/admin.php?page=fcGuestList/guest-listPlugin.phpevents';

    public static function handlePost(){
        list($a,$name) = array_values($_POST);
        $item = Database::hasItem($name, 'name', self::$TABLE_NAME);
        
        if (!$item){
            Database::insert(self::$TABLE_NAME, [
                'name' => $name,
            ]);

            wp_redirect(self::$pageUri, 200);
            exit;
        }
        
        wp_redirect(self::$pageUri.'&exists=true', 200);
    }
    
    public static function handleEdit(){
        
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
        $results = Database::all('event');
        ?>
        <div class="wrap" ng-app="admin" ng-controller="listCtrl">
            <h1>Event List</h1>
            <button  style="float: right" class="page-title-action aria-button-if-js" ng-click="toggleNew()" ng-show="show_edit !== true">New</button>
            <button  style="float: right" class="page-title-action aria-button-if-js" ng-click="toggleNew()" ng-show="show_edit === true">Hide Form</button>
            <div ng-show="show_edit === true" style="width: 80%; margin: 0 auto;">
                <form action="<?php echo self::$postUri ?>" method="POST" name="eventNew">
                    <input type="hidden" name="action" value="submit_event">
                    <table class="form-table">
                        <tr class="form-field form-required">
                            <td><label for="name">Name</label></td>
                            <td><input type="text" name="name" required></td>
                        </tr>
                        <tr class="form-field form-required">
                            <td><label for="lockout">Lockout Date / Time</label></td>
                            <td><input type="datetime" name="lockout" required></td>
                        </tr>
                    </table>
                    <p class="submit">
                        <input type="submit" class="button-primary" value="Add new Event"/>
                    </p>
                </form>
            </div>

            <div ng-show="show_edit_item === true" style="width: 80%; margin: 0 auto;">
                <form action="<?php echo self::$postUri ?>" method="POST" name="eventEdit">
                    <input type="hidden" name="action" value="edit_event">
                    <input type="hidden" name="event_id" value="{{ selected }}">
                    <table class="form-table">
                        <tr class="form-field form-required">
                            <td><label for="name">Name</label></td>
                            <td><input type="text" name="name" required></td>
                        </tr>
                        <tr class="form-field form-required">
                            <td><label for="lockout">Lockout Date / Time</label></td>
                            <td><input type="datetime" name="lockout" required></td>
                        </tr>
                        <tr class="form-field form-required">
                            <td><label for="isActive">Is Active?</label></td>
                            <td><input type="checkbox" name="is_active"</td>
                        </tr>
                    </table>
                    <p class="submit">
                        <input type="submit" class="button-primary" value="Confirm"/>
                    </p>
                </form>
            </div>
            <table class="widefat">
                <thead>
                <th>id</th>
                <th>Name</th>
                <th>Lockout Date</th>
                <th>Is Active</th>
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

                    $lockout = $r->lockout_date ? new \DateTime($r->lockout_date) : false;
                    $fLockout = $lockout ? $lockout->format('m/d/Y h:i:s A') : '-';
                    $active = $r->is_active ? 'Yes' : 'No';
                    echo "<tr>
                            <td>
                                $r->id
                            </td>
                            <td>
                                $r->name
                            </td>
                            <td>
                                $fLockout
                            </td>
                            <td>
                                $active
                            </td>
                            <td>
                                 $formatted
                            </td>
                            
                            <td>
                                <form name='delete{$r->id}' method='post', action='/wp/wp-admin/admin-post.php'>
                                    <input type='hidden' name='action' value='delete_event'>
                                    <input type='hidden' name='id' value='{$r->id}'>
                                    <input type='submit' style='float: right' class='button-secondary delete' value ='Archive'/>
                                </form>
                                <a href=''>Edit</a>
                            </td>
                        </tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
        <?php
    }
}
