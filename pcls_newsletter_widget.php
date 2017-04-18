<?php

/* PCLS Newsletter widget
By (blame) Seth Johnson for the Park County Library System
*/

/* first define global array */

$pcls_newsletter_array=array(
	'newsletter_1'=>array(
	'title'=>'Cody Kids',
	'admin'=>'hbaker@parkcountylibrary.org'),
	'newsletter_2'=>array(
	'title'=>'Cody General',
	'admin'=>'vlivingston@parkcountylibrary.org'),
	'newsletter_3'=>array(
	'title'=>'Meeteetse',
	'admin'=>'djensen@parkcountylibrary.org'),
	'newsletter_4'=>array(
	'title'=>'Powell',
	'admin'=>'powell@parkcountylibrary.org'),
	'newsletter_5'=>array(
	'title'=>'',
	'admin'=>''),
	'newsletter_6'=>array(
	'title'=>'',
	'admin'=>''),
	'newsletter_7'=>array(
	'title'=>'',
	'admin'=>'')
);

/* add the Google recaptcha script  */

function frontend_recaptcha_script() {
	wp_register_script("recaptcha", "https://www.google.com/recaptcha/api.js");
	wp_enqueue_script("recaptcha");
	
	$plugin_url = plugin_dir_url(__FILE__);
	//wp_enqueue_style("no-captcha-recaptcha", $plugin_url ."style.css");
}
add_action("wp_enqueue_scripts", "frontend_recaptcha_script");


add_action( 'widgets_init', 'pclsnewsletter_load_widgets' );
/** Register Widget **/
function pclsnewsletter_load_widgets() {
	register_widget( 'PCLS_Newsletter_Widget' );
}

/** Define the Widget as an extension of WP_Widget **/
class PCLS_Newsletter_Widget extends WP_Widget {
	function PCLS_Newsletter_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_pclsnewsletter', 'description' => 'PCLS Newsletter sign-up' );
		/* Widget control settings. */
		$control_ops = array( 'id_base' => 'pclsnewsletter-widget' );
		/* Create the widget. */
		parent::__construct('pclsnewsletter-widget', __('PCLS Newsletter Widget'), $widget_ops, $control_ops);
		
	}
	function widget( $args, $instance ) {
		//the title is the name of the library - perhaps a bit misleading
		$title = apply_filters( 'widget_title', $instance['title'] );
		$pcls_widget_id=str_replace('-','_',$args['widget_id']);
		$pcls_newsletter_header=$instance['header'];
		
		
		
		global $pcls_newsletter_array;
		$letters=array();
		foreach ($pcls_newsletter_array as $k=>$news){
		$letters[$k]['title']=$instance['newsletter_title_'.$k];
		$letters[$k]['admin']=$instance['newsletter_admin_'.$k];
		}

		
		?>

		<div class="widget-wrap widget" style="padding:.6em">
		
		<?php if (!empty($pcls_newsletter_header)){?>
		
		<h4 class="widget-title widgettitle">
		<?php echo $pcls_newsletter_header;?>
		</h4>
		
		
		<?php } 

		//if(!empty($_POST['email'])) {
		if(!empty($_POST['g-recaptcha-response'])) {
			$recaptcha_secret = get_option('captcha_secret_key');
			$response = wp_remote_get("https://www.google.com/recaptcha/api/siteverify?secret=". $recaptcha_secret ."&response=". $_POST['g-recaptcha-response']);
			$response = json_decode($response["body"], true);
			if (true == $response["success"]) {
					//send the newsletters
				$msg='<span style="color:red">Please choose at least one</span>';
				foreach ($letters as $k=>$news){
					if (isset($_POST[$k])){
						$to = $news['admin'];
						$subject = 'Signup for: '.$news['title'];
						$message = 'signup Email: '.$_POST['email'];
						$headers = 'From: NO-REPLY@parkcountylibrary.org' . "\r\n";
			 
						if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
							mail($to, $subject, $message, $headers);
							$msg= '<span style="color:green">Your request has been sent!</span>';
						}else{
							$msg= "Please enter your email";
						}
					}
				}
				
			} else {
				$msg="Bots are not allowed to submit comments.";
				return null;
			}
			echo '<div style="background-color:white; padding:15px; border: 4px solid blue; margin: 4px; width:87%;"><h4><strong>'.$msg.'</strong></h4></div>';
		} 
		else {
			echo __("Bots are not allowed to submit comments. If you are not a bot then please enable JavaScript in browser.");
			//return null;
		}
		
		
		
		
		
		?>
		
	<form name="contactform" method="post">	
		
		<input type="email" style="font-size:1.2em; max-width:88%" id="" required="required" name="email" placeholder="<?php echo $instance['placeholder']; ?>" onkeypress=""/>&nbsp;<br />
		
		<?
		$br=0;
		//check the first box
		$checked='checked';
		foreach ($letters as $k=>$news){?>
		<?if (!empty($news['title'])){
		?>
		
		 <input type="checkbox" name="<?=$k?>" id="" <?=$checked?> />
		 <? echo '<span style="font-size:1.1em">'.$news['title'].'&nbsp;&nbsp</span>';
		 $br++;
		 $checked=false;
		// if ($br==2){
			$br=0;
			echo '<br />';
		 //}
		 
		 }
		 }?>
		
		<br />
		<div class="g-recaptcha" style="transform:scale(0.77);-webkit-transform:scale(0.77);transform-origin:0 0;-webkit-transform-origin:0 0;" data-sitekey="<?php echo get_option('captcha_site_key'); ?>"></div>
	<!-- input name="submit" type="submit" value="Submit Comment" -->
		<input class="" id="" type="submit" value="Submit"  style="font-size:1.2em;" />
		
