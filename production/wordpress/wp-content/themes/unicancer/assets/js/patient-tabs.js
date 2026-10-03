(function () {
  const tabs = document.querySelectorAll('[role="tablist"] [data-tab-id]');
  const items = document.querySelectorAll('[data-patient-item]');
  function select(type) {
    tabs.forEach(function (tab) {
      const active = tab.dataset.tabId === type;
      tab.setAttribute('aria-selected', String(active));
    });
    items.forEach(function (item) {
      const diagnoses = (item.dataset.diagnosisSlugs || '').split(/[\s,]+/);
      item.hidden = type !== 'all' && !diagnoses.includes(type);
    });
  }
  tabs.forEach(function (tab) {
    tab.addEventListener('click', function () { select(tab.dataset.tabId || 'all'); });
  });
  select('all');
  const more = document.querySelector('[role="tablist"] a[href]');
  if (more) {
    more.href = '/?s=ung+thu&post_type=patient_story';
  }
}());
