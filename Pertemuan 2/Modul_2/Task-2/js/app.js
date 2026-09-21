'use strict';

const peserta = [
  { id: 1, nama: 'Alya', prodi: 'Teknik Informatika' },
  { id: 2, nama: 'Bima', prodi: 'Sistem Informasi' },
];

const form = document.querySelector('#form-peserta');
const namaInput = document.querySelector('#nama');
const prodiInput = document.querySelector('#prodi');
const filterInput = document.querySelector('#filter-prodi');
const daftar = document.querySelector('#daftar-peserta');
const status = document.querySelector('#status');
const errorNama = document.querySelector('#error-nama');
const errorProdi = document.querySelector('#error-prodi');

// 1. Validasi calon peserta
function validasiPeserta(calon) {
  const hasil = { valid: true, errorNama: '', errorProdi: '' };

  if (calon.nama.length < 3) {
    hasil.valid = false;
    hasil.errorNama = 'Nama minimal 3 karakter.';
  }

  if (!calon.prodi) {
    hasil.valid = false;
    hasil.errorProdi = 'Program studi wajib dipilih.';
  }

  return hasil;
}

// 2. Buat elemen kartu DOM
function buatKartuPeserta(item) {
  const article = document.createElement('article');
  const heading = document.createElement('h2');
  const description = document.createElement('p');

  article.classList.add('kartu');
  heading.textContent = item.nama;
  description.textContent = item.prodi;

  article.append(heading, description);
  return article;
}

// 3. Render daftar peserta ke DOM
function renderPeserta(data) {
  // Kosongkan daftar sebelum merender
  daftar.replaceChildren();

  if (data.length === 0) {
    const pesanKosong = document.createElement('p');
    pesanKosong.textContent = 'Tidak ada peserta.';
    daftar.append(pesanKosong);
    return;
  }

  for (const item of data) {
    daftar.append(buatKartuPeserta(item));
  }
}

// 4. Submit form
form.addEventListener('submit', (event) => {
  event.preventDefault();

  const calon = {
    nama: namaInput.value.trim(),
    prodi: prodiInput.value
  };

  const validasi = validasiPeserta(calon);

  // Tampilkan pesan error dan atur atribut aria-invalid
  errorNama.textContent = validasi.errorNama;
  namaInput.setAttribute('aria-invalid', String(Boolean(validasi.errorNama)));

  errorProdi.textContent = validasi.errorProdi;
  prodiInput.setAttribute('aria-invalid', String(Boolean(validasi.errorProdi)));

  if (!validasi.valid) {
    status.textContent = 'Data gagal ditambahkan. Periksa inputan form.';
    return;
  }

  // Tambahkan peserta baru dengan ID unik
  const pesertaBaru = {
    id: Date.now(),
    nama: calon.nama,
    prodi: calon.prodi
  };
  peserta.push(pesertaBaru);

  // Reset form, kembalikan filter ke 'semua', dan perbarui status
  form.reset();
  namaInput.removeAttribute('aria-invalid');
  prodiInput.removeAttribute('aria-invalid');
  filterInput.value = 'semua';
  status.textContent = `Peserta ${pesertaBaru.nama} berhasil ditambahkan.`;

  // Render ulang daftar peserta
  renderPeserta(peserta);
});

// 5. Filter peserta (tanpa mengubah array peserta asli)
filterInput.addEventListener('change', () => {
  const pilihan = filterInput.value;
  if (pilihan === 'semua') {
    renderPeserta(peserta);
  } else {
    const hasilFilter = peserta.filter(item => item.prodi === pilihan);
    renderPeserta(hasilFilter);
  }
});

// Render awal saat script dimuat
renderPeserta(peserta);