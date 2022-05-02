<?php
/**
 * Plugin Name: test woo

 */
 add_action( 'woocommerce_product_options_general_product_data', 'test_adv_product_options');
function test_adv_product_options(){
 
	echo '<div class="options_group">';
 
	woocommerce_wp_select( array(
		'id'          => 'selector',
		'value'       => get_post_meta( get_the_ID(), 'selector', true ),
		'label'       => 'Select',
		'options'     => array( '' => 'Please select', 'rare' => 'Rare', 'frequent' => 'Frequent', 'unusual' => 'Unusual'),
	) );
 
	echo '</div>';
	
	echo '<div class="options_group">';
 

 
	echo '</div>';
	
		echo '<div class="options_group">';
 
	woocommerce_wp_text_input( array(
		'id'          => 'dater',
		'value'       => get_post_meta( get_the_ID(), 'dater', true ),
		'label'       => 'Date',
		'type'       => 'date',
	) );
 
	echo '</div>';
    echo '<div class="button-primary" onclick="reseter()">Reset</div><button class="button-primary" onclick="reseter()">subbmit</button>
    
    	  <script>
 function reseter() {

 document.getElementById("selector").value = ""
 document.getElementById("dater").value = ""
 }
 </script>
    
    ';
}

 add_action( 'woocommerce_product_options_general_product_data', 'test_adv_product_options' );
 
 
 
 
 add_action( 'woocommerce_process_product_meta', 'test_save_fields', 10, 2 );
function test_save_fields( $id, $post ){
 

		update_post_meta( $id, 'selector', $_POST['selector'] );
        update_post_meta( $id, 'dater', $_POST['dater'] );
 
}
 
add_filter( 'manage_edit-product_columns', 'show_product_order',15 );
function show_product_order($columns){

   //remove column
   unset( $columns['thumb'] );

   //add column
   $columns['imager'] = __( 'newimage'); 

   return $columns;
}
 add_action( 'manage_product_posts_custom_column', 'add_product_column_image', 10, 2 );

function add_product_column_image( $column, $postid ) {
    if ( $column == 'imager' ) {
        $atid = get_post_meta( $postid, 'post_banner_img', true );
       echo wp_get_attachment_image($atid, array('80', '50'));
      
    }
    
}
add_filter( 'manage_edit-product_columns', 'custom_product_column',11);
function custom_product_column($columns)
{
    $new_columns = array();
    foreach( $columns as $key => $column ){
        $new_columns[$key] =  $columns[$key];
        if( $key === 'thumb' )
            $new_columns['imager'] = __( 'Cost','woocommerce');
    }
    return $new_columns;
}
 
 
// Add Meta Box to post
add_action( 'add_meta_boxes', 'multi_media_uploader_meta_box' );

function multi_media_uploader_meta_box() {
	add_meta_box( 'my-post-box', 'Изображение', 'multi_media_uploader_meta_box_func', 'product', 'normal', 'high' );
}

function multi_media_uploader_meta_box_func($post) {
	$banner_img = get_post_meta($post->ID,'post_banner_img',true);
	?>
	
	
	

	
	
	<style type="text/css">
		.multi-upload-medias ul li .delete-img { position: absolute; right: 3px; top: 2px; background: aliceblue; border-radius: 50%; cursor: pointer; font-size: 14px; line-height: 20px; color: red; }
		.multi-upload-medias ul li { width: 120px; display: inline-block; vertical-align: middle; margin: 5px; position: relative; }
		.multi-upload-medias ul li img { width: 100%; }
		#publishing-action{display:none}
	</style>

	<table cellspacing="10" cellpadding="10">
		<tr>
			<td>Изображение</td>
			<td>
				<?php echo multi_media_uploader_field( 'post_banner_img', $banner_img ); ?>
			</td>
		</tr>
	</table>

	<script type="text/javascript">
		jQuery(function($) {

			$('body').on('click', '.wc_multi_upload_image_button', function(e) {
				e.preventDefault();

				var button = $(this),
				custom_uploader = wp.media({
					title: 'Выбрать изображение',
					button: { text: 'Выбрать изображение' },
					multiple: true 
				}).on('select', function() {
					var attech_ids = '';
					attachments
					var attachments = custom_uploader.state().get('selection'),
					attachment_ids = new Array(),
					i = 0;
					attachments.each(function(attachment) {
						attachment_ids[i] = attachment['id'];
						attech_ids += ',' + attachment['id'];
						if (attachment.attributes.type == 'image') {
							$(button).siblings('ul').append('<li data-attechment-id="' + attachment['id'] + '"><a href="' + attachment.attributes.url + '" target="_blank"><img class="true_pre_image" src="' + attachment.attributes.url + '" /></a><i class=" dashicons dashicons-no delete-img"></i></li>');
						} else {
							$(button).siblings('ul').append('<li data-attechment-id="' + attachment['id'] + '"><a href="' + attachment.attributes.url + '" target="_blank"><img class="true_pre_image" src="' + attachment.attributes.icon + '" /></a><i class=" dashicons dashicons-no delete-img"></i></li>');
						}

						i++;
					});

					var ids = $(button).siblings('.attechments-ids').attr('value');
					if (ids) {
						var ids = ids + attech_ids;
						$(button).siblings('.attechments-ids').attr('value', ids);
					} else {
						$(button).siblings('.attechments-ids').attr('value', attachment_ids);
					}
					$(button).siblings('.wc_multi_remove_image_button').show();
				})
				.open();
			});

			$('body').on('click', '.wc_multi_remove_image_button', function() {
				$(this).hide().prev().val('').prev().addClass('button').html('Добавить');
				$(this).parent().find('ul').empty();
				return false;
			});

		});

		jQuery(document).ready(function() {
			jQuery(document).on('click', '.multi-upload-medias ul li i.delete-img', function() {
				var ids = [];
				var this_c = jQuery(this);
				jQuery(this).parent().remove();
				jQuery('.multi-upload-medias ul li').each(function() {
					ids.push(jQuery(this).attr('data-attechment-id'));
				});
				jQuery('.multi-upload-medias').find('input[type="hidden"]').attr('value', ids);
			});
		})
	</script>

	<?php
}


