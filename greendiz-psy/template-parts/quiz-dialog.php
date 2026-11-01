<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<dialog class="gz-dialog gz-dialog--side gz-dialog--left" id="gz-quiz" aria-labelledby="gz-quiz-title" data-quiz-config="<?php echo esc_attr( wp_json_encode( gdpsy_quiz_config() ) ); ?>">
      <div class="gz-dialog-head">
        <div class="gz-quiz-heading">
          <p class="gz-quiz-eyebrow">Подбор сайта</p>
          <h2 id="gz-quiz-title">
            <span>Что нужно</span>
            <em>вашей практике?</em>
          </h2>
        </div>
        <button class="gz-dialog-close gz-icon-action" type="button" data-close-dialog="" aria-label="Закрыть">×</button>
      </div>
      <div class="gz-quiz-progress" role="progressbar" aria-label="Прохождение подбора" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><span></span></div>
      <div class="gz-quiz-scroll">
        <div id="gz-quiz-content"></div>
        <details class="gz-menu-collapse gz-quiz-hint" id="gz-quiz-hint" hidden>
          <summary>На что влияет ответ <span class="line" aria-hidden="true"></span><span class="gz-menu-chevron" aria-hidden="true"></span></summary>
          <div class="gz-menu-collapse-content"><p id="gz-quiz-hint-text"></p></div>
        </details>
      </div>
      <div class="gz-quiz-actions gz-quiz-controls" id="gz-quiz-controls" hidden><button class="gz-inline-button gz-text-action" id="gz-quiz-back" type="button">Назад</button><button class="gz-primary gz-button" id="gz-quiz-next" type="button" disabled>Дальше →</button></div>
      <div class="gz-quiz-summary" id="gz-quiz-summary" hidden></div>
      <p class="gz-form-status gz-sr-only" id="gz-quiz-status" aria-live="polite"></p>
    </dialog>
