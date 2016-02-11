`                                                   <script src="<?php echo GIFTCARD_URL?>/assets/form-validator/jquery.form-validator.min.js"></script>

<?php   
global $post;
$post_id = $post->ID;

$is_giftcard = get_post_meta( $post_id, '_giftcard', true );
if ($is_giftcard) {
	$price_model = get_post_meta($post_id,'_giftcard-price-model' , true);
}

$presets = get_post_meta($post_id ,'_giftcard-preset-price' , true);
$preset = explode(';', $presets);

$currency_symbol =get_woocommerce_currency_symbol();
$html = __('From', GIFTCARD_TEXT_DOMAIN) .' ' .$currency_symbol.$preset[0];

$html = '<select class="giftcardinputprice" name="giftcard[amount]" id="magenest-giftcard-selector-price"  data-r="giftcard-amount-'.$post_id.'">';
if (!empty($preset)) {
	foreach ($preset as $op) {
		$html .='<option>'. $op . '</option>';
	} }
	$html .='</select>';
?>    <div id="giftcard-sf-wr">
    <?php if ($price_model == 'selected-price') { ?>
    <div>
    <label> <?php echo __('Select value' , GIFTCARD_TEXT_DOMAIN) ?></label>
    <select name="giftcard[amount]" >
    
    <?php if (!empty($preset))  {
    	 foreach ($preset as $op) { ?>
     <option value="<?php echo $op?>"> <?php echo $op ?> </option>
    <?php } } ?>
    </select>
    </div>
    <?php } elseif ($price_model == 'custom-price') {
    	
    	/*get the price range  */
    	$price_range = get_post_meta($post_id ,'_giftcard-price-range' , true);
    	
    	$min = 1;
    	$max = 1000000000000000;
    	
    	$prices = explode('-', $price_range);
    	
    	if (isset($prices[0])) $min = $prices[0];
    	
    	if (isset($prices[1])) $max = $prices[1];
    	 
    	
    	/* */
    	 ?>
      <label> <?php echo __('Enter a value' , GIFTCARD_TEXT_DOMAIN) ?></label> <br>
    <input class='giftcardinputprice' type='text' id='magenest-giftcard-define-price-' data-r='giftcard-amount' name='giftcard[amount]'  data-validation="number" data-validation-allowing="range[<?php echo $min?>;<?php echo $max ?>]" data-validation-help="<?php echo __('Please enter a valid amout of gift card value which is in range ').$min .' to '. $max ?>" >
    <br>
    <?php  } ?>
  <label for="send_friend" ><?php echo __("Send to friend" , GIFTCARD_TEXT_DOMAIN) ?></label>
       
        </div>
        <div>
			<input name="giftcard[send_to_name]" id="send_to_name" class="input-text"  style="margin-bottom:5px;" placeholder="<?php echo __('Send to') ?>">
			<input type="text" name="giftcard[send_to_email]" id="send_to_email" data-validation="email" data-validation-help="<?php echo __('Please enter a valid email address') ?>"  placeholder="<?php echo __('Recipient email') ?>" id="giftcard_to_email" class="input-text"  style="margin-bottom:5px;">
			<textarea class="input-text" id="message" name="giftcard[message]" rows="2"  style="margin-bottom:5px;" placeholder="<?php echo __('Message') ?>" maxlength="400" ></textarea>
		
		</div>
		
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#magenest-giftcard-selector-price').on('change', function(event) {

		var gcprice = jQuery('#magenest-giftcard-selector-price').val();
		console.log(gcprice);
		jQuery('#giftcard-amount').val(gcprice);
		});
});

function bindprice() {
	jQuery('#magenest-giftcard-selector-price').on('change', function(event) {

		var gcprice = jQuery('#magenest-giftcard-selector-price').val();
		console.log(gcprice);
		jQuery('#giftcard-amount').val(gcprice);
		});
}
bindprice();

jQuery.validate(); 
</script>



