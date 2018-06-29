<?php
/**
 * Created by PhpStorm.
 * User: karl
 * Date: 09/03/18
 * Time: 14:13
 */
?>
<nav>
    <ul>

        <?php foreach($menu as $menuitem) : ?>
            <li>
                <a href="<?php echo $menuitem->url ?>" class="btn border-none text-center block white bold p3 xl-p4 nowrap">
                    <?php echo $menuitem->title; ?>
                </a>
            </li>
        <?php endforeach ?>

    </ul>
</nav>
