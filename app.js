const chipGroups = document.querySelectorAll('.chip-group');
const navItems = document.querySelectorAll('.nav__item');
const panels = document.querySelectorAll('[data-tab-panel]');

chipGroups.forEach((group) => {
  const chips = group.querySelectorAll('.chip');
  chips.forEach((chip) => {
    chip.addEventListener('click', () => {
      chips.forEach((item) => item.classList.remove('chip--active'));
      chip.classList.add('chip--active');
    });
  });
});

const setActiveTab = (tabName) => {
  navItems.forEach((item) => {
    item.classList.toggle('nav__item--active', item.dataset.tab === tabName);
  });

  panels.forEach((panel) => {
    panel.classList.toggle('is-active', panel.dataset.tabPanel === tabName);
  });
};

navItems.forEach((item) => {
  item.addEventListener('click', () => {
    setActiveTab(item.dataset.tab);
  });
});

setActiveTab('overview');
