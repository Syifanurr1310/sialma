// Highlight active nav link
document.addEventListener('DOMContentLoaded', () => {
  const here = location.pathname.split('/').pop();
  document.querySelectorAll('.nav a').forEach(a => {
    if (a.getAttribute('href').endsWith(here)) a.classList.add('active');
  });
  // Confirm destructive actions
  document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', e => {
      if (!confirm(el.dataset.confirm)) e.preventDefault();
    });
  });
});
