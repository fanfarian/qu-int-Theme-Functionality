<?php

class SR_Plugin_Updater {

	private $file;

	private $plugin;

	private $basename;

	private $active;

	private $username;

	private $repository;

	private $authorize_token;

	private $github_response;

	public function __construct( $file ) {

		$this->file = $file;
		add_action( 'admin_init', array( $this, 'set_plugin_properties' ) );

		return $this;
	}

	public function set_plugin_properties() {
		$this->plugin	= get_plugin_data( $this->file );
		$this->basename = plugin_basename( $this->file );
		$this->active	= is_plugin_active( $this->basename );
	}

	public function set_username( $username ) {
		$this->username = $username;
	}

	public function set_repository( $repository ) {
		$this->repository = $repository;
	}

	public function authorize( $token ) {
		$this->authorize_token = $token;
	}

	private function get_repository_info() {
	    if ( is_null( $this->github_response ) ) { // Do we have a response?
	        $request_uri = sprintf( 'https://api.github.com/repos/%s/%s/releases', $this->username, $this->repository ); // Build URI
	        
	        if( $this->authorize_token ) { // Is there an access token?
	            $request_uri = add_query_arg( 'access_token', $this->authorize_token, $request_uri ); // Append it
	        }        
	        
	        $response = json_decode( wp_remote_retrieve_body( wp_remote_get( $request_uri ) ), true ); // Get JSON and parse it
	        
	        if( is_array( $response ) ) { // If it is an array
	            $response = current( $response ); // Get the first item
	        }
	        
	        if( $this->authorize_token ) { // Is there an access token?
	            $response['zipball_url'] = add_query_arg( 'access_token', $this->authorize_token, $response['zipball_url'] ); // Update our zip url with token
	        }
	        
	        $this->github_response = $response; // Set it to our property  
	    }
	}

