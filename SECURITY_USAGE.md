# دليل استخدام دوال الأمان

## 1. التحقق من المدخلات

```php
// تنظيف المدخلات من XSS
$username = sanitizeInput($_POST['username']);

// التحقق من صحة البريد
if (!validateEmail($_POST['email'])) {
    sendErrorResponse("Invalid email address");
}

// التحقق من طول الإدخال
if (!validateLength($_POST['password'], 8, 128)) {
    sendErrorResponse("Password must be 8-128 characters");
}

// التحقق من رقم الهاتف
if (!validatePhone($_POST['phone'])) {
    sendErrorResponse("Invalid phone number");
}

// التحقق من الأرقام
if (!validateNumeric($_POST['age'], 18, 120)) {
    sendErrorResponse("Age must be between 18 and 120");
}
```

## 2. حماية كلمات المرور

```php
// تشفير كلمة المرور
$hashedPassword = hashPassword($_POST['password']);

// التحقق من قوة كلمة المرور
$errors = validatePasswordStrength($_POST['password']);
if (!empty($errors)) {
    sendErrorResponse("Weak password", 400, $errors);
}

// التحقق من صحة كلمة المرور
if (verifyPassword($_POST['password'], $user['password_hash'])) {
    // كلمة المرور صحيحة
}
```

## 3. حماية تسجيل الدخول

```php
// فحص محاولات متكررة
checkRateLimit('login_' . getClientIP(), 5, 300); // 5 محاولات في 5 دقائق

// تسجيل محاولة دخول فاشلة
logFailedLogin($username);

// تسجيل دخول ناجح
logSuccessfulLogin($userId, $username);

// إعادة توليد معرف الجلسة (بعد الدخول الناجح)
regenerateSessionID();
```

## 4. الاستجابات الآمنة

```php
// إرسال استجابة خطأ
sendErrorResponse("Something went wrong", 500);

// إرسال استجابة نجاح
sendSuccessResponse([
    'user_id' => 123,
    'username' => 'john'
], "Login successful");

// الاستجابة تلقائياً تحتوي على Content-Type: application/json
```

## 5. تسجيل الأحداث الأمنية

```php
// تسجيل نشاط مريب
logSuspiciousActivity('UNAUTHORIZED_ACCESS', [
    'resource' => '/admin/users',
    'user_id' => $userId
]);

// تسجيل محاولة فشل (في security.php)
logSecurityEvent('DATABASE_ERROR', [
    'error' => 'Connection failed'
]);
```

## 6. الحصول على معلومات الجلسة

```php
// الحصول على عنوان IP للعميل
$clientIP = getClientIP();

// ستقرأ من:
// - HTTP_CLIENT_IP (إذا كان متوفراً)
// - HTTP_X_FORWARDED_FOR (خلف proxy)
// - REMOTE_ADDR (القيمة الافتراضية)
```

## 7. حماية CSRF (إذا كنت تستخدم نماذج HTML)

```php
// في النموذج:
<form method="POST">
    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
</form>

// في معالج النموذج:
if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
    sendErrorResponse("Invalid request", 403);
}
```

## 8. أمثلة عملية - نموذج تسجيل دخول آمن

```php
<?php
require 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // فحص محاولات متكررة
    checkRateLimit('login_' . getClientIP(), 5, 300);
    
    // الحصول على المدخلات
    $username = sanitizeInput($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // التحقق من أن المدخلات ليست فارغة
    if (empty($username) || empty($password)) {
        sendErrorResponse("Username and password required");
    }
    
    try {
        // البحث عن المستخدم
        $stmt = $con->prepare("SELECT id, password_hash FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        // التحقق من وجود المستخدم والتحقق من كلمة المرور
        if ($user && verifyPassword($password, $user['password_hash'])) {
            // تسجيل دخول ناجح
            logSuccessfulLogin($user['id'], $username);
            regenerateSessionID();
            $_SESSION['user_id'] = $user['id'];
            
            sendSuccessResponse(['user_id' => $user['id']], "Login successful");
        } else {
            // تسجيل محاولة فاشلة
            logFailedLogin($username);
            sendErrorResponse("Invalid credentials", 401);
        }
    } catch (PDOException $e) {
        logSecurityEvent('LOGIN_ERROR', ['error' => $e->getMessage()]);
        sendErrorResponse("An error occurred", 500);
    }
}
?>
```

## 9. أمثلة عملية - إنشاء مستخدم جديد آمن

```php
<?php
require 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitizeInput($_POST['username'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // التحقق من صحة المدخلات
    if (!validateLength($username, 3, 50)) {
        sendErrorResponse("Username must be 3-50 characters");
    }
    
    if (!validateEmail($email)) {
        sendErrorResponse("Invalid email address");
    }
    
    $passwordErrors = validatePasswordStrength($password);
    if (!empty($passwordErrors)) {
        sendErrorResponse("Weak password", 400, $passwordErrors);
    }
    
    try {
        $hashedPassword = hashPassword($password);
        
        $stmt = $con->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashedPassword]);
        
        logSecurityEvent('USER_REGISTERED', ['username' => $username, 'email' => $email]);
        sendSuccessResponse(['user_id' => $con->lastInsertId()], "Registration successful");
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate') !== false) {
            sendErrorResponse("Username or email already exists", 409);
        }
        logSecurityEvent('REGISTRATION_ERROR', ['error' => $e->getMessage()]);
        sendErrorResponse("An error occurred", 500);
    }
}
?>
```

## 10. ملاحظات مهمة

- ✅ جميع المدخلات تمر عبر `sanitizeInput()` قبل الاستخدام
- ✅ استخدام prepared statements دائماً مع قاعدة البيانات
- ✅ تسجيل جميع الأنشطة المريبة والأخطاء الأمنية
- ✅ استخدام هاشات قوية لكلمات المرور (BCRYPT مع cost=12)
- ⚠️ تأكد من إنشاء مجلد `logs/` مع صلاحيات الكتابة
- ⚠️ احذف المعلومات الحساسة من رسائل الخطأ في الإنتاج
- ⚠️ استخدم HTTPS دائماً في بيئة الإنتاج
