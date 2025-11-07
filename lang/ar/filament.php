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
    ],

    // Descriptions
    'descriptions' => [
        'department_info' => 'أدخل تفاصيل القسم لإنشاء أو تحديث قسم',
        'academic_level_info' => 'حدد المستويات الدراسية ومتطلبات الساعات المعتمدة',
        'grade_scale_info' => 'قم بإعداد سلالم التقديرات مع الدرجات والنقاط',
        'semester_info' => 'أنشئ وأدر الفصول الدراسية',
        'student_registration_info' => 'حدد فترة تسجيل الطلاب وحالة الفصل الدراسي',
    ],

    // Field Labels
    'field' => [
        'department_code' => 'رمز القسم',
        'department_prefix' => 'بادئة القسم',
        'department_name' => 'اسم القسم',
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
        'year' => 'السنة',
        'start_date' => 'تاريخ البداية',
        'end_date' => 'تاريخ النهاية',
        'enrollments_count' => 'عدد التسجيلات',
        'assignments_count' => 'عدد التعيينات',
        'is_active' => 'نشط',
        'student_registeration_start_at' => 'بداية تسجيل الطلاب',
        'student_registeration_end_at' => 'نهاية تسجيل الطلاب',
    ],

    // Helper Text
    'helper_text' => [
        'department_code' => 'أدخل رمزًا فريدًا للقسم (مثلاً: علم)',
        'department_prefix' => 'أدخل بادئة المقرر للقسم (مثلاً: عل)',
        'department_name' => 'أدخل الاسم الكامل للقسم',
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
        'year' => 'السنة الأكاديمية',
        'start_date' => 'التاريخ الذي يبدأ فيه الفصل الدراسي',
        'end_date' => 'التاريخ الذي ينتهي فيه الفصل الدراسي',
        'is_active' => 'تفعيل هذا الفصل لعمليات الطلاب',
        'student_registeration_start_at' => 'التاريخ والوقت الذي يمكن للطلاب بدء التسجيل',
        'student_registeration_end_at' => 'التاريخ والوقت الذي ينتهي فيه تسجيل الطلاب',
    ],

    // Navigation
    'navigation' => [
        'department' => 'الأقسام',
        'academic_level' => 'المستويات الدراسية',
        'grade_scale' => 'سلالم التقديرات',
        'semester' => 'الفصول الدراسية',
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

];
