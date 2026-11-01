<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function gdpsy_register_submissions() {
	$capabilities = array_fill_keys( array( 'edit_post', 'read_post', 'delete_post', 'edit_posts', 'edit_others_posts', 'publish_posts', 'read_private_posts', 'delete_posts', 'delete_private_posts', 'delete_published_posts', 'delete_others_posts', 'edit_private_posts', 'edit_published_posts' ), 'manage_options' );
	$capabilities['create_posts'] = 'do_not_allow';
	register_post_type( 'gdpsy_submission', array(
		'labels' => array( 'name' => 'Обращения и опросы', 'singular_name' => 'Обращение', 'menu_name' => 'Обращения и опросы' ),
		'public' => false, 'publicly_queryable' => false, 'exclude_from_search' => true,
		'show_ui' => true, 'show_in_rest' => false, 'can_export' => false, 'rewrite' => false, 'query_var' => false,
		'supports' => array( 'title' ), 'menu_icon' => 'dashicons-email-alt',
		'capabilities' => $capabilities, 'map_meta_cap' => false,
	) );
}
add_action( 'init', 'gdpsy_register_submissions' );

function gdpsy_submission_box( $post ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$rows = get_post_meta( $post->ID, '_gdpsy_answers', true );
	foreach ( is_array( $rows ) ? $rows : array() as $label => $value ) {
		echo '<p><strong>' . esc_html( $label ) . ':</strong><br>' . nl2br( esc_html( gdpsy_text( $value ) ) ) . '</p>';
	}
	$status = get_post_meta( $post->ID, '_gdpsy_mail', true );
	echo '<p><strong>Уведомление по email:</strong> ' . esc_html( $status === 'not_sent' ? 'Для незавершённого опроса уведомление не отправляется.' : ( $status === 'accepted' ? 'Передано почтовой системе; доставка не подтверждена.' : 'Не отправлено. Обращение сохранено здесь.' ) ) . '</p>';
}

function gdpsy_submission_meta_boxes() {
	add_meta_box( 'gdpsy-submission-data', 'Полученные данные', 'gdpsy_submission_box', 'gdpsy_submission', 'normal', 'high' );
}
add_action( 'add_meta_boxes_gdpsy_submission', 'gdpsy_submission_meta_boxes' );

function gdpsy_form_fields( $kind ) {
	?>
	<input type="hidden" name="action" value="gdpsy_submit">
	<input type="hidden" name="kind" value="<?php echo esc_attr( $kind ); ?>">
	<input type="hidden" name="gdpsy_nonce" value="<?php echo esc_attr( wp_create_nonce( 'gdpsy_submit' ) ); ?>">
	<div class="gdpsy-honeypot" aria-hidden="true"><label>Оставьте поле пустым<input type="text" name="website" value="" autocomplete="off" tabindex="-1"></label></div>
	<?php
}

function gdpsy_post_text( $key, $limit = 5000 ) {
	if ( ! isset( $_POST[ $key ] ) || ! is_string( $_POST[ $key ] ) ) {
		return '';
	}
	$value = wp_unslash( $_POST[ $key ] );
	if ( strlen( $value ) > $limit * 4 ) {
		return '';
	}
	$value = sanitize_textarea_field( $value );
	return mb_strlen( $value ) <= $limit ? trim( $value ) : '';
}

function gdpsy_cabinet_choices( $key, $allowed, $required = true ) {
	$values = $_POST[ $key ] ?? array();
	if ( ! is_array( $values ) || count( $values ) > count( $allowed ) || ( $required && ! $values ) ) {
		return new WP_Error( 'cabinet_answers', 'Проверьте ответы опроса.' );
	}
	$clean = array();
	foreach ( $values as $value ) {
		if ( ! is_string( $value ) || ! in_array( wp_unslash( $value ), $allowed, true ) ) {
			return new WP_Error( 'cabinet_answers', 'Проверьте выбранные возможности.' );
		}
		$clean[] = wp_unslash( $value );
	}
	return array_values( array_unique( $clean ) );
}

