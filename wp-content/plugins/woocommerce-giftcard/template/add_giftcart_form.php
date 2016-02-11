<form method="POST" id="giftcard-apply-form">
<div class="giftcard" >
			<label for="giftcard_code" style="display: none;"><?php _e( 'Giftcard', 'woocommerce' ); ?>:</label>
			<input type="text" name="giftcard_code" class="input-text" id="giftcard_code" value="<?php  if ( isset($woocommerce->session->giftcard_code )) :?><?php echo $woocommerce->session->giftcard_code ?> <?php endif;?>" placeholder="<?php _e( 'Gift Card', 'woocommerce' ); ?>" style="    border: 1px solid #E0DADF;
			    box-shadow: 0 1px 4px 0 rgba(0, 0, 0, 0.1) inset;
			    box-sizing: border-box;
			    float: left;
			    line-height: 1em;
			    margin: 0 4px 0 0;
			    outline: 0 none;
			    padding: 6px 6px 5px;"/>
			<input type="submit" class="button" name="update_cart" value="<?php _e( 'Apply Gift card', 'woocommerce' ); ?>" />
		</div>
</form>