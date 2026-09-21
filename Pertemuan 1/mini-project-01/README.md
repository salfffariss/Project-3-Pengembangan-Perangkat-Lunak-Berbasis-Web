# Praktikum Modul 1 - Salman Alfarisi Firdaus / 251511030

## Ringkasan halaman
Halaman ini adalah sebuah landing page statis untuk Program Studi JTK (Jurusan Teknik Komputer dan Informatika) Politeknik Negeri Bandung. Website ini dirancang untuk memberikan informasi resmi mengenai pilihan program studi vokasi (D3 dan D4), visi dan misi jurusan, alur seleksi mahasiswa baru, serta kontak komunikasi. Halaman dibangun secara murni menggunakan elemen HTML5 semantik dan CSS3 dengan pendekatan *mobile-first design*, tanpa bergantung pada framework pihak ketiga seperti Bootstrap atau Tailwind.

## Tiga keputusan teknis
1. **Penggunaan Semantic HTML:** Elemen generik `<div>` diganti dengan tag semantik yang lebih spesifik seperti `<header>`, `<nav>`, `<main>`, dan `<section>`. Daftar yang memiliki urutan hierarkis, seperti Visi Misi dan Alur Pendaftaran, dibungkus menggunakan tag `<ol>`. Keputusan ini memastikan struktur dokumen memiliki makna yang jelas bagi mesin pencari (*browser*) dan alat bantu aksesibilitas (*screen reader*).
2. **Implementasi CSS Custom Properties (Tokens):** Variabel utama untuk warna, tipografi dasar, dan ukuran spasi (*spacing*) didefinisikan secara terpusat di dalam selector `:root` (contoh: `--color-primary`, `--space-base`). Keputusan ini menjamin konsistensi visual di seluruh komponen dan sangat menyederhanakan proses pemeliharaan kode jika ada perubahan tema desain di kemudian hari.
3. **Pendekatan Mobile-First dengan Flexbox:** Desain diprioritaskan untuk layar kecil dengan mengatur susunan `.card-list` menjadi satu kolom vertikal (`flex-direction: column`) secara bawaan. Layout tersebut baru diinstruksikan berubah menjadi susunan grid/baris sejajar melalui *media query* ketika rentang lebar layar mencapai minimum 768px (ukuran tablet).

## Masalah, diagnosis, dan perbaikan
1. 1. **Masalah:** Terjadi *overflow* horizontal (muncul *scrollbar* di bawah layar) saat diuji pada resolusi sempit seperti 320px, yang disebabkan oleh gambar profil dan kotak komponen (*card*) yang melebar melampaui batas layar.
   **Diagnosis:** Gambar (`<img>`) secara bawaan dirender oleh *browser* sesuai dengan resolusi piksel aslinya, sehingga ukurannya menabrak batas elemen pembungkusnya. Selain itu, *padding* dan *border* yang diberikan pada `.card` secara *default* menambah total lebar elemen melebihi 100% karena *browser* masih menggunakan standar `box-sizing: content-box`.
   **Perbaikan:** Saya membatasi ukuran gambar dengan menambahkan properti `max-width: 100%;` dan `height: auto;` pada *class* `.profile-img` agar ukurannya mengecil secara dinamis. Untuk masalah *border*, saya mendeklarasikan *reset* `box-sizing: border-box;` pada *universal selector* (`*`) sehingga nilai *padding* dan *border* tidak lagi merusak batas lebar *container*.
2. **Masalah:** Tautan internal (anchor link) pada menu navigasi gagal melompat ke bagian halaman yang dituju.
   **Diagnosis:** Atribut `href` di navigasi mengandung spasi (misal: `href="#visi misi"`), dan atribut `id` pada target memiliki perbedaan kapitalisasi (`id="Visi misi"`). Sintaks HTML mewajibkan penulisan atribut ID bersifat sama persis (*case-sensitive*) dan bebas dari karakter spasi.
   **Perbaikan:** Menyeragamkan nilai atribut dengan format yang valid menggunakan tanda hubung, yaitu `href="#visi-misi"` pada navigasi dan `id="visi-misi"` pada elemen section.

