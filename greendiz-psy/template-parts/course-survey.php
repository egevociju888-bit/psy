<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<dialog class="gz-dialog" id="gz-course-survey" aria-labelledby="gz-survey-title" aria-describedby="gz-survey-intro">
  <div class="gz-dialog-head">
    <div class="gz-survey-heading"><p class="gz-survey-eyebrow">Опрос о курсах</p><h2 id="gz-survey-title"><span>Продажа курсов</span><em>на вашем сайте</em></h2></div>
    <button class="gz-dialog-close gz-icon-action" type="button" data-close-dialog aria-label="Закрыть опрос">×</button>
  </div>
  <p class="gz-survey-intro" id="gz-survey-intro" data-course-intro>Расскажите о своей программе и пожеланиях. Ответы помогут оценить спрос и определить состав будущего модуля. Скидка 20% на подключение при запуске закрепляется за вашим email после отправки ответов.</p>
  <form id="gz-survey-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"><?php gdpsy_form_fields( 'survey' ); ?>
    <p class="gz-quiz-step" data-course-step-label aria-live="polite" hidden></p>
    <div class="gz-quiz-progress" data-course-progress hidden role="progressbar" aria-label="Прохождение опроса о курсах" aria-valuemin="1" aria-valuemax="4" aria-valuenow="1"><span></span></div>
    <fieldset class="gz-survey-step" data-course-step="interest">
      <legend>Рассматриваете ли вы размещение курсов на своём сайте?</legend>
      <label class="gz-survey-choice"><input type="radio" name="interest" value="yes" required /><span>Да</span></label>
      <label class="gz-survey-choice"><input type="radio" name="interest" value="maybe" /><span>Возможно, хочу разобраться</span></label>
      <label class="gz-survey-choice"><input type="radio" name="interest" value="no" /><span>Пока нет</span></label>
    </fieldset>
    <fieldset class="gz-survey-step" data-course-step="program">
      <legend>Есть ли у вас программа?</legend>
      <label class="gz-survey-choice"><input type="radio" name="program" value="Уже продаю" /><span>Уже продаю</span></label>
      <label class="gz-survey-choice"><input type="radio" name="program" value="Готовлю к запуску" /><span>Готовлю к запуску</span></label>
      <label class="gz-survey-choice"><input type="radio" name="program" value="Пока только идея" checked /><span>Пока только идея</span></label>
    </fieldset>
    <fieldset class="gz-survey-step" data-course-step="features">
      <legend>Что вам нужно?<span>Можно выбрать несколько вариантов или пропустить</span></legend>
      <label class="gz-survey-choice"><input type="checkbox" name="features[]" value="Продажа записанного курса" /><span>Продажа записанного курса</span></label>
      <label class="gz-survey-choice"><input type="checkbox" name="features[]" value="Уроки по расписанию" /><span>Уроки по расписанию</span></label>
      <label class="gz-survey-choice"><input type="checkbox" name="features[]" value="Задания и обратная связь" /><span>Задания и обратная связь</span></label>
      <label class="gz-survey-choice"><input type="checkbox" name="features[]" value="Подписка на материалы" /><span>Подписка на материалы</span></label>
      <label class="gz-survey-choice"><input type="checkbox" name="features[]" value="Укажу в комментарии" /><span>Укажу в комментарии</span></label>
    </fieldset>
    <fieldset class="gz-survey-step" data-course-step="contact">
      <legend>Что ещё важно учесть?<span>Комментарий необязателен</span></legend>
      <div class="gz-survey-field gz-wide"><label class="gz-sr-only" for="gz-survey-comment">Комментарий</label><textarea id="gz-survey-comment" name="comment" maxlength="2000" rows="4"></textarea></div>
      <div class="gz-survey-field gz-wide gz-survey-offer"><b>Скидка 20% за участие в опросе</b><p>При запуске модуля скидка будет действовать на его подключение и закрепится за вашим email.</p></div>
      <div class="gz-survey-field"><label for="gz-survey-name">Имя</label><input id="gz-survey-name" type="text" name="name" maxlength="100" autocomplete="name" /></div>
      <div class="gz-survey-field"><label for="gz-survey-email">Email *</label><input id="gz-survey-email" type="email" name="email" maxlength="200" autocomplete="email" required /></div>
      <div class="gz-survey-field gz-wide gz-survey-actions">
        <?php get_template_part( 'template-parts/consent-field' ); ?>
        <label class="gz-consent"><input type="checkbox" name="launch" value="yes" /><span>Хочу получить письмо о запуске модуля</span></label>
      </div>
    </fieldset>
    <div class="gz-survey-controls" data-course-controls hidden><button class="gz-text-action" type="button" data-course-back>Назад</button><button class="gz-primary gz-button" type="button" data-course-next>Дальше →</button></div>
    <button class="gz-primary gz-button" type="submit">Отправить ответы</button>
    <p class="gz-form-status" id="gz-survey-status" aria-live="polite"></p>
    <p class="gz-footnote">Это опрос об интересе. Решение о запуске и состав модуля пока не определены.</p>
  </form>
  <section class="gz-contact-success" data-course-success hidden aria-labelledby="gz-course-success-title">
    <span class="gz-contact-success-icon" aria-hidden="true"><svg viewBox="0 0 64 64" fill="none"><path d="m18 32 10 10 19-21" /></svg></span>
    <h3 id="gz-course-success-title" tabindex="-1" aria-describedby="gz-course-success-copy">Спасибо,<br>опрос пройден.</h3>
    <p id="gz-course-success-copy">Ваши ответы сохранены. Скидка 20% на подключение модуля курсов при запуске закреплена за вашим email.</p>
  </section>
</dialog>
