<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<dialog class="gz-dialog gz-blog-dialog" id="gz-module-blog" aria-labelledby="gz-blog-title" aria-describedby="gz-blog-lead">
      <div class="gz-dialog-head">
        <div>

          <h2 id="gz-blog-title">Чтобы вас находили</h2>
        </div>
        <button class="gz-dialog-close gz-icon-action" type="button" data-close-dialog="" aria-label="Закрыть">×</button>
      </div>
      <div class="gz-dialog-content">
        <p class="gz-detail-description" id="gz-blog-lead">Помогу превратить ваши публикации в раздел сайта, которым удобно делиться с клиентами. Блог, анонсы в каналах и email-рассылку можно подключать по отдельности; для автопубликации нужен блог.</p>
        <div class="gz-detail-features">
          <section class="gz-detail-feature" aria-labelledby="gz-blog-feature-title">
            <div class="gz-detail-feature-head"><h3 id="gz-blog-feature-title">Блог</h3><span><?php echo esc_html( gdpsy_price_label( 'blog', '+' ) ); ?></span></div>
            <p>Раздел со статьями, рубриками и поиском. Пять стартовых статей подготовлю с вашим участием; настрою адреса страниц и SEO-плагин. Это техническая подготовка, без гарантии позиций и обращений.</p>
            <button class="gz-primary gz-button" type="button" data-add-modules="blog">Добавить к сайту</button>
          </section>
          <section class="gz-detail-feature" aria-labelledby="gz-blog-autopost-title">
            <div class="gz-detail-feature-head"><h3 id="gz-blog-autopost-title">Автопубликация</h3><span><?php echo esc_html( gdpsy_price_label( 'autopost', '+' ) ); ?></span></div>
            <p>Анонсы новых статей отправляются в Telegram, VK и MAX. Для этой возможности нужен блог.</p>
            <button class="gz-primary gz-button" type="button" data-add-modules="autopost" aria-label="Добавить автопубликацию к сайту">Добавить к сайту</button>
          </section>
          <section class="gz-detail-feature" aria-labelledby="gz-blog-mail-title">
            <div class="gz-detail-feature-head"><h3 id="gz-blog-mail-title">Email-рассылка</h3><span><?php echo esc_html( gdpsy_price_label( 'mail', '+' ) ); ?></span></div>
            <p>Форма подписки и шаблоны писем. Рассылка может работать самостоятельно, без блога.</p>
            <button class="gz-primary gz-button" type="button" data-add-modules="mail" aria-label="Добавить email-рассылку к сайту">Добавить к сайту</button>
          </section>
        </div>
      </div>
    </dialog>
    <dialog class="gz-dialog gz-detail-dialog" id="gz-module-booking" aria-labelledby="gz-booking-title" aria-describedby="gz-booking-lead">
      <div class="gz-dialog-head"><div><h2 id="gz-booking-title">Онлайн-запись и приём платежей</h2></div><button class="gz-dialog-close gz-icon-action" type="button" data-close-dialog aria-label="Закрыть">×</button></div>
      <div class="gz-dialog-content">
        <p class="gz-detail-description" id="gz-booking-lead">Идея — сократить путь от выбора времени до оплаты консультации. Готового модуля пока нет: изучаю задачи и сервисы, с которыми можно было бы связать сайт.</p>
        <div class="gz-detail-features">
          <section class="gz-detail-feature"><div class="gz-detail-feature-head"><h3>Выбор времени</h3></div><p>Возможные функции: свободные окна из календаря, подтверждения и напоминания о встрече.</p></section>
          <section class="gz-detail-feature"><div class="gz-detail-feature-head"><h3>Приём платежей</h3></div><p>Переход к оплате консультации, группы или программы. Приём платежей можно рассматривать отдельно от записи.</p></section>
          <section class="gz-detail-feature"><div class="gz-detail-feature-head"><h3>Под вашу практику</h3></div><p>Сервисы и требования к подключению нужно изучить под ваш формат работы. Состав, стоимость и сроки запуска пока не определены; в расчёт эти функции не включены.</p></section>
        </div>
        <div class="gz-detail-followup"><button class="gz-primary gz-button" type="button" data-open-dialog="gz-contact-dialog" data-contact-preset="Здравствуйте, Анастасия! Хочу обсудить возможную разработку онлайн-записи и приёма платежей на сайте." aria-haspopup="dialog" aria-controls="gz-contact-dialog">Обсудить задачу</button></div>
      </div>
    </dialog>
    <?php $gdpsy_tools = gdpsy_interactive_offer(); ?>
    <dialog class="gz-dialog gz-detail-dialog" id="gz-module-library" aria-labelledby="gz-library-title" aria-describedby="gz-library-lead">
      <div class="gz-dialog-head"><div><h2 id="gz-library-title">Тесты, квизы, упражнения</h2></div><button class="gz-dialog-close gz-icon-action" type="button" data-close-dialog aria-label="Закрыть">×</button></div>
      <div class="gz-dialog-content">
        <p class="gz-detail-description" id="gz-library-lead"><?php echo esc_html( $gdpsy_tools['story'][0] ); ?> Один интерактив уже предусмотрен в лендинге; здесь — варианты для дополнительных инструментов.</p>
        <div class="gz-detail-features">
          <section class="gz-detail-feature"><div class="gz-detail-feature-head"><h3>Дополнительный интерактив</h3><span><?php echo esc_html( gdpsy_price_label( 'toolkit', 'от ' ) ); ?></span></div><p><?php echo esc_html( $gdpsy_tools['features'][0]['text'] ); ?></p><button class="gz-primary gz-button" type="button" data-add-modules="toolkit">Добавить к сайту</button></section>
          <section class="gz-detail-feature"><div class="gz-detail-feature-head"><h3>Несколько инструментов</h3></div><p><?php echo esc_html( $gdpsy_tools['note'] ); ?></p><p>В калькуляторе можно указать количество дополнительных инструментов. Расчёт — по минимальной цене, без учёта скидки. Организацию каталога и PWA согласуем отдельно.</p><a class="gz-text-action" href="<?php echo esc_url( home_url( '/interactive/' ) ); ?>">Каталог интерактивов</a></section>
          <section class="gz-detail-feature"><div class="gz-detail-feature-head"><h3>Приложение на телефоне</h3></div><p><?php echo esc_html( $gdpsy_tools['features'][2]['text'] ); ?></p></section>
        </div>
      </div>
    </dialog>
    <dialog class="gz-dialog gz-detail-dialog" id="gz-module-courses" aria-labelledby="gz-courses-title" aria-describedby="gz-module-courses-lead">
      <div class="gz-dialog-head"><div><h2 id="gz-courses-title">Чтобы зарабатывать не только сессиями</h2></div><button class="gz-dialog-close gz-icon-action" type="button" data-close-dialog aria-label="Закрыть">×</button></div>
      <div class="gz-dialog-content">
        <p class="gz-detail-description" id="gz-module-courses-lead">Изучаю спрос на модуль для продажи программ и работы с участниками. Готового модуля пока нет; состав, стоимость и дата запуска не определены. Ваши ответы в опросе помогут выбрать полезные функции.</p>
        <div class="gz-detail-features"><section class="gz-detail-feature"><div class="gz-detail-feature-head"><h3>Материалы и уроки</h3></div><p>Можно предусмотреть видео, аудио и PDF, уроки по расписанию, задания и обратную связь.</p></section><section class="gz-detail-feature"><div class="gz-detail-feature-head"><h3>Личный кабинет</h3></div><p>Можно предусмотреть покупки и прогресс в одном месте, а также подписку на материалы.</p></section><section class="gz-detail-feature"><div class="gz-detail-feature-head"><h3>Повлиять на состав</h3></div><p>Можно рассказать о своей программе и пожеланиях, чтобы повлиять на состав будущего модуля.</p></section></div>
        <div class="gz-detail-followup"><button class="gz-primary gz-button" type="button" data-open-dialog="gz-course-survey">Рассказать о своих задачах</button></div>
      </div>
    </dialog>
    <?php get_template_part( 'template-parts/course-survey' ); ?>
    <dialog class="gz-dialog gz-detail-dialog" id="gz-module-cabinet" aria-labelledby="gz-cabinet-title" aria-describedby="gz-cabinet-lead">
      <div class="gz-dialog-head"><div><h2 id="gz-cabinet-title">Чтобы держать практику в порядке</h2></div><button class="gz-dialog-close gz-icon-action" type="button" data-close-dialog aria-label="Закрыть">×</button></div>
      <div class="gz-dialog-content">
        <p class="gz-detail-description" id="gz-cabinet-lead">Изучаю, какие привычные инструменты было бы удобно объединить на вашем сайте для ведения практики. Готового кабинета пока нет; состав, стоимость и дата запуска не определены. Опрос поможет выбрать приоритеты.</p>
        <div class="gz-detail-features">
          <section class="gz-detail-feature"><div class="gz-detail-feature-head"><h3>Работа специалиста</h3></div><p>Возможные функции: расписание встреч, учёт оплат, история сессий и рабочие заметки. Состав пока не определён.</p></section>
          <section class="gz-detail-feature"><div class="gz-detail-feature-head"><h3>Статистика и аналитика</h3></div><p>Исследую, какие показатели нужны для ведения практики и удобно ли смотреть их на сайте.</p></section>
          <section class="gz-detail-feature"><div class="gz-detail-feature-head"><h3>Нужен ли вход клиентам?</h3></div><p>Выясним, нужен ли клиентам свой вход на сайт и какие возможности там пригодятся.</p></section>
        </div>
        <div class="gz-detail-followup"><button class="gz-primary gz-button" type="button" data-open-dialog="gz-cabinet-survey">Рассказать о своих задачах</button></div>
      </div>
    </dialog>
    <dialog class="gz-dialog" id="gz-cabinet-survey" aria-labelledby="gz-cabinet-survey-title" aria-describedby="gz-cabinet-survey-intro">
      <div class="gz-dialog-head">
        <div class="gz-survey-heading"><p class="gz-survey-eyebrow">Опрос о кабинете специалиста</p><h2 id="gz-cabinet-survey-title"><span>Ваша практика</span><em>в одном месте</em></h2></div>
        <button class="gz-dialog-close gz-icon-action" type="button" data-close-dialog aria-label="Закрыть опрос">×</button>
      </div>
      <p class="gz-survey-intro" id="gz-cabinet-survey-intro" data-cabinet-intro>Расскажите, что поможет вам организовать работу. Нужны только пожелания о функциях — без имён и других сведений о клиентах. Незавершённые ответы сохраняются по мере прохождения опроса, без имени и email. Скидка закрепляется после отправки.</p>
      <form id="gz-cabinet-survey-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"><?php gdpsy_form_fields( 'cabinet_survey' ); ?>
        <input type="hidden" name="draft_token" value="" />
        <p class="gz-quiz-step" data-cabinet-step-label aria-live="polite" hidden></p>
        <div class="gz-quiz-progress" data-cabinet-progress hidden role="progressbar" aria-label="Прохождение опроса" aria-valuemin="1" aria-valuemax="5" aria-valuenow="1"><span></span></div>
        <fieldset class="gz-cabinet-step" data-cabinet-step="tools" data-cabinet-multiple>
          <legend>Как вы сейчас организуете практику?<span>Можно выбрать несколько вариантов</span></legend>
          <label class="gz-survey-choice"><input type="checkbox" name="tools[]" value="Календарь" /><span>Календарь</span></label>
          <label class="gz-survey-choice"><input type="checkbox" name="tools[]" value="Таблицы" /><span>Таблицы</span></label>
          <label class="gz-survey-choice"><input type="checkbox" name="tools[]" value="Бумажные записи" /><span>Бумажные записи</span></label>
          <label class="gz-survey-choice"><input type="checkbox" name="tools[]" value="Специальный сервис" /><span>Специальный сервис</span></label>
        </fieldset>
        <fieldset class="gz-cabinet-step" data-cabinet-step="features" data-cabinet-multiple>
          <legend>Что хотелось бы собрать в кабинете специалиста?<span>Можно выбрать несколько вариантов</span></legend>
          <label class="gz-survey-choice"><input type="checkbox" name="cabinet_features[]" value="Расписание" /><span>Расписание</span></label>
          <label class="gz-survey-choice"><input type="checkbox" name="cabinet_features[]" value="Учёт оплат" /><span>Учёт оплат</span></label>
          <label class="gz-survey-choice"><input type="checkbox" name="cabinet_features[]" value="Заметки" /><span>Заметки</span></label>
          <label class="gz-survey-choice"><input type="checkbox" name="cabinet_features[]" value="История встреч" /><span>История встреч</span></label>
          <label class="gz-survey-choice"><input type="checkbox" name="cabinet_features[]" value="Статистика" /><span>Статистика</span></label>
        </fieldset>
        <fieldset class="gz-cabinet-step" data-cabinet-step="client_login">
          <legend>Нужен ли вашим клиентам личный вход на вашем сайте?</legend>
          <label class="gz-survey-choice"><input type="radio" name="client_login" value="yes" required /><span>Да</span></label>
          <label class="gz-survey-choice"><input type="radio" name="client_login" value="maybe" /><span>Возможно</span></label>
          <label class="gz-survey-choice"><input type="radio" name="client_login" value="no" /><span>Нет</span></label>
          <label class="gz-survey-choice"><input type="radio" name="client_login" value="unknown" /><span>Пока не знаю</span></label>
        </fieldset>
        <fieldset class="gz-cabinet-step" data-cabinet-step="client_features">
          <legend>Что клиенту было бы полезно там видеть?<span>Можно выбрать несколько вариантов или пропустить. При ответе «Нет» на предыдущий вопрос этот шаг не нужен.</span></legend>
          <label class="gz-survey-choice"><input type="checkbox" name="client_features[]" value="Свои записи" /><span>Свои записи</span></label>
          <label class="gz-survey-choice"><input type="checkbox" name="client_features[]" value="Перенос или отмена встречи" /><span>Перенос или отмена встречи</span></label>
          <label class="gz-survey-choice"><input type="checkbox" name="client_features[]" value="Оплаты" /><span>Оплаты</span></label>
          <label class="gz-survey-choice"><input type="checkbox" name="client_features[]" value="Материалы" /><span>Материалы</span></label>
          <label class="gz-survey-choice"><input type="checkbox" name="client_features[]" value="Приобретённые курсы" /><span>Приобретённые курсы</span></label>
        </fieldset>
        <fieldset class="gz-cabinet-step" data-cabinet-step="goal">
          <legend>Какую главную задачу должен решить кабинет, чтобы вы захотели им пользоваться?<span>Ответ необязателен</span></legend>
          <div class="gz-survey-field gz-wide"><label class="gz-sr-only" for="gz-cabinet-comment">Главная задача кабинета</label><textarea id="gz-cabinet-comment" name="comment" maxlength="2000" rows="4"></textarea></div>
          <div class="gz-survey-field gz-wide gz-survey-offer"><b>Скидка 20% за участие в опросе</b><p>Действует на модуль кабинета специалиста после запуска и закрепляется за вашим email.</p></div>
          <div class="gz-survey-field"><label for="gz-cabinet-name">Имя</label><input id="gz-cabinet-name" type="text" name="name" maxlength="100" autocomplete="name" /></div>
          <div class="gz-survey-field"><label for="gz-cabinet-email">Email *</label><input id="gz-cabinet-email" type="email" name="email" maxlength="200" autocomplete="email" required /></div>
          <div class="gz-survey-field gz-wide gz-survey-actions">
            <?php get_template_part( 'template-parts/consent-field' ); ?>
            <label class="gz-consent"><input type="checkbox" name="launch" value="yes" /><span>Хочу получить письмо о запуске модуля</span></label>
          </div>
        </fieldset>
        <div class="gz-cabinet-controls" data-cabinet-controls hidden><button class="gz-text-action" type="button" data-cabinet-back>Назад</button><button class="gz-primary gz-button" type="button" data-cabinet-next>Дальше →</button></div>
        <button class="gz-primary gz-button" type="submit">Отправить ответы</button>
        <p class="gz-form-status" data-cabinet-status aria-live="polite"></p>
        <p class="gz-footnote">Это опрос об интересе. Цена и дата запуска пока не определены.</p>
      </form>
      <section class="gz-contact-success" data-cabinet-success hidden aria-labelledby="gz-cabinet-success-title">
        <span class="gz-contact-success-icon" aria-hidden="true"><svg viewBox="0 0 64 64" fill="none"><path d="m18 32 10 10 19-21" /></svg></span>
        <h3 id="gz-cabinet-success-title" tabindex="-1" aria-describedby="gz-cabinet-success-copy">Спасибо,<br>опрос пройден.</h3>
        <p id="gz-cabinet-success-copy">Ваши ответы сохранены. Скидка 20% на модуль кабинета специалиста после запуска закреплена за вашим email.</p>
      </section>
    </dialog>
    <?php get_template_part( 'template-parts/quiz-dialog' ); ?>
<div class="gz-mini-plan is-away" id="gz-mini-plan" hidden aria-hidden="true" inert>
      <button class="gz-mini-main" type="button" id="gz-mini-go" aria-label="Посмотреть состав и стоимость сайта">
        <span>Состав вашего сайта</span><b id="gz-mini-sum"></b>
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6"/></svg>
      </button>
      <button class="gz-mini-close gz-icon-action" type="button" id="gz-mini-close" aria-label="Скрыть расчёт"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg></button>
    </div>





