# Praktikum Modul 3 - Salman Alfarisi Firdaus / 251511030

## Ringkasan halaman
Aplikasi ini adalah Activity Manager v1, sebuah sistem pengelolaan kegiatan berbasis web server-side yang dibangun menggunakan Laravel 13. Aplikasi ini mengelola siklus data kegiatan (judul, deskripsi, tanggal, kategori, dan status) dari database SQLite secara terstruktur. Arsitektur aplikasi dirancang menggunakan pola MVC (Model-View-Controller) dengan pemisahan tanggung jawab yang tegas: Form Request untuk validasi input, Service Class untuk penegakan aturan bisnis transisi status, Eloquent Model untuk persistensi data, dan Blade untuk menyajikan antarmuka visual.

## Tiga keputusan teknis
1. **Pemisahan Business Rules ke Service Class:** Aturan transisi status (BR-03A) yang melarang status mundur (Done ke Planned/Ongoing) dipisahkan dari controller ke dalam `ActivityService`. Keputusan ini menjaga method controller tetap ramping (*skinny controller*) dan memungkinkan aturan domain diuji secara mandiri (*unit testable*).
2. **Isolasi Validasi Masukan pada Form Request:** Pemeriksaan kelayakan input HTTP (judul min 5 karakter, tanggal valid, status sah) ditangani oleh `StoreActivityRequest` dan `UpdateActivityRequest`. Keputusan ini mencegah duplikasi kode validasi pada Blade dan memastikan seluruh data yang masuk ke controller sudah bersih di sisi server.
3. **Pemanfaatan Route Model Binding dan Local Query Scope:** Menggunakan type-hint `Activity $activity` pada method controller sehingga penanganan entitas dan respon 404 dilakukan otomatis oleh Laravel. Selain itu, query penyaringan status (Independent Challenge) diekstrak ke dalam Local Scope `scopeFilterStatus` pada model `Activity.php` agar controller tidak tercampur detail query SQL.

## Masalah, diagnosis, dan perbaikan
1. **Masalah:** Terjadi error `Target class [ActivityController] does not exist` dan `Class "App\Http\Controllers\Activity" not found` saat membuka rute `/activities`.  
   **Diagnosis:** Berkas `routes/web.php` dan `ActivityController.php` memanggil nama class controller dan model tanpa menyertakan deklarasi namespace melalui pernyataan `use`.  
   **Perbaikan:** Menambahkan deklarasi impor namespace yang sesuai di baris atas berkas: `use App\Http\Controllers\ActivityController;` pada rute dan `use App\Models\Activity;` pada controller.
2. **Masalah:** Data kegiatan muncul berulang kali (terduplikasi 3 kali lipat) pada halaman daftar setelah menjalankan seeder.  
   **Diagnosis:** Perintah `php artisan db:seed` dijalankan berulang kali tanpa mengosongkan tabel terlebih dahulu, sehingga perintah seeder terus melakukan operasi `INSERT` data baru di atas data lama.  
   **Perbaikan:** Menjalankan perintah `php artisan migrate:fresh` untuk menghapus dan membangun ulang seluruh skema tabel dari awal, kemudian mengeksekusi seeder satu kali saja melalui perintah `php artisan db:seed --class=ActivitySeeder`.

## Hasil pengujian fitur dan acceptance criteria
* **Pengujian CRUD Lengkap:** Berhasil menambah kegiatan baru, melihat rincian detail data via route model binding, mengubah data yang ada, dan menghapus data target secara presisi tanpa memengaruhi data lain (Lulus uji AC-01, AC-02, AC-03, AC-06, AC-07).
* **Pengujian Validasi Form:** Mengisi judul kurang dari 5 karakter ("Web") berhasil ditolak server dengan pesan error *"The title field must be at least 5 characters."* serta nilai input lama tetap terjaga berkat fungsi `old()` (Lulus uji AC-04 dan AC-05).
* **Pengujian Transisi Status (BR-03A):** Mengubah status dari Planned ke Ongoing diterima, sedangkan upaya menurunkan status dari Done kembali ke Planned ditolak dengan pesan: *"Transisi status (Done ke Planned) tidak diizinkan."* (Lulus uji AC-08 dan AC-09).
* **Pengujian Filter Status:** Parameter URL `?status=Ongoing` berhasil menyaring data secara presisi, dan parameter yang tidak valid ditangani secara aman (*fallback*) tanpa memicu error 500 (Lulus uji F-01 sampai F-04).
* **Pengujian Static Analysis:** Eksekusi `php vendor/bin/pint` menghasilkan status hijau **PASS**, dan analisis SonarQube for IDE menunjukkan kode controller bersih dengan nilai maintainability **A**.

## Panduan menjalankan proyek
1. Clone repository: `git clone https://github.com/salfffariss/Mini-Project.git`
2. Masuk ke folder proyek: `cd Mini-Project/activity-manager`
3. Pasang dependensi: `composer install`
4. Buat berkas environment: `copy .env.example .env` lalu `php artisan key:generate`
5. Migrasi dan isi data database: `php artisan migrate:fresh` lalu `php artisan db:seed --class=ActivitySeeder`
6. Jalankan server: `php artisan serve` dan buka peramban pada `http://127.0.0.1:8000/activities`

## Refleksi belajar
Peralihan dari web statis dan manipulasi DOM (Modul 1 dan 2) ke framework server-side Laravel 13 memberikan pemahaman mendalam mengenai arsitektur perangkat lunak. Pemahaman paling fundamental adalah mengenai Request Lifecycle: browser tidak menjalankan Laravel, melainkan hanya mengirim HTTP Request dan menerima respons HTML yang diracik server. Saya belajar pentingnya membatasi tanggung jawab controller agar tidak menjadi *Fat Controller*, yaitu dengan memindahkan validasi ke Form Request, aturan bisnis ke Service Class, dan kueri data ke Eloquent Model. Hal ini membuat aplikasi lebih mudah dipelihara (*maintainable*), mudah diuji, dan aman.

## Log AI atau sumber bantuan
* **Pertanyaan:** "Mengapa data kegiatan di database terduplikasi berkali-kali setelah perintah seeder dijalankan, dan bagaimana cara meresetnya agar bersih kembali?"
* **Jawaban AI (Intisari):** AI menjelaskan bahwa perintah `db:seed` melakukan operasi `INSERT` baru setiap kali dijalankan tanpa menimpa data lama. Solusinya adalah menjalankan `php artisan migrate:fresh` untuk me-reset tabel dari awal, lalu mengeksekusi seeder satu kali melalui `php artisan db:seed --class=ActivitySeeder`.
* **The Fact Check:** Saya memverifikasi fungsi `migrate:fresh` melalui dokumentasi resmi Laravel Migrations. Dokumentasi mengonfirmasi bahwa perintah ini akan menghapus (*drop*) seluruh tabel yang ada lalu menjalankan kembali metode migrasi dari awal.
* **The Twist:** Selain menerapkan reset database tersebut, saya mengonfigurasi penanganan filter status pada model menggunakan *Local Query Scope* (`scopeFilterStatus`), sehingga pengujian data awal dapat dilakukan secara bersih baik pada kondisi normal maupun terfilter.

## Url
* Repository: https://github.com/salfffariss/Project-3-Pengembangan-Perangkat-Lunak-Berbasis-Web 
* Checkpoint Tag: `modul-3-final`