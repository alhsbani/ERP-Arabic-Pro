# المساهمة في ERP-Arabic-Pro | Contributing to ERP-Arabic-Pro

## إرشادات المساهمة | Contribution Guidelines

شكراً لاهتمامك بالمساهمة في هذا المشروع! نرحب بالمساهمات من الجميع.

Thank you for your interest in contributing to this project! We welcome contributions from everyone.

### قبل البدء | Before You Start

1. تأكد من أن لديك حساب GitHub | Make sure you have a GitHub account
2. اقرأ الـ README للتعرف على المشروع | Read the README to understand the project
3. تحقق من القضايا المفتوحة والطلبات المعلقة | Check open issues and pending pull requests

### خطوات المساهمة | Contribution Steps

1. **اعمل Fork للمستودع**
   ```bash
   # انسخ المستودع إلى حسابك
   ```

2. **اعمل Clone للمستودع المنسوخ**
   ```bash
   git clone https://github.com/your-username/ERP-Arabic-Pro.git
   cd ERP-Arabic-Pro
   ```

3. **أنشئ فرع للميزة الجديدة**
   ```bash
   git checkout -b feature/your-feature-name
   # أو للإصلاحات
   git checkout -b fix/your-fix-name
   ```

4. **قم بإجراء التغييرات**
   - التزم بمعايير الكود
   - اكتب رسائل commit واضحة
   - أضف اختبارات إن لزم الأمر

5. **اختبر التغييرات**
   ```bash
   php artisan test
   ```

6. **Commit التغييرات**
   ```bash
   git commit -m "إضافة وصف واضح للتغييرات | Clear description of changes"
   ```

7. **Push للفرع**
   ```bash
   git push origin feature/your-feature-name
   ```

8. **افتح Pull Request**
   - قدم وصفاً واضحاً للتغييرات
   - ارجع إلى أي قضايا ذات صلة
   - انتظر المراجعة

### معايير الكود | Code Standards

- اتبع معايير Laravel
- استخدم PSR-12 لمعايير الأسلوب
- اكتب أسماء متغيرات واضحة وافتراضية واضحة
- أضف التعليقات للأكواد المعقدة

### الترخيص | License

بالمساهمة، فإنك توافق على أن تكون مساهماتك تحت رخصة MIT.

By contributing, you agree that your contributions will be licensed under the MIT License.
