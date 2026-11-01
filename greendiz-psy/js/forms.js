(function () {
  'use strict';
  const pending = new WeakSet();
  const lifetime = 10 * 60 * 1000;
  let cached = null;
  async function requestNonce() {
    const body = new FormData();
    body.set('action', 'gdpsy_nonce');
    const controller = new AbortController();
    const timeout = window.setTimeout(() => controller.abort(), 8000);
    try {
      const response = await fetch(window.GdpsyFormConfig.url, {
        method: 'POST', body, credentials: 'same-origin', cache: 'no-store', signal: controller.signal,
        headers: { Accept: 'application/json' },
      });
      const result = await response.json();
      if (!response.ok || result.success !== true || typeof result.data?.nonce !== 'string') throw new Error('Nonce unavailable');
      return result.data.nonce;
    } finally {
      window.clearTimeout(timeout);
    }
  }
  window.GdpsyForms = {
    async nonce() {
      if (!cached || Date.now() - cached.time > lifetime) {
        cached = { time: Date.now(), value: requestNonce() };
        cached.value.catch(() => { cached = null; });
      }
      return cached.value;
    },
    async submit(form, status, { announceSuccess = true } = {}) {
      if (pending.has(form)) return { success: false, pending: true };
      const data = new FormData(form);
      const submit = form.querySelector('[type="submit"]');
      pending.add(form);
      submit.disabled = true;
      form.setAttribute('aria-busy', 'true');
      status.textContent = 'Отправляю…';
      const controller = new AbortController();
      const timeout = window.setTimeout(() => controller.abort(), 20000);
      try {
        try {
          data.set('gdpsy_nonce', await window.GdpsyForms.nonce());
        } catch (error) {}
        const response = await fetch(window.GdpsyFormConfig.url, {
          method: 'POST', body: data, credentials: 'same-origin', signal: controller.signal,
          headers: { Accept: 'application/json' },
        });
        const result = await response.json();
        if (!response.ok || result.success !== true) throw new Error(result.data?.message || 'Не удалось отправить форму. Попробуйте позже.');
        status.textContent = announceSuccess ? result.data.message : '';
        return { success: true };
      } catch (error) {
        status.textContent = error.name === 'AbortError'
          ? 'Ответ задерживается. Текст сохранён в форме — попробуйте отправить ещё раз.'
          : error instanceof TypeError || error instanceof SyntaxError
            ? 'Не удалось получить ответ. Проверьте соединение и повторите отправку.'
            : error.message;
        return { success: false };
      } finally {
        window.clearTimeout(timeout);
        submit.disabled = false;
        form.removeAttribute('aria-busy');
        pending.delete(form);
      }
    },
  };
})();
