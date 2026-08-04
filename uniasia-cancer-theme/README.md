# UNI-ASIA Cancer Theme

WordPress theme chuyên nghiệp cho **Bệnh viện Ung thư UNI-ASIA**. Clone y nguyên giao diện từ [unicancercenter.com](https://unicancercenter.com/) và xây dựng thành một WordPress theme đầy đủ tính năng.

## 🌟 Tính năng nổi bật

- ✅ **Clone 100% giao diện** từ unicancercenter.com (Tiếng Việt làm ngôn ngữ chính)
- ✅ **Đa ngôn ngữ** với WPML: Tiếng Việt, Tiếng Anh, Tiếng Indonesia, Tiếng Trung
- ✅ **Tích hợp Elementor Pro** Page Builder với các custom widgets
- ✅ **5 Custom Post Types**: Bác sĩ, Câu chuyện bệnh nhân, Loại ung thư, Kỹ thuật điều trị, FAQ
- ✅ **3 Custom Taxonomies** với dữ liệu mẫu được tạo sẵn
- ✅ **ACF Pro** field groups cho từng CPT
- ✅ **SEO Schema markup** đầy đủ: MedicalOrganization, Physician, FAQPage, BreadcrumbList, Article
- ✅ **Open Graph + Twitter Card** meta tags
- ✅ **Responsive 100%** (mobile-first, breakpoints: 480px, 768px, 1024px, 1280px)
- ✅ **Floating contact buttons** (WhatsApp + Quick contact)
- ✅ **Swiper.js** slider cho MDT Team và Patient Stories
- ✅ **FAQ Accordion** với schema markup chuẩn
- ✅ **Form tư vấn** với AJAX handler + lưu database + gửi email
- ✅ **Accessibility-ready** (ARIA labels, keyboard nav, skip links, reduced motion)
- ✅ **Print-friendly** styles
- ✅ **Translation-ready** với file .pot

## 📋 Yêu cầu

| Yêu cầu | Phiên bản |
|---------|-----------|
| WordPress | 6.5+ |
| PHP | 8.0+ |
| MySQL | 5.7+ hoặc MariaDB 10.3+ |
| Memory Limit | 256M+ (khuyến nghị) |

## 🔌 Plugins cần cài đặt (Bắt buộc)

| Plugin | Mục đích | Link |
|--------|----------|------|
| **Elementor Pro** | Page Builder | [elementor.com](https://elementor.com/) |
| **Advanced Custom Fields Pro** | Custom Fields | [advancedcustomfields.com](https://www.advancedcustomfields.com/) |
| **WPML Multilingual CMS** | Đa ngôn ngữ | [wpml.org](https://wpml.org/) |
| **Custom Post Type UI** | (Backup nếu không có code-based CPT) | [wordpress.org](https://wordpress.org/plugins/custom-post-type-ui/) |
| **Yoast SEO** hoặc **Rank Math** | SEO bổ sung | - |
| **Contact Form 7** | Form backup | - |

### Plugins khuyến nghị (Tùy chọn)
- **WP Rocket** - Cache & Performance
- **ShortPixel** - Nén ảnh
- **Wordfence** - Bảo mật
- **UpdraftPlus** - Backup

## 🚀 Hướng dẫn cài đặt

### 1. Upload theme
1. Nén thư mục `uniasia-cancer-theme` thành file `.zip`
2. Vào **Appearance → Themes → Add New → Upload Theme**
3. Chọn file `.zip` và click **Install Now**
4. Click **Activate**

### 2. Cài plugins bắt buộc
1. Vào **Plugins → Add New**
2. Cài và kích hoạt các plugin: Elementor Pro, ACF Pro, WPML, ...

### 3. Import dữ liệu mẫu (Optional)
Sử dụng **WordPress Importer** hoặc plugin **All-in-One WP Migration** để import dữ liệu mẫu (nếu có).

### 4. Cấu hình menu
1. Vào **Appearance → Menus**
2. Tạo menu với location "Primary Menu (Tiếng Việt)"
3. Thêm các trang: Trang chủ, Giới thiệu, Bác sĩ, Dịch vụ, Câu chuyện, Liên hệ

### 5. Cấu hình cài đặt site
1. Vào **Cài đặt site** (Sidebar Admin)
2. Điền hotline, email, địa chỉ, social links

## 📁 Cấu trúc thư mục

```
uniasia-cancer-theme/
├── style.css                     # Theme metadata
├── functions.php                 # Theme setup + hooks
├── theme.json                    # Block editor config
├── header.php                    # Header template
├── footer.php                    # Footer template
├── front-page.php                # Homepage template
├── page.php                      # Default page template
├── index.php                     # Fallback template
├── 404.php                       # 404 page
│
├── page-templates (CPT archives) /
│   ├── archive-doctor.php
│   ├── archive-patient_story.php
│   ├── archive-cancer_type.php
│   ├── archive-technology.php
│   └── archive-faq.php
│
├── single-templates (CPT singles) /
│   ├── single-doctor.php
│   └── single-patient_story.php
│
├── template-parts/                # Reusable sections
│   ├── section-hero.php
│   ├── section-why-choose.php
│   ├── section-mdt-team.php
│   ├── section-cancer-types.php
│   ├── section-treatment-tech.php
│   ├── section-patient-stories.php
│   ├── section-international-guide.php
│   ├── section-faqs.php
│   └── section-contact-form.php
│
├── inc/                           # PHP includes
│   ├── custom-post-types.php
│   ├── custom-taxonomies.php
│   ├── acf-fields.php
│   ├── elementor-support.php
│   ├── wpml-config.php
│   ├── wpml-config.xml
│   ├── seo-schema.php
│   ├── form-handler.php
│   └── template-helpers.php
│
├── template-elementor/            # Elementor integration
│   ├── elementor-templates/        # JSON templates
│   │   └── homepage.json
│   └── elementor-custom-widgets/   # Custom widgets
│       ├── widget-stats-counter.php
│       └── widget-doctor-card.php
│
├── assets/
│   ├── css/
│   │   ├── main.css               # Main stylesheet
│   │   ├── responsive.css         # Responsive
│   │   └── elementor-overrides.css
│   ├── js/
│   │   ├── main.js                # Main interactions
│   │   ├── swiper-init.js         # Slider init
│   │   └── faq-accordion.js       # FAQ toggle
│   ├── images/                    # Theme images
│   └── fonts/                     # Custom fonts (if any)
│
└── languages/                     # i18n files
    └── uniasia-vi.pot
```

## 🎨 Custom Post Types

| CPT | Slug | Mục đích | Menu Icon |
|-----|------|----------|-----------|
| Doctor | `/doctors/` | Quản lý bác sĩ | 👨‍⚕️ Businessman |
| Patient Story | `/patient-stories/` | Câu chuyện bệnh nhân | ❤️ Heart |
| Cancer Type | `/cancer-types/` | Loại ung thư | 🌐 Press This |
| Technology | `/technologies/` | Kỹ thuật điều trị | 🔧 Tools |
| FAQ | `/faqs/` | Câu hỏi thường gặp | 💬 Format Chat |
| Consultation | (private) | Yêu cầu tư vấn | 📧 Email |

## 🏷️ Custom Taxonomies

| Taxonomy | Slug | Dùng cho |
|----------|------|----------|
| Cancer Category | `cancer_category` | Patient Story, Cancer Type |
| Doctor Specialty | `doctor_specialty` | Doctor |
| FAQ Group | `faq_group` | FAQ |

## 🎨 Color Palette

| Tên | Hex | Mục đích |
|-----|-----|----------|
| Primary | `#0066a4` | Màu chính (CTA, links) |
| Primary Dark | `#004a7c` | Hover states |
| Primary Light | `#e6f2fa` | Backgrounds nhẹ |
| Secondary | `#00a3cc` | Hero stats, accents |
| Accent | `#ff6b35` | Cancer type tags |
| Background Dark | `#1a2332` | Top bar, footer |

## 📱 Breakpoints

| Breakpoint | Width | Thiết bị |
|-----------|-------|----------|
| Mobile | `< 480px` | Điện thoại nhỏ |
| Mobile | `< 768px` | Điện thoại |
| Tablet | `< 1024px` | Tablet |
| Desktop | `< 1280px` | Laptop |
| Wide | `>= 1280px` | Desktop lớn |

## 🌍 Đa ngôn ngữ

Theme hỗ trợ 4 ngôn ngữ:

| Code | Ngôn ngữ | Mặc định |
|------|----------|----------|
| `vi` | Tiếng Việt | ✅ |
| `en` | English | - |
| `id` | Indonesia | - |
| `zh-cn` | 中文 (Giản thể) | - |

URL Slug cho từng ngôn ngữ:
- Tiếng Việt: `/vi/` (mặc định)
- English: `/en/` hoặc `/`
- Indonesia: `/id/`
- Chinese: `/zh-cn/`

## 🛠️ Customization

### Thay đổi màu chính
Mở `assets/css/main.css` và chỉnh:
```css
:root {
  --uniasia-primary: #YOUR_COLOR;
}
```

### Thêm section mới vào trang chủ
Mở `front-page.php` và thêm:
```php
get_template_part( 'template-parts/section', 'ten-moi' );
```

Tạo file `template-parts/section-ten-moi.php` với code HTML/PHP.

### Tùy chỉnh CSS
- Theme styles: `assets/css/main.css`
- Responsive: `assets/css/responsive.css`
- Elementor overrides: `assets/css/elementor-overrides.css`

## 📞 Hỗ trợ

- **Website**: https://unicancercenter.com/
- **Documentation**: [Xem trong thư mục docs/](#)
- **Issues**: Liên hệ dev team qua email admin@uniasia-cancer.com

## 📄 License

GNU General Public License v2 or later.
URI: http://www.gnu.org/licenses/gpl-2.0.html

## 👥 Credits

- **Design Source**: [unicancercenter.com](https://unicancercenter.com/)
- **Page Builder**: [Elementor Pro](https://elementor.com/)
- **Icons**: Material Design Icons (SVG inline)
- **Fonts**: [Google Fonts - Inter](https://fonts.google.com/specimen/Inter)
- **Slider**: [Swiper.js](https://swiperjs.com/)
- **Schema**: [Schema.org](https://schema.org/)

---

**Version 1.0.0** - Phát hành 2025-01-15