<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Filament Resource Translations (Arabic)
    |--------------------------------------------------------------------------
    |
    | Arabic translations for Filament resources and form components.
    |
    */

    // Sections
    'sections' => [
        'department_information' => 'معلومات القسم',
        'academic_level_information' => 'معلومات المستوى الدراسي',
        'grade_scale_information' => 'معلومات سلم التقديرات',
        'semester_information' => 'معلومات الفصل الدراسي',
        'student_registration_information' => 'معلومات تسجيل الطلاب',
        'instructor_information' => 'معلومات المدرس',
        'course_information' => 'معلومات المقرر',
        'student_information' => 'معلومات الطالب',
        'prerequisite_information' => 'معلومات المتطلب السابق',
        'program_requirement_information' => 'معلومات متطلب البرنامج',
        'assignment_information' => 'معلومات التعيين',
        'enrollment_information' => 'معلومات التسجيل',
    ],

    // Descriptions
    'descriptions' => [
        'department_info' => 'أدخل تفاصيل القسم لإنشاء أو تحديث قسم',
        'academic_level_info' => 'حدد المستويات الدراسية ومتطلبات الساعات المعتمدة',
        'grade_scale_info' => 'قم بإعداد سلالم التقديرات مع الدرجات والنقاط',
        'semester_info' => 'أنشئ وأدر الفصول الدراسية',
        'student_registration_info' => 'حدد فترة تسجيل الطلاب وحالة الفصل الدراسي',
        'instructor_info' => 'أدخل تفاصيل المدرس بما في ذلك معلومات الاتصال والحالة',
        'course_info' => 'أدخل معلومات المقرر بما في ذلك الرمز والاسم والساعات المعتمدة',
        'student_info' => 'أدخل تفاصيل الطالب والمعلومات الأكاديمية',
        'prerequisite_info' => 'حدد المقررات المتطلبة السابقة للمقررات الأخرى',
        'program_requirement_info' => 'قم بإعداد متطلبات البرنامج حسب القسم والمستوى الدراسي',
        'assignment_info' => 'تعيين المدرسين للمقررات لفصل دراسي محدد',
        'enrollment_info' => 'إدارة تسجيلات الطلاب في المقررات',
    ],

    // Field Labels
    'field' => [
        'department_code' => 'رمز القسم',
        'department_prefix' => 'بادئة القسم',
        'department_name' => 'اسم القسم',
        'department' => 'القسم',
        'students_count' => 'عدد الطلاب',
        'courses_count' => 'عدد المقررات',
        'instructors_count' => 'عدد المدرسين',
        'level_name' => 'اسم المستوى',
        'level_number' => 'رقم المستوى',
        'min_credit_hours' => 'الحد الأدنى من الساعات المعتمدة',
        'max_credit_hours' => 'الحد الأقصى من الساعات المعتمدة',
        'grade_letter' => 'حرف التقدير',
        'min_percentage' => 'الحد الأدنى للنسبة المئوية',
        'max_percentage' => 'الحد الأقصى للنسبة المئوية',
        'grade_points' => 'نقاط التقدير',
        'status' => 'الحالة',
        'semester_code' => 'رمز الفصل الدراسي',
        'semester_name' => 'اسم الفصل الدراسي',
        'semester' => 'الفصل الدراسي',
        'year' => 'السنة',
        'start_date' => 'تاريخ البداية',
        'end_date' => 'تاريخ النهاية',
        'enrollments_count' => 'عدد التسجيلات',
        'assignments_count' => 'عدد التعيينات',
        'is_active' => 'نشط',
        'student_registeration_start_at' => 'بداية تسجيل الطلاب',
        'student_registeration_end_at' => 'نهاية تسجيل الطلاب',
        // Instructor Fields
        'instructor' => 'المدرس',
        'instructor_code' => 'رمز المدرس',
        'instructor_name' => 'اسم المدرس',
        'first_name' => 'الاسم الأول',
        'last_name' => 'اسم العائلة',
        'full_name_arabic' => 'الاسم الكامل (بالعربية)',
        'email' => 'البريد الإلكتروني',
        'phone' => 'الهاتف',
        'title' => 'اللقب',
        // Course Fields
        'course' => 'المقرر',
        'course_code' => 'رمز المقرر',
        'course_name' => 'اسم المقرر',
        'credit_hours' => 'الساعات المعتمدة',
        'course_type' => 'نوع المقرر',
        'category' => 'الفئة',
        'prerequisites_count' => 'عدد المتطلبات السابقة',
        // Student Fields
        'student' => 'الطالب',
        'student_id' => 'الرقم الجامعي',
        'student_name' => 'اسم الطالب',
        'current_level' => 'المستوى الحالي',
        'academic_level' => 'المستوى الدراسي',
        'enrollment_date' => 'تاريخ التسجيل',
        'cgpa' => 'المعدل التراكمي',
        'credit_hours_completed' => 'الساعات المعتمدة المنجزة',
        'credit_hours_in_progress' => 'الساعات المعتمدة قيد الدراسة',
        'credit_hours_failed' => 'الساعات المعتمدة الراسبة',
        // Prerequisite Fields
        'prerequisite_course' => 'المتطلب السابق',
        'prerequisite_code' => 'رمز المتطلب',
        'prerequisite_name' => 'اسم المتطلب',
        // Program Requirement Fields
        'requirement_type' => 'نوع المتطلب',
        // Assignment Fields
        'section_number' => 'رقم الشعبة',
        'student_count' => 'عدد الطلاب',
        // Enrollment Fields
        'is_retake' => 'إعادة',
    ],

    // Helper Text
    'helper_text' => [
        'department_code' => 'أدخل رمزًا فريدًا للقسم (مثلاً: علم)',
        'department_prefix' => 'أدخل بادئة المقرر للقسم (مثلاً: عل)',
        'department_name' => 'أدخل الاسم الكامل للقسم',
        'department' => 'اختر القسم',
        'level_name' => 'أدخل اسم المستوى الدراسي',
        'level_number' => 'أدخل رقم تسلسل المستوى',
        'min_credit_hours' => 'الحد الأدنى من الساعات المعتمدة لهذا المستوى',
        'max_credit_hours' => 'الحد الأقصى من الساعات المعتمدة لهذا المستوى',
        'grade_letter' => 'حرف التقدير (أ، ب، ج، د، هـ، إلخ)',
        'min_percentage' => 'الحد الأدنى للنسبة المئوية لتحقيق هذه الدرجة',
        'max_percentage' => 'الحد الأقصى للنسبة المئوية لهذه الدرجة',
        'grade_points' => 'قيمة نقاط التقدير (0.0 - 4.0)',
        'status' => 'تفعيل أو تعطيل سلم التقديرات',
        'semester_code' => 'رمز فريد للفصل الدراسي (مثلاً: ص2024)',
        'semester_name' => 'الاسم الكامل للفصل الدراسي (مثلاً: الفصل الخريفي 2024)',
        'semester' => 'اختر الفصل الدراسي',
        'year' => 'السنة الأكاديمية',
        'start_date' => 'التاريخ الذي يبدأ فيه الفصل الدراسي',
        'end_date' => 'التاريخ الذي ينتهي فيه الفصل الدراسي',
        'is_active' => 'تفعيل هذا الفصل لعمليات الطلاب',
        'student_registeration_start_at' => 'التاريخ والوقت الذي يمكن للطلاب بدء التسجيل',
        'student_registeration_end_at' => 'التاريخ والوقت الذي ينتهي فيه تسجيل الطلاب',
        // Instructor Helper Text
        'instructor_code' => 'أدخل رمزًا فريدًا للمدرس',
        'instructor' => 'اختر المدرس',
        'first_name' => 'أدخل الاسم الأول للمدرس',
        'last_name' => 'أدخل اسم العائلة للمدرس',
        'full_name_arabic' => 'أدخل الاسم الكامل بالعربية',
        'email' => 'أدخل البريد الإلكتروني الصحيح',
        'phone' => 'أدخل رقم الهاتف',
        'instructor_title' => 'أدخل اللقب الأكاديمي للمدرس',
        'instructor_status' => 'اختر الحالة الحالية للمدرس',
        // Course Helper Text
        'course_code' => 'أدخل رمزًا فريدًا للمقرر (مثلاً: عل101)',
        'course_name' => 'أدخل الاسم الكامل للمقرر',
        'course' => 'اختر المقرر',
        'credit_hours' => 'عدد الساعات المعتمدة لهذا المقرر',
        'course_type' => 'اختر نوع المقرر',
        'course_category' => 'اختر فئة المقرر',
        // Student Helper Text
        'student_id' => 'أدخل رقمًا جامعيًا فريدًا للطالب',
        'student' => 'اختر الطالب',
        'current_level' => 'اختر المستوى الدراسي الحالي للطالب',
        'academic_level' => 'اختر المستوى الدراسي',
        'enrollment_date' => 'التاريخ الذي التحق فيه الطالب',
        'cgpa' => 'المعدل التراكمي (0.0 - 4.0)',
        'credit_hours_completed' => 'إجمالي الساعات المعتمدة المنجزة',
        'credit_hours_in_progress' => 'الساعات المعتمدة قيد الدراسة حاليًا',
        'credit_hours_failed' => 'الساعات المعتمدة الراسبة',
        'student_status' => 'اختر الحالة الحالية للطالب',
        // Prerequisite Helper Text
        'prerequisite_course' => 'اختر المقرر الرئيسي',
        'prerequisite_course_required' => 'اختر المقرر المتطلب السابق المطلوب',
        // Program Requirement Helper Text
        'requirement_type' => 'اختر ما إذا كان هذا المقرر إلزاميًا أو اختياريًا',
        // Assignment Helper Text
        'section_number' => 'أدخل رقم الشعبة',
        'student_count' => 'عدد الطلاب في هذه الشعبة',
        // Enrollment Helper Text
        'enrollment_status' => 'اختر حالة التسجيل',
        'is_retake' => 'حدد إذا كان هذا المقرر إعادة',
    ],

    // Navigation
    'navigation' => [
        'department' => 'الأقسام',
        'academic_level' => 'المستويات الدراسية',
        'grade_scale' => 'سلالم التقديرات',
        'semester' => 'الفصول الدراسية',
    ],

    // Navigation Groups
    'navigation_groups' => [
        'academic_structure' => 'الهيكل الأكاديمي',
        'people' => 'الأفراد',
        'course_management' => 'إدارة المقررات',
        'operations' => 'العمليات',
    ],

    // Page Titles
    'titles' => [
        'department' => 'الأقسام',
        'academic_level' => 'المستويات الدراسية',
        'grade_scale' => 'سلالم التقديرات',
        'semester' => 'الفصول الدراسية',
        'create_department' => 'إنشاء قسم',
        'create_academic_level' => 'إنشاء مستوى دراسي',
        'create_grade_scale' => 'إنشاء سلم تقديرات',
        'create_semester' => 'إنشاء فصل دراسي',
        'edit_department' => 'تحرير القسم',
        'edit_academic_level' => 'تحرير المستوى الدراسي',
        'edit_grade_scale' => 'تحرير سلم التقديرات',
        'edit_semester' => 'تحرير الفصل الدراسي',
        'view_department' => 'عرض القسم',
        'view_academic_level' => 'عرض المستوى الدراسي',
        'view_grade_scale' => 'عرض سلم التقديرات',
        'view_semester' => 'عرض الفصل الدراسي',
    ],

    // Buttons
    'buttons' => [
        'create' => 'إنشاء',
        'save' => 'حفظ',
        'delete' => 'حذف',
        'edit' => 'تحرير',
        'view' => 'عرض',
        'cancel' => 'إلغاء',
    ],

    // Confirmation
    'confirmation' => [
        'delete_title' => 'حذف',
        'delete_message' => 'هل تريد فعلا حذف هذا السجل؟',
    ],

    // Actions
    'actions' => [
        'create_department' => 'إنشاء قسم',
        'edit_department' => 'تحديث القسم',
        'delete_department' => 'حذف القسم',
        'view_department' => 'عرض القسم',
        'create_academic_level' => 'إنشاء مستوى دراسي',
        'edit_academic_level' => 'تحديث المستوى الدراسي',
        'delete_academic_level' => 'حذف المستوى الدراسي',
        'view_academic_level' => 'عرض المستوى الدراسي',
        'create_grade_scale' => 'إنشاء سلم تقديرات',
        'edit_grade_scale' => 'تحديث سلم التقديرات',
        'delete_grade_scale' => 'حذف سلم التقديرات',
        'view_grade_scale' => 'عرض سلم التقديرات',
        'create_semester' => 'إنشاء فصل دراسي',
        'edit_semester' => 'تحديث الفصل الدراسي',
        'delete_semester' => 'حذف الفصل الدراسي',
        'view_semester' => 'عرض الفصل الدراسي',
    ],

    // Messages
    'messages' => [
        'department_created' => 'تم إنشاء القسم بنجاح',
        'department_updated' => 'تم تحديث القسم بنجاح',
        'department_deleted' => 'تم حذف القسم بنجاح',
        'academic_level_created' => 'تم إنشاء المستوى الدراسي بنجاح',
        'academic_level_updated' => 'تم تحديث المستوى الدراسي بنجاح',
        'academic_level_deleted' => 'تم حذف المستوى الدراسي بنجاح',
        'grade_scale_created' => 'تم إنشاء سلم التقديرات بنجاح',
        'grade_scale_updated' => 'تم تحديث سلم التقديرات بنجاح',
        'grade_scale_deleted' => 'تم حذف سلم التقديرات بنجاح',
        'semester_created' => 'تم إنشاء الفصل الدراسي بنجاح',
        'semester_updated' => 'تم تحديث الفصل الدراسي بنجاح',
        'semester_deleted' => 'تم حذف الفصل الدراسي بنجاح',
    ],

    // Validation
    'validation' => [
        'department_code_unique' => 'رمز القسم هذا موجود بالفعل',
        'department_prefix_unique' => 'بادئة القسم هذه موجودة بالفعل',
        'grade_letter_unique' => 'حرف التقدير هذا موجود بالفعل',
        'semester_code_unique' => 'رمز الفصل الدراسي هذا موجود بالفعل',
    ],

    // Filters
    'filters' => [
        'active' => 'نشط',
        'inactive' => 'غير نشط',
    ],

    // Table Actions
    'table_actions' => [
        'edit' => 'تحرير',
        'view' => 'عرض',
        'delete' => 'حذف',
    ],

    // Status Values
    'status' => [
        'active' => 'نشط',
        'inactive' => 'غير نشط',
        'on_leave' => 'في إجازة',
        'graduated' => 'متخرج',
        'suspended' => 'موقوف',
    ],

    // Course Types
    'course_type' => [
        'theory' => 'نظري',
        'practical' => 'عملي',
        'mixed' => 'نظري وعملي',
        'M' => 'نظري وعملي',
        'E' => 'اختياري',
    ],

    // Course Categories
    'category' => [
        'university_requirement' => 'متطلب جامعة',
        'college_requirement' => 'متطلب كلية',
        'department_requirement' => 'متطلب قسم',
        'elective' => 'اختياري',
    ],

    // Requirement Types
    'requirement_type' => [
        'mandatory' => 'إلزامي',
        'elective' => 'اختياري',
    ],

    // Enrollment Status
    'enrollment_status' => [
        'registered' => 'مسجل',
        'withdrawn' => 'منسحب',
        'completed' => 'مكتمل',
    ],

    // Filter Labels
    'filter' => [
        'retake_only' => 'الإعادة فقط',
        'first_attempt' => 'المحاولة الأولى',
    ],

    // Bulk Import Messages
    'bulk_import' => [
        'title' => 'استيراد/تحديث الطلاب بكميات كبيرة',
        'description' => 'رفع ملف Excel لاستيراد أو تحديث بيانات الطلاب. الأعمدة المطلوبة: student_id، full_name_arabic، cgpa، earned_credit_hours، studied_credit_hours، actual_credit_hours، total_points',
        'submit_button' => 'بدء الاستيراد',
        'cancel_button' => 'إلغاء',
        'department_label' => 'القسم',
        'department_help' => 'حدد القسم للطلاب الجدد',
        'level_label' => 'المستوى الأكاديمي الحالي',
        'level_help' => 'اختياري: قم بتعيين المستوى الأكاديمي للطلاب الجدد',
        'file_label' => 'ملف Excel',
        'file_help' => 'الصيغ المدعومة: .xls، .xlsx',
        'started' => 'بدأ الاستيراد',
        'started_message' => 'يتم معالجة ملفك. سيتم إخطارك عند الانتهاء.',
        'completed' => 'تم استيراد الطلاب بنجاح',
        'completed_message' => 'تم الإنشاء: :created، تم التحديث: :updated',
        'errors_count' => 'الأخطاء: :count',
        'failed' => 'فشل استيراد الطلاب',
        'file_not_found' => 'لم يتم العثور على الملف. يرجى محاولة التحميل مرة أخرى.',
    ],

    // Grades Import Messages
    'grades_import' => [
        'title' => 'استيراد درجات الطلاب بكميات كبيرة',
        'description' => 'رفع ملف Excel لاستيراد أو تحديث درجات الطلاب في المقررات. الأعمدة المطلوبة: STUDENT_ID، STUDENT_NAME، YEAR، SEMESTER، COURSE_TITLE، MARKS، GRADE_PERCENT، GRADE_LETTER، POINTS، CREDIT_HOURS، ACT_CREDIT_HOURS، COMMENT',
        'submit_button' => 'بدء الاستيراد',
        'cancel_button' => 'إلغاء',
        'file_label' => 'ملف Excel',
        'file_help' => 'الصيغ المدعومة: .xls، .xlsx. عناوين المقررات يجب أن تكون بالصيغة: "ABC 123-اسم المقرر"',
        'started' => 'بدأ الاستيراد',
        'started_message' => 'يتم معالجة ملفك. سيتم إخطارك عند الانتهاء.',
        'completed' => 'تم استيراد الدرجات بنجاح',
        'completed_message' => 'التسجيلات المنشأة: :created، المحدثة: :updated، الدرجات المعالجة: :grades',
        'errors_count' => 'الأخطاء: :count',
        'failed' => 'فشل استيراد الدرجات',
        'file_not_found' => 'لم يتم العثور على الملف. يرجى محاولة التحميل مرة أخرى.',
    ],

];
