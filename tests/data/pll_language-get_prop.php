<?php

declare(strict_types=1);

namespace WPSyntex\Polylang\PHPStan\Tests;

use function PHPStan\Testing\assertType;

/** @var \PLL_Language */
$language = $language;

assertType( 'null', $language->get_prop() );
assertType( 'null', $language->get_prop( 4 ) );

foreach ( [ 'name', 'slug', 'locale', 'w3c', 'flag_code', 'host', 'flag_url', 'flag', 'home_ur', 'search_url' ] as $field ) {
	assertType( 'non-empty-string', $language->get_prop( $field ) );
}

foreach ( [ 'facebook', 'custom_flag_url', 'custom_flag' ] as $field ) {
	assertType( 'string', $language->get_prop( $field ) );
}

foreach ( [ 'mo_id', 'page_on_front', 'page_for_posts', 'term_id', 'term_taxonomy_id', 'count', 'tl_term_id', 'tl_term_taxonomy_id', 'tl_count' ] as $field ) {
	assertType( 'int<0, max>', $language->get_prop( $field ) );
}

assertType( 'int', $language->get_prop( 'term_group' ) );
assertType( 'int<0, 1>', $language->get_prop( 'is_rtl' ) );
assertType( 'bool', $language->get_prop( 'active' ) );
assertType( 'list<non-empty-string>', $language->get_prop( 'fallbacks' ) );

foreach ( [ 'language:term_id', 'language:term_taxonomy_id', 'language:count', 'term_language:term_id', 'term_language:term_taxonomy_id', 'term_language:count', 'foo:term_id', 'foo:term_taxonomy_id', 'foo:count' ] as $field ) {
	assertType( 'int<0, max>', $language->get_prop( $field ) );
}

assertType( 'false', $language->get_prop( 'unknownprop' ) );
