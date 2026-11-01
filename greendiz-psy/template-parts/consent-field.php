<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$gdpsy_policy = gdpsy_privacy_url();
?>
<label class="gz-consent"><input type="checkbox" name="consent" value="1" required><span>Даю <a href="<?php echo esc_url( gdpsy_consent_url() ); ?>" target="_blank" rel="noopener noreferrer">согласие</a> на <?php if ( $gdpsy_policy ) : ?><a href="<?php echo esc_url( $gdpsy_policy ); ?>" target="_blank" rel="noopener noreferrer">обработку персональных данных</a><?php else : ?>обработку персональных данных<?php endif; ?>.</span></label>