function gdpsy_cabinet_survey_rows( $draft = false ) {
	$email = gdpsy_post_text( 'email', 200 );
	$login = gdpsy_post_text( 'client_login', 20 );
	$logins = array( 'yes' => 'Да', 'maybe' => 'Возможно', 'no' => 'Нет', 'unknown' => 'Пока не знаю' );
	if ( ( ! $draft && ( ! is_email( $email ) || ! isset( $logins[ $login ] ) ) ) || ( $draft && $login !== '' && ! isset( $logins[ $login ] ) ) ) {
		return new WP_Error( 'cabinet_answers', 'Проверьте email и ответы опроса.' );
	}
	$tools = gdpsy_cabinet_choices( 'tools', array( 'Календарь', 'Таблицы', 'Бумажные записи', 'Специальный сервис' ), ! $draft );
	$features = gdpsy_cabinet_choices( 'cabinet_features', array( 'Расписание', 'Учёт оплат', 'Заметки', 'История встреч', 'Статистика' ), ! $draft );
	$client_features = $login === 'no' || $login === '' ? array() : gdpsy_cabinet_choices( 'client_features', array( 'Свои записи', 'Перенос или отмена встречи', 'Оплаты', 'Материалы', 'Приобретённые курсы' ), false );
	foreach ( array( $tools, $features, $client_features ) as $answer ) {
		if ( is_wp_error( $answer ) ) {
			return $answer;
		}
	}
	$rows = $draft ? array( 'Статус опроса' => 'Форма не заполнена до конца' ) : array( 'Статус опроса' => 'Завершён', 'Имя' => gdpsy_post_text( 'name', 100 ), 'Email' => $email );
	$rows['Как организована практика'] = $tools ? implode( ', ', $tools ) : 'Не отвечено';
	$rows['Функции кабинета специалиста'] = $features ? implode( ', ', $features ) : 'Не отвечено';
	$rows['Личный вход клиентов'] = $logins[ $login ] ?? 'Не отвечено';
	if ( $login !== 'no' && $login !== '' ) {
		$rows['Функции для клиентов'] = $client_features ? implode( ', ', $client_features ) : 'Не выбрано';
	}
	$rows['Главная задача кабинета'] = gdpsy_post_text( 'comment', 2000 );
	if ( ! $draft ) {
		$rows['Письмо о запуске'] = gdpsy_post_text( 'launch', 10 ) === 'yes' ? 'Да' : 'Нет';
		$rows['Скидка на модуль кабинета специалиста после запуска'] = '20%';
	}
	return $rows;
}

function gdpsy_cabinet_draft_key() {
	$token = gdpsy_post_text( 'draft_token', 64 );
	return preg_match( '/\A[a-f0-9]{64}\z/', $token ) ? hash_hmac( 'sha256', $token, wp_salt( 'nonce' ) ) : '';
}

function gdpsy_cabinet_draft_id( $key ) {
	if ( $key === '' ) {
		return 0;
	}
	$ids = get_posts( array( 'post_type' => 'gdpsy_submission', 'post_status' => 'private', 'posts_per_page' => 1, 'fields' => 'ids', 'no_found_rows' => true, 'meta_query' => array( array( 'key' => '_gdpsy_draft_key', 'value' => $key ), array( 'key' => '_gdpsy_kind', 'value' => 'cabinet_survey' ) ) ) );
	return $ids ? (int) $ids[0] : 0;
}

function gdpsy_cabinet_lock( $key ) {
	$lock = 'gdpsy_cabinet_lock_' . $key;
	if ( add_option( $lock, time(), '', false ) ) {
		return $lock;
	}
	if ( (int) get_option( $lock ) > time() - 60 ) {
		return false;
	}
	delete_option( $lock );
	return add_option( $lock, time(), '', false ) ? $lock : false;
}

