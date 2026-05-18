<?php

include __DIR__ . '/server/config.php';

$api_key = "REMOVED_OPENAI_API_KEY"; // הכנס את המפתח שלך כאן

$query = "SELECT title FROM posts";
$result = $con->query($query);

$titles = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $titles[] = $row['title'];
    }
}

$existing_titles = implode(", ", $titles);


function getUniqueTopic($api_key , $existing_titles) {
    $prompt = "הצע נושא חדש וייחודי למאמר, ב-5 מילים או פחות, שיכול לקדם את בית התוכנה שלנו היום. אנו מתמחים בבניית אתרים, מערכות CRM, שיווק דיגיטלי, דפי נחיתה, קידום אורגני, קידום ממומן, פיתוח API ופיתוח קוד חדשני. אנא הצע נושא אחד בלבד המתמקד באחד מהתחומים הללו.";
    $prompt .= "אתה אפילו יכול לגשת לאתרים פופולרים כמו AskPavel או Appsoft או Poptin  שיהיה לך תוכן חדשני ומעודכן  בבקשה שים לב אני צריך  5 מילים  אבל שיהיו איכותיים"; 
    $prompt .= "שים לב זה חייב להיות נושא  איכותי ברמות הכי גבוהות אנחנו רוצים להגיע ל3 מקומות הראשונים בגוגל הכי מהר שאפשר";
    $prompt .= "כמובן שיקדם את העסק שלי בית התוכנה שלי";
    $prompt .= "אנא ודא שהנושא אינו אחד מהנושאים הבאים:" . $existing_titles;
    
    $data = [
        "model" => "gpt-4o",
        "messages" => [
            ["role" => "system", "content" => "אתה מומחה לשיווק דיגיטלי וקידום אתרים."],
            ["role" => "user", "content" => $prompt]
        ],
        "temperature" => 0.7
    ];

    $ch = curl_init("https://api.openai.com/v1/chat/completions");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer {$api_key}",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    curl_close($ch);

    $response_data = json_decode($response, true);
    return $response_data['choices'][0]['message']['content'] ?? "לא התקבל נושא מה-AI.";
}

// קבלת נושא חדש
$topic = getUniqueTopic($api_key  , $existing_titles);


function createSlug() {
    return "article-" . time();
}


function formatContent($content) {
    return trim($content);
}


