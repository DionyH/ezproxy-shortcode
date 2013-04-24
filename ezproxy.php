<?php
/*
Plugin Name: EZProxy Shortcode
Plugin URI: http://www.delputnam.com/plugins/ezproxy.zip
Description: Provides a shortcode that generates an EZProxy ticket and link.
Version: 1.0
Author: Del Putnam
Author URI: http://www.delputnam.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

class EZproxyTicket {
  var $EZproxyStartingPointURL;

  function EZproxyTicket(
    $EZproxyServerURL,
    $secret,
    $user,
    $groups = "")
  {
    if (strcmp($secret, "") == 0) {
      echo("EZproxyURLInit secret cannot be blank");
      exit(1);
    }

    $packet = '$u' . time();
    if (strcmp($groups, "") != 0) {
      $packet .=  '$g' . $groups;
    }
    $packet .= '$e';
    $EZproxyTicket = urlencode(md5($secret . $user . $packet) . $packet);
    $this->EZproxyStartingPointURL = $EZproxyServerURL . "/login?user=" .
      urlencode($user) . "&ticket=" . $EZproxyTicket;
  }

  function URL($url)
  {
    return $this->EZproxyStartingPointURL . "&url=" . $url;
  }
}

function ezproxy_shortcode( $atts ) {
	extract( shortcode_atts( array(
		'proxy' => null,
		'url' => null,
		'userid' => null,
		'userguid' => false,
		'secret' => null
	), $atts ) );

	if ( $userguid ) {
		if ( !empty( $userid ) ) {
			$userid = $userid . '_';
		}
		$userid = uniqid( $userid, true );
	}

	$ezproxy = new EZproxyTicket( $proxy, $secret, $userid );

	return $ezproxy->url( $url );
}

add_shortcode( 'ezproxy', 'ezproxy_shortcode' );