## Hasil pengujian empat viewport
* **Viewport 320px (Mobile S):** Lulus uji. Konten mengalir rapi membentuk satu kolom vertikal. Tidak ditemukan *horizontal overflow*. Daftar tautan di navigasi berhasil beradaptasi (*wrap*) tanpa terpotong berkat properti Flexbox.
* **Viewport 375px (Mobile M):** Lulus uji. Visual tetap aman. Elemen gambar (`.profile-img`) mengecil secara proporsional berkat properti `max-width: 100%`.
* **Viewport 768px (Tablet):** Lulus uji. Aturan *media query* terpicu, mengubah susunan daftar card vertikal menjadi dua kolom horizontal (`flex-direction: row` dan `flex-basis: calc(50% - 12px)`). Jarak antarkonten proporsional.
* **Viewport 1024px (Desktop):** Lulus uji. Tampilan stabil. Lebar keseluruhan situs ditahan oleh elemen `.container` dengan batasan `max-width: 1120px` dan diposisikan di tengah (`margin-inline: auto`), menjaga panjang baris teks tetap ideal untuk kenyamanan membaca.

## Refleksi belajar
Proyek pembuatan landing page statis ini memberikan penegasan bahwa pengembangan web front-end bukan sebatas menata letak visual, melainkan juga merancang arsitektur informasi yang terstruktur. Menerapkan pola pikir *mobile-first* adalah keputusan teknis paling krusial. Pada eksperimen sebelumnya, merancang dari versi desktop sering kali berujung pada susunan elemen yang berantakan ketika dikecilkan ke layar ponsel. Dengan memulai desain dari ruang (*viewport*) yang sempit, saya dipaksa untuk lebih ketat memprioritaskan hierarki konten. Penggabungan logika *mobile-first* dengan Flexbox membuat proses adaptasi *layout* ke layar lebar menjadi jauh lebih logis dan efisien.

Kesalahan paling esensial terjadi pada fase penulisan atribut dan validasi struktur. Peringatan tentang *trailing slash* serta tautan internal yang tidak berfungsi (akibat penggunaan spasi pada atribut `id`) memberikan teguran teknis yang berharga. Hal ini membuktikan bahwa meskipun *browser* modern cukup toleran terhadap penulisan kode yang berantakan (*quirks mode*), memastikan keabsahan sintaks sesuai standar W3C adalah kewajiban untuk menjamin aksesibilitas dan fungsionalitas murni. Kesalahan sepele pada satu karakter dapat menyebabkan interaksi situs gagal sepenuhnya.

Target berikutnya adalah memperkuat penguasaan saya di ranah layouting CSS modern, khususnya mengeksplorasi modul CSS Grid untuk merancang struktur *layout* makro dua dimensi yang tidak bisa sepenuhnya diselesaikan oleh Flexbox, serta mendalami praktik terbaik mengenai struktur semantik tingkat lanjut untuk teknologi aksesibilitas.

## Log AI atau sumber bantuan
* **Pertanyaan:** "Bagaimana cara menyusun kerangka dasar CSS (Base CSS) untuk sebuah landing page tanpa menyentuh urusan layout? Tolong jelaskan konsep atau langkah-langkah utamanya saja (seperti token, box model, dan typography) tanpa memberikan source code jadi."
* **Jawaban AI (Intisari):** AI menjelaskan bahwa Base CSS terdiri dari tiga pilar utama: 1. Pembuatan *Design Tokens* (variabel) menggunakan `:root` untuk menyimpan palet warna dan ukuran agar konsisten; 2. Penerapan *Box Model Reset* menggunakan *universal selector* (`*`) dan `box-sizing: border-box` agar *padding* tidak merusak lebar elemen; dan 3. *Global Typography*, yaitu mengatur jenis font, warna dasar, dan *line-height* pada elemen `body`.
* **The Fact Check:** Saya memverifikasi fungsi `box-sizing: border-box` melalui dokumentasi MDN Web Docs. Dokumentasi tersebut mengonfirmasi bahwa properti ini menginstruksikan *browser* untuk memasukkan *padding* dan *border* ke dalam perhitungan lebar total elemen, yang terbukti sangat krusial agar *card* tidak melebar melebihi wadahnya.
* **The Twist:** Karena AI hanya memberikan panduan konseptual, saya menulis seluruh sintaks CSS secara mandiri. Sebagai tambahan dari konsep dasar tersebut, saya secara inisiatif menerapkan reset spesifik untuk *margin* bawah pada elemen teks (`h1`, `h2`, `p`, `ul`, `ol`) menggunakan variabel `--space-base`, guna memastikan *vertical rhythm* (irama jarak vertikal) antarkomponen tetap seragam dan proporsional.
 ## Url
 https://github.com/salfffariss/Mini-Project  
 https://salfffariss.github.io/Mini-Project/  

 # Praktikum Modul 2 - Salman Alfarisi Firdaus / 251511030

