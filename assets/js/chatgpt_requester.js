jQuery( function($) {
	const requestChatGPT = () => {
		const params = makeChatGPTRequestParams();

		if (!params) {
			return;
		}

		console.log(params);

		$.ajax({
			type: 'POST',
			url: CHATGPT_REQUESTER_ENDPOINT,
			data: {params: params},
		}).done(function(data) {
			$('#chatGPT_response_area p').text(data);
		}).fail(function(data) {
			$('#chatGPT_response_area p').text('error');
			console.log(data);
		});
	}

	const makeChatGPTRequestParams = () => {
		const message = $('#chatGPT_request_message_field').val();
		if (!message) {
			return '';
		}

		const params = JSON.stringify({
			'model': 'text-davinci-003',
			'prompt': message,
			'max_tokens': 4000,
			'temperature': 1.0
		});

		return params;
	}
	$('#chatGPT_request_button').on('click', requestChatGPT);

	const saveChatGPTSetting = () => {
		const value = $('#chatGPT_secret_key_field').val();

		if (!value) {
			return;
		}

		$.ajax({
			type: 'POST',
			url: CHATGPT_SETTING_ENDPOINT,
			data: {secret_key: value},
		}).done(function(data) {
			$('#chatGPT_response_area p').text('保存しました');
		}).fail(function(data) {
			$('#chatGPT_response_area p').text('error');
		});
	}

	$('#chatGPT_setting_save_button').on('click', saveChatGPTSetting);
} );