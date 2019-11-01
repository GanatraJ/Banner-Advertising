<?php 
    global $wpdb;
    $table_name = $wpdb->prefix . 'banner';
    
    //get post categories
    $cat_args = array(
        'parent'  => 0,
        'hide_empty' => 0,
        'order'    => 'ASC',
    );
    $categories = get_categories($cat_args);
    
    
    if(isset( $_GET['id'] ) ){
	    $id = $_GET['id'];
        if(isset( $_POST['ba-update'] ) ){	
	        $ba_name = $_POST['ba-name'];
	        $ba_size = $_POST['ba-size'];
	        $ba_cat = $_POST['ba-category'];
	        
	        
	        if(empty($_FILES["ba-img"]["tmp_name"])){
	            //echo '11';
	            //echo $wpdb->last_query;
                $wpdb->update($table_name, array('name'=>"$ba_name", 'banner_size'=>"$ba_size", 'post_cat'=>"$ba_cat"), array('id'=>$id));
                        
                $msg = '<div id="message" class="updated notice is-dismissible">
                		    <p><strong>Banner Updated Successfully.</strong></p>
                			<button type="button" class="notice-dismiss">
                				<span class="screen-reader-text">Dismiss this notice.</span>
                			</button>
                		</div>';
	        }else{
	            //echo '22';
    	        $check = getimagesize($_FILES["ba-img"]["tmp_name"]);
    	        
    	        if($check !== false){
    	            
    	            $image_width = $check[0];
                    $image_height = $check[1];
                
                    $image = $_FILES['ba-img']['tmp_name'];
                    $ba_img = addslashes(file_get_contents($image));
                    
                    $dim = explode(" x ",$ba_size);
                    $width = $dim[0];
                    $height = $dim[1];
                    
                    if(($width == $image_width) && ($height == $image_height)){
                        
                        //echo $wpdb->last_query;
                        //$wpdb->update($table_name, array('name'=>"$ba_name", 'banner_image'=>"$ba_img", 'banner_size'=>"$ba_size", 'post_cat'=>"$ba_cat"), array('id'=>$id));
                        
                        $sql = "UPDATE $table_name SET `name`='$ba_name',`banner_image`='$ba_img',`banner_size`='$ba_size',`post_cat`='$ba_cat' WHERE id = $id";
    	                $wpdb->query($sql); 
                        
                        $msg = '<div id="message" class="updated notice is-dismissible">
                			<p><strong>Banner Updated Successfully.</strong></p>
                			<button type="button" class="notice-dismiss">
                				<span class="screen-reader-text">Dismiss this notice.</span>
                			</button>
                		</div>';
                    }else{
                        $msg = '<div id="message" class="updated notice is-dismissible">
            				<p><strong>Check Banner Image size</strong></p>
            				<button type="button" class="notice-dismiss">
            					<span class="screen-reader-text">Dismiss this notice.</span>
            				</button>
            			</div>';
                    }
    	        }
	        }
        }
	    $result = $wpdb->get_results ( "SELECT * FROM ".$table_name." WHERE id = $id " );
        foreach ( $result as $banner ){
            $ba_name  = $banner->name;
	        $ba_img = $banner->banner_image;
	        $ba_size = $banner->banner_size;
    	    $ba_cat = $banner->post_cat;
        }		
    }
?>
<div class="wrap">
    <h1>Edit Banner</h1>
    <div id="ajax-response"><?php echo $msg; ?></div>
    <form name="editbanner" id="editbanner" method="post" enctype="multipart/form-data">
        <table class="form-table">
            <tbody>
                <tr class="form-field form-required">
        		    <th scope="row"><label for="ba-name">Title</label></th>
        			<td><input name="ba-name" id="ba-name" type="text" value="<?php echo $ba_name; ?>" aria-required="true" required></td>
        		</tr>
        		<tr class="form-field form-required">
        		    <th scope="row"><label for="ba-img">Banner Image</label></th>
        			<td><img src="data:image/jpeg;base64,<?php echo base64_encode($ba_img)?>" style="max-width:100%;" /><br/><input name="ba-img" id="ba-img" type="file"></td>
        		</tr>
        		<tr class="form-field">
        			<th scope="row"><label for="ba-size">Banner size</label></th>
        			<td>
        			    <input type="radio" name="ba-size" value="728 x 90" <?php if( $ba_size == '728 x 90' ){ echo 'checked'; }?> >
        			        <label for="ba-size">728 x 90</label>
                        <input type="radio" name="ba-size" value="300 x 250" <?php if( $ba_size == '300 x 250' ){ echo 'checked'; }?> >
                            <label for="ba-size">300 x 250</label>
                        <input type="radio" name="ba-size" value="160 x 600" <?php if( $ba_size == '160 x 600' ){ echo 'checked'; }?> >
                            <label for="ba-size">160 x 600</label>
        			</td>
        		</tr>
        		<tr class="form-field">
        			<th scope="row"><label for="ba-category">Category</label></th>
        			<td>
        			    <select name="ba-category" id="ba-category">
        			        <option>Select Categories</option>
        			        <?php 
        			            foreach($categories as $category) {
                                   if ( $category->term_id == $ba_cat ) {
                                        echo "<option value='".$category->term_id."' selected>".get_cat_name($category->term_id)."</option>";	
                                    }else{
                                        echo "<option value='".$category->term_id."'>".get_cat_name($category->term_id)."</option>";
                                    }
                                    
                                }
                                
                            ?>
        			    </select>
        			</td>
        		</tr>
        	</tbody>
        </table>
        <div class="edit-tag-actions">
            <input type="submit" class="button button-primary" value="Update Banner" name="ba-update" >
        </div>
    </form>
</div>