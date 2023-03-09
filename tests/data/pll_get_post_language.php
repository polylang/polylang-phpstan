<?php

declare(strict_types=1);

namespace WPSyntex\Polylang\PHPStan\Tests;

use function PHPStan\Testing\assertType;

assertType( 'false', pll_get_post_language( 0 ) );
assertType( 'PLL_Language|false', pll_get_post_language( 7, \OBJECT ) );
assertType( 'non-empty-string', pll_get_post_language( 8 ) );

foreach ( [ 'name', 'slug', 'locale', 'search_url' ] as $field ) {
	assertType( 'non-empty-string', pll_get_post_language( 9, $field ) );
}

foreach ( [ 'facebook', 'custom_flag_url', 'custom_flag' ] as $field ) {
	assertType( 'string', pll_get_post_language( 10, $field ) );
}

foreach ( [ 'mo_id', 'page_on_front', 'term_id', 'term_taxonomy_id', 'tl_term_taxonomy_id' ] as $field ) {
	assertType( 'int<0, max>', pll_get_post_language( 11, $field ) );
}

assertType( 'int', pll_get_post_language( 12, 'term_group' ) );
assertType( 'int<0, 1>', pll_get_post_language( 13, 'is_rtl' ) );
assertType( 'bool', pll_get_post_language( 14, 'active' ) );
assertType( 'list<non-empty-string>', pll_get_post_language( 15, 'fallbacks' ) );

assertType( 'int<0, max>', pll_get_post_language( 16, 'foo:term_taxonomy_id' ) );

assertType( 'false', pll_get_post_language( 17, 'unknownprop' ) );
