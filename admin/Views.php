<?php

class Views {
    public static function guestListAdmin(){
        $results = Database::doQuery("select *", 'guest_list');
        ?>
        <div class="wrap">
        <h1>Guest List</h1>
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
        <div class="wrap">
            <h1>Event List</h1>
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
        <div class="wrap">
            <h1>Role List</h1>
            <button  style="float: right" class="page-title-action aria-button-if-js">New</button>
            <form name="roleNew">
                <input type="text" name="role">
                <input type="number" name="price">
                <button class="submit" type="submit">Add New Role</button>
            </form>
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
                                    <button  style='float: right' class='page-title-action aria-button-if-js'>Edit</button>
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
