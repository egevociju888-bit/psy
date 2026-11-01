<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$complete = gdpsy_contact_success();
$gdpsy_contact_number = is_front_page() ? '' : '10';
$heading_tag = ! empty( $args['page'] ) ? 'h1' : 'h2';
?>
<section class="gz-block gz-contact gz-inner<?php echo ! empty( $args['dark'] ) ? ' gz-contact--dark' : ( ! empty( $args['light'] ) ? ' gz-contact--light' : '' ); ?>" id="gz-contact" data-contact-variant="main"<?php echo '' !== $gdpsy_contact_number ? ' data-screen-number="' . esc_attr( $gdpsy_contact_number ) . '"' : ''; ?> aria-labelledby="gz-contact-title"<?php echo ! empty( $args['popup_source'] ) ? ' data-popup-source' : ''; ?>>
<?php if ( empty( $args['page'] ) ) : ?>
<div class="gz-screen-meta">
  <div class="gz-index"><?php if ( '' !== $gdpsy_contact_number ) : ?><span><?php echo esc_html( $gdpsy_contact_number ); ?></span><span class="gz-screen-slash" aria-hidden="true">/</span><?php endif; ?><span>Ваш первый шаг</span></div>
  <div class="gz-screen-caption">Личный ответ</div>
</div>
<?php endif; ?>
<div class="gz-contact-copy">
  <p class="gz-contact-availability">Принимаю заказы на <?php echo esc_html( gdpsy_contact_month() ); ?></p>
  <<?php echo $heading_tag; ?> class="gz-screen-title" id="gz-contact-title">Давайте обсудим ваш сайт</<?php echo $heading_tag; ?>>
  <p>Расскажите о задаче — даже пары предложений достаточно. В форме или сразу в мессенджере. Отвечу в течение рабочего дня лично, без ботов и отдела продаж.</p>

</div>
<form class="gz-contact-form" id="gz-contact-form" aria-label="Обсудить сайт" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"<?php echo $complete ? ' data-contact-complete="true"' : ''; ?>>
<div class="gz-contact-inputs"<?php echo $complete ? ' hidden' : ''; ?>><?php gdpsy_form_fields( 'contact' ); ?>
  <label for="gz-message">Ваше сообщение</label>
  <p class="gz-message-hint" id="gz-message-hint">Можно изменить или скопировать для мессенджера.</p>
  <div class="gz-message-box">
    <textarea class="gz-field" id="gz-message" name="message" rows="4" maxlength="5000" required aria-describedby="gz-message-hint"><?php echo esc_textarea( gdpsy_text( $args['message'] ?? 'Здравствуйте, Анастасия! Мне нужен сайт, давайте обсудим.' ) ); ?></textarea>
    <div class="gz-message-actions">
      <button class="gz-text-action" type="button" data-message-action="clear">Очистить</button>
      <button class="gz-text-action" type="button" data-message-action="default">Готовый текст</button>
      <button class="gz-message-copy gz-button" type="button" data-message-action="copy"><svg viewBox="0 0 24 24" aria-hidden="true" fill="none"><rect x="8" y="7" width="12" height="14" rx="1"/><path d="M16 7V3H4v14h4"/></svg><span class="gz-message-copy-label">Скопировать текст</span></button>
    </div>
  </div>
  <p class="gz-contact-feedback" id="gz-contact-status" role="status" aria-live="polite"></p>
  <button class="gz-contact-toggle gz-button<?php echo ! empty( $args['dark'] ) ? ' gz-button--black' : ' gz-button--navy'; ?>" type="button" aria-expanded="true" aria-controls="gz-contact-reveal">Оставить контакт<svg viewBox="0 0 24 24" aria-hidden="true" fill="none"><path d="m6 9 6 6 6-6"/></svg></button>
  <div class="gz-contact-reveal is-open" id="gz-contact-reveal">
    <div class="gz-contact-reveal-inner">
      <fieldset class="gz-contact-fields" aria-label="Контакт для ответа">
        <fieldset class="gz-reply-options">
          <legend>Где вам ответить?</legend>
          <label><input type="radio" name="reply" value="phone" checked>Телефон</label>
          <label><input type="radio" name="reply" value="email">Почта</label>
          <label><input type="radio" name="reply" value="telegram">Telegram</label>
          <label><input type="radio" name="reply" value="max">MAX</label>
        </fieldset>
        <label for="gz-reply-contact" id="gz-reply-label">Номер телефона</label>
        <input class="gz-field" id="gz-reply-contact" name="contact" maxlength="200" type="tel" autocomplete="tel" required placeholder="+7 …">
        <?php get_template_part( 'template-parts/consent-field' ); ?>
        <button type="submit" class="gz-primary gz-button<?php echo ! empty( $args['dark'] ) ? ' gz-button--black' : ' gz-button--navy'; ?>">Отправить заявку</button>
      </fieldset>
    </div>
  </div>
