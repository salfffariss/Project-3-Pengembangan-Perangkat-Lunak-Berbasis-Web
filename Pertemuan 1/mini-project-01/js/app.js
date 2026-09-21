'use strict';

const menuBtn = document.querySelector('#menu-btn');
const mainNav = document.querySelector('#main-nav');

menuBtn.addEventListener('click', () => {
  const isOpen = mainNav.classList.toggle('is-open');
  menuBtn.setAttribute('aria-expanded', String(isOpen));
});

const dataLayanan = [
  { id: 1, nama: 'Praktikum Komputer', kategori: 'Fasilitas', deskripsi: 'Laboratorium modern untuk menunjang praktikum software.' },
  { id: 2, nama: 'Bimbingan Akademik', kategori: 'Akademik', deskripsi: 'Konsultasi berkala perkembangan studi mahasiswa.' },
  { id: 3, nama: 'Perpustakaan Digital', kategori: 'Fasilitas', deskripsi: 'Akses referensi buku dan jurnal informatika.' }
];

const daftarLayanan = document.querySelector('#daftar-layanan');
const filterLayanan = document.querySelector('#filter-layanan');

function renderLayanan(items) {
  daftarLayanan.replaceChildren();

  if (items.length === 0) {
    const p = document.createElement('p');
    p.textContent = 'Tidak ada layanan pada kategori ini.';
    daftarLayanan.append(p);
    return;
  }

  items.forEach((item) => {
    const box = document.createElement('article');
    box.classList.add('layanan-item');

    const judul = document.createElement('h3');
    judul.textContent = item.nama;

    const desk = document.createElement('p');
    desk.textContent = item.deskripsi;

    box.append(judul, desk);
    daftarLayanan.append(box);
  });
}

filterLayanan.addEventListener('change', () => {
  const nilai = filterLayanan.value;
  if (nilai === 'semua') {
    renderLayanan(dataLayanan);
  } else {
    const hasil = dataLayanan.filter(item => item.kategori === nilai);
    renderLayanan(hasil);
  }
});

renderLayanan(dataLayanan); 

const semuaFaqBtn = document.querySelectorAll('.faq-btn');

semuaFaqBtn.forEach((tombol) => {
  tombol.addEventListener('click', () => {
    const targetId = tombol.getAttribute('aria-controls');
    const panel = document.getElementById(targetId);
    const sedangBuka = tombol.getAttribute('aria-expanded') === 'true';

    semuaFaqBtn.forEach((b) => {
      b.setAttribute('aria-expanded', 'false');
      const p = document.getElementById(b.getAttribute('aria-controls'));
      if (p) p.hidden = true;
    });

    if (!sedangBuka && panel) {
      tombol.setAttribute('aria-expanded', 'true');
      panel.hidden = false;
    }
  });
});

const formKontak = document.querySelector('#form-kontak');
const namaInput = document.querySelector('#nama');
const emailInput = document.querySelector('#email');
const errorNama = document.querySelector('#error-nama');
const errorEmail = document.querySelector('#error-email');
const statusKontak = document.querySelector('#status-kontak');

formKontak.addEventListener('submit', (event) => {
  event.preventDefault();
  let valid = true;

  const nama = namaInput.value.trim();
  if (nama.length < 3) {
    errorNama.textContent = 'Nama minimal 3 karakter.';
    namaInput.setAttribute('aria-invalid', 'true');
    valid = false;
  } else {
    errorNama.textContent = '';
    namaInput.removeAttribute('aria-invalid');
  }

  const email = emailInput.value.trim();
  if (!email.includes('@')) {
    errorEmail.textContent = 'Email harus memuat tanda @.';
    emailInput.setAttribute('aria-invalid', 'true');
    valid = false;
  } else {
    errorEmail.textContent = '';
    emailInput.removeAttribute('aria-invalid');
  }

  if (valid) {
    statusKontak.textContent = `Pesan dari ${nama} siap dikirim. Terima kasih!`;
    formKontak.reset();
  } else {
    statusKontak.textContent = '';
  }
});

const btnTop = document.querySelector('#btn-top');
btnTop.addEventListener('click', () => {
  window.scrollTo({ top: 0, behavior: 'smooth' });
});

const themeBtn = document.querySelector('#theme-btn');
themeBtn.addEventListener('click', () => {
  const isDark = document.body.classList.toggle('dark-theme');
  themeBtn.textContent = isDark ? 'Tema Terang' : 'Tema Gelap';
});