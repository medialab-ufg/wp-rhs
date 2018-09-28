<?php get_header('full'); ?>

<main role="main" class="max-large margin-one-column">
    <div class="row container">
        <div class="col-xs-12 col-sm mx-sm-auto">
            <div class="form-inline mt-4 tainacan-collection-list--simple-search justify-content-between">
                
                <div class="dropdown dropdown-sorting btn-group">
                    <button class="btn bg-white dropdown-toggle text-black" type="button" id="dropdownMenuSorting" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php _e('Sorting', 'tainacan-interface'); ?> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuSorting">
                        <li><a class="dropdown-item text-black <?php tainacan_active(get_query_var('orderby'), 'date'); ?>" href="<?php echo add_query_arg('orderby', 'date'); ?>"><?php _e('Creation date', 'tainacan-interface'); ?></a></li>
                        <li><a class="dropdown-item text-black <?php tainacan_active(get_query_var('orderby'), 'title'); ?>" href="<?php echo add_query_arg('orderby', 'title'); ?>"><?php _e('Title', 'tainacan-interface'); ?></a></li>
                    </ul>
                </div>
                    
                <a class="btn btn-white bg-white margin-one-column-left <?php tainacan_active(get_query_var('order'), 'ASC'); ?>" href="<?php echo add_query_arg('order', 'ASC'); ?>">
                    <i class="mdi mdi-sort-ascending"></i>
                </a>
                <a class="btn btn-white bg-white <?php tainacan_active(get_query_var('order'), 'DESC'); ?>" href="<?php echo add_query_arg('order', 'DESC'); ?>">
                    <i class="mdi mdi-sort-descending"></i>
                </a>
                
                <div class="dropdown margin-one-column-left dropdown-viewMode">
                    <button class="btn bg-white dropdown-toggle text-black" type="button" id="dropdownMenuViewMode" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="mdi mdi-view-list text-oslo-gray"></i>
                        <span class="d-none d-md-inline"><?php _e('View Mode', 'tainacan-interface'); ?></span> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuViewMode">
                        <li><a class="dropdown-item text-black <?php tainacan_active(get_query_var('tainacan_collections_viewmode'), 'cards'); ?>" href="<?php echo add_query_arg('tainacan_collections_viewmode', 'cards'); ?>"><?php _e('Cards', 'tainacan-interface'); ?></a></li>
                        <li><a class="dropdown-item text-black <?php tainacan_active(get_query_var('tainacan_collections_viewmode'), 'grid'); ?>" href="<?php echo add_query_arg('tainacan_collections_viewmode', 'grid'); ?>"><?php _e('Thumbnails', 'tainacan-interface'); ?></a></li>
                        <li><a class="dropdown-item text-black <?php tainacan_active(get_query_var('tainacan_collections_viewmode'), 'table'); ?>" href="<?php echo add_query_arg('tainacan_collections_viewmode', 'table'); ?>"><?php _e('Table', 'tainacan-interface'); ?></a></li>
                    </ul>
                </div>
                
                <form role="search" class="ml-auto" method="get" id="tainacan-collection-search">
                    <input type="hidden" name="orderby" value="<?php echo get_query_var('orderby'); ?>" />
                    <input type="hidden" name="order" value="<?php echo get_query_var('order'); ?>" />
                    <input type="hidden" name="tainacan_collections_viewmode" value="<?php echo get_query_var('tainacan_collections_viewmode'); ?>" />
                    <div class="input-group">
                        <input class="form-control rounded-0" type="search" name="s" value="<?php echo get_query_var('s'); ?>" placeholder="<?php _e('Search collections', 'tainacan-interface'); ?>" />
                        <span class="input-group-btn">
                            <button class="btn btn-default border border-left-0 rounded-0 bg-white text-midnight-blue" type="submit">
                                <i class="mdi mdi-magnify" style="line-height: inherit;"></i>
                            </button>
                        </span>
                    </div>
                </form>
            </div>

            <?php get_template_part('partes-templates/loop-tainacan-collection', get_query_var('tainacan_collections_viewmode')); ?>
        </div>
    </div><!-- /.row -->
</main>
<?php get_footer('full'); ?>