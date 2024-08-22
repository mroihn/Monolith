# Monolith

## Technologies Used
- Laravel - version 11.2.0
- PHP - version 8.3.10

## Design Pattern
1. Factory<br>
   Factory digunakan dalam proyek ini untuk keperluan seeding data. Dalam konteks seeding, Factory Pattern memudahkan pembuatan sejumlah besar objek Film dengan berbagai variasi atribut secara otomatis dan konsisten. Dengan menggunakan factory, kita dapat mendefinisikan template atau blueprint dari objek yang akan dihasilkan, sehingga setiap kali seeding dilakukan, data yang dihasilkan memiliki struktur yang sesuai dan siap digunakan dalam pengembangan atau pengujian.
2. Adapter<br>
    Pada proyek ini, penggunaan Adapter Pattern terlihat pada implementasi kelas FilmResource dan UserResource di Laravel. Adapter Pattern digunakan untuk menyesuaikan format data dari model Film dan User agar sesuai dengan format yang diharapkan oleh API atau klien. Dalam hal ini, FilmResource dan UserResource bertindak sebagai adapter yang mengubah representasi objek Film dan User menjadi format JSON yang konsisten dan terstruktur. Dengan menggunakan Resource, Kita dapat memisahkan logika format data dari logika bisnis utama, memungkinkan fleksibilitas dalam mengubah format respons tanpa mengubah kode yang mengelola data itu sendiri.
3. Active Record <br>
Penggunaan Active Record Pattern terlihat melalui implementasi kelas model seperti User, Film, dan Purchase. Active Record Pattern memudahkan pengelolaan data dengan menggabungkan logika akses data dan logika bisnis dalam satu kelas. Setiap model mewakili sebuah tabel dalam database dan menyediakan metode untuk operasi CRUD seperti menyimpan, mengupdate, atau menghapus data secara langsung.Active Record Pattern memastikan bahwa logika terkait data terpusat dalam satu tempat, memudahkan pemeliharaan dan pengembangan aplikasi lebih lanjut.


## How To Use
```
https://github.com/mroihn/Monolith.git
composer install
setup the env
php artisan migrate
php artisan db:seed
npm install
```
```
php artisan serve
```
```
npm run dev
```
## Endpoint
1. Rest API
```
POST api/login
GET api/self
GET api/users
GET api/users/{id}
POST api/users/{id}/balance
DELETE api/users/{id}
POST api/films
GET api/films
GET api/films/{id}
PUT api/films/{id}
DELETE api/films/{id}
```
2. BE
```
GET /
GET /my_list
POST /but/{id}
GET /logout
GET /film/{id}
GET /login
POST /login
GET /register
POST /process_register
```

## Bonus
1. API Documentation
```
php artisan l5-swagger:generate
```
Dapat dilihat pada
```
http://127.0.0.1:8000/api/documentation
```
2. Fitur Tambahan