	public function initialize() {
		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'modify_transient' ), 10, 1 );
		add_filter( 'plugins_api', array( $this, 'plugin_popup' ), 10, 3);
		add_filter( 'upgrader_source_selection', array($this, 'fixDirectoryName'), 10, 3);
		add_filter( 'upgrader_post_install', array( $this, 'after_install' ), 10, 3 );
	}

	public function modify_transient( $transient ) {
		
		if( property_exists( $transient, 'checked') ) { // Check if transient has a checked property
			
			if( $checked = $transient->checked ) { // Did Wordpress check for updates?
	
				$this->get_repository_info(); // Get the repo info
				
				$github_version = str_replace("v", "", $this->github_response['tag_name']);
	
				$out_of_date = version_compare( $github_version, $checked[ $this->basename ], 'gt' ); // Check if we're out of date
	
				if( $out_of_date ) {
	
					$new_files = $this->github_response['zipball_url']; // Get the ZIP
					
					$slug = current( explode('/', $this->basename ) ); // Create valid slug
					
					$plugin = array( // setup our plugin info
						'url' => $this->plugin["PluginURI"],
						'slug' => $slug,
						'package' => $new_files,
						'new_version' => $github_version
					);
	
					$transient->response[$this->basename] = (object) $plugin; // Return it in response
				}
			}
		}

		return $transient; // Return filtered transient
	}

	public function plugin_popup( $result, $action, $args ) {

		if( ! empty( $args->slug ) ) { // If there is a slug
			$plugin_slug = current( explode('/', $this->basename ) ); // Create valid slug
			
			if( $args->slug == $plugin_slug ) { // And it's our slug
				
				$this->get_repository_info(); // Get our repo info
							
				$plugin = array(
					'name'				=> $this->plugin["Name"],
					'slug'				=> $this->basename,
					'version'			=> str_replace("v", "", $this->github_response['tag_name']),					
					'author'			=> $this->plugin["AuthorName"],
					'author_profile'	=> $this->plugin["AuthorURI"],
					'last_updated'		=> $this->github_response['published_at'],
					'homepage'			=> $this->plugin["PluginURI"],
					'short_description' => $this->plugin["Description"],
					'sections'			=> array( 
						'Description'	=> $this->plugin["Description"],
						'Changelog'		=> $this->github_response['body'],
					),
					'download_link'		=> $this->github_response['zipball_url']
				);
		
				return (object) $plugin; // Return the data
			}
		}
		return $result; // Otherwise return default
	}

	public function after_install( $response, $hook_extra, $result ) {
		global $wp_filesystem; // Get global FS object
		
		$install_directory = plugin_dir_path( $this->file ); // Our plugin directory 
		$wp_filesystem->move( $result['destination'], $install_directory ); // Move files to the plugin dir
		$result['destination'] = $install_directory; // Set the destination for the rest of the stack

		if ( $this->active ) { // If it was active
			activate_plugin( $this->basename ); // Reactivate
		}

		return $result;
	}
	
	/**
	 * Rename the update directory to match the existing plugin directory.
	 * From: https://github.com/YahnisElsts/plugin-update-checker
	 *
	 * When WordPress installs a plugin or theme update, it assumes that the ZIP file will contain
	 * exactly one directory, and that the directory name will be the same as the directory where
	 * the plugin/theme is currently installed.
	 *
	 * GitHub and other repositories provide ZIP downloads, but they often use directory names like
	 * "project-branch" or "project-tag-hash". We need to change the name to the actual plugin folder.
	 *
	 * @param string $source The directory to copy to /wp-content/plugins. Usually a subdirectory of $remoteSource.
	 * @param string $remoteSource WordPress has extracted the update to this directory.
	 * @param WP_Upgrader $upgrader
	 * @return string|WP_Error
	 */
	function fixDirectoryName($source, $remoteSource, $upgrader) {
		global $wp_filesystem; /** @var WP_Filesystem_Base $wp_filesystem */
		//Basic sanity checks.
		if ( !isset($source, $remoteSource, $upgrader, $upgrader->skin, $wp_filesystem) ) {
			return $source;
		}
		
		//Figure out which plugin is being upgraded.
		$pluginFile = null;
		$skin = $upgrader->skin;
		
		if ( $skin instanceof Plugin_Upgrader_Skin ) {
			if ( isset($skin->plugin) && is_string($skin->plugin) && ($skin->plugin !== '') ) {
				$pluginFile = $skin->plugin;
			}
		} elseif ( isset($skin->plugin_info) && is_array($skin->plugin_info) ) {
			//This case is tricky because Bulk_Plugin_Upgrader_Skin (etc) doesn't actually store the plugin
			//filename anywhere. Instead, it has the plugin headers in $plugin_info. So the best we can
			//do is compare those headers to the headers of installed plugins.
			if ( !function_exists('get_plugins') ){
				require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
			}
			$installedPlugins = get_plugins();
			$matches = array();
			foreach($installedPlugins as $pluginBasename => $headers) {
				$diff1 = array_diff_assoc($headers, $skin->plugin_info);
				$diff2 = array_diff_assoc($skin->plugin_info, $headers);
				if ( empty($diff1) && empty($diff2) ) {
					$matches[] = $pluginBasename;
				}
			}
			//It's possible (though very unlikely) that there could be two plugins with identical
			//headers. In that case, we can't unambiguously identify the plugin that's being upgraded.
			if ( count($matches) !== 1 ) {
				return $source;
			}
			$pluginFile = reset($matches);
			
		} elseif ( !empty($this->upgradedPluginFile) ) {
			$pluginFile = $this->upgradedPluginFile;
		}
		
		//If WordPress is upgrading anything other than our plugin, leave the directory name unchanged.
		if ( empty($pluginFile) || ($pluginFile !== $this->basename) ) {
			return $source;
		}
		//Rename the source to match the existing plugin directory.
		$pluginDirectoryName = dirname($this->basename);
		if ( ($pluginDirectoryName === '.') || ($pluginDirectoryName === '/') ) {
			return $source;
		}
		
		$correctedSource = trailingslashit($remoteSource) . $pluginDirectoryName . '/';
		if ( $source !== $correctedSource ) {
			//The update archive should contain a single directory that contains the rest of plugin files. Otherwise,
			//WordPress will try to copy the entire working directory ($source == $remoteSource). We can't rename
			//$remoteSource because that would break WordPress code that cleans up temporary files after update.
			$sourceFiles = $wp_filesystem->dirlist($remoteSource);
			
			if ( is_array($sourceFiles) ) {
				$sourceFiles = array_keys($sourceFiles);
				$firstFilePath = trailingslashit($remoteSource) . $sourceFiles[0];
				if ( (count($sourceFiles) > 1) || (!$wp_filesystem->is_dir($firstFilePath)) ) {
					return new WP_Error(
						'puc-incorrect-directory-structure',
						sprintf(
							'The directory structure of the update is incorrect. All plugin files should be inside ' .
							'a directory named <span class="code">%s</span>, not at the root of the ZIP file.',
							htmlentities($this->slug)
						)
					);
				}
			}
			
			$upgrader->skin->feedback(sprintf(
				'Renaming %s to %s&#8230;',
				'<span class="code">' . basename($source) . '</span>',
				'<span class="code">' . $pluginDirectoryName . '</span>'
			));
			if ( $wp_filesystem->move($source, $correctedSource, true) ) {
				$upgrader->skin->feedback('Plugin directory successfully renamed.');
				
				return $correctedSource;
			} else {
				return new WP_Error(
					'puc-rename-failed',
					'Unable to rename the update to match the existing plugin directory.'
				);
			}
		}
		
		return $source;
	}
}

?>