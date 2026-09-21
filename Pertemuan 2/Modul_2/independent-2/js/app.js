'use strict';

const semuaTombol = document.querySelectorAll('.faq-btn');

semuaTombol.forEach((tombol) => {
  tombol.addEventListener('click', () => {
    const sedangTerbuka = tombol.getAttribute('aria-expanded') === 'true';
    const panelJawaban = document.getElementById(tombol.getAttribute('aria-controls'));

    // 1. Tutup SEMUA item terlebih dahulu (hanya satu yang boleh terbuka)
    semuaTombol.forEach((btn) => {
      btn.setAttribute('aria-expanded', 'false');
      btn.classList.remove('is-active');
      const panel = document.getElementById(btn.getAttribute('aria-controls'));
      if (panel) panel.hidden = true;
    });

    // 2. Jika item yang diklik sebelumnya TERTUTUP, maka buka item tersebut
    if (!sedangTerbuka) {
      tombol.setAttribute('aria-expanded', 'true');
      tombol.classList.add('is-active');
      if (panelJawaban) panelJawaban.hidden = false;
    }
  });
});