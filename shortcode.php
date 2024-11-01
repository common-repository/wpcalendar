<?php


add_shortcode( 'wpcalendar', 'wpcal_overview' );

function wpcal_overview( $atts ) {

		
	extract(shortcode_atts(array(
      		'archiv' => 'false',
			'kat' => '',
			'anzahl' => '50',
   ), $atts));
   
   	global $wp_query,$paged,$post;
	$temp = $wp_query;
	$wp_query= null;
	$wp_query = new WP_Query();


	if( $archiv === 'true'){
		$order='DESC'; $compare='<=';
	}else{
		$order='ASC'; $compare='>=';
	}
	
	
		$args = array(
					'post_type'=>'termine',
					'orderby' => 'meta_value',
	        		'meta_key' => '_zeitstempel',
	        		'termine_type' => $kat,
					'order' => $order,
					'posts_per_page' => $anzahl,
					'meta_query' => array(
									array(
										'key' => '_zeitstempel',
										'value' => time(),
										'compare' => $compare
									)
							)
					);
						
						
							
				$wp_query->query($args);
				ob_start();

				?>			

			<?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
			
			<article <?php post_class('clearfix'); ?>>
				 <?php if ( has_post_thumbnail() ): ?>
							<div class="postimg">
								
								<?php the_post_thumbnail('titelbild');  ?>
								
									
							</div>
							<?php endif; ?>
				<?php  if(function_exists('the_termin_short')) the_termin_short(); ?>
				<h2><a href="<?php the_permalink();?>"><?php the_title(); ?></a></h2>
				<?php the_excerpt();?>
				<a href="<?php the_permalink();?>" class="weiterlesen">Termindetails »</a>

			</article>
			
			<?php endwhile; ?>
					<?php $wp_query = null; $wp_query = $temp;
					$content = ob_get_contents();
					ob_end_clean();
					return $content;

}
