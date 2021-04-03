<?php
/**
 * MantisBT - A PHP based bugtracking system
 *
 * MantisBT is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * MantisBT is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with MantisBT.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright Copyright MantisBT Team - mantisbt-dev@lists.sourceforge.net
 *
 * Modifications by: Louis Abel <label@rockylinux.org>
 */

/**
 * Mantis Libravatar Plugin
 *
 * This is an avatar provider plugin that is based on https://www.libravatar.org
 * which is loosely based on the MantisBT plugin for Libravatar (with some
 * modifications of parameters that are not required).
 *
 * This will require users to register their same email address used to login
 * to work. When a registration or avatar change occurs, it will take time
 * for the updates to come in.
 *
 */
class LibravatarPlugin extends MantisPlugin {
	const LIBRAVATAR_URL = 'https://seccdn.libravatar.org/';

	/**
	 * Default Libravatar image types
	 *
	 * @link https://wiki.libravatar.org/api/
	 */
	const LIBRAVATAR_DEFAULT_MYSTERYMAN = 'mm';
	const LIBRAVATAR_DEFAULT_IDENTICON  = 'identicon';
	const LIBRAVATAR_DEFAULT_MONSTERID  = 'monsterid';
	const LIBRAVATAR_DEFAULT_ROBOHASH   = 'robohash';
	const LIBRAVATAR_DEFAULT_WAVATAR    = 'wavatar';
	const LIBRAVATAR_DEFAULT_RETRO      = 'retro';
	const LIBRAVATAR_DEFAULT_PAGAN      = 'pagan';
	const LIBRAVATAR_DEFAULT_BLANK      = 'blank';

	/**
	 * Plugin information and minimum requirements
	 * @return void
	 */
	function register() {
		$this->name = plugin_lang_get( 'title' );
		$this->description = plugin_lang_get( 'description' );
		$this->page = 'https://github.com/nazunalika/mantisbt-libravatar';

		$this->version = MANTIS_VERSION;
		$this->requires = array(
			'MantisCore' => '2.0.0',
		);

		$this->author = 'Louis Abel';
		$this->contact = 'label@rockylinux.org';
		$this->url = 'https://rockylinux.org';
	}

	/**
	 * Default plugin configuration.
	 * @return array
	 */
	function config() {
		return array(
			/**
			 * The kind of avatar to use:
			 *
			 * - One of Libravatar's defaults as explained in the link below.
			 *   @link https://wiki.libravatar.org/api/
			 * - An URL to the default image to be used (for example,
			 *   "http:/path/to/unknown.jpg" or "%path%images/avatar.png")
			 */
			'default_avatar' => self::LIBRAVATAR_DEFAULT_RETRO
		);
	}

	/**
	 * Register event hooks for plugin.
	 */
	function hooks() {
		return array(
			'EVENT_USER_AVATAR' => 'user_get_avatar',
			'EVENT_CORE_HEADERS' => 'csp_headers',
		);
	}

	/**
	 * Register libravatar url as an img-src for CSP header
	 */
	function csp_headers() {
		if( config_get( 'show_avatar' ) !== OFF ) {
			http_csp_add( 'img-src', self::LIBRAVATAR_URL );
		}
	}

	/**
	 * Return the user avatar image URL
   *
   * Only libravatar API based avatars are supported
	 *
	 * This function returns an array( URL, width, height ) or an empty array when the given user has no avatar.
	 *
	 * @param string  $p_event   The name for the event.
	 * @param integer $p_user_id A valid user identifier.
	 * @param integer $p_size    The required number of pixel in the image to retrieve the link for.
	 *
	 * @return object An instance of class Avatar or null.
	 */
	function user_get_avatar( $p_event, $p_user_id, $p_size = 80 ) {
		$t_default_avatar = plugin_config_get( 'default_avatar' );

		# Default avatar is either one of Libravatar's options, or
		# assumed to be an URL to a default avatar image
		$t_default_avatar = urlencode( $t_default_avatar );

		if( user_exists( $p_user_id ) ) {
			$t_email_hash = md5( strtolower( trim( user_get_email( $p_user_id ) ) ) );
		} else {
			$t_email_hash = md5( 'generic-avatar-since-user-not-found' );
		}

		# Build Libravatar URL
		$t_avatar_url = self::LIBRAVATAR_URL .
			'avatar/' . $t_email_hash . '?' .
			http_build_query(
				array(
					'd' => $t_default_avatar,
					's' => $p_size,
				)
			);

		$t_avatar = new Avatar();
		$t_avatar->image = $t_avatar_url;

		return $t_avatar;
	}
}
