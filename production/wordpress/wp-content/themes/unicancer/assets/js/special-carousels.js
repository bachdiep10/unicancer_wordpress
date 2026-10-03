document.querySelectorAll('.uc-special-track').forEach(track => {
  const viewport = track.parentElement;
  viewport.style.overflowX = 'auto';
  viewport.style.scrollBehavior = 'smooth';
  let section = viewport.parentElement;
  while (section && !section.querySelector('.move-left')) section = section.parentElement;
  if (!section) return;
  [['.move-left', 1, 'Xem tiếp'], ['.move-right', -1, 'Xem trước']].forEach(([selector, direction, label]) => {
    const control = section.querySelector(selector);
    if (!control) return;
    control.setAttribute('role', 'button');
    control.setAttribute('tabindex', '0');
    control.setAttribute('aria-label', label);
    const move = () => viewport.scrollBy({left: direction * (track.firstElementChild.getBoundingClientRect().width + 16), behavior: 'smooth'});
    control.addEventListener('click', move);
    control.addEventListener('keydown', event => { if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); move(); } });
  });
});
