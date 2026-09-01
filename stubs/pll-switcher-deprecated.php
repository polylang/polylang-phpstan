<?php

/**
 * Deprecated API kept for static analysis only.
 *
 * @package Polylang
 */

/**
 * @deprecated 3.9 Removed. Use pll_the_languages() or WP_Syntex\Polylang\Switcher\Switcher.
 */
class PLL_Switcher {
	/**
	 * @deprecated 3.9 Use pll_the_languages() instead.
	 *
	 * @param PLL_Links $links Instance of PLL_Links.
	 * @param array     $args  Optional array of arguments.
	 * @return string|array<string, mixed>
	 */
	public function the_languages( $links, $args = array() ) {
	}
}
