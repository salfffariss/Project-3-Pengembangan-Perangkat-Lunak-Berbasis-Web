'use strict';

// 1. Selector Elemen DOM
const toggleTemaBtn = document.querySelector('#toggle-tema');
const statusBox = document.querySelector('#status-container');
const statusText = document.querySelector('#status');
const cobaLagiBtn = document.querySelector('#coba-lagi');

const profileCard = document.querySelector('#profile-card');
const profilNama = document.querySelector('#profil-nama');
const profilPeran = document.querySelector('#profil-peran');
const profilBio = document.querySelector('#profil-bio');
const profilKontak = document.querySelector('#profil-kontak');

const toggleDetailBtn = document.querySelector('#toggle-detail');
const profilDetail = document.querySelector('#profil-detail');

const formSkill = document.querySelector('#form-skill');
const inputSkill = document.querySelector('#input-skill');
const errorSkill = document.querySelector('#error-skill');
const daftarSkill = document.querySelector('#daftar-skill');
const emptySkillMsg = document.querySelector('#empty-skill-msg');

// State lokal keterampilan
let listKeterampilan = [];

// 2. Fungsi Manajemen State Antarmuka
function aturState(state, pesan) {
  statusBox.dataset.state = state;
  statusText.textContent = pesan;
  cobaLagiBtn.hidden = state !== 'error';

  if (state === 'success') {
    profileCard.hidden = false;
  } else if (state === 'loading' || state === 'error' || state === 'empty') {
    profileCard.hidden = true;
  }
}

// 3. Mengambil Data Profil dari JSON Lokal
async function ambilDataProfil() {
  const response = await fetch('data/profile.json');
  if (!response.ok) {
    throw new Error(`HTTP ${response.status}`);
  }
  return response.json();
}

// 4. Render Daftar Keterampilan
function renderDaftarSkill() {
  daftarSkill.replaceChildren();

  if (listKeterampilan.length === 0) {
    emptySkillMsg.hidden = false;
    return;
  }

  emptySkillMsg.hidden = true;

  listKeterampilan.forEach((skill, index) => {
    const li = document.createElement('li');
    li.classList.add('skill-item');

    const spanTeks = document.createElement('span');
    spanTeks.textContent = skill;

    const btnHapus = document.createElement('button');
    btnHapus.type = 'button';
    btnHapus.classList.add('btn-hapus');
    btnHapus.textContent = 'Hapus';
    btnHapus.setAttribute('aria-label', `Hapus keterampilan ${skill}`);

    // Event Hapus Keterampilan
    btnHapus.addEventListener('click', () => {
      listKeterampilan.splice(index, 1);
      renderDaftarSkill();
    });

    li.append(spanTeks, btnHapus);
    daftarSkill.append(li);
  });
}

// 5. Render Seluruh Profil ke Tampilan
function tampilkanProfil(data) {
  profilNama.textContent = data.nama || 'Tanpa Nama';
  profilPeran.textContent = data.peran || 'Tanpa Peran';
  profilBio.textContent = data.bio || '-';
  profilKontak.textContent = data.kontak || '-';

  listKeterampilan = Array.isArray(data.keterampilan) ? [...data.keterampilan] : [];
  renderDaftarSkill();
}

// 6. Alur Utama Pemuatan Data Asinkron
async function muatProfil() {
  aturState('loading', 'Memuat data profil...');
  cobaLagiBtn.disabled = true;

  try {
    const data = await ambilDataProfil();

    // Validasi struktur data profil
    if (!data || typeof data !== 'object' || Object.keys(data).length === 0) {
      aturState('empty', 'Data profil kosong.');
      return;
    }

    tampilkanProfil(data);
    aturState('success', 'Profil berhasil dimuat.');
  } catch (error) {
    console.error(error);
    aturState('error', `Gagal memuat profil: ${error.message}`);
  } finally {
    cobaLagiBtn.disabled = false;
  }
}

// 7. Event: Toggle Detail Profil (classList & aria-expanded)
toggleDetailBtn.addEventListener('click', () => {
  const isExpanded = toggleDetailBtn.getAttribute('aria-expanded') === 'true';
  const newStatus = !isExpanded;

  toggleDetailBtn.setAttribute('aria-expanded', String(newStatus));
  toggleDetailBtn.textContent = newStatus ? 'Sembunyikan Detail' : 'Lihat Detail';
  profilDetail.hidden = !newStatus;
  profilDetail.classList.toggle('is-collapsed', !newStatus);
});

// 8. Event: Ganti Tema Gelap / Terang
toggleTemaBtn.addEventListener('click', () => {
  const isDark = document.body.classList.toggle('dark-theme');
  toggleTemaBtn.setAttribute('aria-pressed', String(isDark));
  toggleTemaBtn.textContent = isDark ? 'Tema Terang' : 'Tema Gelap';
});

// 9. Event: Form Tambah Keterampilan (Validasi Input Kosong)
formSkill.addEventListener('submit', (event) => {
  event.preventDefault();
  const nilaiSkill = inputSkill.value.trim();

  if (nilaiSkill === '') {
    errorSkill.textContent = 'Nama keterampilan tidak boleh kosong.';
    inputSkill.setAttribute('aria-invalid', 'true');
    inputSkill.focus();
    return;
  }

  // Bersihkan pesan error dan tambahkan item
  errorSkill.textContent = '';
  inputSkill.removeAttribute('aria-invalid');

  listKeterampilan.push(nilaiSkill);
  inputSkill.value = '';
  renderDaftarSkill();
});

// 10. Inisialisasi Event Pemuatan
cobaLagiBtn.addEventListener('click', muatProfil);
document.addEventListener('DOMContentLoaded', muatProfil);