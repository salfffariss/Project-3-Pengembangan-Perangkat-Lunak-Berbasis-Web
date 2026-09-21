'use strict';

const status = document.querySelector('#status');
const daftar = document.querySelector('#daftar-materi');
const tombolMuat = document.querySelector('#muat');
const tombolCobaLagi = document.querySelector('#coba-lagi');

// Mengatur status antarmuka dan tombol Coba Lagi
function aturState(state, pesan) {
  status.dataset.state = state;
  status.textContent = pesan;
  tombolCobaLagi.hidden = state !== 'error';
}

// 1. Mengambil data JSON dengan fetch
async function ambilMateri() {
  const response = await fetch('data/materi.json');
  if (!response.ok) {
    throw new Error(`HTTP ${response.status}`);
  }
  return response.json();
}

// 2. Render data kartu materi menggunakan createElement dan textContent
function renderMateri(data) {
  daftar.replaceChildren();

  for (const item of data) {
    const article = document.createElement('article');
    const heading = document.createElement('h3');
    const durasi = document.createElement('p');

    article.classList.add('kartu');
    heading.textContent = item.judul;
    durasi.textContent = `Durasi: ${item.durasi} menit`;

    article.append(heading, durasi);
    daftar.append(article);
  }
}

// 3. Alur utama asynchronous pemuatan data
async function muatData() {
  aturState('loading', 'Memuat data...');
  tombolMuat.disabled = true;
  daftar.replaceChildren();

  try {
    const data = await ambilMateri();

    if (!Array.isArray(data)) {
      throw new Error('Format data bukan array.');
    }

    if (data.length === 0) {
      aturState('empty', 'Data materi kosong.');
      return;
    }

    renderMateri(data);
    aturState('success', `${data.length} materi berhasil dimuat.`);
  } catch (error) {
    console.error(error);
    aturState('error', `Gagal memuat data: ${error.message}`);
  } finally {
    tombolMuat.disabled = false;
  }
}

// Pasang event listener pada kedua tombol
tombolMuat.addEventListener('click', muatData);
tombolCobaLagi.addEventListener('click', muatData);