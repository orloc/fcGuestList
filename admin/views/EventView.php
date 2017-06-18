<?php

class EventView {
    
    public static function handlePost(){

    }

    public static function getView(){
        $results = Database::all('event');
        ?>
        <div class="wrap" ng-app="admin" ng-controller="listCtrl">
            <h1>Event List</h1>
            <button  style="float: right" class="page-title-action aria-button-if-js" ng-click="toggleNew()" ng-show="show_edit !== true">New</button>
            <button  style="float: right" class="page-title-action aria-button-if-js" ng-click="toggleNew()" ng-show="show_edit === true">Hide Form</button>
            <div ng-show="show_edit === true" style="width: 80%; margin: 0 auto;">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]).'?page=guest-listPlugin/guest-listPlugin.phproles' ?>" method="POST" name="roleNew">
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
