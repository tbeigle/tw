<div class="wrap">
<?php wp_enqueue_style('ee_rg_admin_template', plugins_url() ."/simple-contact-form/extension/readygraph/assets/css/upgrade.css");

echo '<div class="rg_info rg_message"><img src="'.plugins_url() .'/simple-contact-form/extension/readygraph/assets/Sign-Alert-icon.png" style="float: left;height: 50px;padding-right: 10px;"><a href="admin.php?page=readygraph-app"><button class="button-warning pure-button" style="float: right; margin-right: 15px;">Connect ReadyGraph</button></a><h3 style="color:white">Grow your site traffic faster: Activate Simple Contact Form\'s User Growth Engine (ReadyGraph)</h3><p style="color: whitesmoke">Promotion to New Users | Viral Signup Form | Site Update emails | Import Existing Users</p></div>'; ?>
  <div class="form-wrap">
    <div id="icon-edit" class="icon32 icon32-posts-post"><br>
    </div>
    <h2><?php _e('Simple contact form', 'simple-contact-form'); ?></h2>	
    <?php
	$gCF_title = get_option('gCF_title');
	$gCF_On_Homepage = get_option('gCF_On_Homepage');
	$gCF_On_Posts = get_option('gCF_On_Posts');
	$gCF_On_Pages = get_option('gCF_On_Pages');
	//$gCF_On_Search = get_option('gCF_On_Search');
	//$gCF_On_Archives = get_option('gCF_On_Archives');
	$gCF_On_SendEmail = get_option('gCF_On_SendEmail');
	$gCF_On_MyEmail = get_option('gCF_On_MyEmail');
	$gCF_On_Subject = get_option('gCF_On_Subject');
