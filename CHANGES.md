# HloMedia - סיכום השינויים שבוצעו

תאריך: 2026-05-18

## תקציר
שלב 0 הושלם: כל ה-baseline SEO של האתר שופר, נוסף lead attribution מלא, ונבנתה תשתית למעקב אחרי תוצאות. **לא נגענו בעיצוב** - רק תכנים תחת `<head>`, טופס יצירת קשר, וקבצי תוכן (PHP).

---

## קבצים חדשים

| נתיב | תפקיד |
|------|--------|
| `includes/site-config.php` | קונפיג מרכזי (טלפון, מייל, GA4 ID, schema defaults) - שנה כאן, מתפזר לכל מקום |
| `includes/head-meta.php` | כל ה-meta tags (description, OG, Twitter, canonical, hreflang) |
| `includes/schema.php` | JSON-LD: Organization + LocalBusiness + WebSite + תוספות לפי דף |
| `includes/analytics.php` | GA4 + Hotjar + מעקב אוטומטי על phone/whatsapp/email/lead clicks |
| `includes/attribution-capture.php` | hidden fields ו-JS שמלכדים landing page + UTM + referrer לטופס |
| `sitemap.php` | sitemap דינמי שמושך את כל 144 המאמרים מה-DB אוטומטית |
| `robots.txt` | מצביע על sitemap, חוסם /server/, /vendor/, /includes/, /thanks.php |
| `.htaccess` | HTTPS+www forced, gzip, browser cache, security headers, /sitemap.xml→sitemap.php rewrite |
| `server/migrations/001_attribution.sql` | טבלת `leads` חדשה עם 20 עמודות (כולל UTM, landing_page, search_query) |
| `scripts/fix-blog-articles.php` | סקריפט one-shot שעבר על 144 המאמרים ותיקן את כולם |

---

## קבצים שתוקנו

### `index.php`
- `<head>` שוכתב לחלוטין דרך includes (`head-meta.php` + `schema.php` + `analytics.php`)
- title חדש עם geo-target: "הלו מדיה - חברת תוכנה ושיווק דיגיטלי בתל אביב | פיתוח אתרים, CRM, SEO"
- meta description ממוקד עם טלפון + CTA
- **5 schemas** נטענים: Organization, LocalBusiness, WebSite, FAQPage (4 שאלות), 3× Service
- canonical + hreflang (he/en/x-default)
- email mismatch תוקן (`hlomedia.office@email.com` → `hlomedia.office@gmail.com` בכל המופעים)
- logo `href="#"` → `href="/"` (טוב יותר ל-SEO)
- alt tags משופרים על כל התמונות החשובות (slider, about, services, portfolio)
- service-icon-01 שהיה משוכפל ל-"שיווק דיגיטלי" → תוקן ל-service-icon-02
- כפתור עם syntax שגוי `if class active color : #fa65b1 !important` תוקן
- טופס יצירת קשר: הוסף שדה טלפון, label-ים נסתרים לנגישות, attribution hidden fields, honeypot anti-spam
- scripts: `defer` על כולם → טעינה מהירה יותר
- geo-redirect משופר: לא רץ על בוטים, ניתן לעקיפה עם `?nogeo=1`
- WhatsApp button: `rel="noopener"` + `aria-label`
- footer: הוספו טלפון ומייל קליקביליים

### `thanks.php`
- shuktav לדף תודה דו-לשוני (HE/EN) לפי referrer
- `lang="he"` (היה `lang="en"` באג)
- `<meta name="robots" content="noindex, nofollow">` - דף תודה לא צריך להיכנס לאינדקס
- conversion event `lead_conversion_complete` נשלח ל-GA4 בטעינת הדף
- email mismatch תוקן

### `en.php`
- title + description באנגלית עם geo-target Tel Aviv
- canonical: `https://www.hlo-media.com/en.php`
- hreflang מצביע בחזרה לעברית
- email mismatch תוקן + logo href

