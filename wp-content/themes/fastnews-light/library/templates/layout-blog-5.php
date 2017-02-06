<?php
$kopa_setting = kopa_get_template_setting();
$sidebars = $kopa_setting['sidebars'];
?>

<?php get_header(); ?>

<div class="wrapper">
    <div class="col-a widget-area-1">

        <?php get_template_part('library/templates/contents'); ?>

    </div>
    <!-- col-a -->

    <div class="col-b">
        <?php if (is_active_sidebar($sidebars[0])) { ?>
            <div class="widget-area-4">
                <?php dynamic_sidebar($sidebars[0]); ?>
            </div>
            <!-- widget-area-4 -->
        <?php } ?>
        <?php if (is_active_sidebar($sidebars[1])) { ?>
            <div class="widget-area-3">
                <?php
                dynamic_sidebar($sidebars[1]);
                ?>       
            </div>
            <!-- widget-area-3 -->
        <?php } ?>
        <div class="clear"></div>
    </div>
    <!-- col-b -->
    <div class="clear"></div>
</div>
<!-- wrapper -->

<?php if (is_active_sidebar($sidebars[2]) || is_active_sidebar($sidebars[3]) || is_active_sidebar($sidebars[4]) || is_active_sidebar($sidebars[5]) || is_active_sidebar($sidebars[6])) { ?>
    <div class="widget-area-5">
        <ul class="wrapper clearfix">
            <?php if (is_active_sidebar($sidebars[2])) { ?>
                <li class="widget-area-6">
                    <?php
                    dynamic_sidebar($sidebars[2]);
                    ?>
                </li>
                <!-- widget-area-6 -->
    <?php } ?>
            <?php if (is_active_sidebar($sidebars[3])) { ?>
                <li class="widget-area-7">
                <?php
                dynamic_sidebar($sidebars[3]);
                ?>
                </li>
                <!-- widget-area-7 -->
                <?php } ?>
                <?php if (is_active_sidebar($sidebars[4])) { ?>
                <li class="widget-area-8">
                <?php
                dynamic_sidebar($sidebars[4]);
                ?>
                </li>
                <!-- widget-area-8 -->
                <?php } ?>
                <?php if (is_active_sidebar($sidebars[5])) { ?>
                <li class="widget-area-9">
                    <?php
                    dynamic_sidebar($sidebars[5]);
                    ?>
                </li>
                <!-- widget-area-9 -->
                <?php } ?>
                <?php if (is_active_sidebar($sidebars[6])) { ?>
                <li class="widget-area-10">
                    <?php
                    dynamic_sidebar($sidebars[6]);
                    ?>
                </li>
                <!-- widget-area-10 -->
            <?php } ?>
        </ul>
        <!-- wrapper -->
    </div>
    <!-- widget-area-5 -->
            <?php } // endif  ?>

            <?php if (is_active_sidebar($sidebars[7])) { ?>
    <div class="widget-area-11">
        <div class="wrapper">
            <?php dynamic_sidebar($sidebars[7]); ?>
        </div>
        <!-- wrapper -->
    </div>
    <!-- widget-area-11 -->
<?php } // endif   ?>

<?php get_footer(); ?>