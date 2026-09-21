'use strict';

function validasiInput(harga, jumlah) {
  return typeof harga === 'number' && typeof jumlah === 'number' && harga > 0 && jumlah > 0;
}

function hitungPersenDiskon(subtotal, isMember) {
  let diskon = 0;
  if (subtotal >= 200000) {
    diskon = 0.20;
  } else if (subtotal >= 100000) {
    diskon = 0.10;
  }

  if (isMember) {
    diskon += 0.05;
  }

  return Math.min(diskon, 0.25); 
}

function hitungPembayaran(harga, jumlah, isMember) {
  if (!validasiInput(harga, jumlah)) {
    return { status: 'Input tidak valid' };
  }

  const subtotal = harga * jumlah;
  const persenDiskon = hitungPersenDiskon(subtotal, isMember);
  const potongan = subtotal * persenDiskon;
  const total = subtotal - potongan;

  return {
    subtotal,
    diskon: `${persenDiskon * 100}%`,
    potongan,
    total,
    status: 'Sukses'
  };
}

const daftarUji = [
  { kasus: '1. Subtotal < 100k', harga: 50000, jumlah: 1, isMember: false },
  { kasus: '2. Batas Rp100.000', harga: 50000, jumlah: 2, isMember: false },
  { kasus: '3. Batas Rp200.000', harga: 100000, jumlah: 2, isMember: false },
  { kasus: '4. Diskon Max (Member)', harga: 250000, jumlah: 1, isMember: true },
  { kasus: '5. Tidak Valid', harga: 50000, jumlah: 0, isMember: false },
];

console.table(daftarUji.map(item => ({
  Kasus: item.kasus,
  ...hitungPembayaran(item.harga, item.jumlah, item.isMember)
})));