</div>
<div class="gz-contact-success"<?php echo $complete ? '' : ' hidden'; ?>>
  <span class="gz-contact-success-icon" aria-hidden="true"><svg viewBox="0 0 64 64" fill="none"><path d="m18 32 10 10 19-21" /></svg></span>
  <h3 id="gz-contact-success-title" tabindex="-1" aria-describedby="gz-contact-success-copy gz-contact-success-note">Спасибо,<br>заявка принята.</h3>
  <p id="gz-contact-success-copy">Ваше сообщение сохранено. <br>Отвечу лично в течение рабочего дня.</p>
  <p id="gz-contact-success-note">Свяжусь в выбранном канале</p>
</div>
</form>
<aside class="gz-contact-direct" aria-labelledby="gz-contact-direct-title">
          <h3 id="gz-contact-direct-title">для связи напрямую</h3>
          <div class="gz-direct-links">
            <?php if ( gdpsy_messenger_url( 'telegram' ) !== '' ) : ?><a href="<?php echo esc_url( gdpsy_messenger_url( 'telegram' ) ); ?>" target="_blank" rel="noopener noreferrer">
              <span class="gz-contact-icon" aria-hidden="true"><svg class="gz-icon-filled" viewBox="0 0 32 32" aria-hidden="true"><path d="M0.724 9.787c-0.188 0.063-0.355 0.177-0.481 0.329s-0.207 0.338-0.234 0.534c-0.026 0.196 0.003 0.396 0.085 0.577s0.212 0.335 0.377 0.445l9.363 5.162c0.175 0.096 0.373 0.141 0.572 0.13s0.391-0.079 0.553-0.194l9.947-7.093c0.481-0.322 1.056 0.253 0.734 0.734l-7.093 9.947c-0.115 0.162-0.183 0.354-0.194 0.553s0.034 0.397 0.13 0.571l5.162 9.363c0.11 0.165 0.264 0.295 0.445 0.377s0.38 0.111 0.577 0.085c0.196-0.026 0.381-0.107 0.534-0.234s0.267-0.293 0.329-0.481l9.733-29.199c0.062-0.186 0.071-0.386 0.026-0.578s-0.143-0.366-0.282-0.505c-0.139-0.139-0.314-0.236-0.505-0.282s-0.391-0.036-0.578 0.026l-29.199 9.733z" /></svg></span>
              <span class="gz-contact-channel"><b>Telegram</b><span>Личная переписка</span></span>
            </a><?php endif; ?>
            <?php if ( gdpsy_messenger_url( 'max' ) !== '' ) : ?><a href="<?php echo esc_url( gdpsy_messenger_url( 'max' ) ); ?>" target="_blank" rel="noopener noreferrer">
              <span class="gz-contact-icon" aria-hidden="true">
                <svg class="gz-icon-filled" viewBox="0 0 32 32" aria-hidden="true"><path d="M16.336 31.411c-3.056 0-4.477-0.445-6.944-2.23-1.562 2.006-6.506 3.574-6.72 0.893 0-2.016-0.445-3.718-0.95-5.574-0.605-2.291-1.29-4.842-1.29-8.534 0-8.826 7.242-15.462 15.821-15.462 8.586 0 15.315 6.966 15.315 15.546 0.029 8.445-6.787 15.318-15.232 15.363zM16.461 8.128c-4.179-0.214-7.434 2.675-8.157 7.213-0.592 3.754 0.461 8.323 1.363 8.563 0.432 0.106 1.517-0.774 2.192-1.45 1.12 0.774 2.422 1.238 3.776 1.347 4.33 0.208 8.029-3.088 8.32-7.411 0.17-4.333-3.165-8.003-7.494-8.253v-0.010z" /></svg>
                </span>
              <span class="gz-contact-channel"><b>MAX</b><span>Личная переписка</span></span>
            </a><?php endif; ?>
            <?php if ( gdpsy_tel_href() !== '' ) : ?><a class="gz-contact-phone" href="<?php echo esc_url( gdpsy_tel_href() ); ?>">
              <span class="gz-contact-icon" aria-hidden="true"><svg class="gz-icon-filled" viewBox="0 0 32 32" aria-hidden="true"><path d="M32 26.128c0 0.576-0.128 1.168-0.401 1.744s-0.625 1.12-1.090 1.632c-0.785 0.864-1.65 1.488-2.628 1.888-0.961 0.4-2.003 0.608-3.125 0.608-1.634 0-3.381-0.384-5.224-1.168s-3.686-1.84-5.512-3.168c-1.843-1.344-3.589-2.832-5.256-4.48-1.651-1.664-3.141-3.408-4.471-5.232-1.314-1.824-2.372-3.648-3.141-5.456-0.769-1.824-1.154-3.568-1.154-5.232 0-1.088 0.192-2.128 0.577-3.088 0.385-0.976 0.994-1.872 1.843-2.672 1.026-1.008 2.147-1.504 3.333-1.504 0.449 0 0.897 0.096 1.298 0.288 0.417 0.192 0.785 0.48 1.074 0.896l3.718 5.232c0.288 0.4 0.497 0.768 0.641 1.12 0.144 0.336 0.224 0.672 0.224 0.976 0 0.384-0.112 0.768-0.336 1.136-0.208 0.368-0.513 0.752-0.897 1.136l-1.218 1.264c-0.176 0.176-0.256 0.384-0.256 0.64 0 0.128 0.016 0.24 0.048 0.368 0.048 0.128 0.096 0.224 0.128 0.32 0.288 0.528 0.785 1.216 1.49 2.048 0.721 0.832 1.49 1.68 2.323 2.528 0.865 0.848 1.699 1.632 2.548 2.352 0.833 0.704 1.522 1.184 2.067 1.472 0.080 0.032 0.176 0.080 0.288 0.128 0.128 0.048 0.257 0.064 0.401 0.064 0.272 0 0.481-0.096 0.657-0.272l1.218-1.2c0.401-0.4 0.785-0.704 1.154-0.896 0.369-0.224 0.737-0.336 1.138-0.336 0.305 0 0.625 0.064 0.977 0.208s0.721 0.352 1.122 0.624l5.304 3.76c0.417 0.288 0.705 0.624 0.881 1.024 0.16 0.4 0.256 0.8 0.256 1.248z" /></svg></span>
              <span class="gz-contact-channel"><b><?php echo esc_html( gdpsy_phone() ); ?></b><span>Позвонить</span></span>
            <span class="gz-contact-hours"><span>будни</span> <span>9:00–20:00</span></span></a><?php endif; ?>
            <?php if ( gdpsy_email() !== '' ) : ?><a href="<?php echo esc_url( 'mailto:' . gdpsy_email() ); ?>">
              <span class="gz-contact-icon" aria-hidden="true"><svg class="gz-icon-filled" viewBox="0 0 32 32" aria-hidden="true"><path d="M5.333 2.667c-1.415 0-2.771 0.562-3.771 1.562s-1.562 2.357-1.562 3.771v0.536l16 8.616 16-8.613v-0.539c0-1.415-0.562-2.771-1.562-3.771s-2.357-1.562-3.771-1.562h-21.333zM32 11.565l-15.368 8.275c-0.194 0.105-0.411 0.159-0.632 0.159s-0.438-0.055-0.632-0.159l-15.368-8.275v12.435c0 1.414 0.562 2.771 1.562 3.771s2.357 1.562 3.771 1.562h21.333c1.414 0 2.771-0.562 3.771-1.562s1.562-2.357 1.562-3.771v-12.435z" /></svg></span>
              <span class="gz-contact-channel"><b><?php echo esc_html( gdpsy_email() ); ?></b><span>Написать письмо</span></span>
            </a><?php endif; ?>
          </div>
          <?php $channels = gdpsy_contact_channels(); if ( $channels ) : ?>
<nav class="gz-contact-social" aria-labelledby="gz-contact-social-title"><h3 id="gz-contact-social-title">Мои каналы, соцсети и кейсы</h3><div class="gz-social-links">
<?php foreach ( $channels as $channel ) : ?><a href="<?php echo esc_url( $channel['url'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $channel['label'] ); ?>" title="<?php echo esc_attr( $channel['label'] ); ?>"><?php get_template_part( 'template-parts/contact-channel-icon', null, array( 'icon' => $channel['icon'] ) ); ?></a><?php endforeach; ?>
</div></nav><?php endif; ?>
        </aside>
</section>
<?php if ( is_post_type_archive( 'portfolio' ) ) : ?><noscript><style>#greendiz-design-study #gz-contact[data-popup-source][hidden]{display:grid!important}</style></noscript><?php endif; ?>
