<?php

class RoleView {
    
    private static $TABLE_NAME = 'member_type';
    private static $pageUri = '/wp/wp-admin/admin.php?page=fcGuestList/guest-listPlugin.phproles';
    
    public static function handlePost(){
        list($a,$role, $price) = array_values($_POST);
        $item = Database::hasItem($role, 'name', self::$TABLE_NAME);
        
        if (!$item){
            Database::insert(self::$TABLE_NAME, [
                'name' => $role,
                'price' => intval($price)
            ]);
            
            wp_redirect(self::$pageUri, 200);
            exit;
        }
        
        wp_redirect(self::$pageUri.'&exists=true', 200);
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
    
        $results = Database::all(self::$TABLE_NAME);
        

        ?>
        <div class="wrap" ng-app="admin" ng-controller="listCtrl">
            <h1>Role List</h1>
            <div class="error notice" ng-show="exists === true">
                The Role you entered already exists. Please choose a different name.
            </div>

            <div class="error notice" ng-show="not_exists === true">
                We couldn't find that item to modify.
            </div>
            <button  style="float: right" class="page-title-action aria-button-if-js" ng-click="toggleNew()" ng-show="show_edit !== true">New</button>
            <button  style="float: right" class="page-title-action aria-button-if-js" ng-click="toggleNew()" ng-show="show_edit === true">Hide Form</button>
            <div ng-show="show_edit === true" style="width: 80%; margin: 0 auto;">
                <form action="/wp/wp-admin/admin-post.php" name="roleNew" method="POST">
                    <input type="hidden" name="action" value="submit_role">
                    <table class="form-table">
                        <tr class="form-field form-required">
                            <td><label for="role">Role</label></td>
                            <td><input type="text" name="role"></td>
                        </tr>

                        <tr class="form-field form-required">
                            <td><label for="role">Price</label></td>
                            <td><input type="number" name="price"></td>
                        </tr>
                    </table>
                    <p class="submit">
                        <input type="submit" class="button-primary" value="Add new Role"/>
                    </p>
                </form>
            </div>
            <table class="widefat">
                <thead>
                <th>Role</th>
                <th>Price</th>
                <th>Created At</th>
                <th>Archived</th>
                <th></th>
                </thead>
                <tbody>
                <?php
                if (!count($results)){
                    echo "<tr> <td colspan='4'>No Results Founds</td></tr>";
                    return;
                }
                foreach($results as $r) {
                    $price = sprintf("$%s.00", $r->price);
                    $date = new \DateTime($r->created_at);
                    $formatted = $date->format('m/d/Y h:i:s A');
                    $archived = $r->deleted_at ? 'Yes' : 'No';
                    echo "<tr>
                                <td>
                                    $r->name
                                </td>
                                <td>
                                    $price
                                </td>
                                <td>
                                     $formatted
                                </td>
                                <td> $archived </td>
                                <td>
                                    <form name='delete{$r->id}' method='post', action='/wp/wp-admin/admin-post.php'>
                                        <input type='hidden' name='action' value='delete_role'>
                                        <input type='hidden' name='id' value='{$r->id}'>
                                        <input type='submit' style='float: right' class='button-secondary delete' value ='Archive'/>
                                    </form>
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
