# SEO Agent — HloMedia

סוכן שבועי שמנתח ביצועי SEO, כותב מאמר חדש בעברית, יוצר Pull Request ב-GitHub, ושולח לך דוח במייל. אתה מאשר merge - והמאמר עולה.

```
כל יום שישי 22:00 →
  [1] GSC: שלוף נתונים מהשבוע (clicks, impressions, queries, positions)
  [2] Central DB: שלוף לידים שנכנסו השבוע
  [3] Claude Opus 4.7: נתח + כתוב דוח + כתוב מאמר 800-1200 מילים
  [4] בנה קובץ PHP מוכן (self-contained, עם Schema + canonical + OG)
  [5] GitHub: צור branch → דחוף קובץ → פתח PR
  [6] Resend: שלח דוח במייל + קישור ישיר ל-PR
```

**אתה בשליטה מלאה.** שום מאמר לא עולה לאתר בלי merge ידני שלך.

---

## תיקוני באגים שעשיתי לעומת הספק המקורי

| בעיה ב-spec המקורי | מה שעשיתי |
|---|---|
| `claude-opus-4-5` - מודל לא קיים | החלפתי ל-`claude-opus-4-7` (הכי חדש) |
| חסר header `anthropic-version` - Claude API היה זורק 400 | הוספתי `anthropic-version: 2023-06-01` |
| ה-workflow המקורי רק יצר קובץ על branch, אבל **לא פתח PR** | פיצלתי ל-4 קריאות: GET main SHA → POST create branch → PUT file → POST pull request |
| ה-PHP template ייבא `includes/header.php` ו-`footer.php` שלא קיימים → 500 error בייצור | החלפתי בטמפלייט self-contained שמתאים לקיים של 144 המאמרים ב-`/blog/` |
| המייל הצביע על `https://github.com/` סתם | מצביע ל-`html_url` של ה-PR שנפתח |
| `$credentials.githubOwner` ב-URL לא עובד על Header Auth credential | hardcoded ל-`idanedri27/hlomedia` |

---

## הקמה - 4 שלבים

### שלב 1: leads-summary.php על השרת (2 דקות)

הקובץ נמצא ב-`seo-agent/leads-summary.php`. תעלה לשרת:

```bash
# מ-VPS
cd /opt/seo-platform/api/
wget https://raw.githubusercontent.com/idanedri27/hlomedia/main/seo-agent/leads-summary.php
# או:
git clone https://github.com/idanedri27/hlomedia.git /tmp/hlomedia-pull
cp /tmp/hlomedia-pull/seo-agent/leads-summary.php /opt/seo-platform/api/leads-summary.php
chown www-data:www-data /opt/seo-platform/api/leads-summary.php
chmod 644 /opt/seo-platform/api/leads-summary.php
```

צור את ה-symlink:

```bash
ln -sf /opt/seo-platform/api/leads-summary.php \
       /home/api/htdocs/api.hlo-media.com/leads-summary.php
```

הוסף Admin API key ל-.env:

```bash
echo "ADMIN_API_KEY=$(openssl rand -hex 32)" >> /opt/seo-platform/config/.env
grep ADMIN_API_KEY /opt/seo-platform/config/.env
# העתק את הערך - תצטרך אותו ב-n8n שלב 3.ב'
```

בדיקה שזה עובד:

```bash
ADMIN_KEY=$(grep ADMIN_API_KEY /opt/seo-platform/config/.env | cut -d= -f2)
curl -s "https://api.hlo-media.com/leads-summary.php?site_slug=hlomedia&days=7" \
     -H "X-API-Key: $ADMIN_KEY" | python3 -m json.tool | head -20
```
אמור להחזיר JSON עם `site`, `total`, `by_source` וכו'.

---

### שלב 2: GSC OAuth (10 דקות, חד-פעמי)

**א. Google Cloud Console:**
1. https://console.cloud.google.com/
2. Create Project → "hlo-seo-agent"
3. APIs & Services → Library → חפש "Search Console API" → Enable

**ב. OAuth Credentials:**
1. APIs & Services → Credentials → Create Credentials → OAuth Client ID
2. אם מבקש OAuth Consent Screen: External, App name `hlo-seo-agent`, Support email `idanedri27@gmail.com`, דלג על scopes
3. חזור ל-Credentials → OAuth Client ID
4. Application type: **Web application**, Name: `n8n`
5. Authorized redirect URIs: `https://n8n.hlo-media.com/rest/oauth2-credential/callback`
6. Create → שמור **Client ID** + **Client Secret**

**ג. ודא ש-`idanedri27@gmail.com` הוא owner ב-GSC property** של `https://www.hlo-media.com/` - אחרת המופע ל-GSC API יחזיר 403.

---

### שלב 3: Credentials ב-n8n (5 דקות)

ב-n8n → Settings → Credentials → Add Credential. צור את 5 ה-credentials הבאים:

**3.א. "GSC - HloMedia"**
- Type: `Google Search Console OAuth2 API`
- Client ID + Client Secret מ-שלב 2.ב'
- Save → "Sign in with Google" → אשר הרשאות

**3.ב. "SEO Platform API"**
- Type: `Header Auth`
- Name: `X-API-Key`
- Value: ה-`ADMIN_API_KEY` מ-שלב 1