//	$gCF_On_App_Key = get_option('gCF_ReadyGraph_API');
	
	if (isset($_POST['gCF_form_submit']) && $_POST['gCF_form_submit'] == 'yes')
	{
		//	Just security thingy that wordpress offers us
		check_admin_referer('gCF_form_setting');
			
		$gCF_title = stripslashes($_POST['gCF_title']);
		$gCF_On_Homepage = stripslashes($_POST['gCF_On_Homepage']);
		$gCF_On_Posts = stripslashes($_POST['gCF_On_Posts']);
		$gCF_On_Pages = stripslashes($_POST['gCF_On_Pages']);
		//$gCF_On_Search = stripslashes($_POST['gCF_On_Search']);
		//$gCF_On_Archives = stripslashes($_POST['gCF_On_Archives']);
		$gCF_On_SendEmail = stripslashes($_POST['gCF_On_SendEmail']);
		$gCF_On_MyEmail = stripslashes($_POST['gCF_On_MyEmail']);
		$gCF_On_Subject = stripslashes($_POST['gCF_On_Subject']);
		$gCF_On_App_Key = stripslashes($_POST['gCF_On_App_Key']);
		
		update_option('gCF_title', $gCF_title );
		update_option('gCF_On_Homepage', $gCF_On_Homepage );
		update_option('gCF_On_Posts', $gCF_On_Posts );
		update_option('gCF_On_Pages', $gCF_On_Pages );
		//update_option('gCF_On_Search', $gCF_On_Search );
		//update_option('gCF_On_Archives', $gCF_On_Archives );
		update_option('gCF_On_SendEmail', $gCF_On_SendEmail );
		update_option('gCF_On_MyEmail', $gCF_On_MyEmail );
		update_option('gCF_On_Subject', $gCF_On_Subject );
		update_option('gCF_ReadyGraph_API', $gCF_On_App_Key );		
		?>
		<div class="updated fade">
			<p><strong><?php _e('Details successfully updated.', 'simple-contact-form'); ?></strong></p>
		</div>
		<?php
	}
	?>
    <form name="gCF_form" method="post" action="">
        <h3><?php _e('Widget setting', 'simple-contact-form'); ?></h3>
		
		<label for="tag-width"><?php _e('Widget title', 'simple-contact-form'); ?></label>
		<input name="gCF_title" type="text" value="<?php echo $gCF_title; ?>"  id="gCF_title" size="50" maxlength="150">
		<p><?php _e('Please enter your widget title.', 'simple-contact-form'); ?></p>
		
		<h3><?php _e('Widget dsiplay setting', 'simple-contact-form'); ?></h3>
		
		<label for="tag-width"><?php _e('Display on home page', 'simple-contact-form'); ?></label>
		<select name="gCF_On_Homepage" id="gCF_On_Homepage">
			<option value='YES' <?php if($gCF_On_Homepage == 'YES') { echo "selected='selected'" ; } ?>>Yes</option>
			<option value='NO' <?php if($gCF_On_Homepage == 'NO') { echo "selected='selected'" ; } ?>>No</option>
		</select>
		<p><?php _e('Do you want to show this widget in home page?', 'simple-contact-form'); ?></p>
		
	    <label for="tag-width"><?php _e('Display on posts', 'simple-contact-form'); ?></label>
		<select name="gCF_On_Posts" id="gCF_On_Posts">
			<option value='YES' <?php if($gCF_On_Posts == 'YES') { echo "selected='selected'" ; } ?>>Yes</option>
			<option value='NO' <?php if($gCF_On_Posts == 'NO') { echo "selected='selected'" ; } ?>>No</option>
		</select>
		<p><?php _e('Do you want to show this widget in all posts?', 'simple-contact-form'); ?></p>
		
		<label for="tag-width"><?php _e('Display on pages', 'simple-contact-form'); ?></label>
		<select name="gCF_On_Pages" id="gCF_On_Pages">
			<option value='YES' <?php if($gCF_On_Pages == 'YES') { echo "selected='selected'" ; } ?>>Yes</option>
			<option value='NO' <?php if($gCF_On_Pages == 'NO') { echo "selected='selected'" ; } ?>>No</option>
		</select>
		<p><?php _e('Do you want to show this widget in all pages?', 'simple-contact-form'); ?></p>
		
		<h3><?php _e('Email setting', 'simple-contact-form'); ?></h3>
		
		<label for="tag-width"><?php _e('Admin email', 'simple-contact-form'); ?></label>
		<select name="gCF_On_SendEmail" id="gCF_On_SendEmail">
			<option value='YES' <?php if($gCF_On_SendEmail == 'YES') { echo "selected='selected'" ; } ?>>Yes (Send email to admin)</option>
			<option value='NO' <?php if($gCF_On_SendEmail == 'NO') { echo "selected='selected'" ; } ?>>No (Dont send email to admin)</option>
		</select>
		<p><?php _e('Do you want to show this widget in all pages?', 'simple-contact-form'); ?></p>
		
		<label for="tag-width"><?php _e('Admin email', 'simple-contact-form'); ?></label>
		<input name="gCF_On_MyEmail" type="text" value="<?php echo $gCF_On_MyEmail; ?>"  id="gCF_title" size="50" maxlength="150">
		<p><?php _e('Please enter admin email address to receive mails.', 'simple-contact-form'); ?></p>
		
		<label for="tag-width"><?php _e('Email subject', 'simple-contact-form'); ?></label>
		<input name="gCF_On_Subject" type="text" value="<?php echo $gCF_On_Subject; ?>"  id="gCF_On_Subject" size="50" maxlength="150">
		<p><?php _e('Please enter admin email subject.', 'simple-contact-form'); ?></p>

<!--		<label for="tag-width"><?php //_e('ReadyGraph API', 'simple-contact-form'); ?></label>
		<input name="gCF_On_App_Key" type="text" value="<?php //echo $gCF_On_App_Key; ?>"  id="gCF_On_App_Key" size="50" maxlength="150">
		<p><?php //_e('Please enter ReadyGraph API detail.', 'simple-contact-form'); ?></p>
-->
		
		<input type="hidden" name="gCF_form_submit" value="yes"/>
		<p class="submit">
		<input name="gCF_submit" id="gCF_submit" class="button" value="<?php _e('Submit', 'simple-contact-form'); ?>" type="submit" />&nbsp;
		<a class="button" target="_blank" href="http://www.gopiplus.com/work/2010/07/18/simple-contact-form/"><?php _e('Help', 'simple-contact-form'); ?></a>
		<?php wp_nonce_field('gCF_form_setting'); ?>
		</p>
    </form>
  </div>
  <p class="description"><?php _e('Check official website for more information', 'simple-contact-form'); ?> 
  <a target="_blank" href="http://www.gopiplus.com/work/2010/07/18/simple-contact-form/"><?php _e('click here', 'simple-contact-form'); ?></a></p>
</div>