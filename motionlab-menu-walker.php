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
add_filter('wp_nav_menu', 'motionlab_menu_walker', 10, 4);


function ml_activate() {
    global $wpdb;

    $sql = '
CREATE TABLE wp_ml_menus (
	`menukey` varchar(100) primary key NOT NULL,
	custom BOOL DEFAULT false NULL,
	namespace varchar(100) NULL,
	template varchar(100) NULL
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci';

    $wpdb->query($sql);

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
    $selected = $wpdb->get_results('SELECT menukey, template FROM wp_ml_menus');

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
                            <?php $booSelected = false;
                             foreach($selected as $s):


                                 if($s->menukey == $key):
                                    $booSelected = true;
                                    $templateName = $s->template;
                                 endif;
                            endforeach;?>
                            <input type="checkbox" name="menus[]" id="<?php echo $key; ?>" value="<?php echo $key; ?>" <?php echo ($booSelected) ? ' checked="checked"' : ''; ?>>
                        </td>
                        <td class="menu-location-title">
                            <label for="<?php echo $key; ?>"><?php echo $location; ?></label>

                        </td>
                        <td class="menu-location-theme">
                            <select name="themes[]">
                                <option value="paprika" <?=$templateName == 'paprika' ? 'selected' : ''?>>Paprika</option>
                                <option value="serrano" <?=$templateName == 'serrano' ? 'selected' : ''?>>Serrano</option>
                            </select>
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

    $menus = (isset($_POST['menus']) ? $_POST['menus'] : []);
    $themes = (isset($_POST['themes']) ? $_POST['themes'] : []);

    $wpdb->query('TRUNCATE wp_ml_menus');

    $i = 0;
    foreach($menus as $menu) {
        $theme = $themes[$i];
        $wpdb->query('REPLACE INTO wp_ml_menus(menukey, custom, template, namespace) VALUES("'.$menu.'", 1, "'.$theme.'", NULL)');
        echo $menu;
        $i++;
    }

    wp_redirect(admin_url('admin.php?page=ml_menu-walker'));
}
add_action( 'admin_post_ml_menu_walker', 'prefix_save_ml_menu_walker' );

/**
 * @param string $menuLocation Template menu location
 * @param int $parentId Menu start level
 * @return array Menu items
 */
 function motionlab_menu_walker($menu_html, $query_object) {
    global $wpdb;
    $selected = $wpdb->get_results('SELECT menukey, template FROM wp_ml_menus', 'OBJECT');

    $render_menu = false;
    foreach($selected as $menu_object) {
        if ($menu_object->menukey === $query_object->theme_location) {
            $render_menu = true;
        }
    }

    if($render_menu) {
        $menuLocation = $query_object->theme_location;

        $menuName       = get_term(get_nav_menu_locations()[$menuLocation], 'nav_menu')->name;
        $data = null;
        if(has_nav_menu($menuLocation)) {
            $data = motionlab_menu_walk($menuName);
        }

        return generate_menu($data, $menuLocation);
    }

    return $menu_html;
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


function generate_menu($menu, $menuLocation) {
    global $wpdb;
    $menu_options = $wpdb->get_results('SELECT template FROM wp_ml_menus WHERE menukey = "'.$menuLocation.'"');

    //TODO: Update to use selected namespace/theme combination
    include_once(dirname(__FILE__) . '/templates/motionlab/'.$menu_options[0]->template.'/template.php');
}
