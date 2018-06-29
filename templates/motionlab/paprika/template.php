<?php
/**
 * Created by PhpStorm.
 * User: karl
 * Date: 09/03/18
 * Time: 14:13
 */
?>

<link rel="stylesheet" type="text/css" href="<?=plugin_dir_url(__FILE__)?>template.css">

<div class="ml_menu ml_menu_paprike">

    <nav class="ml_menu_nav">

        <ul class="ml_menu_ul">

            <?php foreach($menu as $menuitem) : ?>
                <li class="ml_menu_li<?php if(!empty($menuitem->children)){ ?> ml_menu_has_dropdown <?php } ?>">
                    <a href="<?=$menuitem->url?>" class="ml_menu_link">
                        <?=$menuitem->title;?>
                        <?php if(!empty($menuitem->children)) : ?>
                            <small class="ml_menu_arrow_down">&#9660;</small>
                        <?php endif; ?>
                    </a>
                    <?php if(!empty($menuitem->children)) : ?>

                    <!--
                        OK AT THIS POINT WE NEED TO SEE IF ANY OF THE children HAVE children and so use a different menu
                    -->

                    <?php $hasGrandChildren = false; ?>
                    <?php foreach($menuitem->children as $child): ?>
                        <?php if(!empty($child->children)): ?>
                            <?php $hasGrandChildren = true; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <?php if($hasGrandChildren): ?>

                        <ul class="ml_menu_dropdown_ul">

                            <?php $i=1; ?>
                            <?php foreach($menuitem->children as $second_child) : ?>

                                <li class="ml_menu_dropdown_li">
                                    <a href="<?=$second_child->url?>" class="ml_menu_link" data-toggle-section="<?=$second_child->ID;?>">
                                        <?=$second_child->title;?>
                                        <?php
                                        if(!empty($second_child->children)):
                                        ?>
                                                <small class="ml_menu_arrow_right">&#9656;</small>
                                        <?php
                                        endif;
                                        ?>
                                    </a>

                                    <?php if (!empty($second_child->children)):?>
                                        <ul class="ml_menu_dropdown_ul">
                                            <?php
                                            foreach($second_child->children as $child) :
                                            ?>
                                                <li class="ml_menu_dropdown_li">
                                                    <a href="<?=$child->url?>" class="ml_menu_link" data-toggle-section="<?=$child->ID;?>">
                                                        <?=$child->title;?>
                                                    </a>
                                                </li>
                                                <?php $i++; ?>
                                            <?php
                                                endforeach;
                                            ?>
                                        </ul>
                                    <?php endif;?>

                                </li>
                                <?php $i++; ?>

                            <?php endforeach ?>

                        </ul>

                    <?php else: ?>

                        <ul class="ml_menu_dropdown_ul">
                            <?php foreach($menuitem->children as $child) : ?>
                                <li class="ml_menu_dropdown_li">
                                    <a href="<?=$child->url?>" class="ml_menu_link"><?=$child->title;?></a>
                                </li>
                            <?php endforeach ?>
                        </ul>

                    <?php endif; ?>

                <?php endif ?>
            <?php endforeach ?>

        </ul>
    </nav>

</div>
