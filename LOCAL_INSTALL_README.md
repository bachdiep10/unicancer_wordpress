# 🎉 UNI-ASIA Cancer Theme - Đã cài đặt và hoạt động trên Local!

## ✅ Trạng thái

| Thành phần | Trạng thái | Chi tiết |
|-----------|-----------|----------|
| Laragon PHP | ✅ Hoạt động | PHP 8.3.30 (ZTS) |
| MySQL 8.4.3 | ✅ Hoạt động | Database: `wordpress_uniasia` |
| WordPress | ✅ Hoạt động | Version mới nhất |
| Theme UNI-ASIA | ✅ Active | Version 1.0.0 |
| Dữ liệu mẫu | ✅ Đã import | 4 doctors, 5 stories, 6 cancers, 7 techs, 8 FAQs |

## 🌐 Truy cập website

| Trang | URL | Status |
|-------|-----|--------|
| **Trang chủ** | http://127.0.0.1:9000/ | ✅ 200 (87 KB) |
| Bác sĩ | http://127.0.0.1:9000/doctors/ | ✅ 200 (41.7 KB) |
| Câu chuyện bệnh nhân | http://127.0.0.1:9000/patient-stories/ | ✅ 200 (45.6 KB) |
| Loại ung thư | http://127.0.0.1:9000/cancer-types/ | ✅ 200 (39.4 KB) |
| Kỹ thuật điều trị | http://127.0.0.1:9000/technologies/ | ✅ 200 (40.2 KB) |
| FAQ | http://127.0.0.1:9000/faqs/ | ✅ 200 (47.6 KB) |
| Giới thiệu | http://127.0.0.1:9000/about-us/ | ✅ 200 (39.3 KB) |
| **Admin WP** | http://127.0.0.1:9000/wp-admin/ | ✅ Login redirect |

## 🔑 Thông tin đăng nhập

```
URL Admin:  http://127.0.0.1:9000/wp-admin/
Username:   admin
Password:   admin123
Email:      admin@uniasia.local
```

## 📊 Dữ liệu đã có sẵn

- **4 bác sĩ** trong MDT Team (featured)
  - GS. BS. Trương Hiểu Bình - Trưởng khoa Can thiệp
  - PGS. TS. Lý Văn Kiệt - Phó khoa Can thiệp
  - TS. BS. Trần Minh Hùng - Trưởng khoa Xạ trị
  - TS. BS. Vương Gia Nghĩa - Trưởng khoa Hóa trị

- **6 loại ung thư**: Gan, Phổi, Tụy, Vú, Cổ tử cung, Đại trực tràng

- **7 kỹ thuật điều trị**: Dao Nano (IRE), Vi sóng (MWA), Cao tần (RFA), Áp lạnh, TACE, Miễn dịch, SBRT

- **5 câu chuyện** bệnh nhân (4 featured cho trang chủ)

- **8 FAQ** đầy đủ thông tin

- **Menu chính** 7 items: Trang chủ, Giới thiệu, Bác sĩ, Kỹ thuật, Câu chuyện, FAQ, Liên hệ

## 🛠️ Cấu hình kỹ thuật

```
PHP:           PHP 8.3.30 (vs16) trên Laragon
Web Server:    PHP Built-in Server (port 9000) - tạm thời
Database:      MySQL 8.4.3
Database name: wordpress_uniasia
DB User:       wp_user / wp_pass_123
```

## ⚠️ Lưu ý

1. **Server tạm thời**: Tôi dùng PHP built-in server (port 9000) thay vì Apache của Laragon vì Apache gặp vấn đề binding port. Khi restart máy, cần chạy lại lệnh khởi động.

2. **Restart server**: Mở PowerShell tại `C:\laragon\www\uniasia`, chạy:
   ```powershell
   php -S 127.0.0.1:9000 -t C:\laragon\www\uniasia
   ```

3. **MySQL**: Server MySQL đang chạy ở background. Sau khi reboot máy, cần restart bằng:
   ```powershell
   & "C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysqld.exe" --console --datadir="C:\laragon\data\mysql-8.4.3" --basedir="C:\laragon\bin\mysql\mysql-8.4.3-winx64" --port=3306
   ```

## 📁 Vị trí file

- **Theme source**: `C:\unicancer\uniasia-cancer-theme\`
- **Theme installed**: `C:\laragon\www\uniasia\wp-content\themes\uniasia-cancer-theme\`
- **WordPress**: `C:\laragon\www\uniasia\`
- **Database dump**: khi cần có thể backup từ MySQL

## 🚀 Plugin khuyến nghị cài thêm

Vào Admin → Plugins → Add New:
- **Elementor** + Elementor Pro (để dùng page builder)
- **Advanced Custom Fields Pro** (để dùng các field groups)
- **WPML Multilingual CMS** (đa ngôn ngữ)
- **Yoast SEO** hoặc Rank Math (SEO bổ sung)
- **Contact Form 7** (backup form)

Theme đã có fallback cho cả khi không cài Elementor/ACF (vẫn hiển thị với PHP templates).