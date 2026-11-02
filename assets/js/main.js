// TEC International — интерактив демо-сайта (без библиотек)
(function () {
  'use strict';

  var $ = function (sel, root) { return (root || document).querySelector(sel); };
  // строки на языке страницы (выводит PHP в page_end), русский — запасной вариант
  var I = window.TEC_I18N || {};
  function tr(key, ru) { return I[key] || ru; }
  var $$ = function (sel, root) { return Array.prototype.slice.call((root || document).querySelectorAll(sel)); };

  // sessionStorage может быть недоступен (приватный режим) — не ломаем сайт
  function store(key, val) {
    try {
      if (val === undefined) return sessionStorage.getItem(key);
      sessionStorage.setItem(key, val);
    } catch (e) { return null; }
  }

  /* ---------- тост ---------- */
  var toastEl = $('.toast'), toastTimer;
  function toast(text) {
    if (!toastEl) return;
    toastEl.textContent = text;
    toastEl.classList.add('is-show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(function () { toastEl.classList.remove('is-show'); }, 3200);
  }
  document.addEventListener('click', function (e) {
    var soon = e.target.closest('[data-soon]');
    if (soon) { e.preventDefault(); toast(soon.getAttribute('data-soon')); }
  });

  /* ---------- переключатель языков: та же страница, тот же якорь ---------- */
  document.addEventListener('click', function (e) {
    var a = e.target.closest('[data-lang-link]');
    if (!a || a.classList.contains('is-active')) return;
    e.preventDefault();
    location.href = a.getAttribute('href') + location.search + location.hash;
  });

  /* ---------- мобильное меню ---------- */
  var burger = $('.burger'), nav = $('#nav');
  if (burger && nav) {
    burger.addEventListener('click', function () {
      var open = nav.classList.toggle('is-open');
      burger.setAttribute('aria-expanded', open ? 'true' : 'false');
      document.body.classList.toggle('is-locked', open);
    });
  }

  /* ---------- UTM-метки: запоминаем при входе, отправляем с заявкой ---------- */
  (function () {
    var params = new URLSearchParams(location.search), utm = [];
    params.forEach(function (v, k) { if (k.indexOf('utm_') === 0) utm.push(k + '=' + v); });
    if (utm.length) store('tec_utm', utm.join('&'));
  })();

  /* ---------- модальные окна ---------- */
  var lastFocus = null;
  function openModal(el) {
    lastFocus = document.activeElement;
    el.hidden = false;
    document.body.classList.add('is-locked');
    var f = $('input:not([type=hidden]):not([tabindex="-1"]), button', $('.modal__box, .lightbox__box', el) || el);
    if (f) setTimeout(function () { f.focus(); }, 30);
  }
  function closeModal(el) {
    el.hidden = true;
    if (!(nav && nav.classList.contains('is-open'))) document.body.classList.remove('is-locked');
    if (lastFocus) lastFocus.focus();
  }
  $$('.modal, .lightbox').forEach(function (m) {
    m.addEventListener('click', function (e) { if (e.target.closest('[data-close]')) closeModal(m); });
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') $$('.modal:not([hidden]), .lightbox:not([hidden])').forEach(closeModal);
  });

  var leadModal = $('#lead-modal');
  document.addEventListener('click', function (e) {
    var btn = e.target.closest('[data-lead]');
    if (!btn || !leadModal) return;
    e.preventDefault();
    var form = $('form', leadModal);
    resetForm(form);
    var dir = btn.getAttribute('data-direction');
    if (dir) $('[name=direction]', form).value = dir;
    var industry = btn.getAttribute('data-lead-industry');
    if (industry) $('[name=message]', form).value = tr('industry', 'Отрасль:') + ' ' + industry + '. ';
    if (nav) { nav.classList.remove('is-open'); }
    openModal(leadModal);
  });

  /* ---------- отправка заявок ---------- */
  function setMsg(form, text, ok) {
    var m = $('.form-msg', form);
    m.textContent = text;
    m.className = 'form-msg ' + (ok ? 'is-ok' : 'is-err');
  }
  function resetForm(form) {
    if (!form) return;
    var done = $('.form-done', form.parentNode);
    if (done) { done.remove(); form.hidden = false; }
    form.reset(); // модалка общая: не тащим отрасль/направление от прошлой кнопки
    setMsg(form, '', true);
    $$('.is-invalid', form).forEach(function (el) { el.classList.remove('is-invalid'); });
  }
  function validate(form) {
    var bad = [];
    var name = $('[name=name]', form), phone = $('[name=phone]', form), consent = $('[name=consent]', form);
    if (!name.value.trim()) bad.push(name.closest('.field'));
    if (phone.value.replace(/\D/g, '').length < 10) bad.push(phone.closest('.field'));
    if (!consent.checked) bad.push(consent.closest('.consent'));
    $$('.is-invalid', form).forEach(function (el) { el.classList.remove('is-invalid'); });
    bad.forEach(function (el) { el.classList.add('is-invalid'); });
    return bad.length === 0;
  }
  function showDone(form, title, text) {
    var done = document.createElement('div');
    done.className = 'form-done';
    done.innerHTML = '<svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 12l3 3 5-6"/></svg>' +
      '<p><b></b></p><p class="muted"></p>';
    $('b', done).textContent = title;
    $('p.muted', done).textContent = text;
    form.hidden = true;
    form.parentNode.insertBefore(done, form.nextSibling);
  }
  $$('.lead-form').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      if (!validate(form)) { setMsg(form, tr('fill', 'Заполните имя, телефон и отметьте согласие'), false); return; }
      // статическая копия на GitHub Pages: PHP нет, заявку не отправляем
      if (form.hasAttribute('data-static')) {
        showDone(form, tr('staticTitle', 'Это демо-копия сайта'), tr('staticText', 'Здесь заявки не отправляются. Рабочая форма есть на сайте компании.'));
        return;
      }
      var data = new FormData(form);
      data.append('page', location.pathname);
      data.append('utm', store('tec_utm') || '');
      var btn = $('button[type=submit]', form);
      btn.disabled = true;
      setMsg(form, tr('sending', 'Отправляем заявку'), true);
      fetch(form.action, { method: 'POST', body: data })
        .then(function (r) { return r.json(); })
        .then(function (res) {
          if (!res.ok) throw new Error(tr('fill', 'Проверьте поля формы'));
          showDone(form, tr('sent', 'Заявка отправлена'), tr('sentText', 'Свяжемся с вами в течение рабочего дня.'));
        })
        .catch(function (err) { setMsg(form, err.message || tr('fail', 'Не удалось отправить. Позвоните нам.'), false); })
        .then(function () { btn.disabled = false; });
    });
  });

  // перезапуск CSS-анимации: снять класс, дать браузеру пересчитать стили, вернуть класс
  function replay(el, cls) {
    el.classList.remove(cls);
    void el.offsetWidth;
    el.classList.add(cls);
  }

  /* ---------- вкладки «О компании» ---------- */
  var tabs = $$('[data-tab]'), tabBar = $('.tabs'), ink = null;
  var curTab = tabs.filter(function (t) { return t.classList.contains('is-active'); })[0] || null;
  // полоска под активной вкладкой переезжает, а не перескакивает
  function moveInk() {
    if (!ink || !curTab) return;
    ink.style.width = curTab.offsetWidth + 'px';
    ink.style.transform = 'translateX(' + curTab.offsetLeft + 'px)';
  }
  function showTab(id, scroll, animate) {
    var panel = $('[data-panel="' + id + '"]');
    if (!panel) return;
    var prev = tabs.indexOf(curTab), next = null;
    tabs.forEach(function (t) {
      var on = t.getAttribute('data-tab') === id;
      if (on) next = t;
      t.classList.toggle('is-active', on);
      t.setAttribute('aria-selected', on ? 'true' : 'false');
    });
    $$('[data-panel]').forEach(function (p) { p.hidden = p !== panel; });
    if (animate && next !== curTab) {
      // вкладка правее прежней: панель въезжает справа, левее: слева
      panel.style.setProperty('--dx', (tabs.indexOf(next) > prev ? 28 : -28) + 'px');
      replay(panel, 'is-enter');
    }
    curTab = next;
    moveInk();
    // на телефоне вкладки прокручиваются вбок: держим активную по центру
    if (next) tabBar.scrollTo({ left: next.offsetLeft - (tabBar.clientWidth - next.offsetWidth) / 2, behavior: 'smooth' });
    if (scroll) tabBar.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
  if (tabs.length && tabBar) {
    ink = document.createElement('span');
    ink.className = 'tabs__ink';
    ink.setAttribute('aria-hidden', 'true');
    tabBar.appendChild(ink);
    tabBar.classList.add('has-ink');
    tabs.forEach(function (t) {
      t.addEventListener('click', function (e) {
        e.preventDefault();
        var id = t.getAttribute('data-tab');
        history.replaceState(null, '', '#' + id);
        showTab(id, false, true);
      });
    });
    // #cert-cisco (ссылка со статуса на «Партнерах»): вкладка сертификатов + подсветка карточки
    var openHash = function (animate) {
      var id = location.hash.slice(1);
      var cert = id.indexOf('cert-') === 0 ? document.getElementById(id) : null;
      if (!cert) { if (id) showTab(id, true, animate); return; }
      showTab('certificates', false, animate);
      cert.scrollIntoView({ behavior: 'smooth', block: 'center' });
      cert.classList.remove('is-rise'); // анимация фильтра перебила бы подсветку
      replay(cert, 'is-flash');
    };
    openHash(false);
    window.addEventListener('hashchange', function () { openHash(true); });
    // полоску ставим на место без анимации и только потом включаем переезд,
    // иначе при загрузке она выехала бы от левого края
    moveInk();
    void ink.offsetWidth;
    ink.classList.add('is-ready');
    window.addEventListener('resize', moveInk);
    if (document.fonts && document.fonts.ready) document.fonts.ready.then(moveInk);
  }

  /* ---------- фильтры (проекты, новости, сертификаты) ---------- */
  $$('[data-filter-list]').forEach(function (list) {
    var scope = list.closest('section, .tab-panel') || document;
    var state = {};
    var empty = $('.empty', scope);
    function apply() {
      var shown = [];
      $$(':scope > *', list).forEach(function (card) {
        var ok = Object.keys(state).every(function (k) { return !state[k] || card.getAttribute('data-' + k) === state[k]; });
        card.hidden = !ok;
        card.classList.remove('is-rise');
        if (ok) shown.push(card);
      });
      if (empty) empty.hidden = shown.length > 0;
      // один пересчет стилей на весь список, затем карточки поднимаются по очереди;
      // задержка ограничена, чтобы длинный список не тянулся
      void list.offsetWidth;
      shown.forEach(function (card, i) {
        card.style.setProperty('--i', Math.min(i, 8));
        card.classList.add('is-rise');
      });
    }
    $$('.filter', scope).forEach(function (group) {
      var key = group.getAttribute('data-filter');
      group.addEventListener('click', function (e) {
        var chip = e.target.closest('.chip');
        if (!chip) return;
        $$('.chip', group).forEach(function (c) {
          c.classList.toggle('is-active', c === chip);
          c.setAttribute('aria-pressed', c === chip ? 'true' : 'false');
        });
        state[key] = chip.getAttribute('data-value');
        apply();
      });
    });
    $$('.filter-select', scope).forEach(function (sel) {
      sel.addEventListener('change', function () { state[sel.getAttribute('data-filter')] = sel.value; apply(); });
    });
  });

  /* ---------- «Подобрать решение» ---------- */
  var picker = $('.picker');
  if (picker) {
    var items = $$('.sol-item'), links = $$('[data-dir-link]');
    var result = $('.picker__result', picker), text = $('.picker__text', picker);
    var titles = {};
    items.forEach(function (it) { titles[it.getAttribute('data-dir')] = $('h2', it).textContent; });
    var choose = function (chip) {
      $$('.chip', picker).forEach(function (c) { c.setAttribute('aria-pressed', c === chip ? 'true' : 'false'); });
      if (!chip) {
        items.forEach(function (it) { it.classList.remove('is-hit', 'is-dim'); });
        links.forEach(function (a) { a.classList.remove('is-dim'); });
        result.hidden = true;
        return;
      }
      var dirs = chip.getAttribute('data-dirs').split(',');
      items.forEach(function (it) {
        var hit = dirs.indexOf(it.getAttribute('data-dir')) !== -1;
        it.classList.toggle('is-hit', hit);
        it.classList.toggle('is-dim', !hit);
      });
      links.forEach(function (a) { a.classList.toggle('is-dim', dirs.indexOf(a.getAttribute('data-dir-link')) === -1); });
      text.textContent = tr('picker', 'Для отрасли «%1» обычно нужны: %2.')
        .replace('%1', chip.textContent)
        .replace('%2', dirs.map(function (d) { return titles[d]; }).join(', '));
      $('[data-lead-industry]', result).setAttribute('data-lead-industry', chip.textContent);
      result.hidden = false;
    };
    picker.addEventListener('click', function (e) {
      var chip = e.target.closest('.chip');
      if (chip) choose(chip.getAttribute('aria-pressed') === 'true' ? null : chip);
      if (e.target.closest('[data-picker-reset]')) choose(null);
    });
    var pre = new URLSearchParams(location.search).get('industry');
    if (pre) choose($('.chip[data-industry="' + pre + '"]', picker));
  }

  // подсветка текущего направления в боковом меню «Решений»
  var dirLinks = $$('[data-dir-link]');
  if (dirLinks.length && 'IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (!en.isIntersecting) return;
        var id = en.target.id;
        dirLinks.forEach(function (a) { a.classList.toggle('is-active', a.getAttribute('data-dir-link') === id); });
      });
    }, { rootMargin: '-30% 0px -60% 0px' });
    $$('.sol-item').forEach(function (it) { io.observe(it); });
  }

  /* ---------- лайтбокс сканов ---------- */
  var lb = $('#lightbox');
  document.addEventListener('click', function (e) {
    var z = e.target.closest('[data-zoom]');
    if (!z || !lb) return;
    var card = z.closest('.doc');
    var box = $('.lightbox__img', lb);
    box.innerHTML = '';
    lb.classList.toggle('lightbox--scan', z.hasAttribute('data-full'));
    if (z.hasAttribute('data-full')) {
      // настоящий скан сертификата крупно
      var img = document.createElement('img');
      img.src = z.getAttribute('data-full');
      img.alt = $('img', z) ? $('img', z).alt : '';
      box.appendChild(img);
    } else {
      box.appendChild($('.doc__paper', z).cloneNode(true));
    }
    $('figcaption', lb).textContent = $('h3', card).textContent + ' · ' + $('.doc__sub', card).textContent;
    openModal(lb);
  });

  /* ---------- режим «Пометки ТЗ»: номера пунктов из презентации ---------- */
  var notesBtn = $('[data-notes-toggle]');
  var pop = null;
  function showPop(pin) {
    if (!pop) { pop = document.createElement('div'); pop.className = 'note-pin__bubble'; pop.style.position = 'fixed'; pop.style.zIndex = 200; document.body.appendChild(pop); }
    pop.textContent = pin.getAttribute('data-text');
    pop.style.display = 'block';
    var r = pin.getBoundingClientRect();
    var left = Math.min(r.left, window.innerWidth - pop.offsetWidth - 12);
    pop.style.left = Math.max(12, left) + 'px';
    pop.style.top = (r.bottom + 8) + 'px';
  }
  function hidePop() { if (pop) pop.style.display = 'none'; }
  function setNotes(on) {
    $$('.note-pin').forEach(function (p) { p.remove(); });
    $$('.has-note').forEach(function (el) { el.classList.remove('has-note'); });
    hidePop();
    if (notesBtn) {
      notesBtn.setAttribute('aria-pressed', on ? 'true' : 'false');
      notesBtn.textContent = on ? tr('notesHide', 'Скрыть пометки ТЗ') : tr('notesShow', 'Показать пометки ТЗ');
    }
    store('tec_notes', on ? '1' : '0');
    if (!on) return;
    $$('[data-note]').forEach(function (el, i) {
      var target = el.tagName === 'SECTION' ? ($('.container', el) || el) : el;
      if (getComputedStyle(target).position === 'static') target.style.position = 'relative';
      var pin = document.createElement('span');
      pin.className = 'note-pin';
      pin.tabIndex = 0;
      pin.setAttribute('role', 'note');
      pin.setAttribute('data-text', el.getAttribute('data-note'));
      pin.setAttribute('aria-label', tr('note', 'Пометка') + ' ' + (i + 1) + ': ' + el.getAttribute('data-note'));
      pin.textContent = i + 1;
      var r = target.getBoundingClientRect();
      if (r.left < 16 || getComputedStyle(target).overflowX !== 'visible') { pin.style.top = '2px'; pin.style.left = '2px'; }
      pin.addEventListener('mouseenter', function () { showPop(pin); });
      pin.addEventListener('focus', function () { showPop(pin); });
      pin.addEventListener('mouseleave', hidePop);
      pin.addEventListener('blur', hidePop);
      pin.addEventListener('click', function (e) { e.preventDefault(); e.stopPropagation(); showPop(pin); });
      target.appendChild(pin);
      if (el.tagName !== 'SECTION') el.classList.add('has-note');
    });
  }
  if (notesBtn) {
    notesBtn.addEventListener('click', function () { setNotes(notesBtn.getAttribute('aria-pressed') !== 'true'); });
    window.addEventListener('scroll', hidePop, { passive: true });
    if (store('tec_notes') === '1') setNotes(true);
  }
})();
