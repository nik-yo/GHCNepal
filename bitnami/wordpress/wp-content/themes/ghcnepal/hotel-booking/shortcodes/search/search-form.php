<?php
/**
 * Available variables
 * - string $uniqid
 * - string $action Action for search form
 * - string $checkInDate
 * - string $checkOutDate
 * - int $adults
 * - int $children
 * - array $adultsList
 * - array $childrenList
 * - array $attributes [%Attribute name% => [%Term ID% => %Term title%]]
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$firstAvailableCheckInDate = mphb_availability_facade()->getFirstAvailableCheckInDate(
	0,
	MPHB()->settings()->main()->isBookingRulesForAdminDisabled()
)->format( 'Y-m-d' );
?>

<form method="GET" class="mphb_sc_search-form flex justify-center items-end space-x-2" action="<?php echo esc_attr( $action ); ?>" data-first_available_check_in_date="<?php echo esc_attr( $firstAvailableCheckInDate ); ?>">

	<!-- <?php
	/**
	 * @hooked \MPHB\Shortcodes\SearchShortcode::renderHiddenInputs - 10
	 */
	do_action( 'mphb_sc_search_render_form_top' );
	?> -->

	<p class="mphb_sc_search-check-in-date">
		<label for="<?php echo esc_attr( 'mphb_check_in_date-' . $uniqid ); ?>" class="text-md font-bold">
			<?php esc_html_e( 'Check-in', 'motopress-hotel-booking' ); ?>
			<span title="<?php echo esc_attr( sprintf( _x( 'Formatted as %s', 'Date format tip', 'motopress-hotel-booking' ), MPHB()->settings()->dateTime()->getDateFormatJS() ) ); ?>"></span>
		</label>
		<br />
		<input
			id="<?php echo esc_attr( 'mphb_check_in_date-' . $uniqid ); ?>"
			data-datepick-group="<?php echo esc_attr( $uniqid ); ?>"
			value="<?php echo esc_attr( $checkInDate ); ?>"
			placeholder="<?php esc_attr_e( 'Check-in Date', 'motopress-hotel-booking' ); ?>"
			required="required"
			type="text"
			inputmode="none"
			name="mphb_check_in_date"
			class="mphb-datepick border-2 rounded-md px-2 py-1"
			autocomplete="off"
			/>
	</p>

	<p class="mphb_sc_search-check-out-date">
		<label for="<?php echo esc_attr( 'mphb_check_out_date-' . $uniqid ); ?>" class="text-md font-bold">
			<?php esc_html_e( 'Check-out', 'motopress-hotel-booking' ); ?>
			<span title="<?php echo esc_attr( sprintf( _x( 'Formatted as %s', 'Date format tip', 'motopress-hotel-booking' ), MPHB()->settings()->dateTime()->getDateFormatJS() ) ); ?>"></span>
		</label>
		<br />
		<input
			id="<?php echo esc_attr( 'mphb_check_out_date-' . $uniqid ); ?>"
			data-datepick-group="<?php echo esc_attr( $uniqid ); ?>"
			value="<?php echo esc_attr( $checkOutDate ); ?>"
			placeholder="<?php esc_attr_e( 'Check-out Date', 'motopress-hotel-booking' ); ?>"
			required="required"
			type="text"
			inputmode="none"
			name="mphb_check_out_date"
			class="mphb-datepick border-2 rounded-md px-2 py-1"
			autocomplete="off"
			/>
	</p>

	<?php if ( MPHB()->settings()->main()->isAdultsDisabledOrHidden() ) { ?>
		<input type="hidden" id="<?php echo esc_attr( 'mphb_adults-' . $uniqid ); ?>" name="mphb_adults" value="<?php echo esc_attr( MPHB()->settings()->main()->getMinAdults() ); ?>" />
	<?php } else { ?>
		<p class="mphb_sc_search-adults">
			<label for="<?php echo esc_attr( 'mphb_adults-' . $uniqid ); ?>" class="text-md font-bold">
				<?php
				if ( MPHB()->settings()->main()->isChildrenAllowed() ) {
					esc_html_e( 'Adults', 'motopress-hotel-booking' );
				} else {
					esc_html_e( 'Guests', 'motopress-hotel-booking' );
				}
				?>
			</label>
			<br />
			<select id="<?php echo esc_attr( 'mphb_adults-' . $uniqid ); ?>" name="mphb_adults" class="border-2 rounded-md px-2 py-1">
				<?php foreach ( $adultsList as $value ) { ?>
					<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $adults, $value ); ?>><?php echo esc_html( $value ); ?></option>
				<?php } ?>
			</select>
		</p>
	<?php } ?>

	<?php if ( MPHB()->settings()->main()->isChildrenDisabledOrHidden() ) { ?>
		<input type="hidden" id="<?php echo esc_attr( 'mphb_children-' . $uniqid ); ?>" name="mphb_children" value="<?php echo esc_attr( MPHB()->settings()->main()->getMinChildren() ); ?>" />
	<?php } else { ?>
		<p class="mphb_sc_search-children">
			<label for="<?php echo esc_attr( 'mphb_children-' . $uniqid ); ?>" class="text-md font-bold">
				<?php
					$childrenAge = MPHB()->settings()->main()->getChildrenAgeText();
				if ( empty( $childrenAge ) ) {
					esc_html_e( 'Children', 'motopress-hotel-booking' );
				} else {
					echo esc_html( sprintf( __( 'Children %s', 'motopress-hotel-booking' ), $childrenAge ) );
				}
				?>
			</label>
			<br />
			<select id="<?php echo esc_attr( 'mphb_children-' . $uniqid ); ?>" name="mphb_children" class="border-2 rounded-md px-2 py-1">
				<?php foreach ( $childrenList as $value ) { ?>
					<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $children, $value ); ?>><?php echo esc_html( $value ); ?></option>
				<?php } ?>
			</select>
		</p>
	<?php } ?>

	<?php do_action( 'mphb_sc_search_form_before_attributes' ); ?>

	<?php foreach ( $attributes as $attributeName => $terms ) { ?>
		<p class="<?php echo esc_attr( 'mphb_sc_search-' . $attributeName ); ?>">
			<label for="<?php echo esc_attr( 'mphb_' . $attributeName . '-' . $uniqid ); ?>">
				<?php echo esc_html( mphb_attribute_title( $attributeName ) ); ?>
			</label>
			<br />
			<select id="<?php echo esc_attr( 'mphb_' . $attributeName . '-' . $uniqid ); ?>" name="<?php echo esc_attr( 'mphb_attributes[' . $attributeName . ']' ); ?>">
				<option value=""><?php echo esc_html( mphb_attribute_default_text( $attributeName ) ); ?></option>
				<?php foreach ( $terms as $termId => $termLabel ) { ?>
					<option value="<?php echo esc_attr( $termId ); ?>"><?php echo esc_html( $termLabel ); ?></option>
				<?php } ?>
			</select>
		</p>
	<?php } ?>

	<?php do_action( 'mphb_sc_search_form_before_submit_btn' ); ?>

	<p class="mphb_sc_search-submit-button-wrapper px-4">
		<input type="submit" class="button bg-primary py-2 px-4 rounded" value="<?php esc_attr_e( 'Search', 'motopress-hotel-booking' ); ?>"/>
	</p>

	<?php do_action( 'mphb_sc_search_form_bottom' ); ?>

</form>
