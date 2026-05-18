# DEPLOY - איך להעלות את השינויים ל-VPS

## לפני הכל - גיבוי

```bash
ssh user@153.92.209.8
cd /home/transme-ai/htdocs/hlo-media.com
tar -czf /tmp/hlo-backup-$(date +%Y%m%d-%H%M%S).tar.gz .
```

## שלב 1: העלאת קבצים חדשים

תיקיות שלמות חדשות:
- `/includes/` (5 קבצים)
- `/scripts/` (1 קובץ)
- `/server/migrations/` (1 קובץ)

קבצים בודדים חדשים:
- `/sitemap.php`
- `/robots.txt`
- `/.htaccess`
- `/CHANGES.md` (אופציונלי - תיעוד בלבד)

## שלב 2: קבצים שתוקנו (overwrite)

- `/index.php`
- `/en.php`
- `/thanks.php`
- `/sitemap.xml`
- `/generate_post.php`
- `/server/function.php`
- `/assets/js/main.js`
- 144 קבצים תחת `/blog/*.php`

## שלב 3: DB migration

```bash
ssh user@153.92.209.8
cd /home/transme-ai/htdocs/hlo-media.com
mysql -u root -p hlomedia < server/migrations/001_attribution.sql
```

אמת:
```bash
mysql -u root -p hlomedia -e "DESCRIBE leads;"
```

צריך לראות 22 עמודות, כולל `utm_source`, `landing_page`, `search_query`.

## שלב 4: הרשאות קבצים

```bash
chmod 755 scripts/
chmod 644 .htaccess robots.txt
chmod -R 644 includes/*.php server/migrations/*.sql
```

## שלב 5: אימות שהכל עובד

### בדיקות בסיס
```bash
# .htaccess פעיל?
curl -I https://hlo-media.com/ 2>&1 | grep -i 'location\|server'
# צריך 301 ל-www.hlo-media.com

# Sitemap עובד?
curl https://www.hlo-media.com/sitemap.xml | head -20
# צריך לראות urlset עם 146 URLs

# robots.txt חי?
curl https://www.hlo-media.com/robots.txt

# Schema תקין?
# פתח https://validator.schema.org/
# הכנס https://www.hlo-media.com/
# צריך לראות 5 schemas בלי errors
```

### בדיקת טופס יצירת קשר
1. גש ל-https://www.hlo-media.com/?utm_source=test&utm_campaign=deploy_test
2. גלול ל"צרו איתנו קשר"
3. בדוק ב-DevTools → Network שיש hidden inputs `landing_page`, `utm_source` וכו'
4. מלא ושלח את הטופס
5. בדוק ב-DB:
```sql
SELECT id, name, utm_source, utm_campaign, landing_page, created_at
FROM leads ORDER BY id DESC LIMIT 1;
```
צריך לראות את הפרטים + utm_source=test + utm_campaign=deploy_test.

### בדיקת GA4
1. https://analytics.google.com → Realtime
2. גש לאתר מטלפון אחר
3. לחץ על מספר טלפון/וואטסאפ/מייל
4. אמור לראות events: `phone_click`, `whatsapp_click`, `email_click`

## שלב 6: Google Search Console (חד-פעמי)

1. https://search.google.com/search-console
2. Add property → URL prefix → `https://www.hlo-media.com/`
3. Verify (DNS TXT record או HTML tag)
4. Submit sitemap: `https://www.hlo-media.com/sitemap.xml`

## שלב 7: בקשת re-indexing

ב-GSC:
1. URL Inspection → https://www.hlo-media.com/ → Request Indexing
2. אותו דבר לכמה מאמרים בולטים מה-blog
3. Sitemap → אמת שמוצג "Success" ו-146 URLs

---

## Rollback (אם משהו נשבר)

```bash
ssh user@153.92.209.8
cd /home/transme-ai/htdocs/hlo-media.com
# מצא את הגיבוי
ls /tmp/hlo-backup-*.tar.gz
# שחזר
tar -xzf /tmp/hlo-backup-YYYYMMDD-HHMMSS.tar.gz
```

DB rollback (אם migration נכשל):
```sql
DROP TABLE IF EXISTS leads;
```
(הטבלה הישנה `customers` לא נגעה - לא תאבד דאטה).

---

## הצעד הבא אחרי Deploy

1. תקבל לי גישת SSH ל-VPS
2. אני מעלה את כל הקבצים בעצמי (אעדיף מ-deploy ידני)
3. אני מריץ את ה-migration
4. אני מאמת שהכל עובד
5. **חכה 7-14 ימים** לאיסוף data ב-GSC + GA4
6. אז נתחיל Phase 1 - בניית הסוכן עצמו עם feedback loop על data אמיתי
