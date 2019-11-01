<?php
    global $wpdb;
    $table_name = $wpdb->prefix . 'banner';
    
    $total = $wpdb->get_var("SELECT COUNT(*) FROM (SELECT * FROM ".$table_name." LIMIT 0,150) AS a");
    $post_per_page = 20;
    $page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
    $offset = ( $page * $post_per_page ) - $post_per_page;
    
    //draft / published post total count
    $draft = $wpdb->get_var("SELECT COUNT(*) FROM (SELECT * FROM ".$table_name." WHERE status = 0) AS a");
    $published = $wpdb->get_var("SELECT COUNT(*) FROM (SELECT * FROM ".$table_name." WHERE status = 1) AS a");

    $banners = $wpdb->get_results("SELECT * FROM ".$table_name." ORDER BY id ASC LIMIT ${offset}, ${post_per_page}");
    $totalbanners = $wpdb->num_rows;
?>
<div id="wpbody" role="main">
	<div id="wpbody-content" aria-label="Main content" tabindex="0">
		<div class="wrap">
		    <div class="msg">
		        <div id="message" class="updated notice is-dismissible" style="display:none;">
                    <p></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>
		    </div>
			<h1 class="wp-heading-inline">Banners</h1>
			<a href="<?php echo admin_url( 'admin.php?page=banner_insert', 'https' ); ?>" class="page-title-action">Add New Banner</a>
			<hr class="wp-header-end">
			<h2 class="screen-reader-text">Filter posts list</h2>
			<ul class="subsubsub">
            	<li class="all"><a href="<?php echo admin_url( 'admin.php?page=banner_advertise', 'https' ); ?>" class="current" aria-current="page">All <span class="count">(<?php echo $total;?>)</span></a> |</li>
            	<?php if( !empty($published) && ($published !== NULL)) { ?>
            	    <li class="publish"><a href="<?php echo admin_url( 'admin.php?page=banner_advertise&status=1', 'https' ); ?>">Published <span class="count">(<?php echo $published;?>)</span></a> |</li>
            	<?php } ?>
            	<?php if( !empty($draft) && ($draft !== NULL) ) {?>
            	    <li class="draft"><a href="<?php echo admin_url( 'admin.php?page=banner_advertise&status=0', 'https' ); ?>">Draft <span class="count">(<?php echo $draft;?>)</span></a></li>
            	<?php } ?>
            </ul>
            <form id="posts-filter" method="get">
                <div class="tablenav top">
                    <div class="tablenav-pages one-page">
                        <span class="displaying-num"><?php echo $totalbanners; ?> item</span>
                        <?php echo '<span class="pagination-links" style="display:inline-block;">';
                                echo paginate_links( array(
                                    'base'               => add_query_arg( 'cpage', '%#%' ),
                                	'format'             => '',
                                	'total'              =>  ceil($total / $post_per_page),
                                	'current'            => $page,
                                	'show_all'           => false,
                                	'end_size'           => 1,
                                	'mid_size'           => 2,
                                	'prev_next'          => true,
                                    'prev_text' => __('&laquo;'),
                                    'next_text' => __('&raquo;'),
                                	'type'               => 'plain',
                                	'add_args'           => false,
                                	'add_fragment'       => '',
                                	'before_page_number' => '',
                                	'after_page_number'  => ''
                                ));
                            echo '</span>'; ?>
                    </div>
		            <br class="clear">
                </div><!-- tablenav top -->
                <h2 class="screen-reader-text">Banners</h2>
                <table class="wp-list-table widefat fixed striped posts">
				    <thead>
				        <tr>
						    <td id="cb" class="manage-column column-cb check-column">
						        <label class="screen-reader-text" for="cb-select-all-1">Select All</label>
						        <input id="cb-select-all-1" type="checkbox">
						    </td>
						    <th scope="col" id="name" class="manage-column column-title column-primary sortable desc">
						        <a href=""><span>Name</span><span class="sorting-indicator"></span></a>
						    </th>
						    <th scope="col" id="image" class="manage-column column-location">Banner Image</th>
						    <th scope="col" id="size" class="manage-column column-location">Banner Size</th>
						    <th scope="col" id="categories" class="manage-column column-categories">Post Category</th>
						</tr>
				    </thead>
				    <tbody id="the-list">
				        <?php 
				            
                            foreach($banners as $banner){
                        ?>
        				<tr id="<?php echo $banner->id; ?>" class="iedit author-self level-0 post-<?php echo $banner->id; ?> status-<?php echo $banner->status; ?> format-standard hentry category-<?php echo $categories[0]->name; ?> entry">
        				    <th scope="row" class="check-column">			
        						<label class="screen-reader-text" for="cb-select-<?php echo $banner->id; ?>">Select <?php echo $banner->name; ?></label>
        						<input id="cb-select-<?php echo $banner->id; ?>" type="checkbox" name="post[]" value="<?php echo $banner->id; ?>">
        						<div class="locked-indicator">
        							<span class="locked-indicator-icon" aria-hidden="true"></span>
        							<span class="screen-reader-text">“<?php echo $banner->name; ?>” is locked</span>
        						</div>
        					</th>
        					<td class="title column-title has-row-actions column-primary page-title" data-colname="Title">
        						<div class="locked-info">
        							<span class="locked-avatar"></span> <span class="locked-text"></span>
        						</div>
        						<strong>
        							<a class="row-title" href="#" aria-label="“<?php echo $banner->title; ?>” (Edit)"><?php echo $banner->name; ?></a>
        						</strong>
        						<div class="row-actions">
        							<span class="edit">
        								<a href="<?php echo admin_url( 'admin.php?page=banner_edit&id='.$banner->id, 'https' ); ?>" aria-label="Edit “<?php echo $banner->name; ?>”">Edit</a> | 
        							</span>
        							<span class="trash">
        								<a href="" class="submitdelete ba-del-btn" data-id="<?php echo $banner->id; ?>" data-table="<?php echo $table_name; ?>" data-etype="Banner" aria-label="Move “<?php echo $banner->name; ?>” to the Trash">Move to Trash</a> | 
        							</span>
        							
        						</div>
    						</td>
        					<td class="image column-image" data-colname="image">
        						<a><?php if(!empty($banner->banner_image)){echo /*$banner->banner_size;*/'<img src="data:image/jpeg;base64,'.base64_encode($banner->banner_image).'" width="75" height="75" >'; } ?></a>
        					</td>
        					<td class="size column-image" data-colname="size">
        					    <a><?php echo $banner->banner_size; ?></a>
        					</td>
        					<td class="categories column-categories" data-colname="Categories">
        						<a><?php echo get_cat_name($banner->post_cat); ?></a>
        					</td>
        				</tr>
        				<?php  } ?>
				    </tbody>
				    <tfoot>
				        <tr>
						    <td id="cb" class="manage-column column-cb check-column">
						        <label class="screen-reader-text" for="cb-select-all-1">Select All</label>
						        <input id="cb-select-all-1" type="checkbox">
						    </td>
						    <th scope="col" id="name" class="manage-column column-title column-primary sortable desc">
						        <a href=""><span>Name</span><span class="sorting-indicator"></span></a>
						    </th>
						    <th scope="col" id="image" class="manage-column column-location">Banner Image</th>
						    <th scope="col" id="size" class="manage-column column-location">Banner Size</th>
						    <th scope="col" id="categories" class="manage-column column-categories">Post Category</th>
						 </tr>
				    </tfoot>
				</table>
                <div class="tablenav bottom">
                    <div class="tablenav-pages one-page">
                        <span class="displaying-num"><?php echo $totalbanners; ?> item</span>
                        <?php echo '<span class="pagination-links" style="display:inline-block;">';
                                echo paginate_links( array(
                                    'base'               => add_query_arg( 'cpage', '%#%' ),
                                	'format'             => '',
                                	'total'              =>  ceil($total / $post_per_page),
                                	'current'            => $page,
                                	'show_all'           => false,
                                	'end_size'           => 1,
                                	'mid_size'           => 2,
                                	'prev_next'          => true,
                                    'prev_text' => __('&laquo;'),
                                    'next_text' => __('&raquo;'),
                                	'type'               => 'plain',
                                	'add_args'           => false,
                                	'add_fragment'       => '',
                                	'before_page_number' => '',
                                	'after_page_number'  => ''
                                ));
                            echo '</span>'; ?>
                    </div>
		            <br class="clear">
                </div><!-- tablenav bottom -->
            </form>
		</div>
	</div>
</div>
<style type="text/css">
	#toplevel_page_banner_advertise .wp-submenu-wrap li:nth-child(4),
	#toplevel_page_banner_advertise .wp-submenu-wrap li:nth-child(5){
		display:none;
	}
</style>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('.ba-del-btn').click(function(e) {
	    e.preventDefault();
	    var del_id = $(this).data('id');
	    var del_tbl = $(this).data('table');
	    var del_type = $(this).data('etype');
        
        $.ajax({
            type: "POST",
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: { "del_id" : del_id,"del_tbl" : del_tbl,"del_type" : del_type,"action":"ba_delaction" },
            success: function(data){
                
                $('#ajax-response').html(data);
                setTimeout(function(){
                   location.reload(); 
                }, 2000); 
            }
        });
	});	
});
</script>