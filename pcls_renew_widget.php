<?php

/* PCLS WYLD Search Widget
By (blame) Seth Johnson for the Park County Library System
*/
add_action( 'widgets_init', 'pclsrenew_load_widgets' );
/** Register Widget **/
function pclsrenew_load_widgets() {
	register_widget( 'PCLS_Renew_Widget' );
}

/** Define the Widget as an extension of WP_Widget **/
class PCLS_Renew_Widget extends WP_Widget {
	public function __construct()
    {
        /* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_pclsrenew', 'description' => 'Renew widget for PCLS' );
		/* Widget control settings. */
		$control_ops = array( 'id_base' => 'pclsrenew-widget' );
		/* Create the widget. */
		parent::__construct('pclsrenew-widget', __('PCLS Renew Widget'), $widget_ops, $control_ops);
    }

	function widget( $args, $instance ) {
		//the title is the name of the library - perhaps a bit misleading
		//$title = apply_filters( 'widget_title', $instance['title'] );
		//$pcls_widget_id=str_replace('-','_',$args['widget_id']);
		//$pcls_wyld_header=$instance['header'];
		
		?>
		<div class="widget-wrap widget" style="padding:.6em">
		

		
		<h4 class="widget-title widgettitle">
		Renew Online
		</h4>
		<p>
		Use the barcode number on your library card (no spaces, no letters) and PIN (try the word wyld) to log into your account and renew your items. Select the link below.
		</p>
		<h3 align="center"><a href="http://wyld.sdp.sirsi.net/client/park/search/patronlogin/http:$002f$002fwyld.sdp.sirsi.net$002fclient$002fpark$002fsearch$002faccount$003f">Renew Your Items</a></h3>
		</div>
		<?php
		
	}
	
	//admin section
	function update( $new_instance, $old_instance ) {
	//	$instance = $old_instance;
		/* Strip tags (if needed) and update the widget settings. */
		//$instance['title'] = strip_tags( $new_instance['title'] );
	//	$instance['title']=$new_instance['title'];
	//	$instance['placeholder']=$new_instance['placeholder'];
	//	$instance['header']=$new_instance['header'];
	//	return $instance;
	}
	function form( $instance ) {
		/* Set up some default widget settings. */
		/*
		$defaults = array( 'title' => '', 'placeholder'=>'Search Park County using WYLD');
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		 
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">parklibs, park, powl, meet or BLANK for radio buttons </label>
		<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'placeholder' ); ?>">Placeholder text<br /></label>
		<input id="<?php echo $this->get_field_id( 'placeholder' ); ?>" name="<?php echo $this->get_field_name( 'placeholder' ); ?>" size=40 value="<?php echo $instance['placeholder']; ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'header' ); ?>">Header text<br /></label>
		<input id="<?php echo $this->get_field_id( 'header' ); ?>" name="<?php echo $this->get_field_name( 'header' ); ?>" size=40 value="<?php echo $instance['header']; ?>" />
		</p>
		<?php
		*/
	}
}