**3.ג. "Claude API"**
- Type: `Header Auth`
- Name: `x-api-key`
- Value: Claude API key מ-https://console.anthropic.com/settings/keys

**3.ד. "Resend API"**
- Type: `Header Auth`
- Name: `Authorization`
- Value: `Bearer re_YOUR_RESEND_KEY` (החלף ל-key אמיתי)

**3.ה. "GitHub API"**
- צור Personal Access Token: https://github.com/settings/tokens → Generate new token (classic) → scope: `repo`
- Type: `Header Auth`
- Name: `Authorization`
- Value: `Bearer ghp_YOUR_TOKEN`

---

### שלב 4: ייבוא ה-Workflow (1 דקה)

1. n8n → Overview → Create workflow
2. בתוך ה-workflow ריק: שלוש נקודות בפינה ← "**Import from File**"
3. בחר את `seo-agent/workflow.json`
4. אחרי ייבוא: כנס לכל node שמסומן באדום (חסר credential), לחץ על שדה ה-credential, ובחר את הקרדנציאל המתאים מהרשימה. הסדר:
   - `שלוף נתונים מ-GSC` → GSC - HloMedia
   - `שלוף לידי השבוע` → SEO Platform API
   - `Claude מנתח וכותב` → Claude API
   - כל 4 ה-GitHub nodes → GitHub API
   - `שלח דוח במייל` → Resend API
5. Save (Cmd/Ctrl+S)

---

## הרצה ראשונה

לפני להפעיל את ה-schedule, תריץ ידנית:

1. פתח את ה-workflow
2. Execute Workflow (כפתור כתום ימני למעלה)
3. צפה איך כל node רץ - אם משהו אדום, פתח ובדוק את ה-error
4. אחרי 60-120 שניות אמור להגיע:
   - PR חדש ב-https://github.com/idanedri27/hlomedia/pulls
   - מייל ל-idanedri27@gmail.com עם דוח + קישור

---

## הפעלה אוטומטית

אחרי שההרצה הידנית עבדה:

1. ב-workflow למעלה ימין יש toggle אפור עם המילה **Inactive**
2. לחץ עליו → הופך לכחול **Active**
3. הסוכן יתחיל לרוץ אוטומטית כל שישי 22:00 (שעון ישראל)

---

## עלויות צפויות

| שירות | מחיר |
|---|---|
| n8n self-hosted | 0₪ |
| Claude Opus 4.7 (~10K in + 8K out tokens) | ~$1-1.50 לריצה |
| Resend (עד 100 מיילים/יום) | 0₪ |
| GitHub (private repos) | 0₪ |
| GSC API | 0₪ |
| **סה"כ חודשי (4 ריצות)** | **~$4-6** |

**הערה:** ה-spec המקורי העריך $0.30-0.50 לריצה - זה היה תמחור של Sonnet, לא Opus. עם Opus 4.7 (איכות מאמרים גבוהה יותר) המחיר עולה אבל זה עדיין תחת $10/חודש. אם תרצה לחסוך - אפשר להחליף ב-node `Claude מנתח וכותב` את `claude-opus-4-7` ל-`claude-sonnet-4-6` ולהוריד את העלות ל-~$0.30/ריצה.

---

## בעיות נפוצות

**`Claude מנתח וכותב` מחזיר 401:**
- בדוק ש-`x-api-key` (lowercase x) ב-credential, לא `X-API-Key`
- בדוק שה-key פעיל ב-https://console.anthropic.com/settings/keys

**`GitHub: צור branch` מחזיר 422 "Reference already exists":**
- ה-branch כבר קיים מריצה קודמת שכשלה אמצע הדרך
- מחק את ה-branch ב-https://github.com/idanedri27/hlomedia/branches ונסה שוב

**`GSC` מחזיר 403:**
- `idanedri27@gmail.com` (המייל ש-OAuth מאומת עליו) חייב להיות Owner ב-GSC property של `https://www.hlo-media.com/`
- GSC → Settings → Users and permissions

**`Claude מנתח וכותב` עוצר ב-timeout:**
- העלה את `options.timeout` ב-node מ-120000 ל-180000 (3 דקות)
- אם עדיין נופל - תקצר את ה-prompt בקוד ה-node

**ה-מאמר לא בעברית מקצועית מספיק:**
- ערוך את ה-prompt ב-`claude-analyze` node
- הוסף דוגמאות ספציפיות לסגנון שאתה אוהב
- הוסף "אל תכתוב X" / "כתוב Y" בצורה מאוד ספציפית

---

## הצעדים הבאים אחרי שהסוכן רץ

1. **שבוע 1-2:** הסוכן ירוץ ויכתוב 1-2 מאמרים. תאשר/דחה ידנית. אם דחית - הוסף תגובה ל-PR למה, ובשבוע הבא הסוכן ילמד.
2. **שבוע 3-4:** תזהה patterns - איזה נושאים מביאים לידים, איזה לא.
3. **חודש 2:** נכוון את ה-prompt לפי מה שראינו - נוסיף "תעדיף נושאים שמביאים לידים מסוג X".
4. **חודש 3:** נוסיף יכולות - dashboard עם גרפים, internal linking אוטומטי, A/B testing על meta titles.
