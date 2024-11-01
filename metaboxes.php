<?php
add_action( 'add_meta_boxes', 'termine_add_custom_box' );
function termine_add_custom_box() {
    add_meta_box(
        'termine_sectionid', 'Termininfos', 'termine_inner_custom_box','termine', 'side','high'
    );
}


function termine_inner_custom_box( $post ) {

  wp_nonce_field( 'my_wpcalendar_noncename', 'wpcalendar_noncename' );


 $from=get_post_meta(get_the_ID(), '_wpcal_from',true);
 echo '<input type="text" id="wpcal-from" placeholder="Beginn" name="wpc_from" value="'.$from.'">';



  echo '<br/>';
  $wert7=get_post_meta(get_the_ID(), '_bis',true);
  echo '<input type="text" id="termine_new_field7" name="wpc_until" placeholder="Ende" value="'.$wert7.'" />';

   $wert8=get_post_meta(get_the_ID(), '_geoshow',true);
  echo '<input  id="termine_new_field8" name="wpc_geoshow" placeholder="Angezeigte Adresse (ohne Stadt)" value="'.$wert8.'" style="width:90%" />';

   $wert11=get_post_meta(get_the_ID(), '_geostadt',true);
  echo '<input  id="termine_new_field11" name="wpc_geocity" placeholder="Stadt" value="'.$wert11.'" style="width:90%" />';
  echo '<br/><a href="' . plugin_dir_url( __FILE__ ) . '/map/?lat='.$wert6.'&lng='.$wert9.'&zoom='.$wert10.'" target="_blank" onclick="return popup(this.href);">Kartenausschnitt wählen</a>';

   if( 'on' == get_post_meta(get_the_ID(), '_showmap',true) ) $checked='checked="checked"';
   else $checked = '';
  echo '<br/><input type="checkbox" id="termine_new_field14" name="wpc_showmap"  '.$checked . '/><label for="termine_new_field14"> Karte anzeigen</label>';

  
  $wert12=get_post_meta(get_the_ID(), '_veranstalter',true);
  echo '<br><br>Veranstalter (optional):<br><input  id="termine_new_field12" name="wpc_veranstalter" placeholder="Veranstalter" value="'.$wert12.'" style="width:90%" />';

  $wert13=get_post_meta(get_the_ID(), '_veranstalterlnk',true);
  echo '<input  id="termine_new_field13" name="wpc_veranstalterlnk" placeholder="Link zum Veranstalter http://" value="'.$wert13.'" style="width:90%" />';

  echo '<br/><div style="display:none">';
  $wert6=get_post_meta(get_the_ID(), '_lat',true);
  echo '<input  id="termine_new_field6" name="wpc_lat" placeholder="Lat (ca. 48.5)" value="'.$wert6.'" style="width:15%" />';
  $wert9=get_post_meta(get_the_ID(), '_lon',true);
  echo '<input  id="termine_new_field9" name="wpc_lon" placeholder="Lon (ca. 11.4)" value="'.$wert9.'" style="width:15%" />';
  $wert10=get_post_meta(get_the_ID(), '_zoom',true);
  echo '<input  id="termine_new_field10" name="wpc_zoom" placeholder="Zoom (11-13)" value="'.$wert10.'" style="width:15%" />';
  
echo '</div>';


?>
<script type="text/javascript">
function popup (url) {

  fenster = window.open(url, "Landkarte", "width=650,height=300,resizable=yes");
  fenster.focus();
  return false;
}


function setPin( lat, lng, zoom, address, city){
	jQuery('#termine_new_field6').val( lat );
	jQuery('#termine_new_field9').val( lng );
	jQuery('#termine_new_field10').val( zoom );
	jQuery('#termine_new_field8').val( address );
	jQuery('#termine_new_field11').val( city );
}



</script>
<?php

/*
 | <a href="#" onClick="setPin(48.133906203913305,11.568281650543213,14,'Landesgeschäftsstelle, Sendlinger Str. 47', 'München')">LGS</a>
 */


	

}