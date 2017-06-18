<?php

class GuestView {
    
    public static function handlePost(){
        
    }

    public static function getView(){
        $results = Database::all('guest_list');
        $roleList = Database::all('member_type');
        $events = Database::all('event');
        ?>
        <div class="wrap" ng-app="admin" ng-controller="listCtrl">
        <h1>Guest List</h1>
        <button  style="float: right" class="page-title-action aria-button-if-js" ng-click="toggleNew()" ng-show="show_edit !== true">New</button>
        <button  style="float: right" class="page-title-action aria-button-if-js" ng-click="toggleNew()" ng-show="show_edit === true">Hide Form</button>
        <div ng-show="show_edit === true" style="width: 80%; margin: 0 auto;">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]).'?page=guest-listPlugin/guest-listPlugin.phproles' ?>" name="roleNew" method="POST">
                <table class="form-table">
                    <tr class="form-field form-required">
                        <td><label for="role">Email</label></td>
                        <td><input type="email" name="name"></td>
                    </tr>
                    <tr class="form-field form-required">
                        <td><label for="role">Role</label></td>
                        <td><select name="role">
                            <?php foreach ($roleList as $e): ?>
                                <option value="<?= $e->id ?>"><?= $e->name ?></option>
                            <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr class="form-field form-required">
                        <td><label for="event">Event</label></td>
                        <td><select name="event">
                            <?php foreach ($events as $e): ?>
                                <option value="<?= $e->id ?>"><?= $e->name ?></option>
                            <?php endforeach; ?>
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
            <th>Event</th>
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
                                    $r->role_id
                                </td>
                                <td>
                                    $r->event_id
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

}
