(function () {
  'use strict';
  const root = document.getElementById('greendiz-design-study');
  const dialog = root?.querySelector('#gz-quiz');
  if (!dialog || !window.GdpsyQuiz) return;
  window.GdpsyQuiz.create({
    root,
    dialog,
    actions: (quiz) => [
      quiz.action('Обсудить такой сайт', 'gz-primary gz-button', (event) => quiz.discuss(quiz.message(), event.currentTarget)),
      quiz.action('Изменить ответы', 'gz-inline-button gz-text-action', quiz.edit),
    ],
  });
})();
