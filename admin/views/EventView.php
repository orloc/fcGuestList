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
                            <td><label for="role">Name</label></td>
                            <td><input type="text" name="name" required></td>
                        </tr>
                    </table>
                    <p class="submit">
                        <input type="submit" class="button-primary" value="Add new Event"/>
                    </p>
                </form>
            </div>
            <table class="widefat">
                <thead>
                <th>Name</th>
                <th>Created At</th>
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
                    echo "<tr>
                            <td>
                                $r->name
                            </td>
                            <td>
                                 $formatted
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
