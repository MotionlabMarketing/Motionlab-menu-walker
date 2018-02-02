=== Motionlab Menu Walker ===
Tags: menu
Requires at least: 4.8
Tested up to: 4.9.2
Requires PHP: 7.1
License: MIT

Returns a recursive array of data representing the given menu. Useful for outputting the menu then applying your own styles to it. Can go any number of sub menus deep.

== Description ==
This Plugin takes a Menu Location as an argument and returns an array of data representing the chosen Menu. The array is recursive, meaning it follows the sub-menu pattern of the chosen Menu (if any) but applies no styles to it.
This is useful for Wordpress developers who want to quickly output a menu but apply their own styles.

== Installation ==
Download the Plugin.

Activate the Plugin.

Wherever a menu is required in a html template use the following:

    $menu = motionlab_menu_walk('MenuLocation');  // where MenuLocation is the Location NOT the menu Name

Then you can output the top level of the menu with a foreach like:

    foreach($menu as $menuitem):

$menuItem contains the fields url and title which can be used as needed like:

    $menuItem->url

For sub menus you can check the children element like:

    if(!empty($menuitem->children)):

then just start the process again using the children:

    foreach($menuitem->children as $child):

and so on.
