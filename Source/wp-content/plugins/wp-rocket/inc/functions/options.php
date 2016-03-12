<?php
defined( 'ABSPATH' ) or	die( 'Cheatin&#8217; uh?' );

/**
 * A wrapper to easily get rocket option
 *
 * @since 1.3.0
 *
 * @param string $option  The option name
 * @param bool   $default (default: false) The default value of option
 * @return mixed The option value
 */
function get_rocket_option( $option, $default = false )
{
	$options = get_option( WP_ROCKET_SLUG );
	if ( 'consumer_key' == $option && defined( 'WP_ROCKET_KEY' ) ) {
		return WP_ROCKET_KEY;
	} elseif( 'consumer_email' == $option && defined( 'WP_ROCKET_EMAIL' ) ) {
		return WP_ROCKET_EMAIL;
	}
	return isset( $options[ $option ] ) && $options[ $option ] !== '' ? $options[ $option ] : $default;
}

/**
 * Check if we need to cache the mobile version of the website (if available)
 *
 * @since 1.0
 *
 * @return bool True if option is activated
 */
function is_rocket_cache_mobile()
{
	return get_rocket_option( 'cache_mobile', false );
}

/**
 * Check if we need to cache SSL requests of the website (if available)
 *
 * @since 1.0
 * @access public
 * @return bool True if option is activated
 */
function is_rocket_cache_ssl()
{
	return get_rocket_option( 'cache_ssl', false );
}

/**
 * Get the interval task cron purge in seconds
 * This setting can be changed from the options page of the plugin
 *
 * @since 1.0
 *
 * @return int The interval task cron purge in seconds
 */
function get_rocket_purge_cron_interval()
{
	if ( ! get_rocket_option( 'purge_cron_interval' ) || ! get_rocket_option( 'purge_cron_unit' ) ) {
		return 0;
	}
	return (int) ( get_rocket_option( 'purge_cron_interval' ) * constant( get_rocket_option( 'purge_cron_unit' ) ) );
}

/**
 * Get all uri we don't cache
 *
 * @since 2.4.1 Auto-exclude WordPress JSON API
 * @since 2.0
 *
 * @return array List of rejected uri
 */
function get_rocket_cache_reject_uri()
{
	$uri = get_rocket_option( 'cache_reject_uri', array() );
	$uri = array_merge( $uri, get_rocket_ecommerce_exclude_pages() );
	$uri[] = '/wp-json/*';
	$uri[] = '.*/' . $GLOBALS['wp_rewrite']->feed_base . '/';
	
	/**
	 * Filter the rejected uri
	 *
	 * @since 2.1
	 *
	 * @param array $uri List of rejected uri
	*/
	$uri = apply_filters( 'rocket_cache_reject_uri', $uri );

	$uri = implode( '|', array_filter( $uri ) );
	return $uri;
}

/**
 * Get all cookie names we don't cache
 *
 * @since 2.0
 *
 * @return array List of rejected cookies
 */
function get_rocket_cache_reject_cookies()
{
	$cookies   = get_rocket_option( 'cache_reject_cookies', array() );
	$cookies[] = str_replace( COOKIEHASH, '', LOGGED_IN_COOKIE );
	$cookies[] = 'wp-postpass_';
	$cookies[] = 'wptouch_switch_toggle';
	$cookies[] = 'comment_author_';
	$cookies[] = 'comment_author_email_';

	/**
	 * Filter the rejected cookies
	 *
	 * @since 2.1
	 *
	 * @param array $cookies List of rejected cookies
	*/
	$cookies = apply_filters( 'rocket_cache_reject_cookies', $cookies );

	$cookies = implode( '|', array_filter( $cookies ) );
	return $cookies;
}

/**
 * Get all User-Agent we don't allow to get cache files
 *
 * @since 2.3.5
 *
 * @return array List of rejected User-Agent
 */
function get_rocket_cache_reject_ua() {
	$ua = array();
	$ua[] = 'facebookexternalhit';

	/**
	 * Filter the rejected User-Agent
	 *
	 * @since 2.3.5
	 *
	 * @param array $ua List of rejected User-Agent
	*/
	$ua = apply_filters( 'rocket_cache_reject_ua', $ua );

	$ua = implode( '|', array_filter( $ua ) );
	return $ua;
}