### `server/function.php`
- שוכתב לחלוטין: handler ל-quotation וגם ל-lead, עם attribution מלא
- שמירה ל-`leads` table החדש (עם fallback ל-`customers` הישן אם migration לא רץ עדיין)
- email notification כעת כולל: landing_page, UTM source, search_query, mobile yes/no, location, Lead ID
- honeypot anti-spam (`hp_website` hidden field)
- response עכשיו JSON תקין במקום echo גולמי

### `assets/js/main.js`
- function `getAttribution()` חדשה: שולפת את כל ה-hidden fields ושולחת ב-POST
- function `lead()`: מוסיף phone, attribution, ושולח `trackLeadSubmit` ל-GA4
- function `quotation()`: שולח attribution גם הוא
- validation משופר: דורש name + (email OR phone)

### 144 קבצי בלוג ב-`/blog/`
- og:image path תוקן (`/images/og.jpg` → `/assets/images/og.jpg`)
- og:url שונה מ-`.html` ל-`.php` (היה bug בטמפלייט)
- נוסף `<link rel="canonical">` לכל מאמר
- נוסף `<meta name="robots" content="index, follow, max-image-preview:large">`
- נוספה **schema Article + BreadcrumbList** (rich results בגוגל)
- meta description עברה ניקוי משורות בודדות (היה נשמר עם `\n`)
- backup של כל קובץ נשמר ב-`/blog/.backup-20260518/`

### `generate_post.php`
- הטמפלייט של מאמרים חדשים תוקן: בעתיד כל מאמר ייווצר עם canonical, robots, Article+Breadcrumb schemas, og:image תקין, וכו'
- meta description נקייה (`strip_tags + collapse whitespace + htmlspecialchars`)
- og:url מצביע על `.php` (לא `.html`)

### `sitemap.xml`
- היה רק 1 URL (homepage). עכשיו הוא wrapper שמפנה ל-`sitemap.php` הדינמי
- `sitemap.php` מייצר 146 URLs (homepage + en.php + 144 מאמרים) דינמית מה-DB

---

## SEO Score Impact (הערכה)

| מדד | לפני | אחרי |
|-----|------|------|
| Lighthouse SEO score | ~65 | **95+** |
| Schema markup | 0 schemas | 5 schemas dashboard + Article+Breadcrumb בכל מאמר |
| Indexable URLs בסיטמאפ | 1 | 146 |
| Pages with canonical | 0 | 100% |
| Pages with `lang` attr | תקין רק במאמרים | תקין בכל הדפים |
| Lead attribution data | אפס | landing_page + UTM + referrer + search_query + mobile/desktop |

---

## מה הסוכן יוכל לעשות עכשיו (Phase 1)

עכשיו שיש תשתית, הסוכן יכול:
1. **לקרוא** את `leads` table → לראות איזה דפים מביאים לידים אמיתיים
2. **לקרוא** את GSC API (אחרי שתחבר) → לראות איזה queries מביאים impressions
3. **לזהות** opportunities: keywords ב-rank 8-20 שיש להם impressions אבל אין clicks
4. **לכתוב** מאמרים חדשים דרך `generate_post.php` (עם SEO מלא מהרגע הראשון)
5. **לדווח** שבועית במייל מה נעשה ומה התוצאות

---

## מה דרוש ממך לפני Phase 1

1. **GSC verification** - אמת ownership של www.hlo-media.com ב-Google Search Console (5 דקות)
2. **Service account ל-GSC API** - הסבר ב-`הסבר_עבודה_extracted/00-SETUP-GUIDE.md`
3. **גישה ל-VPS** - SSH credentials כדי שאעלה את כל הקבצים (deploy)
4. **הרצת DB migration** - `mysql -u root -p hlomedia < server/migrations/001_attribution.sql` על השרת

ראה `DEPLOY.md` להוראות מדויקות.
