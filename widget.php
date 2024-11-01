<?php


/*Widget Liste */

class termine_liste_widget extends WP_Widget 
{

	//Einstellungen
	function __construct() {
		$widget_ops = array('description' => 'Liste der angelegten Termine');

		parent::__construct(false, __('Termine'),$widget_ops);
	}

	//Form Admin-Area
 	public function form($instance) 
	{
		$defaults = array(
			'title' => 'Termine',
			'cat' => '',
			'limit' => '5',
			'clock' => '',
			'city'  => 'on'
	    );
	    $instance = wp_parse_args((array)$instance, $defaults);

	    $title = $instance['title'];
	    $cat = $instance['cat'];
	    $limit = $instance['limit'];
	    $clock = $instance['clock'];
	    $city = $instance['city'];
	    ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php echo 'Titel:'; ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat'); ?>"><?php echo 'Termin-Kategorie:'; ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('cat'); ?>" name="<?php echo $this->get_field_name('cat'); ?>" type="text" value="<?php echo esc_attr($cat); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('limit'); ?>"><?php echo 'Anzahl:'; ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo esc_attr($limit); ?>" />
		</p>

		<p>
			 
			<?php
				if( esc_attr($clock) ) $checked = 'checked="checked"';
				else $checked = '';
			?>
			<input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id('clock'); ?>" name="<?php echo $this->get_field_name('clock'); ?>" <?=$checked?>>	
			<label for="<?php echo $this->get_field_id('clock'); ?>"><?php echo 'Zeige die Uhrzeit zu jedem Termin: '; ?></label>		
		</p>


		<p>
			
			<?php
				if( esc_attr($city) ) $checked = 'checked="checked"';
				else $checked = '';
			?>
			<input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id('city'); ?>" name="<?php echo $this->get_field_name('city'); ?>" <?=$checked?>>
			<label for="<?php echo $this->get_field_id('city'); ?>"><?php echo 'Zeige die Stadt zu jedem Termin: '; ?></label> 	
		</p>


		<?php
	}

	//Save form
	public function update($new_instance, $old_instance) 
	{
		$instance = array();
		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['cat'] = strip_tags($new_instance['cat']);
		$instance['limit'] = (int)$new_instance['limit'];
		$instance['clock'] = strip_tags($new_instance['clock']);
		$instance['city'] = strip_tags($new_instance['city']);

		return $instance;
	}

	//widget frontend
	public function widget($args, $instance) 
	{
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$cat = $instance['cat'];
		$limit = $instance['limit'];

		echo $before_widget;
		
		if(!empty($title))
		{
			echo $before_title . $title . $after_title;
		}
		

		?><ul><?php
			
			

					// Start your custom WP_query
					$my_query = new WP_query();
					$args = array('post_type'=>'termine',
							'orderby' => 'meta_value',
							'termine_type' => $cat,
	        				'meta_key' => '_zeitstempel',
	        			   'posts_per_page' => $limit,
							      'order' => 'ASC',
							      'meta_query' => array(
									array(
										'key' => '_zeitstempel',
										'value' => time(),
										'compare' => '>='
									)
							)
						);
					// Assign predefined $args to your query
					$my_query->query($args);
					
			 while ($my_query->have_posts()) : $my_query->the_post(); ?>
			
			<li <?php post_class('clearfix'); ?>>
			<?php
				if($instance['clock'] == 'on') $clock = true; else $clock = false;
				if($instance['city'] == 'on') $city = true; else $city = false;
			?>
				<?php  if(function_exists('the_termin_widget')) the_termin_widget($clock,$city);?>
				<h4><a href="<?php the_permalink();?>"><?php the_title(); ?></a></h4>
				<?php /* the_excerpt(); */?>
				

			</li>
			
			<?php endwhile; 

			// RESET THE QUERY
			wp_reset_query();
				
		

	
			
		
		
		?></ul><?php
		
		echo $after_widget;
	}
	
}

add_action('widgets_init', function()
{
     return register_widget('termine_liste_widget');
});




/*Widget Karte */

