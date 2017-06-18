<?php

class Views {

    private static $pluginPath = __FILE__;
    
    public static function guestListAdmin(){
        $results = Database::doQuery("select *", 'guest_list');
        ?>
        <div class="wrap" ng-app="admin" ng-controller="listCtrl">
        <h1>Guest List</h1>
        <button  style="float: right" class="page-title-action aria-button-if-js" ng-click="toggleNew()" ng-show="show_edit !== true">New</button>
        <button  style="float: right" class="page-title-action aria-button-if-js" ng-click="toggleNew()" ng-show="show_edit === true">Hide Form</button>
        <div ng-show="show_edit === true" style="width: 80%; margin: 0 auto;">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]).'?page=guest-listPlugin/guest-listPlugin.phproles' ?>" name="roleNew">
                <table class="form-table">
                    <tr class="form-field form-required">
                        <td><label for="role">Email</label></td>
                        <td><input type="email" name="name"></td>
                    </tr>
                    <tr class="form-field form-required">
                        <td><label for="role">Role</label></td>
                        <td><select name="role">
                                <option>Thing</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" class="button-primary" value="Add new Event"/>
                </p>
            </form>
        </div>
        <table class="widefat">
            <thead>
            <th>Email</th>
            <th>Role</th>
            <th>Responded</th>
            <th>Responded At</th>
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
                echo "<tr>
                                <td>
                                    $r->email
                                </td>
                                <td>
                                    $r->role_io                    
                                </td>
                                <td>
                                    $r->responded
                                </td>
                                <td>
                                     $formatted
                                </td>
                            </tr>";
            }
            ?>
            </tbody>
        </table>
        <?php
    }

    public static function eventListAdmin(){
        $results = Database::doQuery("select *", 'event');
        ?>
        <div class="wrap" ng-app="admin" ng-controller="listCtrl">
            <h1>Event List</h1>
            <button  style="float: right" class="page-title-action aria-button-if-js" ng-click="toggleNew()" ng-show="show_edit !== true">New</button>
            <button  style="float: right" class="page-title-action aria-button-if-js" ng-click="toggleNew()" ng-show="show_edit === true">Hide Form</button>
            <div ng-show="show_edit === true" style="width: 80%; margin: 0 auto;">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]).'?page=guest-listPlugin/guest-listPlugin.phproles' ?>" name="roleNew">
                    <table class="form-table">
                        <tr class="form-field form-required">
                            <td><label for="role">Name</label></td>
                            <td><input type="text" name="name"></td>
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
                <th>Attended</th>
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
                                $r->attended
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

    public static function roleListAdmin(){
    
        $results = Database::doQuery("select *", 'member_type');
        

        ?>
        <div class="wrap" ng-app="admin" ng-controller="listCtrl">
            <h1>Role List</h1>
            <button  style="float: right" class="page-title-action aria-button-if-js" ng-click="toggleNew()" ng-show="show_edit !== true">New</button>
            <button  style="float: right" class="page-title-action aria-button-if-js" ng-click="toggleNew()" ng-show="show_edit === true">Hide Form</button>
            <div ng-show="show_edit === true" style="width: 80%; margin: 0 auto;">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]).'?page=guest-listPlugin/guest-listPlugin.phproles' ?>" name="roleNew">
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
                                <td>
                                    <button  style='float: right' class='page-title-action aria-button-if-js'>Remove</button>
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
