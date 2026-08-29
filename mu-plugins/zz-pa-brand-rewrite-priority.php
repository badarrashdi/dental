<?php
/**
 * The active storefront-based theme registers its own 'product_brand'
 * taxonomy with rewrite slug 'brand', which shadows the WooCommerce global
 * 'Brand' attribute (pa_brand) archives. Reorder the rewrite rules so the
 * pa_brand rules match first and /brand/<term>/ resolves to the real,
 * populated pa_brand archives. Harmless if the theme is changed.
 */
add_filter(
	'rewrite_rules_array',
	function ( $rules ) {
		$pa = array();
		foreach ( $rules as $key => $rule ) {
			if ( false !== strpos( $rule, 'pa_brand=' ) ) {
				$pa[ $key ] = $rule;
				unset( $rules[ $key ] );
			}
		}
		return $pa + $rules;
	}
);