class termine_widget extends WP_Widget{
  function __construct() {
    $widget_ops = array('classname' => 'termine_widget', 'description' => 'Alle Termine auf einer Karte' );
    parent::__construct(false, __('Termine Widgets (Karte)'),$widget_ops);

  }
 
 
 	//Form Admin-Area
 	public function form($instance) 
	{
		$defaults = array(
			'title' => 'Terminkarte',
			'lon' => '11',
			'lat' => '50',
			'zoom' => '5'
	    );
	    $instance = wp_parse_args((array)$instance, $defaults);

	    $title = $instance['title'];
	    $lon = $instance['lon'];
	    $lat = $instance['lat'];
	    $zoom = $instance['zoom'];
	    ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php echo 'Titel:'; ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('lon'); ?>"><?php echo 'Längengrad:'; ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('lon'); ?>" name="<?php echo $this->get_field_name('lon'); ?>" type="text" value="<?php echo esc_attr($lon); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('lat'); ?>"><?php echo 'Breitengrad:'; ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('lat'); ?>" name="<?php echo $this->get_field_name('lat'); ?>" type="text" value="<?php echo esc_attr($lat); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('zoom'); ?>"><?php echo 'Zoom (1-9):'; ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('zoom'); ?>" name="<?php echo $this->get_field_name('zoom'); ?>" type="text" value="<?php echo esc_attr($zoom); ?>" />
		</p>
		<?php
	}
 
 
	//Save form
	public function update($new_instance, $old_instance) 
	{
		$instance = array();
		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['lat'] = strip_tags($new_instance['lat']);
		$instance['lon'] = strip_tags($new_instance['lon']);
		$instance['zoom'] = (int)$new_instance['zoom'];

		return $instance;
	}
	
//Frontend 
  function widget($args, $instance)  {
    extract($args, EXTR_SKIP);
 
   
    $title = apply_filters('widget_title', $instance['title']);
    $lat = $instance['lat'];
	$lon = $instance['lon'];
	$zoom = $instance['zoom'];
	
	 echo $before_widget;
	
    if(!empty($title))
		{
			echo $before_title . $title . $after_title;
		}
 

    // WIDGET CODE GOES HERE
    
  ?>
<div id="termin_karte" style="">

<div id="termin_karte_map" style="width:100%; height:350px;"></div>
<?php /* <h3 class="widget-title" style="position: relative;top: -260px;left:10px;padding-left:1em;width:120px;background:white;"><?=$title?></h3> */ ?>
<script>

var map = L.map('termin_karte_map',{ zoomControl:false }).setView([<?=$lat?>, <?=$lon?>], <?=$zoom?>);
mapLink = '<a href="http://openstreetmap.org">OpenStreetMap</a>';
L.tileLayer(
'http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
attribution: 'Map data © ' + mapLink,
maxZoom: 18,
}).addTo(map);

var greenIcon = L.icon({
  iconUrl: ' <?= plugin_dir_url( __FILE__ ) . 'map/images/icon.png'?>',
    shadowUrl: '<?= plugin_dir_url( __FILE__ ) . 'map/images/icon-shadow.png'?>',

    iconSize:     [21, 34], // size of the icon
    shadowSize:   [35, 34], // size of the shadow
    iconAnchor:   [11, 34], // point of the icon which will correspond to marker's location
    shadowAnchor: [10, 34],  // the same for the shadow
    popupAnchor:  [0, -30] // point from which the popup should open relative to the iconAnchor
});

var blueIcon = L.icon({
    iconUrl: ' <?= plugin_dir_url( __FILE__ ) . 'map/images/icon.png'?>',
    shadowUrl: '<?= plugin_dir_url( __FILE__ ) . 'map/images/icon-shadow.png'?>',

    iconSize:     [21, 34], // size of the icon
    shadowSize:   [35, 34], // size of the shadow
    iconAnchor:   [11, 34], // point of the icon which will correspond to marker's location
    shadowAnchor: [10, 34],  // the same for the shadow
    popupAnchor:  [0, -30] // point from which the popup should open relative to the iconAnchor
});
<?php

  // kommende Termine
	$termine = get_posts(array('post_type'=>'termine',
						'orderby' => 'meta_value',
        				'meta_key' => '_zeitstempel',
        			   'posts_per_page' => 50,
						      'order' => 'DESC',
						      'meta_query' => array(
								array(
									'key' => '_zeitstempel',
									'value' => time(),
									'compare' => '>='
								)
						)
					)
	);





	foreach ($termine AS $termin){

		//echo $termin->post_title;
		//echo "<br/>";
		//echo $termin->post_name;
		// print_r(get_post_meta( $termin->ID ));
		//print_r($termin);
		$lat = get_post_meta( $termin->ID, '_lat', true );
		$lon = get_post_meta( $termin->ID, '_lon', true );

    $ort = preg_replace('/,/', '\\,' , utf8_decode( get_post_meta( $termin->ID, '_geoshow', true ) . ' ' . get_post_meta( $termin->ID, '_geostadt', true ) ) );
    $tag = sprintf( '%04d%02d%02d' , 
        get_post_meta( $termin->ID, '_jahr', true ),
        get_post_meta( $termin->ID, '_monat', true ),
        get_post_meta( $termin->ID, '_tag', true ) );
    
    $stunde = sprintf( '%02d', get_post_meta( $termin->ID, '_stunde', true ) );
    $minute = sprintf( '%02d', get_post_meta( $termin->ID, '_minute', true ) );

    $dtstart = "{$tag}T{$stunde}{$minute}00Z";
    $stundebis = sprintf( '%02d', ($stunde + 1 ) );
    $dtend = "{$tag}T{$stundebis}{$minute}00Z";
    $titelcal = preg_replace('/,/', '\\,' , utf8_decode($termin->post_title) );
    $descriptioncal = preg_replace('/,/', '\\,' , utf8_decode($termin->post_name) );

		if( $lat == '' ) continue;
		//echo "$lat<hr/>";	
		?>
		var marker = L.marker([<?=$lat?>, <?=$lon?>],{icon:greenIcon}).addTo(map).bindPopup("<a href=\"/termin/<?="$termin->post_name"?>\" class=\"noicon\">&rarr; <?=wordwrap($termin->post_title, 20, '<br/>')?></a>");
		<?php



	}


 

  // Alle Beiträge
  $termine = get_posts(array('post_type'=>'post',         
            'posts_per_page' => 50,
            'order' => 'DESC'
         
          )
  );
  $tz=0;
  foreach ($termine AS $termin){

    $lat = get_post_meta( $termin->ID, '_lat', true );
    $lon = get_post_meta( $termin->ID, '_lon', true );

    if( $lat == '') continue;
    $tz++;
    if($tz > 7 ) break;
    //echo "$lat<hr/>"; 
    ?>
  var marker = L.marker([<?=$lat?>, <?=$lon?>],{icon:blueIcon}).addTo(map).bindPopup("<a href=\"/<?=$termin->post_name;?>\" class=\"noicon\">&rarr; <?=wordwrap($termin->post_title, 20, '<br/>')?></a>"); 

    <?php
     }
    ?>

</script>
</div>

<?php 
    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("termine_widget");') );?>