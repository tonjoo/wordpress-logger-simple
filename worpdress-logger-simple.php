<?php
/* 
Plugin Name: Wordpress Logger Simple
Description: Simple logger
Author: Tonjoo
Author URI: http://tonjoostudio.com/
Plugin URI: http://tonjoostudio.com
Version: 0.1
Text Domain: wp-logger-simple
*/

class WordpressLogger {

	public $file = WP_CONTENT_DIR . '/log/';

	public $message = false;

	public function __construct() {
		global $wp_filesystem;

		$filename = apply_filters( 'wp_logger_file', 'log.txt' );

		if ( empty($wp_filesystem) ) {
			require_once (ABSPATH . '/wp-admin/includes/file.php');
			WP_Filesystem();
		}
		if ( ! $wp_filesystem->exists( $this->file ) ) {
			$wp_filesystem->mkdir( $this->file );
		}

		$this->file = $this->file . $filename;
		add_action( "admin_menu", array( $this, "menu_page" ) );
	}

	public function logMessage($newMessage) {
		$this->message = $newMessage;

		global $wp_filesystem;

		if ( empty($wp_filesystem) ) {
			require_once (ABSPATH . '/wp-admin/includes/file.php');
			WP_Filesystem();
		}

		$content = $wp_filesystem->get_contents( $this->file );

		if ( strlen($content) >= 200000 ) 
			$content = "";

		$text = "[".current_time( 'mysql' )."] " . $this->message;

		$content = $content . $text . PHP_EOL;

		$tes = $wp_filesystem->put_contents( $this->file, $content );
	}

	function menu_page() {
		add_management_page( __('Wordpress Logger', 'wordpress-logger'), __('Wordpress Logger', 'wordpress-logger'), 'manage_options', 'wordpress_logger', array( $this, 'wordpress_logger_page' ) );
	}

	function wordpress_logger_page() {
		?>
		<div id="" class="wrap">
			<h1><?php _e('Wordpress Logger', 'wordpress-logger') ?></h1>
			<br>
			<div class="progress">
				<?php
				if (is_file($this->file)) {
					$filearray = file($this->file);
					$lastlines = array_slice($filearray, -100);
					$reversed = array_reverse($lastlines);
				}
					$content = '';
					if ( isset( $reversed ) ) {
						foreach ($reversed as $key => $value) {
							$content .= $value;
						}
					} else {
						$content = 'No Log to display.';
					}
				?>
				<textarea style="width:100%; max-width: 600px;" class="log-text" rows="20" readonly><?php echo $content; ?></textarea>
			</div>
		</div>
		<?php 
	}
}
global $wp_logger;
$wp_logger = new WordpressLogger();
$wp_logger->logMessage("tess");