<?php
/**
 * Plugin Name: Motionlab Menu Walker
 * Plugin URI: http://www.motionlab.com
 * Author: Sharif Khan
 * Author URI: http://www.motionlab.com
 * Version: 0.7
 * License: Proprietry
 **/

function motionlab_menu_walker($menu, $parentId = 0) {
    $data = motionlab_menu_walk($menu, $parentId);
    return $data;
}

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