## Ringkasan halaman
Halaman ini merupakan pengembangan lanjutan dari landing page statis Modul 1 menjadi landing page yang interaktif untuk Program Studi JTK (Jurusan Teknik Komputer dan Informatika) Politeknik Negeri Bandung. Seluruh interaktivitas halaman dibangun menggunakan Vanilla JavaScript (ES6) murni tanpa bergantung pada *framework* maupun *library* eksternal. Fitur interaktif yang ditambahkan mencakup menu navigasi *mobile*, daftar layanan dinamis yang dirender dari data objek, penyaringan (*filter*) kategori, komponen FAQ *accordion*, formulir kontak tervalidasi, tombol kembali ke atas, serta kemampuan pergantian tema visual (*dark mode*).

## Tiga keputusan teknis
1. **Pemisahan Peran dan Manipulasi DOM Murni:** Pembuatan elemen dinamis (seperti kartu layanan) menggunakan metode `document.createElement()` dan pengisian nilai menggunakan `.textContent`, bukan `innerHTML`. Keputusan ini menjaga keamanan aplikasi dari risiko celah injeksi kode berbahaya (*Cross-Site Scripting* / XSS) saat menampilkan data atau teks masukan pengguna.
2. **Pengendalian State Berbasis Class CSS dan Aksesibilitas (WAI-ARIA):** JavaScript tidak memanipulasi properti visual secara langsung (*inline style*), melainkan hanya mengubah status (*state*) melalui penambahan/penghapusan *class* (contoh: `.dark-theme` pada `body` dan `.is-open` pada menu navigasi). Selain itu, atribut aksesibilitas seperti `aria-expanded` dan `aria-invalid` diperbarui secara dinamis agar halaman tetap ramah bagi pembaca layar (*screen reader*).
3. **Penanganan Event Terpusat Tanpa Reload Halaman:** Seluruh interaksi pengguna dipasang secara deklaratif menggunakan `addEventListener` pada file JavaScript eksternal yang dimuat dengan atribut `defer`. Pada formulir kontak, pemanggilan `event.preventDefault()` diterapkan untuk menahan perilaku bawaan *browser* yang memuat ulang halaman, sehingga aplikasi tetap berjalan mulus dalam satu siklus interaksi (*single-page interaction*).

## Masalah, diagnosis, dan perbaikan
1. **Masalah:** Formulir kontak memuat ulang (*reload*) halaman secara otomatis saat tombol "Kirim Pesan" ditekan, sehingga pesan keberhasilan dan status validasi langsung hilang dari layar.  
   **Diagnosis:** Secara bawaan (*default behavior*), *event submit* pada elemen `<form>` akan mencoba mengirimkan data ke server dan me-*refresh* dokumen HTML jika tidak dicegah.  
   **Perbaikan:** Memanggil fungsi `event.preventDefault()` pada baris pertama *handler event submit* untuk menghentikan aksi bawaan *browser*, kemudian memproses validasi nama dan email secara lokal melalui JavaScript.
2. **Masalah:** Kartu layanan bertumpuk dan berulang (*duplikasi render*) saat pengguna mengubah pilihan pada *dropdown* filter kategori.  
   **Diagnosis:** Kontainer daftar kartu langsung diisi oleh kartu-kartu baru tanpa mengosongkan elemen-elemen kartu yang sudah dirender pada pemilihan filter sebelumnya.  
   **Perbaikan:** Menggunakan metode `daftarLayanan.replaceChildren()` di awal fungsi `renderLayanan()` untuk membersihkan seluruh elemen anak secara instan sebelum daftar hasil penyaringan baru disisipkan ke DOM.

