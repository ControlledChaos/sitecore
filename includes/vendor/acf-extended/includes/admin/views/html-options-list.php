<div class="wrap" id="acfe-admin-options">

    <h1 class="wp-heading-inline"><?php _e( 'Website Options' ); ?></h1>
    <a href="<?php echo sprintf('?page=%s&action=add', esc_attr($_REQUEST['page'])); ?>" class="page-title-action"><?php _e('Add New'); ?></a>

    <div id="user-message" class="notice notice-warning is-dismissible">
        <p class="description"><span class="dashicons dashicons-warning" style="color: #dba617"></span> <?php _e( 'This tool is for advanced users familiar with the database and may break this website if used incorrectly.' ); ?></p>
    </div>

    <hr class="wp-header-end" />

    <div id="poststuff">

        <div id="post-body" class="metabox-holder">
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <form method="post">

                        <?php

                        // WP List
                        $acfe_options_list = new ACFE_Admin_Options_List();

                        // Prepare items
                        $acfe_options_list->prepare_items();

                        $acfe_options_list->search_box('Search', 'search');

                        $acfe_options_list->display();

                        ?>

                    </form>
                </div>
            </div>
        </div>

        <br class="clear" />

    </div>

</div>