</form>

		</div>
		<?php
	}
	
	//admin section
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		/* Strip tags (if needed) and update the widget settings. */
		//$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['title']=$new_instance['title'];
		$instance['placeholder']=$new_instance['placeholder'];
		$instance['header']=$new_instance['header'];
		global $pcls_newsletter_array;
		foreach ($pcls_newsletter_array as $k=>$news){
		$instance['newsletter_title_'.$k]=$new_instance['newsletter_title_'.$k];
		$instance['newsletter_admin_'.$k]=$new_instance['newsletter_admin_'.$k];
		}
		return $instance;
	}
	function form( $instance ) {
	
		global $pcls_newsletter_array;
		//print_r($pcls_newsletter_array);
		/* Set up some default widget settings. */
		$defaults = array( 'title' => '', 'placeholder'=>'E-mail address','header'=>'Newsletter Signup');
		foreach ($pcls_newsletter_array as $k=>$news){
			$defaults['newsletter_title_'.$k]=$news['title'];
			$defaults['newsletter_admin_'.$k]=$news['admin'];
		}
		
		
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
		<label for="<?php echo $this->get_field_id( 'placeholder' ); ?>">Placeholder text<br /></label>
		<input id="<?php echo $this->get_field_id( 'placeholder' ); ?>" name="<?php echo $this->get_field_name( 'placeholder' ); ?>" size=40 value="<?php echo $instance['placeholder']; ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'header' ); ?>">Header text<br /></label>
		<input id="<?php echo $this->get_field_id( 'header' ); ?>" name="<?php echo $this->get_field_name( 'header' ); ?>" size=40 value="<?php echo $instance['header']; ?>" />
		</p>
		<?foreach ($pcls_newsletter_array as $k=>$news){?>
		<strong><?=$k?></strong><br />
		<label for="<?php echo $this->get_field_id( 'newsletter_title_'.$k ); ?>">Title<br /></label>
		<input id="<?php echo $this->get_field_id( 'newsletter_title_'.$k ); ?>" name="<?php echo $this->get_field_name( 'newsletter_title_'.$k ); ?>" size=40 value="<?php echo $instance['newsletter_title_'.$k]; ?>" />
		
		
		<label for="<?php echo $this->get_field_id( 'newsletter_admin_'.$k ); ?>">Admin<br /></label>
		<input id="<?php echo $this->get_field_id( 'newsletter_admin_'.$k ); ?>" name="<?php echo $this->get_field_name( 'newsletter_admin_'.$k ); ?>" size=40 value="<?php echo $instance['newsletter_admin_'.$k]; ?>" />
		<br /><br />
		<?}?>

		<?php
	}
}