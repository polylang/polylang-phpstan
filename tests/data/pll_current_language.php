<?php

declare(strict_types=1);

namespace WPSyntex\Polylang\PHPStan\Tests;

use function PHPStan\Testing\assertType;

assertType( 'PLL_Language|false', pll_current_language( \OBJECT ) );
assertType( 'non-empty-string', pll_current_language() );

foreach ( [ 'name', 'slug', 'locale', 'search_url' ] as $field ) {
	assertType( 'non-empty-string', pll_current_language( $field ) );
}

foreach ( [ 'facebook', 'custom_flag_url', 'custom_flag' ] as $field ) {
	assertType( 'string', pll_current_language( $field ) );
}

foreach ( [ 'mo_id', 'page_on_front', 'term_id', 'term_taxonomy_id', 'tl_term_taxonomy_id' ] as $field ) {
	assertType( 'int<0, max>', pll_current_language( $field ) );
}

assertType( 'int', pll_current_language( 'term_group' ) );
assertType( 'int<0, 1>', pll_current_language( 'is_rtl' ) );
assertType( 'bool', pll_current_language( 'active' ) );
assertType( 'list<non-empty-string>', pll_current_language( 'fallbacks' ) );

assertType( 'int<0, max>', pll_current_language( 'foo:term_taxonomy_id' ) );

assertType( 'false', pll_current_language( 'unknownprop' ) );