function gdpsy_save_cabinet_draft() {
	$key = gdpsy_cabinet_draft_key();
	if ( ! wp_doing_ajax() || $key === '' ) {
		gdpsy_submission_result( false, 'Не удалось сохранить незавершённые ответы.', 400 );
	}
	$rows = gdpsy_cabinet_survey_rows( true );
	if ( is_wp_error( $rows ) || $rows['Как организована практика'] === 'Не отвечено' ) {
		gdpsy_submission_result( false, 'Проверьте ответы опроса.', 400 );
	}
	$steps = array( 'tools' => 'Организация практики', 'features' => 'Функции кабинета', 'client_login' => 'Личный вход клиентов', 'client_features' => 'Функции для клиентов', 'goal' => 'Главная задача и отправка' );
	$step = gdpsy_post_text( 'draft_step', 30 );
	if ( ! isset( $steps[ $step ] ) ) {
		gdpsy_submission_result( false, 'Неизвестный шаг опроса.', 400 );
	}
	$rows['Текущий шаг'] = $steps[ $step ];
	$rows['Скидка'] = 'Не закреплена: опрос не отправлен';
	$remote = is_string( $_SERVER['REMOTE_ADDR'] ?? null ) ? $_SERVER['REMOTE_ADDR'] : '';
	$rate = 'gdpsy_draft_rate_' . hash_hmac( 'sha256', $remote, wp_salt( 'nonce' ) );
	$writes = (int) get_transient( $rate );
	if ( $writes >= 60 ) {
		gdpsy_submission_result( false, 'Слишком много сохранений. Попробуйте позже.', 429 );
	}
	$lock = gdpsy_cabinet_lock( $key );
	if ( ! $lock ) {
		gdpsy_submission_result( false, 'Сохранение уже обрабатывается.', 409 );
	}
	$id = gdpsy_cabinet_draft_id( $key );
	if ( $id && get_post_meta( $id, '_gdpsy_state', true ) === 'complete' ) {
		delete_option( $lock );
		wp_send_json_success( array( 'complete' => true ) );
	}
	$create_rate = 'gdpsy_draft_create_' . hash_hmac( 'sha256', $remote, wp_salt( 'nonce' ) );
	$created = (int) get_transient( $create_rate );
	if ( ! $id && $created >= 8 ) {
		delete_option( $lock );
		gdpsy_submission_result( false, 'Слишком много новых опросов. Попробуйте позже.', 429 );
	}
	$entry = array( 'post_type' => 'gdpsy_submission', 'post_status' => 'private', 'post_title' => 'Опрос кабинета — форма не заполнена до конца — ' . current_time( 'd.m.Y H:i' ), 'meta_input' => array( '_gdpsy_kind' => 'cabinet_survey', '_gdpsy_state' => 'incomplete', '_gdpsy_draft_key' => $key, '_gdpsy_answers' => wp_slash( $rows ), '_gdpsy_mail' => 'not_sent' ) );
	if ( $id ) {
		$entry['ID'] = $id;
	}
	$saved = wp_insert_post( $entry, true );
	delete_option( $lock );
	if ( is_wp_error( $saved ) || ! $saved ) {
		gdpsy_submission_result( false, 'Не удалось сохранить ответы.', 500 );
	}
	set_transient( $rate, $writes + 1, 10 * MINUTE_IN_SECONDS );
	if ( ! $id ) {
		set_transient( $create_rate, $created + 1, 10 * MINUTE_IN_SECONDS );
	}
	wp_send_json_success( array( 'saved' => true ) );
}

function gdpsy_contact_success() {
	$token = $_GET['gdpsy-contact-receipt'] ?? '';
	return is_string( $token ) && preg_match( '/\A[a-zA-Z0-9]{48}\z/', $token ) && get_transient( 'gdpsy_receipt_' . hash( 'sha256', $token ) ) === 'contact';
}

