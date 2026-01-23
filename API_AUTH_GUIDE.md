# دليل استخدام API المصادقة والتوكن

## 1. التسجيل (Sign Up)

### URL
```
POST /auth/signup.php
```

### Request Body
```json
{
    "username": "john_doe",
    "email": "john@example.com",
    "password": "SecurePass@123",
    "phone": "+20123456789"
}
```

### Response (Success)
```json
{
    "status": "success",
    "message": "Registration successful",
    "data": {
        "token": "a1b2c3d4e5f6...",
        "user_id": 1,
        "user_name": "john_doe",
        "user_email": "john@example.com",
        "user_phone": "+20123456789"
    }
}
```

### Response (Error)
```json
{
    "status": "error",
    "message": "Weak password",
    "details": [
        "Password must be at least 8 characters long",
        "Password must contain at least one uppercase letter",
        "Password must contain at least one number"
    ]
}
```

### Password Requirements
- ✅ At least 8 characters
- ✅ One uppercase letter (A-Z)
- ✅ One lowercase letter (a-z)
- ✅ One number (0-9)
- ✅ One special character (!@#$%^&*)

---

## 2. تسجيل الدخول (Login)

### URL
```
POST /auth/login.php
```

### Request Body
```json
{
    "username": "john_doe",
    "password": "SecurePass@123"
}
```

### Response (Success)
```json
{
    "status": "success",
    "message": "Login successful",
    "data": {
        "token": "a1b2c3d4e5f6...",
        "user_id": 1,
        "user_name": "john_doe",
        "user_email": "john@example.com",
        "user_phone": "+20123456789",
        "user_image": "uploads/profiles/profile_1_1234567890.jpg"
    }
}
```

### Response (Error)
```json
{
    "status": "error",
    "message": "Invalid credentials"
}
```

---

## 3. استخدام التوكن (Using Token)

### معلومات التوكن
- التوكن صالح لمدة **30 يوم**
- يمكن تحديثه قبل انتهاؤه
- يتم حذفه عند تسجيل الخروج

### طرق الإرسال:

#### 1. Authorization Header (الأفضل)
```
Authorization: Bearer a1b2c3d4e5f6...
```

#### 2. X-Auth-Token Header
```
X-Auth-Token: a1b2c3d4e5f6...
```

#### 3. POST Data (للتطوير فقط)
```
POST /api/endpoint.php
Content-Type: application/x-www-form-urlencoded

token=a1b2c3d4e5f6...&data=value
```

---

## 4. الحصول على بيانات المستخدم (Verified)

### URL
```
POST /auth/verify_token.php
```

### Request Headers
```
Authorization: Bearer a1b2c3d4e5f6...
```

### Response (Success)
```json
{
    "status": "success",
    "message": "Token is valid",
    "data": {
        "user_id": 1,
        "user_name": "john_doe",
        "user_email": "john@example.com",
        "user_phone": "+20123456789",
        "user_image": "uploads/profiles/profile_1_1234567890.jpg"
    }
}
```

---

## 5. تحديث التوكن (Refresh Token)

### URL
```
POST /auth/verify_token.php
```

### Request
```
Action: refresh_token
Authorization: Bearer a1b2c3d4e5f6...
```

### Response
```json
{
    "status": "success",
    "message": "Token refreshed successfully",
    "data": {
        "token": "new_token_a1b2c3d4e5f6...",
        "expires_at": "2026-02-23 12:00:00"
    }
}
```

---

## 6. تسجيل الخروج (Logout)

### URL
```
POST /auth/verify_token.php
```

### Request Headers
```
Authorization: Bearer a1b2c3d4e5f6...
```

### Action
```
logout
```

### Response
```json
{
    "status": "success",
    "message": "Logged out successfully"
}
```

---

## 7. استخدام في Dart/Flutter

### مثال في AuthController

```dart
// تحديث الـ login method
Future<void> login(String username, String password) async {
    isLoading.value = true;
    errorMessage.value = '';

    final response = await crud.postData(AppLink.login, {
        'username': username,
        'password': password,
    });

    response.fold(
        (failure) {
            errorMessage.value = 'Login failed. Please try again.';
        },
        (data) async {
            if (data['status'] == 'success') {
                final token = data['data']['token'];  // احصل على التوكن الحقيقي
                final user = data['data'];
                
                final prefs = await SharedPreferences.getInstance();
                await prefs.setString('auth_token', token);
                
                // احفظ بيانات المستخدم
                currentUser.value = User(
                    userId: user['user_id'],
                    userName: user['user_name'],
                    userEmail: user['user_email'],
                );
                
                isLoggedIn.value = true;
                Get.offAllNamed('/home');
            } else {
                errorMessage.value = data['message'] ?? 'Login failed';
            }
        },
    );

    isLoading.value = false;
}

// تحديث الـ signup method
Future<void> signup(String username, String email, String password, String phone) async {
    try {
        statusRequest = StatusRequest.loading;
        isLoading.value = true;

        final response = await crud.postData(AppLink.signup, {
            'username': username,
            'email': email,
            'password': password,
            'phone': phone,  // استخدم الرقم الحقيقي
        });

        response.fold(
            (failure) {
                errorMessage.value = 'Signup failed. Please try again.';
            },
            (data) async {
                if (data['status'] == 'success') {
                    final token = data['data']['token'];
                    final user = data['data'];
                    
                    final prefs = await SharedPreferences.getInstance();
                    await prefs.setString('auth_token', token);
                    
                    currentUser.value = User(
                        userId: user['user_id'],
                        userName: user['user_name'],
                        userEmail: user['user_email'],
                    );
                    
                    isLoggedIn.value = true;
                    Get.offAllNamed(AppRoutes.home);
                } else {
                    errorMessage.value = data['message'] ?? 'Signup failed';
                    if (data.containsKey('details')) {
                        Get.snackbar('Error', (data['details'] as List).join('\n'));
                    }
                }
            },
        );
    } catch (e) {
        errorMessage.value = 'An error occurred';
    }
    isLoading.value = false;
}
```

### إضافة Interceptor للتوكن

```dart
class AuthInterceptor extends GetConnect {
    @override
    void onInit() {
        super.onInit();
        
        httpClient.addRequestModifier((request) async {
            final prefs = await SharedPreferences.getInstance();
            final token = prefs.getString('auth_token');
            
            if (token != null) {
                request.headers['Authorization'] = 'Bearer $token';
            }
            
            return request;
        });
    }
}
```

---

## 8. معالجة الأخطاء الشائعة

### 401 - Unauthorized
```json
{
    "status": "error",
    "message": "Invalid or expired token"
}
```
**الحل**: اطلب من المستخدم تسجيل الدخول مرة أخرى

### 409 - Conflict
```json
{
    "status": "error",
    "message": "Email or phone already registered"
}
```
**الحل**: استخدم بريد أو رقم هاتف مختلف

### 429 - Too Many Requests
```json
{
    "status": "error",
    "message": "Too many attempts. Please try again later."
}
```
**الحل**: انتظر 5 دقائق قبل المحاولة مجددًا

---

## 9. متطلبات قاعدة البيانات

قم بتشغيل هذا الـ SQL لإنشاء جدول التوكنات:

```sql
CREATE TABLE IF NOT EXISTS auth_tokens (
    token_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NULL,
    last_used_at TIMESTAMP NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_token (token),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

---

## 10. نصائح الأمان

✅ استخدم دائماً **HTTPS** في الإنتاج  
✅ أرسل التوكن في **Authorization Header** وليس في URL  
✅ احفظ التوكن في **localStorage** أو **SharedPreferences**  
✅ احذف التوكن عند تسجيل الخروج  
✅ استخدم **token refresh** قبل انتهاء صلاحيته  
⚠️ لا تعرض التوكن الكامل في السجلات  
⚠️ لا تحفظ كلمات المرور - استخدم التوكنات فقط
