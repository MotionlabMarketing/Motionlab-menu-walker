<?php
/**
 * Plugin Name: Motionlab Menu Walker
 * Plugin URI: http://www.motionlab.com
 * Author: Sharif Khan
 * Author URI: http://www.motionlab.com
 * Version: 0.7
 * License: Proprietry
 **/

register_activation_hook(__FILE__, 'ml_activate');
function ml_activate() {
    global $wpdb;

    $sql = '
CREATE TABLE wp_ml_menus (
	`menukey` varchar(100) primary key NOT NULL,
	custom BOOL DEFAULT false NULL,
	theme varchar(100) null
)   
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci';

    $wpdb->query($wpdb->prepare($sql, []));

}




function my_admin_menu()
{
    add_menu_page( 'Menu Walker Settings', 'ML Menu Walker', 'manage_options', 'ml_menu-walker', 'menu_walker_admin', 'dashicons-tickets', 6  );
}
add_action( 'admin_menu', 'my_admin_menu' );


function menu_walker_admin()
{
    global $wpdb;

    $locations = get_registered_nav_menus();
    $selected = $wpdb->get_results($wpdb->prepare('SELECT menukey FROM wp_ml_menus', []));


    ?>
    <div class="wrap">
        <h1 class="class="wp-heading-inline">Motionlab Menu Walker</h1>
        <form method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
            <input type="hidden" name="action" value="ml_menu_walker">
            <table class="widefat fixed">
                <thead>
                <tr>
                    <th style="width: 15%;">ML Menu</th>
                    <th>Menu Location</th>
                </tr>
                </thead>
                <?php foreach($locations as $key => $location): ?>
                    <tbody class="menu-locations">
                    <tr class="menu-locations-row">
                        <td>
                            <?php $booSelected = false; ?>
                            <?php foreach($selected as $s): ?>
                                <?php if($s->menukey == $key): ?>
                                    <?php $booSelected = true; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <input type="checkbox" name="menus[]" id="<?php echo $key; ?>" value="<?php echo $key; ?>" <?php echo ($booSelected) ? ' checked="checked"' : ''; ?>>
                        </td>
                        <td class="menu-location-title">
                            <label for="<?php echo $key; ?>"><?php echo $location; ?></label>

                        </td>
                    </tr>
                    </tbody>
                <?php endforeach; ?>
            </table>
            <p class="button-controls wp-clearfix">
                <input type="submit" name="ml_menu_walker" id="ml-menu-walker" class="button button-primary left" value="Save Changes">
            </p>
        </form>
    </div>

    <?php
}


function prefix_save_ml_menu_walker()
{
    /**
     * At this point, $_GET/$_POST variable are available
     *
     * We can do our normal processing here
     */

    global $wpdb;

    $menus = $_POST['menus'];

    $wpdb->query($wpdb->prepare('TRUNCATE wp_ml_menus', []));

    foreach($menus as $menu) {
        $wpdb->query($wpdb->prepare('REPLACE INTO wp_ml_menus(menukey, custom, theme) VALUES("'.$menu.'", 1, NULL)', []));
        echo $menu;
    }

    wp_redirect(admin_url('admin.php?page=ml_menu-walker'));
}
add_action( 'admin_post_ml_menu_walker', 'prefix_save_ml_menu_walker' );





/**
 * @param string $menuLocation Template menu location
 * @param int $parentId Menu start level
 * @return array Menu items
 */
function motionlab_menu_walker($menuLocation, $parentId = 0) {
    $menuName       = get_term(get_nav_menu_locations()[$menuLocation], 'nav_menu')->name;
    $data = null;
    if(has_nav_menu($menuLocation)) {
        $data = motionlab_menu_walk($menuName, $parentId);
    }
    return $data;
}

/**
 * @param string $menu
 * @param int $parentId
 * @return array
 */
function motionlab_menu_walk($menu, $parentId = 0) {
    $elements = wp_get_nav_menu_items($menu);
    $tree = [];

    foreach($elements as $element) {
        if($element->menu_item_parent == $parentId) {
            $temp = new stdClass();
            $temp->menu_parent = $element->menu_item_parent;
            $temp->post_parent = $element->post_parent;
            $temp->title = $element->title;
            $temp->url = $element->url;
            $temp->classes = $element->classes;
            $temp->ID = $element->ID;
            $temp->menu_id = $element->object_id;

            if ($element->menu_item_parent == $parentId) {
                $temp->children = motionlab_menu_walk($menu, $temp->ID);
            }

            $tree[] = $temp;
        }
    }
    return $tree;
}