function gdpsy_contact_receipt_headers() {
	if ( ! isset( $_GET['gdpsy-contact-receipt'] ) ) {
		return;
	}
	if ( ! defined( 'DONOTCACHEPAGE' ) ) {
		define( 'DONOTCACHEPAGE', true );
	}
	nocache_headers();
}
add_action( 'template_redirect', 'gdpsy_contact_receipt_headers' );

function gdpsy_submission_result( $success, $message, $status = 200 ) {
	if ( wp_doing_ajax() ) {
		if ( $success ) {
			wp_send_json_success( array( 'message' => $message ), $status );
		}
		wp_send_json_error( array( 'message' => $message ), $status );
	}
	if ( ! $success ) {
		wp_die( esc_html( $message ), 'Отправка формы', array( 'response' => $status, 'back_link' => true ) );
	}
	$url = wp_get_referer() ?: home_url( '/' );
	$url = remove_query_arg( array( 'gdpsy-sent', 'gdpsy-contact-receipt' ), $url );
	if ( gdpsy_post_text( 'kind', 20 ) === 'contact' ) {
		$token = wp_generate_password( 48, false, false );
		set_transient( 'gdpsy_receipt_' . hash( 'sha256', $token ), 'contact', 10 * MINUTE_IN_SECONDS );
		$url = preg_replace( '/#.*$/', '', $url );
		$url = add_query_arg( 'gdpsy-contact-receipt', $token, $url ) . '#gz-contact';
	} else {
		$url = add_query_arg( 'gdpsy-sent', '1', $url );
	}
	wp_safe_redirect( $url );
	exit;
}

