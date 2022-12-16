<?php
/*
Plugin Name: ChatGPT Requester
Plugin URI:
Description:
Version:
Author:
Author URI:
License:
License URI:
*/

define( 'CHATGPT_REQUESTER_PATH', plugin_dir_path( __FILE__ ) );
define( 'CHATGPT_REQUESTER_URL', plugin_dir_url( __FILE__ ) );

class ChatGPT_Requester {
	const JS_DIR_PATH = 'assets/js';
	public static $instance;

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function admin_menu() {
		add_menu_page(
			'ChatGPT Requester',
			'ChatGPT Requester',
			'manage_options',
			'chatgpt_requester',
			[$this, 'show_options_page'],
			'',
			1
		);
	}

	public function show_options_page() {
		echo $this->get_options_page_html();
	}

	private function get_options_page_html() {
		$secret_key = get_option( 'chatGPT_secret_key', '' );
		$html = <<< HTML
		<h1>ChatGPT Requester</h1>
		<form action="" onsubmit="return false;">
			<textarea name="request_message" id="chatGPT_request_message_field" cols="40" rows="10" placeholder="メッセージを入力してください"></textarea>
			<button type="button" name="post" id="chatGPT_request_button">送信</button>
		</form>

		<form action="" onsubmit="return false;">
			<input type="password" name="secret_key" id="chatGPT_secret_key_field" value="{$secret_key}">
			<button type="button" name="post" id="chatGPT_setting_save_button">保存</button>
		</form>

		<div id="chatGPT_response_area">
			<p></p>
		</div>
HTML;
		return $html;
	}

	public function enqueue_scripts() {
		$handle = 'chatgpt_requester';
		$dir_path = self::JS_DIR_PATH;
		foreach ( glob( CHATGPT_REQUESTER_PATH . "/{$dir_path}/*.js" ) as $filepath ) {
			$src = CHATGPT_REQUESTER_URL . "{$dir_path}/" . basename( $filepath );
			wp_enqueue_script( "{$handle}_js", $src, $handle );
		}
		wp_localize_script( "{$handle}_js", 'CHATGPT_REQUESTER_ENDPOINT', CHATGPT_REQUESTER_URL . 'endpoints/request.php' );
		wp_localize_script( "{$handle}_js", 'CHATGPT_SETTING_ENDPOINT', CHATGPT_REQUESTER_URL . 'endpoints/setting.php' );
	}
}

ChatGPT_Requester::get_instance();
