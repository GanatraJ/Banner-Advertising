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
    
    if(isset( $_POST['ba-add'] ) )
	{
	    $ba_name = $_POST['ba-name'];
	    $ba_size = $_POST['ba-size'];
	    $ba_cat = $_POST['ba-category'];
	  
	    //echo ' aa '.$_POST['ba-img'].' aa '.$_FILES["ba-img"]["tmp_name"];
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
                $sql = "INSERT INTO $table_name (`name`, `banner_image`, `banner_size`, `post_cat`) VALUES ('$ba_name', '$ba_img', '$ba_size', '$ba_cat')";
    	        $wpdb->query($sql);
    	        
                $msg = '<div id="message" class="updated notice is-dismissible">
        				<p><strong>Banner Added Successfully.</strong></p>
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
?>
<div class="wrap">
    <h1>Add New Banner</h1>
    <div id="ajax-response"><?php echo $msg; ?></div>
    <p>Add a new Banner and add them to this site.</p>
    <form name="addbanner" id="addbanner" method="post" enctype="multipart/form-data">
        <table class="form-table">
            <tbody>
                <tr class="form-field form-required">
        		    <th scope="row"><label for="ba-name">Name</label></th>
        			<td><input name="ba-name" id="ba-name" type="text" value="<?php if(isset( $_POST['ba-name'] )){ echo $_POST['ba-name']; }?>" aria-required="true" required></td>
        		</tr>
                <tr class="form-field form-required">
        		    <th scope="row"><label for="ba-img">Banner Image</label></th>
        			<td><input name="ba-img" id="ba-img" type="file"></td>
        		</tr>
        		<tr class="form-field">
        			<th scope="row"><label for="ba-size">Banner size</label></th>
        			<td>
        			    <input type="radio" name="ba-size" value="728 x 90" <?php if(isset( $_POST['ba-size'] ) && ($_POST['ba-size'] == '728 x 90') ){ echo 'checked'; }?> >
        			        <label for="ba-size">728 x 90</label>
                        <input type="radio" name="ba-size" value="300 x 250" <?php if(isset( $_POST['ba-size'] ) && ($_POST['ba-size'] == '300 x 250') ){ echo 'checked'; }?> >
                            <label for="ba-size">300 x 250</label>
                        <input type="radio" name="ba-size" value="160 x 600" <?php if(isset( $_POST['ba-size'] ) && ($_POST['ba-size'] == '160 x 600') ){ echo 'checked'; }?> >
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
                                   if ( isset( $_POST['ba-category'] ) && ($category->term_id == $_POST['ba-category']) ) {
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
            <input type="submit" class="button button-primary" value="Add New Banner" name="ba-add" >
        </div>
    </form>
</div>