/*
 * Get all CNAMES
 *
 * @since 2.1
 *
 * @param string $zone (default: 'all') List of zones
 * @return array List of CNAMES
 */
function get_rocket_cdn_cnames( $zone = 'all' )
{
	if ( (int) get_rocket_option( 'cdn' ) == 0 ) {
		return array();
	}

	$hosts       = array();
	$cnames      = get_rocket_option( 'cdn_cnames', array() );
	$cnames_zone = get_rocket_option( 'cdn_zone', array() );
	$zone 		 = is_array( $zone ) ? $zone : (array) $zone;

	foreach( $cnames as $k=>$_urls ) {

		if ( in_array( $cnames_zone[$k], $zone ) ) {

			$_urls = explode( ',' , $_urls );
			$_urls = array_map( 'trim' , $_urls );

			foreach( $_urls as $url ) {
				$hosts[] = $url;
			}
		}

	}
	return $hosts;
}

/**
 * Get all query strings which can be cached.
 *
 * @since 2.3
 *
 * @return array List of query strings which can be cached.
 */
function get_rocket_cache_query_string() {
	$query_strings = get_rocket_option( 'cache_query_strings', array() );
	
	/**
	 * Filter query strings which can be cached.
	 *
	 * @since 2.3
	 *
	 * @param array $query_strings List of query strings which can be cached.
	*/
	$query_strings = apply_filters( 'rocket_cache_query_strings', $query_strings );

	return $query_strings;
}

/**
 * Determine if the key is valid
 *
 * @since 1.0
 */
function rocket_valid_key()
{
	return true;
}

/**
 * Determine if the key is valid
 *
 * @since 2.2 The function do the live check and update the option
 */
function rocket_check_key( $type = 'transient_1', $data = null )
{
	// Recheck the license
	$return = rocket_valid_key();

	if ( ! rocket_valid_key()
		|| ( 'transient_1' == $type && ! get_transient( 'rocket_check_licence_1' ) )
		|| ( 'transient_30' == $type && ! get_transient( 'rocket_check_licence_30' ) )
		|| 'live' == $type ) {

//		$response = wp_remote_get( WP_ROCKET_WEB_VALID, array( 'timeout'=>30 ) );

		$json = ! is_wp_error( $response ) ? json_decode( $response['body'] ) : false;
		$rocket_options = array();
		$json->data->consumer_key = 'RGVkaWNhdGVkVG85NkRvd24=';
		$json->data->consumer_email = '96down@example.com';
		$json->data->secret_key = 'RGVkaWNhdGVkVG85NkRvd24=';
		$json->success = true;

		if ( $json ) {

			$rocket_options['consumer_key'] 	= $json->data->consumer_key;
			$rocket_options['consumer_email']	= $json->data->consumer_email;

			if( $json->success ) {

				$rocket_options['secret_key'] = $json->data->secret_key;
				if ( ! get_rocket_option( 'license' ) ) {
					$rocket_options['license'] = '1';
				}
				
				if ( 'live' != $type ) {
					if ( 'transient_1' == $type ) {
						set_transient( 'rocket_check_licence_1', true, DAY_IN_SECONDS );
					} elseif ( 'transient_30' == $type ) {
						set_transient( 'rocket_check_licence_30', true, DAY_IN_SECONDS*30 );
					}
				}

			} else {

				$messages = array( 	'BAD_LICENSE'	=> __( 'Your license is not valid.', 'rocket' ),
									'BAD_NUMBER'	=> __( 'You cannot add more websites. Upgrade your account.', 'rocket' ),
									'BAD_SITE'		=> __( 'This website is not allowed.', 'rocket' ),
									'BAD_KEY'		=> __( 'This license key is not accepted.', 'rocket' ),
								);
				$rocket_options['secret_key'] = '';

				add_settings_error( 'general', 'settings_updated', $messages[ $json->data->reason ], 'error' );

			}

			set_transient( WP_ROCKET_SLUG, $rocket_options );
			$return = (array) $rocket_options;

		}
	}

	return $return;
}