## Hasil pengujian empat viewport
* **Viewport 320px (Mobile S):** Lulus uji. Tombol menu navigasi muncul dan berfungsi membuka/menutup tautan menu dengan nilai `aria-expanded` yang sinkron. Seluruh teks kartu layanan dan form kontak tertata rapi dalam satu kolom tanpa *overflow* horizontal.
* **Viewport 375px (Mobile M):** Lulus uji. Interaksi FAQ *accordion* berjalan konsisten di mana membuka satu pertanyaan otomatis menutup pertanyaan lain. Form kontak menolak input kosong dengan pesan peringatan yang jelas.
* **Viewport 768px (Tablet):** Lulus uji. Tombol navigasi *mobile* otomatis tersembunyi dan navigasi kembali ke tata letak horizontal. Daftar layanan dan kartu konten beradaptasi menjadi susunan dua kolom sejajar yang proporsional.
* **Viewport 1024px (Desktop):** Lulus uji. Fitur tema gelap (*dark theme*) mengubah kontras warna latar belakang dan kartu secara merata tanpa merusak keterbacaan teks. Tombol melayang "↑ Ke Atas" bekerja mulus menggulung layar kembali ke posisi puncak. Seluruh fitur dapat dinavigasi sepenuhnya menggunakan *keyboard* (*Tab* dan *Enter*).

## Refleksi belajar
Pengembangan Modul 2 ini memberikan pemahaman mendalam mengenai peran JavaScript sebagai pengendali perilaku (*behavior*) di atas struktur HTML dan presentasi CSS. Tantangan terbesar bukan hanya membuat antarmuka bergerak, melainkan bagaimana memastikan perubahan DOM dilakukan secara aman, terprediksi, dan mempertahankan aksesibilitas. Saya menyadari bahwa manipulasi teks pengguna wajib dilakukan menggunakan `.textContent` guna mencegah celah keamanan injeksi.

Selain itu, pemisahan tanggung jawab antara logika data dan pembaruan antarmuka terbukti sangat memudahkan proses pelacakan masalah (*debugging*). Mengelola transisi status tombol dan formulir memberikan pelajaran bahwa umpan balik visual yang jelas bagi pengguna—baik saat input valid, salah, maupun saat sistem memproses—merupakan pilar utama pengalaman pengguna (*UX*) yang baik.

Target berikutnya adalah memperdalam pemrograman *asynchronous* tingkat lanjut untuk integrasi data dinamis dari REST API publik, serta mempelajari modularisasi kode JavaScript modern menggunakan *ES Modules* (`import`/`export`).

## Log AI atau sumber bantuan
* **Pertanyaan:** "Bagaimana cara menyusun fungsi filter daftar kartu berbasis array of objects pada Vanilla JS agar tidak merusak array data aslinya dan mencegah duplikasi elemen pada DOM?"
* **Jawaban AI (Intisari):** AI menyarankan untuk memanfaatkan metode non-mutasi `Array.prototype.filter()` guna menghasilkan salinan array baru sesuai kategori terpilih, serta selalu memanggil `container.replaceChildren()` sebelum perulangan pembuatan elemen DOM dimulai.
* **The Fact Check:** Saya memverifikasi metode `replaceChildren()` melalui dokumentasi MDN Web Docs. Dokumentasi mengonfirmasi bahwa metode ini merupakan cara paling efisien dan bersih untuk mengosongkan seluruh node anak dari sebuah elemen DOM sebelum diisi kembali.
* **The Twist:** Selain menerapkan fungsi filter dan render tersebut, saya berinisiatif menambahkan penanganan kondisi kosong (*empty state*): jika hasil filter menghasilkan array kosong, antarmuka akan secara otomatis merender paragraf pemberitahuan khusus yang menginformasikan pengguna bahwa data kategori tersebut tidak ditemukan.