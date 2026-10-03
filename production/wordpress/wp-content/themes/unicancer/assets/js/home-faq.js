(function () {
  const entries = document.querySelectorAll('[data-faq-item]');
  function setOpen(entry, open) {
    const button = entry.querySelector('[data-faq-trigger]');
    const panel = entry.querySelector('[data-faq-panel]');
    if (!button || !panel) return;
    button.setAttribute('aria-expanded', String(open));
    panel.classList.toggle('hidden', !open);
    entry.classList.toggle('is-open', open);
    ['rounded-2xl', 'border-b-0', 'bg-[#eaf6ff]'].forEach(function (name) {
      entry.classList.toggle(name, open);
    });
    ['bg-secondary', 'text-white', 'hover:bg-secondary'].forEach(function (name) {
      button.classList.toggle(name, open);
    });
    ['bg-white', 'text-gray-850', 'hover:bg-[#eaf6ff]'].forEach(function (name) {
      button.classList.toggle(name, !open);
    });
    const plus = entry.querySelector('[data-faq-plus]');
    const minus = entry.querySelector('[data-faq-minus]');
    if (plus) plus.classList.toggle('hidden', open);
    if (minus) minus.classList.toggle('hidden', !open);
  }
  entries.forEach(function (entry) {
    const button = entry.querySelector('[data-faq-trigger]');
    if (!button) return;
    button.addEventListener('click', function () {
      const shouldOpen = button.getAttribute('aria-expanded') !== 'true';
      entries.forEach(function (other) { setOpen(other, other === entry && shouldOpen); });
    });
  });
}());