function multi_media_uploader_field($name, $value = '') {
	$image = '">Выбрать';
	$image_str = '';
	$image_size = 'full';
	$display = 'none';
	$value = explode(',', $value);

	if (!empty($value)) {
		foreach ($value as $values) {
			if ($image_attributes = wp_get_attachment_image_src($values, $image_size)) {
				$image_str .= '<li data-attechment-id=' . $values . '><a href="' . $image_attributes[0] . '" target="_blank"><img src="' . $image_attributes[0] . '" /></a><i class="dashicons dashicons-no delete-img"></i></li>';
			}
		}

	}

	if($image_str){
		$display = 'inline-block';
	}

	return '<div class="multi-upload-medias"><ul>' . $image_str . '</ul><a href="#" class="wc_multi_upload_image_button button' . $image . '</a><input type="hidden" class="attechments-ids ' . $name . '" name="' . $name . '" id="' . $name . '" value="' . esc_attr(implode(',', $value)) . '" /><a href="#" class="wc_multi_remove_image_button button" style="display:inline-block;display:' . $display . '">Убрать</a></div>';
}

// Save Meta Box values.
add_action( 'save_post', 'wc_meta_box_save' );

function wc_meta_box_save( $post_id ) {
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;	
	}

	if( !current_user_can( 'edit_post' ) ){
		return;	
	}
	
	if( isset( $_POST['post_banner_img'] ) ){
		update_post_meta( $post_id, 'post_banner_img', $_POST['post_banner_img'] );
	}
}




function my_ajax_aplyd(){
    
$name = $_POST['name'];
$selector = $_POST['selector'];
$dater = $_POST['dater'];
$post_data = array(
	'post_title'    => $name,
	'post_status'   => 'publish',
	'post_type' => 'product',
);



 $support_title = !empty($_POST['supporttitle']) ? 
       $_POST['supporttitle'] : 'Support Title';

        if (!function_exists('wp_handle_upload')) {
           require_once(ABSPATH . 'wp-admin/includes/file.php');
       }
      // echo $_FILES["upload"]["name"];
      $uploadedfile = $_FILES['file'];
      $upload_overrides = array('test_form' => false);
      $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

    // echo $movefile['url'];
      if ($movefile && !isset($movefile['error'])) {
         echo "File Upload Successfully";
    } else {
        /**
         * Error generated by _wp_handle_upload()
         * @see _wp_handle_upload() in wp-admin/includes/file.php
         */
        echo $movefile['error'];
    }


$url = $movefile['url'];

//$attach_id = wp_insert_attachment($movefile, $_FILES['file'] );
//$attach_data = wp_generate_attachment_metadata( $attach_id, $url);
//wp_update_attachment_metadata( $attach_id, $attach_data );


 $wp_filetype = wp_check_filetype(basename($_FILES['file']['name']), null );
 
  $attachment = array(
     'post_mime_type' => $wp_filetype['type'],
     'post_title' => preg_replace('/\.[^.]+$/', '', basename($_FILES['file']['name'])),
     'post_content' => '',
     'post_status' => 'inherit'
  );
  $attach_id = wp_insert_attachment( $attachment, $url, 37 );
  // you must first include the image.php file
  // for the function wp_generate_attachment_metadata() to work
  require_once(ABSPATH . "wp-admin" . '/includes/image.php');
  $attach_data = wp_generate_attachment_metadata( $attach_id, $url );
  wp_update_attachment_metadata( $attach_id,  $attach_data );



$post_id = wp_insert_post(wp_slash($post_data) );
update_post_meta($post_id, 'selector', $selector , true);
update_post_meta($post_id, 'dater', $dater  , true);
update_post_meta($post_id, 'post_banner_img', $attach_id , true);
echo $movefile['url'];

die();
};
add_action( 'wp_ajax_aplyd', 'my_ajax_aplyd' );










add_shortcode( 'testform', 'foobar_shortcode' );

function foobar_shortcode( $atts ){
	return '

<form method="post" id="form"style="margin-top: 100px;">

<div >
   <input id="name" placeholder="name" type="text" size="40">

   <input id="price" placeholder="price" type="text" size="40">
   
   <select style="" id="selector"  >
			<option value="" selected="selected">Please select</option><option value="rare">Rare</option><option value="frequent">Frequent</option><option value="unusual">Unusual</option>		</select>
<input type="date"  id="dater" value="">
<input type="file" name="file" id="file">




<div class="aplyd">Отправить</div>
</div>




 </form>
 
  <script>


jQuery(function($){

$(".aplyd").click(function(){

var name = document.getElementById("name").value;
var price = document.getElementById("price").value;
var selector = document.getElementById("selector").value;
var dater = document.getElementById("dater").value;
 var file = jQuery("#file").prop("files")[0];

  		

        
        var fd = new FormData();
        fd.append( "file", file )
        fd.append( "action", "aplyd" )
        fd.append( "name", name )
        fd.append( "price", price )
        fd.append( "dater", dater )
        fd.append( "selector", selector )

$.ajax({
        contentType: false,
        processData: false,
type:"post",
data:fd,
url:"/wp-admin/admin-ajax.php",
success: function(response){ 
console.log(response)
            }

});

});

});





</script>
 ';
 }