// פונקציה ליצירת מאמר איכותי עם דגש על SEO
function generateContent($api_key, $topic) {

    $prompt = "כתוב מאמר מקיף ומבוסס SEO בנושא: " . $topic . ".
    על המאמר להיות **ברמה הגבוהה ביותר**, ולכלול:
    - **כותרת ראשית (H1)** – ברורה וממוקדת, עם מילת מפתח עיקרית.
    - **מבוא קצר (פסקת פתיחה עם `<p>`)** – שמבהיר את חשיבות הנושא ומושך את הקורא להמשיך לקרוא.
    - **כותרות משנה (H2, H3)** – שיחלקו את המאמר בצורה הגיונית עם מילות מפתח רלוונטיות.
    - **פסקאות קצרות וברורות (Paragraphs `<p>`)** – עם הסבר מעמיק ותוכן מעניין.
    - **רשימות ממוספרות (`<ol>`) ורשימות נקודות (`<ul>`)** – לעזרה בקריאה מהירה ומובנית.
    - **תוכן מעשי** – שיכלול דוגמאות אמיתיות, סטטיסטיקות, וטיפים שימושיים.
    - **סיכום ברור עם קריאה לפעולה (CTA)** – כדי להניע את הקוראים לפעול.
    - **שאלות ותשובות (FAQ)** – בפורמט `<div class='faq'><h3>שאלה</h3><p>תשובה</p></div>` כדי לשפר את החיפוש הקולי בגוגל.";

    $prompt .= "
    **דרישות קריטיות:**
    - יש להחזיר **HTML מלא ונקי**, ללא שימוש בתגי `<meta>`, `<html>`, `<body>`, `<head>`, `<!DOCTYPE>`.
    - **אין להחזיר Markdown** (למשל ```html).
    - **יש להשתמש במילות מפתח** טבעיות, אך להימנע מהעמסת מילות מפתח (Keyword Stuffing).
    - **המאמר חייב לכלול לפחות 400 מילים** – כדי שיהיה מקיף ויספק ערך אמיתי לגולשים.
    - **השתמש בקישורים פנימיים וחיצוניים** (`<a href>`) במידת הצורך לחיזוק ה-SEO.
    - **וודא שהתוכן קריא ומניע לפעולה**, תוך שמירה על שפה מקצועית אך ידידותית. 

    **שפר את האיכות!**
    - הימנע מתוכן רדוד וחוזר, השתמש בעובדות ובסטטיסטיקות אם יש.
    - **הוסף מחקרים, נתונים וסטטיסטיקות** שמוכיחים את הטענות שלך.
    - **שלב דוגמאות מעשיות מהעולם האמיתי**.
    - **אל תיצור תוכן גנרי או חוזר על עצמו**, אלא שיהיה **חדשני, מקורי וייחודי**.
    - **התוכן חייב לשרת את הרצונות והדרישות של גוגל** כדי לקדם את האתר www.hlo-media.com לעמוד הראשון בתוצאות החיפוש.";

    $prompt .= "
        שיהיה ידידותי למשתמשים 
        שיהיה קריא  ומקצועי
        שישרת את הרצונות והדרישות של גוגל לקידום אתרים ברמות הכי גבוהות שיש
        תתן תוכן איכותי חשוב  ומעניין";

    

    $data = [
        "model" => "gpt-4o",
        "messages" => [
            ["role" => "system", "content" => "אתה מומחה לקידום אתרים, בניית אתרים, פיתוח CRM ושיווק דיגיטלי. כתוב מאמר איכותי שמדורג גבוה בגוגל."],
            ["role" => "user", "content" => $prompt]
        ],
        "temperature" => 0.7
    ];

    $ch = curl_init("https://api.openai.com/v1/chat/completions");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer {$api_key}",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    curl_close($ch);

    $response_data = json_decode($response, true);

    return $response_data['choices'][0]['message']['content'] ?? "לא התקבלה תגובה מה-AI.";
}

// יצירת כותרת, `slug` ותוכן למאמר
$title = $topic;
$slug = createSlug();
$content = generateContent($api_key, $topic);
$content = trim($content);

// מסיר ```html מההתחלה (אם קיים)
$content = preg_replace('/^```html\s*/', '', $content);

// מסיר ``` מהסוף (אם קיים)
$content = preg_replace('/\s*```$/', '', $content);

// שמירת המאמר במסד הנתונים
$stmt = $con->prepare("INSERT INTO posts (title, slug, content) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $title, $slug, $content);
$stmt->execute();

$file_path = __DIR__ . "/blog/{$slug}.php";

// Clean description (one line, max 160 chars)
$meta_desc = mb_substr(trim(preg_replace('/\s+/u', ' ', strip_tags($content))), 0, 160, 'UTF-8');
$meta_desc_safe = htmlspecialchars($meta_desc, ENT_QUOTES, 'UTF-8');
$title_safe = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
$canonical_url = "https://www.hlo-media.com/blog/{$slug}.php";
$pub_iso = date('c');

$html_content = "
<!DOCTYPE html>
<html lang='he'>

<head>
    <!-- Google tag (gtag.js) -->
    <script async src='https://www.googletagmanager.com/gtag/js?id=G-FDD7G7KZ8W'></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-FDD7G7KZ8W');
    </script>

    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
    <meta name='description' content='{$meta_desc_safe}'>
    <meta name='author' content='HloMedia'>
    <meta name='robots' content='index, follow, max-image-preview:large'>

    <link rel='canonical' href='{$canonical_url}'>

    <!-- Keywords for SEO -->
    <meta name='keywords' content='פיתוח תוכנה, שיווק דיגיטלי, SEO, CRM, בניית אתרים, קידום אתרים, הלו מדיה'>

    <!-- Open Graph for Social Media -->
    <meta property='og:type' content='article'>
    <meta property='og:site_name' content='HloMedia'>
    <meta property='og:locale' content='he_IL'>
    <meta property='og:title' content='{$title_safe} | HloMedia'>
    <meta property='og:description' content='{$meta_desc_safe}'>
    <meta property='og:image' content='https://www.hlo-media.com/assets/images/og.jpg'>
    <meta property='og:url' content='{$canonical_url}'>
    <meta property='article:published_time' content='{$pub_iso}'>

    <!-- Twitter Card -->
    <meta name='twitter:card' content='summary_large_image'>
    <meta name='twitter:title' content='{$title_safe} | HloMedia'>
    <meta name='twitter:description' content='{$meta_desc_safe}'>
    <meta name='twitter:image' content='https://www.hlo-media.com/assets/images/og.jpg'>

    <!-- Schema.org: Article + Breadcrumb -->
    <script type='application/ld+json'>
    {
      \"@context\": \"https://schema.org\",
      \"@type\": \"Article\",
      \"headline\": " . json_encode($title, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . ",
      \"description\": " . json_encode($meta_desc, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . ",
      \"datePublished\": \"{$pub_iso}\",
      \"dateModified\": \"{$pub_iso}\",
      \"author\": { \"@type\": \"Organization\", \"name\": \"HloMedia\" },
      \"publisher\": {
        \"@type\": \"Organization\",
        \"name\": \"HloMedia\",
        \"logo\": { \"@type\": \"ImageObject\", \"url\": \"https://www.hlo-media.com/assets/images/logo_1.jpg\" }
      },
      \"mainEntityOfPage\": { \"@type\": \"WebPage\", \"@id\": \"{$canonical_url}\" },
      \"image\": \"https://www.hlo-media.com/assets/images/og.jpg\",
      \"inLanguage\": \"he-IL\"
    }
    </script>
    <script type='application/ld+json'>
    {
      \"@context\": \"https://schema.org\",
      \"@type\": \"BreadcrumbList\",
      \"itemListElement\": [
        { \"@type\": \"ListItem\", \"position\": 1, \"name\": \"דף הבית\", \"item\": \"https://www.hlo-media.com/\" },
        { \"@type\": \"ListItem\", \"position\": 2, \"name\": \"בלוג\", \"item\": \"https://www.hlo-media.com/#blog\" },
        { \"@type\": \"ListItem\", \"position\": 3, \"name\": " . json_encode($title, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . ", \"item\": \"{$canonical_url}\" }
      ]
    }
    </script>

    <link rel='preconnect' href='https://fonts.googleapis.com'>
    <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
    <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap' rel='stylesheet'>

    <title>{$title_safe} | הבלוג של HloMedia</title>

    <!-- Bootstrap core CSS -->
    <link href='../vendor/bootstrap/css/bootstrap.min.css' rel='stylesheet'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css' integrity='sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==' crossorigin='anonymous' referrerpolicy='no-referrer' />

    <!-- Additional CSS Files -->
    <link rel='stylesheet' href='../assets/css/fontawesome.css'>
    <link rel='stylesheet' href='../assets/css/templatemo-digimedia-v1.css'>
    <link rel='stylesheet' href='../assets/css/animated.css'>
    <link rel='stylesheet' href='../assets/css/owl.css'>
    <link rel='stylesheet' href='../assets/css/main.css'>
    <link rel='stylesheet' href='../assets/css/blog.css'>

    <!-- Favicon -->
    <link rel='icon' type='image/png' sizes='32x32' href='../assets/images/favicon-32x32.png'>

</head>

<body dir='rtl'>

    <header class='header-area header-sticky wow slideInDown' style='position:relative'>
        <div class='container'>
            <div class='row'>
                <div class='col-12 p-0'>
                    <nav class='main-nav'>
                        <a href='../index.php' class='logo' style='margin-left:20px'>
                            <img src='../assets/images/logo_1.jpg' alt='logo' id='logo'>
                        </a>
                    </nav>
                    <ul>
                    <li class='pink-btn' style='position: absolute; right: 10px; top: 25px;'>
                        <div class='btn btn-light' style='padding: 10px 20px; border-radius: 10px;background-color: #fa65b1;'>
                            <a href='../index.php' style='color: white; text-decoration: none; font-weight: bold;'>כניסה לאתר</a>
                        </div>
                    </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
    <div class='container'>
    <div class='blog-content py-4'>" . nl2br(htmlspecialchars_decode(trim($content))) . "</div>
    </div>
    
        <style>
    .modal-body img {
        max-width: 100%;
        height: 300px;
        border-radius: 5px;
        }

    .modal-body p {
        font-size: 20px;
        color: black;
        text-align: center;
        }

    </style>
    <!-- מבנה ה-Modal -->
    <div class='modal fade' id='contactModal' tabindex='-1' aria-labelledby='contactModalLabel' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
        <div class=modal-header'>
        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
        </div>
        <div class='modal-body' dir='rtl'>
            <h4 class='modal-title text-center pb-3' id='contactModalLabel' >מעוניין לשפר את העסק שלך?</h4>
            <div class='text-center'>
            <img src='../assets/images/about-dec.png' class='img-fluid mb-3' alt='תיאור התמונה' >
            </div>
            <p>אנו מציעים ייעוץ חינם ושירותי פיתוח מתקדמים. השאירו פרטים ונחזור אליכם בהקדם!</p>
            <form id='contactForm' onsubmit='sendToWhatsApp(event)'>
                <div class='mb-3'>
                    <label for='name' class='form-label'>שם מלא</label>
                    <input type='text' class='form-control' id='name' required>
                </div>
                <div class='mb-3'>
                    <label for='phone' class='form-label'>מספר טלפון</label>
                    <input type='tel' class='form-control' id='phone' required>
                </div>
                <button type='submit' class='btn btn-primary'>שלח</button>
            </form>
        </div>
        </div>
    </div>
    </div>
    <script src='https://code.jquery.com/jquery-3.5.1.slim.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js'></script>
    <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js'></script>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('#contactModal').modal('show');
            }, 3000); 

             $('.btn-close').click(function () {
                // סגור את המודל
                $('#contactModal').modal('hide');
            });
        });

        function sendToWhatsApp(event) {
            event.preventDefault(); // מונע את שליחת הטופס הסטנדרטית

            // קבלת הערכים מהטופס
            var name = document.getElementById('name').value;
            var phone = document.getElementById('phone').value;

            // מספר הוואטסאפ של העסק (בפורמט בינלאומי, ללא סימני + או -)
            var businessWhatsAppNumber = '972526814747';

            // יצירת הודעה
            var message = 'שלום, שמי ' + name + '. מספר הטלפון שלי הוא ' + phone + '. הגעתי דרך אתר הלו-מדיה ואשמח לקבל ייעוץ חינם.';

            // קידוד ההודעה ל-URL
            var encodedMessage = encodeURIComponent(message);

            // יצירת קישור לוואטסאפ
            var whatsappURL = 'https://wa.me/' + businessWhatsAppNumber + '?text=' + encodedMessage;

            // פתיחת וואטסאפ בחלון חדש
            window.open(whatsappURL, '_blank');
        }

    </script>
</body>
</html>";


// כתיבה לקובץ
if (file_put_contents($file_path, $html_content)) {
    echo "✅ מאמר חדש נוצר ונשמר בהצלחה! נושא: $title. קובץ נוצר: $file_path";
} else {
    echo "❌ שגיאה בשמירת הקובץ.";
}

?>