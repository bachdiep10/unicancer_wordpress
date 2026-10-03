(function () {
  const tabs = document.querySelectorAll('[data-news-tab]');
  const items = document.querySelectorAll('[data-news-item]');
  tabs.forEach(function (tab) {
    tab.addEventListener('click', function () {
      const type = tab.dataset.tab || 'all';
      tabs.forEach(function (other) {
        other.setAttribute('aria-selected', String(other === tab));
      });
      items.forEach(function (item) {
        item.toggleAttribute('hidden', type !== 'all' && item.dataset.newsType !== type);
      });
    });
  });
}());
