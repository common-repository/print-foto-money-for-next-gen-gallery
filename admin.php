<?php 
	$settings = get_option('printmoney_settings',true);
	if ( $_POST['img-print-btn-cats-update'] ) {
		
		$_POST['button_text'] = sanitize_text_field($_POST['button_text']);
		$_POST['button_text_color'] = sanitize_text_field($_POST['button_text_color']);
		$_POST['button_bg_color'] = sanitize_text_field($_POST['button_bg_color']);
		$_POST['affliateID'] = esc_url($_POST['affliateID']);
		$_POST['return_url'] = esc_url($_POST['return_url']);
		$_POST['position'] = sanitize_text_field($_POST['position']);
		$_POST['epage'] = array_map( 'absint',$_POST['epage']);
		$_POST['dimension'] = array_map( 'absint',$_POST['dimension']);
		$_POST['container'] = explode(',',sanitize_text_field($_POST['container']));
		
		update_option('printmoney_settings',$_POST);
		
		wp_redirect(esc_url(admin_url('?page=print-money&success')));
		exit();
	}
?>

<style type="text/css">
.style1 {color: #009900}
</style>

<div class="wrap">

	<?php if ( isset($_GET['success']) ) : ?>
	<div class="updated settings-error" id="setting-error-settings_updated"> 
<p><strong>Settings saved.</strong></p></div>
	<?php endif; ?>
    
	<form method="POST">
		<div class="dx-help-page">
		<div class="content alignleft">
            
			<h2 class='page-welcome'>Print Foto Money <span>Plugin</span></h2>
			<div id="dx-help-content">
	

			<section>
            <h2><span class="style1">Your site is all setup!</span> <a href="<?php echo esc_url('http://www.dotphoto.com/WPLand.asp?affiliateID='.site_url().'&amp;action=home'); ?>">Create your DotPhoto Account</a> for free to <a href="<?php echo esc_url('http://www.dotphoto.com/WPLand.asp?affiliateID='.site_url().'&amp;action=claim') ?>">view your earnings</a>.</h2>
            <p>Currently this plugin is compatible with over 16,300,247+ downloaded Wordpress themes. If you don't see it when you hover over an image, no sweat - <a href="mailto:printmoney@dotphoto.com">Email us</a> the name of your theme  and we'll get you going within 24 hours.</small></p>
			</section>
            
            <section>   
            <h2>Additional Settings</h2>
            <h3>Button Options</h3>
            <p>Please enter the text you want to display on the button <small>(ex: Buy Me)</small></p>
            <input type="text" style="width:90%;" value="<?php echo esc_html($settings['button_text']) ?>" name="button_text">
            <input class="button-primary" type="submit" value="Update" name="img-print-btn-cats-update" style="float:right;">
            
            <table cellpadding="10">
            	<tr>
                	<td>
                    	 <h3> Button Text Color </h3><br />
            			  <div class="textcolorwheel"><div></div><input type="text" name="button_text_color" value="<?php echo esc_html($settings['button_text_color']) ?>"/></div>
                    </td>
                    <td>
                    	<h3> Button Background Color </h3><br />
            			<div class="bgcolorwheel"><div></div><input type="text" name="button_bg_color" value="<?php echo esc_html($settings['button_bg_color']) ?>"/></div>
                    </td>
                </tr>
            </table>
           
            
            
            </section>  
            
            <section>      
				<h3>Affiliate ID</h3> 
                <p>Please enter the affiliate ID <small>(ex: www.printme.com)</small></p>
                <input type="text" style="width:90%;" placeholder="<?php echo site_url(); ?>" value="<?php echo esc_url($settings['affliateID']) ?>" input="" name="affliateID">
            	<input class="button-primary" type="submit" value="Update" name="img-print-btn-cats-update" style="float:right;">
            </section>
            <section>      
				<h3>Return Page</h3> 
                <p>Please enter the return page <small>(ex: www.printme.com)</small></p>
                <input type="text" style="width:90%;" placeholder="<?php echo site_url(); ?>" value="<?php echo esc_url($settings['return_url']) ?>" name="return_url">
            	<input class="button-primary" type="submit" value="Update" name="img-print-btn-cats-update" style="float:right;">
            </section>
            
            <section>      
				<h3>Set where the button should appear</h3> <br />
                <input type="radio" name="position" value="top-left" <?php echo esc_html($settings['position']) == 'top-left' ? 'checked="checked"' : '' ?> />Top Left &nbsp;&nbsp;
                <input type="radio" name="position" value="top-right" <?php echo esc_html($settings['position']) == 'top-right' ? 'checked="checked"' : '' ?>/>Top Right  &nbsp;&nbsp;
                <input type="radio" name="position" value="bottom-left" <?php echo esc_html($settings['position']) == 'bottom-left' ? 'checked="checked"' : '' ?>/>Bottom Left &nbsp;&nbsp;
                <input type="radio" name="position" value="bottom-rigt" <?php echo esc_html($settings['position']) == 'bottom-rigt' ? 'checked="checked"' : '' ?>/>Bottom Right &nbsp;&nbsp;
				<input class="button-primary" type="submit" value="Update" name="img-print-btn-cats-update" style="float:right;">
            </section>
            
            
            
            <section>
            	<h3>Button will appear on all pages except...</h3>
 				<ul>
				<?php 
					$new_query = new WP_Query(array(
						'post_type' => 'page',
						'posts_per_page' => -1
					));
					
					if ( $new_query->have_posts() ) :
						while( $new_query->have_posts() ) : $new_query->the_post();
							$settings['epage'] = !is_array($settings['epage']) ? array() : $settings['epage'];
							$checked =  in_array(get_the_ID(),array_map( 'absint', $settings['epage']),true) ? 'checked="checked"' : '';
							echo '<li><input type="checkbox" value="'.get_the_ID().'" name="epage[]" '.$checked.'>'.get_the_title().'</li>';
						endwhile;
					endif; wp_reset_postdata();
				?>
                </ul>
                <input class="button-primary" type="submit" value="Update" name="img-print-btn-cats-update" style="float:right;">
                <div style="clear:both"></div>
            </section>  
            
            <section>
            	<h3>Image Protection</h3>
                <br />
                <input type="checkbox" name="image_protection_visitors" value="1" <?php echo esc_html($settings['image_protection_visitors']) == 1 ? 'checked="checked"' : '' ?> /> Protect Image from Visitors &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="checkbox" name="image_protection_users" value="1" <?php echo esc_html($settings['image_protection_users']) == 1 ? 'checked="checked"' : '' ?> /> Protect Image from Logged-in Users                
            	<input class="button-primary" type="submit" value="Update" name="img-print-btn-cats-update" style="float:right;">
            </section>
            
             <section>
            	<h3>Disable Button on image size below</h3>
                <br />
                Width <input type="text" name="dimension[]" value="<?php echo esc_html($settings['dimension'][0]) ;?>" />  &nbsp;&nbsp
                Height <input type="text" name="dimension[]" value="<?php echo esc_html($settings['dimension'][1]) ;?>" />                
            	<input class="button-primary" type="submit" value="Update" name="img-print-btn-cats-update" style="float:right;">
            </section>
            
            <section>
            <h3>Advanced Settings - Manual Theme Compatibility</h3>
            <p>If the image is not showing on your theme, you'll have to add a class identifier for your theme. You can add multiple classes to target parts of your site Not sure what it is, try "blog" and update. <small>(other typical classes to try: blog,entry-content,entry-header)</small></p>
            <input type="text" style="width:90%;" value="<?php echo esc_html(implode(',',$settings['container'])) ?>" input="" name="container">
            <input class="button-primary" type="submit" value="Update" name="img-print-btn-cats-update" style="float:right;">
			</section>
            
            
            <section>
            <h2>PrintMoney Stats</h2>

<p>Track how many times a button has been clicked for an image. This doesn't mean sales but shows how well your buttons are performing click wise. Larger Images size are more acceptable prints.</p>

<div><h3>View/Withdraw Your <a href=<?php echo esc_url('http://www.dotphoto.com/WPLand.asp?affiliateID='.site_url().'&action=claim'); ?>>Earnings</a></h3></div><br />
            <table class="widefat">
                <thead>
                <tr>
                    <th>Image URL</th>
                    <th>Affiliate ID</th>
                    <th>Clicked Count</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $args=array(
                    'post_type' => 'image_count',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                );
				
                $my_query = new WP_Query($args);
                if( $my_query->have_posts() ) {
                    while ($my_query->have_posts()) : $my_query->the_post(); 
						$datas = explode('|',get_the_content());
					?>
                        <tr>
                            <td><a href="<?php echo esc_url(get_the_title()); ?>" target="_blank"><?php echo esc_url(get_the_title()); ?></a></td>
                            <td><?php echo esc_url($datas[0]); ?></td>
                            <td><?php echo esc_html($datas[1]); ?></td>
                        </tr>
                    <?php
                    endwhile;
                }
                wp_reset_query();
                ?>
                </tbody>
            </table>
		</div>
	</div>
	
</form></div>