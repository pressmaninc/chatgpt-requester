<?php
require_once( '../../../../wp-load.php' );

$request = new ChatGPT_Request;
$request->post( $_POST['params'] );

class ChatGPT_Request {
	const ENDPOINT = 'https://api.openai.com/v1/completions';

	public function post( $params ) {
		$secret_key = get_option( 'chatGPT_secret_key', '' );
		pm_log($params);
		$params = str_replace('\"', '"', $params );
		pm_log(json_decode($params), true);
		pm_log($secret_key);
		if (!$secret_key) {
			echo 'secret_keyを設定してください';
			return;
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::ENDPOINT);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Content-Type:application/json',
			"Authorization: Bearer $secret_key"
		]);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$text = curl_exec($ch);
		pm_log($text);
		$response = json_decode( curl_exec($ch), true );
		curl_close($ch);

		pm_log($response['choices'][0]['text']);
		echo $response['choices'][0]['text'] ?? '';
		return;
	}
}