function gdpsy_submit() {
	if ( ( $_SERVER['REQUEST_METHOD'] ?? '' ) !== 'POST' ) {
		gdpsy_submission_result( false, 'Используйте форму на сайте.', 405 );
	}
	if ( ! wp_verify_nonce( gdpsy_post_text( 'gdpsy_nonce', 100 ), 'gdpsy_submit' ) ) {
		gdpsy_submission_result( false, 'Обновите страницу и попробуйте ещё раз.', 403 );
	}
	if ( ! isset( $_POST['website'] ) || ! is_string( $_POST['website'] ) || $_POST['website'] !== '' ) {
		gdpsy_submission_result( false, 'Не удалось отправить форму.', 400 );
	}
	$kind = gdpsy_post_text( 'kind', 20 );
	if ( $kind === 'cabinet_draft' ) {
		gdpsy_save_cabinet_draft();
	}
	if ( ! in_array( $kind, array( 'contact', 'survey', 'cabinet_survey' ), true ) || gdpsy_post_text( 'consent', 10 ) !== '1' ) {
		gdpsy_submission_result( false, 'Подтвердите согласие на обработку данных.', 400 );
	}
	$rows = array();
	$reply_email = '';
	if ( $kind === 'contact' ) {
		$message = gdpsy_post_text( 'message' );
		$contact = gdpsy_post_text( 'contact', 200 );
		$reply = gdpsy_post_text( 'reply', 20 );
		$methods = array( 'phone' => 'Телефон', 'email' => 'Email', 'telegram' => 'Telegram', 'max' => 'MAX' );
		$digits = preg_replace( '/\D/', '', $contact );
		if ( $message === '' || $contact === '' || ! isset( $methods[ $reply ] ) || ( $reply === 'email' && ! is_email( $contact ) ) || ( $reply === 'phone' && ( strlen( $digits ) < 10 || strlen( $digits ) > 15 ) ) || preg_match( '/[\r\n]/', $contact ) ) {
			gdpsy_submission_result( false, 'Проверьте сообщение и контакт для ответа.', 400 );
		}
		$rows = array( 'Сообщение' => $message, 'Способ связи' => $methods[ $reply ], 'Контакт' => $contact );
		$reply_email = $reply === 'email' ? $contact : '';
	} elseif ( $kind === 'cabinet_survey' ) {
		$rows = gdpsy_cabinet_survey_rows();
		if ( is_wp_error( $rows ) ) {
			gdpsy_submission_result( false, $rows->get_error_message(), 400 );
		}
		$reply_email = $rows['Email'];
	} else {
		$email = gdpsy_post_text( 'email', 200 );
		$program = gdpsy_post_text( 'program', 100 );
		$interest = gdpsy_post_text( 'interest', 20 );
		$interests = array( 'yes' => 'Да', 'maybe' => 'Возможно, хочу разобраться', 'no' => 'Пока нет' );
		$allowed_programs = array( 'Уже продаю', 'Готовлю к запуску', 'Пока только идея' );
		$allowed_features = array( 'Продажа записанного курса', 'Уроки по расписанию', 'Задания и обратная связь', 'Подписка на материалы', 'Укажу в комментарии' );
		$features = $_POST['features'] ?? array();
		if ( ! is_email( $email ) || ( isset( $_POST['interest'] ) && ! isset( $interests[ $interest ] ) ) || ! in_array( $program, $allowed_programs, true ) || ! is_array( $features ) || count( $features ) > count( $allowed_features ) ) {
			gdpsy_submission_result( false, 'Проверьте email и ответы опроса.', 400 );
		}
		foreach ( $features as $feature ) {
			if ( ! is_string( $feature ) || ! in_array( wp_unslash( $feature ), $allowed_features, true ) ) {
				gdpsy_submission_result( false, 'Проверьте выбранные возможности.', 400 );
			}
		}
		$rows = array( 'Имя' => gdpsy_post_text( 'name', 100 ), 'Email' => $email, 'Интерес к курсам на своём сайте' => $interests[ $interest ] ?? 'Не отвечено', 'Программа' => $program, 'Нужные функции' => implode( ', ', array_unique( wp_unslash( $features ) ) ), 'Комментарий' => gdpsy_post_text( 'comment', 2000 ), 'Письмо о запуске' => gdpsy_post_text( 'launch', 10 ) === 'yes' ? 'Да' : 'Нет', 'Скидка на модуль курсов, если он будет запущен' => '20%' );
		$reply_email = $email;
	}
	$draft_key = $kind === 'cabinet_survey' ? gdpsy_cabinet_draft_key() : '';
	$hash = hash_hmac( 'sha256', $kind . wp_json_encode( $rows ) . $draft_key, wp_salt( 'nonce' ) );
	$duplicate = 'gdpsy_sent_' . $hash;
	if ( get_transient( $duplicate ) ) {
		gdpsy_submission_result( true, 'Спасибо! Обращение уже сохранено.' );
	}
	$remote = is_string( $_SERVER['REMOTE_ADDR'] ?? null ) ? $_SERVER['REMOTE_ADDR'] : '';
	$rate = 'gdpsy_rate_' . hash_hmac( 'sha256', $remote, wp_salt( 'nonce' ) );
	$attempts = (int) get_transient( $rate );
	if ( $attempts >= 8 ) {
		gdpsy_submission_result( false, 'Слишком много отправок. Попробуйте через несколько минут.', 429 );
	}
	$lock = 'gdpsy_lock_' . $hash;
	if ( ! add_option( $lock, time(), '', false ) ) {
		$started = (int) get_option( $lock );
		if ( $started > time() - 60 ) {
			gdpsy_submission_result( false, 'Отправка уже обрабатывается. Подождите немного.', 409 );
		}
		delete_option( $lock );
		if ( ! add_option( $lock, time(), '', false ) ) {
			gdpsy_submission_result( false, 'Попробуйте отправить чуть позже.', 409 );
		}
	}
	$rows['Согласие на обработку данных'] = 'Да';
	$rows['Дата согласия (UTC)'] = current_time( 'mysql', true );
	$rows['Политика конфиденциальности'] = gdpsy_privacy_url();
	$title = $kind === 'cabinet_survey' ? 'Опрос кабинета специалиста' : ( $kind === 'survey' ? 'Опрос курсов' : 'Обращение о сайте' );
	$draft_lock = $draft_key !== '' ? gdpsy_cabinet_lock( $draft_key ) : '';
	if ( $draft_lock === false ) {
		delete_option( $lock );
		gdpsy_submission_result( false, 'Автосохранение ещё обрабатывается. Повторите отправку.', 409 );
	}
	$draft_id = gdpsy_cabinet_draft_id( $draft_key );
	if ( $draft_id && get_post_meta( $draft_id, '_gdpsy_state', true ) === 'complete' ) {
		delete_option( $draft_lock );
		delete_option( $lock );
		gdpsy_submission_result( true, 'Спасибо! Опрос уже отправлен. Скидка закреплена за вашим email.' );
	}
	$meta = array( '_gdpsy_kind' => $kind, '_gdpsy_answers' => wp_slash( $rows ), '_gdpsy_mail' => 'failed' );
	if ( $kind === 'cabinet_survey' ) {
		$meta['_gdpsy_state'] = 'complete';
		if ( $draft_key !== '' ) {
			$meta['_gdpsy_draft_key'] = $draft_key;
		}
	}
	$entry = array( 'post_type' => 'gdpsy_submission', 'post_status' => 'private', 'post_title' => $title . ' — ' . current_time( 'd.m.Y H:i' ), 'meta_input' => $meta );
	if ( $draft_id ) {
		$entry['ID'] = $draft_id;
	}
	$id = wp_insert_post( $entry, true );
	if ( $draft_lock ) {
		delete_option( $draft_lock );
	}
	if ( is_wp_error( $id ) || ! $id ) {
		delete_option( $lock );
		gdpsy_submission_result( false, 'Не удалось сохранить обращение. Попробуйте позже.', 500 );
	}
	set_transient( $duplicate, $id, 10 * MINUTE_IN_SECONDS );
	set_transient( $rate, $attempts + 1, 10 * MINUTE_IN_SECONDS );
	$lines = array();
	foreach ( $rows as $label => $value ) {
		$lines[] = $label . ': ' . $value;
	}
	$lines[] = 'В админке: ' . admin_url( 'post.php?post=' . $id . '&action=edit' );
	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	if ( $reply_email !== '' ) {
		$headers[] = 'Reply-To: ' . sanitize_email( $reply_email );
	}
	$recipient = gdpsy_email();
	$sent = $recipient !== '' && wp_mail( $recipient, $title, implode( "\n\n", $lines ), $headers );
	update_post_meta( $id, '_gdpsy_mail', $sent ? 'accepted' : 'failed' );
	delete_option( $lock );
	gdpsy_submission_result( true, $kind === 'cabinet_survey' ? 'Спасибо! Опрос пройден. Ответы сохранены. Скидка 20% на модуль кабинета специалиста после запуска закреплена за вашим email.' : ( $kind === 'survey' ? 'Спасибо! Ответы сохранены. Скидка 20% закреплена за вашим email. При запуске модуля она будет действовать на его подключение.' : 'Спасибо! Обращение сохранено. Отвечу в течение рабочего дня.' ) );
}

function gdpsy_fresh_nonce() {
	if ( ( $_SERVER['REQUEST_METHOD'] ?? '' ) !== 'POST' ) {
		wp_send_json_error( null, 405 );
	}
	nocache_headers();
	wp_send_json_success( array( 'nonce' => wp_create_nonce( 'gdpsy_submit' ) ) );
}
add_action( 'wp_ajax_gdpsy_nonce', 'gdpsy_fresh_nonce' );
add_action( 'wp_ajax_nopriv_gdpsy_nonce', 'gdpsy_fresh_nonce' );

add_action( 'wp_ajax_gdpsy_submit', 'gdpsy_submit' );
add_action( 'wp_ajax_nopriv_gdpsy_submit', 'gdpsy_submit' );
add_action( 'admin_post_gdpsy_submit', 'gdpsy_submit' );
add_action( 'admin_post_nopriv_gdpsy_submit', 'gdpsy_submit' );

