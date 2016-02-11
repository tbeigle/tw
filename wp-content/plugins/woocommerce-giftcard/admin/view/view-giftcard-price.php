<?php 
global $post;

function magenest_giftcard_show_price_model($value) {
	global $post;
	$product_price_model = get_post_meta($post->ID,'_giftcard-price-model' ,true);
	if ($product_price_model ==$value ) {
		echo "checked";
	}
}
?>
<p class="form-field price-model giftcard">
	<label for="_regular_price"><?php echo __('Price model', GIFTCARD_TEXT_DOMAIN) ?></label>
	<input type="radio" name="_giftcard-price-model" value="fixed-price" <?php echo magenest_giftcard_show_price_model('fixed-price') ?>><?php echo __('Fixed price', GIFTCARD_TEXT_DOMAIN) ?><br>
    <input type="radio" name="_giftcard-price-model" value="selected-price" <?php echo magenest_giftcard_show_price_model('selected-price') ?> ><?php echo __('Selected price', GIFTCARD_TEXT_DOMAIN) ?> <br>
    <input type="radio" name="_giftcard-price-model" value="custom-price" <?php echo magenest_giftcard_show_price_model('custom-price') ?> ><?php echo __('Custom price', GIFTCARD_TEXT_DOMAIN) ?>
</p>

<p id="selected-price-for-giftcard" class="form-field _regular_price_field giftcard selector-price-model giftcard-price" <?php if (get_post_meta($post->ID,'__giftcard-price-model' ,true)!='selected-price') :?> style="display:none"<?php endif;?>>
	<label for="_regular_price"><?php echo __('Gift card pre-set', GIFTCARD_TEXT_DOMAIN) ?> (<?php echo get_woocommerce_currency_symbol() ?>)</label><input type="text"
		class="giftcard-label" name="_giftcard-preset-price" id="_giftcard-preset-price"
		value="<?php echo get_post_meta($post->ID,'_giftcard-preset-price' ,true) ?>" placeholder="">
</p>
<p class="form-field _regular_price_field giftcard custom-price-model giftcard-price" <?php if (get_post_meta($post->ID,'__giftcard-price-model' ,true)!='custom-price') :?> style="display:none"<?php endif;?>>
	<label for="_regular_price"><?php echo __('Price range', GIFTCARD_TEXT_DOMAIN) ?>(<?php echo get_woocommerce_currency_symbol() ?>)</label>
	<input type="text" value="<?php echo get_post_meta($post->ID,'_giftcard-price-range' ,true) ?>"
		class="_giftcard-price-range" name="_giftcard-price-range" id="_giftcard-price-range"
		 placeholder="">
</p>
<p class="form-field _regular_price_field giftcard">
	<label for="_regular_price"><?php echo __('Expiry date', GIFTCARD_TEXT_DOMAIN) ?> </label>
	<input type="text" value="<?php echo get_post_meta($post->ID,'_giftcard-expiry-date' ,true) ?>"
		class="custome-giftcard-checkbox" name="_giftcard-expiry-date" id="_giftcard-expiry-date"
		placeholder="mm-dd-yyyy">
</p>

<script type="text/javascript">
jQuery(document).ready(function() {
	show_price_giftcard_model();
	
	jQuery('#_giftcard').on('change', function(event) {
		if (jQuery('#_giftcard').is(':checked')) {
			jQuery('.giftcard').each(function(i){
				jQuery(this).show();
				show_price_giftcard_model();
			});
		} else {
			jQuery('.giftcard').each(function(i){
				jQuery(this).hide();
			});
		}
		
		
	});


	jQuery('input:radio[name="_giftcard-price-model"]').change(function() {
		
		show_price_giftcard_model();
	});

	
});

function show_price_giftcard_model() {
	var pricemodel = jQuery('input:radio[name="_giftcard-price-model"]:checked').val();
	//console.log(pricemodel);
	switch (pricemodel) {
		
	case 'fixed-price': {
		
		jQuery('.giftcard-price').each(function(i) {
			jQuery(this).hide();
		}) ;
		break;
	}
	case 'selected-price': {
		jQuery('.giftcard-price').each(function(i) {
			jQuery(this).hide();
		}) ;
		console.log('selected-price');
//			jQuery('.selector-price-model').each(function(i) {
//				jQuery(this).show();
//			}) ;

		jQuery('#selected-price-for-giftcard').show();
		break;
	}
	case 'custom-price' : {
		jQuery('.giftcard-price').each(function(i) {
			jQuery(this).hide();
		}) ;
		jQuery('.custom-price-model').each(function(i) {
			jQuery(this).show();
		}) ;
		break;
	}
	}
}
</script>