<?php
/**
 * Created by PhpStorm.
 * User: karl
 * Date: 09/03/18
 * Time: 14:13
 */
?>
<nav class="js-priority-nav">
    <ul class="list-reset inline-block m0 p0">

        <?php foreach($menu as $menuitem) : ?>
            <li class="inline-block hover-bg-darken-5 animate <?php if(!empty($menuitem->children)){ ?>has-dropdown <?php } ?>">
                <a href="<?php echo $menuitem->url ?>" class="btn border-none text-center block white bold p3 xl-p4 nowrap">
                    <?php echo $menuitem->title; ?>
                    <?php if(!empty($menuitem->children)) : ?>
                        <small class="ml2">&#9660;</small>
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

                    <ul class="list-reset m0 p0 grandychildren">

                        <?php $i=1; ?>
                        <?php foreach($menuitem->children as $second_child) : ?>

                            <li class="relative overflow-hidden hover-bg-white border border-right-none border-transparent <?php if($i==1) : ?>section-active<?php endif; ?>" style="margin-right:-1px;">
                                <a href="<?php echo $second_child->url ?>" class="block black p3 bold text-decoration-none" data-toggle-section="<?php echo $second_child->ID; ?>">
                                    <?php echo $second_child->title; ?>
                                    <?php
                                    if(!empty($second_child->children)):
                                    ?>
                                            <small class="ml2" style="float:right">&#9656;</small>
                                    <?php
                                    endif;
                                    ?>
                                </a>

                            <ul>
                                <?php
                                foreach($second_child->children as $child) :
                                ?>
                                    <li class="relative overflow-hidden hover-bg-white border border-right-none border-transparent <?php if($i==1) : ?>section-active<?php endif; ?>" style="margin-right:-1px;">
                                        <a href="<?php echo $child->url ?>" class="block black p3 bold text-decoration-none" data-toggle-section="<?php echo $child->ID; ?>">
                                            <?php echo $child->title; ?>
                                        </a>
                                    </li>
                                    <?php $i++; ?>
                                <?php
                                    endforeach;
                                ?>
                            </ul>
                            </li>
                            <?php $i++; ?>

                        <?php endforeach ?>

                    </ul>

                <?php else: ?>

                    <ul class="dropdown list-reset bg-black">
                        <?php foreach($menuitem->children as $child) : ?>
                            <li class="border-bottom border-darkgrey">
                                <a href="<?php echo $child->url ?>" class="block white bold p3 nowrap hover-bg-darken-5 animate block"><?php echo $child->title; ?></a>
                            </li>
                        <?php endforeach ?>
                    </ul>

                <?php endif; ?>

            <?php endif ?>
        <?php endforeach ?>

    </ul>
</nav>
