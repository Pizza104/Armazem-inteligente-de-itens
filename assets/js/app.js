document.addEventListener('DOMContentLoaded', () => {
  const menuButton = document.querySelector('[data-sidebar-toggle]');
  menuButton?.addEventListener('click', () => {
    const open = document.body.classList.toggle('sidebar-open');
    menuButton.setAttribute('aria-expanded', String(open));
  });

  document.querySelectorAll('[data-table-search]').forEach((input) => {
    const target = document.querySelector(input.dataset.tableSearch);
    input.addEventListener('input', () => {
      const query = input.value.trim().toLocaleLowerCase('pt-BR');
      target?.querySelectorAll('[data-search-row]').forEach((row) => {
        row.hidden = !row.textContent.toLocaleLowerCase('pt-BR').includes(query);
      });
    });
  });

  document.querySelectorAll('[data-confirm]').forEach((element) => {
    element.addEventListener('click', (event) => {
      if (!window.confirm(element.dataset.confirm)) event.preventDefault();
    });
  });
});
