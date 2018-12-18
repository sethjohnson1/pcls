<?php

/* PCLS WYLD Search Widget
By (blame) Seth Johnson for the Park County Library System
*/
add_action( 'widgets_init', 'wyldsearch_load_widgets' );
/** Register Widget **/
function wyldsearch_load_widgets() {
	register_widget( 'WYLD_Widget' );
}

/** Define the Widget as an extension of WP_Widget **/
class WYLD_Widget extends WP_Widget {
	public function __construct()
    {
        /* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_pclswyld', 'description' => 'WYLD Search for PCLS' );
		/* Widget control settings. */
		$control_ops = array( 'id_base' => 'pclswyld-widget' );
		/* Create the widget. */
		parent::__construct('pclswyld-widget', __('WYLD Search Widget'), $widget_ops, $control_ops);
    }

	function widget( $args, $instance ) {
		//the title is the name of the library - perhaps a bit misleading
		$title = apply_filters( 'widget_title', $instance['title'] );
		$pcls_widget_id=str_replace('-','_',$args['widget_id']);
		$pcls_wyld_header=$instance['header'];
		
		?>

			<script>
			function searchKeyPress_<?php echo $pcls_widget_id?>(e,id)
			{

				e = e || window.event;
				if (e.keyCode == 13)
				{
					document.getElementById('search_wyld_button-<?php echo $pcls_widget_id?>').click();
				}
			}
			


			
			function pclsWyldSearch_<?php echo $pcls_widget_id?>(pcls_wyld_admin_choice)
			{
				var pcls_wyld_end_url='';
				var pcls_wyld_library_option='';

				
				
				if (pcls_wyld_admin_choice==''){
					console.log('empty');
					//this is the NEW way from jQuery radio value
					pcls_wyld_library_option=jQuery('input[name=pcls_wyld_radio]:checked', '#wyld_library_value-<?php echo $pcls_widget_id?>').val();
				}
				else {
					pcls_wyld_library_option=pcls_wyld_admin_choice;
				}
				
				if (pcls_wyld_library_option=='powl'){
					pcls_wyld_end_url='PARK-POWL';
				}
				if (pcls_wyld_library_option=='meet'){
					pcls_wyld_end_url='PARK-MEET';
				}
				if (pcls_wyld_library_option=='park'){
					pcls_wyld_end_url='PARK';
				}
				if (pcls_wyld_library_option=='parklibs'){
					pcls_wyld_end_url='PARKLIBS';					pcls_wyld_library_option='park';
				}
				
				//console.log(pcls_wyld_library_option);
				//finally, do the redirect
				window.location='https://wyld.ent.sirsi.net/client/en_US/'+pcls_wyld_library_option+'/search/results?qu='+encodeURIComponent(document.getElementById('pcls_wyld_search_value-<?php echo $pcls_widget_id?>').value)+'&te=&lm='+pcls_wyld_end_url;
				
				
			}
			
			
			</script>

		<style>
		.search_wyld_button{
			border: 0;
			clip: rect(0, 0, 0, 0);
			height: 1px;
			margin: -1px;
			padding: 0;
			position: absolute;
			width: 1px;
		}
		</style>
		<?php ?>
		<div class="widget-wrap widget" style="padding:.6em">
		
		<?php if (!empty($pcls_wyld_header)){?>
		
		<h4 class="widget-title widgettitle">
		<?php echo $pcls_wyld_header;?>
		</h4>
		
		
		<?php } ?>
		
		
		<input type="text" style="font-size:1.2em; max-width:88%" id="pcls_wyld_search_value-<?php echo $pcls_widget_id?>" placeholder="<?php echo $instance['placeholder']; ?>" onkeypress="searchKeyPress_<?php echo $pcls_widget_id?>(event,'<?php echo $pcls_widget_id?>');"/>&nbsp;

		<input class="search_wyld_button" id="search_wyld_button-<?php echo $pcls_widget_id?>" type="button" value="Search"  style="font-size:1.2em;" onclick="pclsWyldSearch_<?php echo $pcls_widget_id?>('<?php echo $title;?>');" />
		
		<?php if (empty($title)){?>
		<p>
		<form action="" id="wyld_library_value-<?php echo $pcls_widget_id?>">
			<input type="radio" name="pcls_wyld_radio" value="parklibs" id="pcls_wyld_parklibs-<?php echo $pcls_widget_id?>" checked="checked">
			<label for="pcls_wyld_parklibs">Park County &nbsp;&nbsp;</label>
			<input type="radio" name="pcls_wyld_radio" value="park" id="pcls_wyld_park-<?php echo $pcls_widget_id?>">
			<label for="pcls_wyld_park">Cody &nbsp;&nbsp;</label>
			<input type="radio" name="pcls_wyld_radio" value="powl" id="pcls_wyld_powl-<?php echo $pcls_widget_id?>">
			<label for="pcls_wyld_powl">Powell &nbsp;&nbsp;</label>
			<input type="radio" name="pcls_wyld_radio" value="meet" id="pcls_wyld_meet-<?php echo $pcls_widget_id?>">
			<label for="pcls_wyld_meet">Meeteetse &nbsp;&nbsp;</label>
		</form>
		</p>
		<?php }?>

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
		return $instance;
	}
	function form( $instance ) {
		/* Set up some default widget settings. */
		$defaults = array( 'title' => '', 'placeholder'=>'Search Park County using WYLD','header'=>'WYLD Search');
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
	}
}