<?php
/**
 * Plugin Name: Motionlab Menu Walker
 * Plugin URI: http://www.motionlab.com
 * Author: Sharif Khan
 * Author URI: http://www.motionlab.com
 * Version: 0.7
 * License: Proprietry
 **/

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
