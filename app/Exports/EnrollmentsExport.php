<?php

namespace App\Exports;

use App\Models\Enrollment;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EnrollmentsExport implements FromQuery, WithHeadings, WithMapping, WithTitle, WithStyles
{
    protected int $semesterId;

    public function __construct(int $semesterId)
    {
        $this->semesterId = $semesterId;
    }

    public function query()
    {
        return Enrollment::query()
            ->where('semester_id', $this->semesterId)
            ->with(['student.department', 'student.academicLevel', 'course', 'semester'])
            ->orderBy('student_id')
            ->orderBy('course_id');
    }

    public function headings(): array
    {
        return [
            __('filament.field.student_id'),
            __('filament.field.student_name'),
            __('filament.field.department'),
            __('filament.field.current_level'),
            __('filament.field.course_code'),
            __('filament.field.course_name'),
            __('filament.field.credit_hours'),
            __('filament.field.enrollment_date'),
            __('filament.field.status'),
            __('filament.field.is_retake'),
            __('filament.field.grade_points'),
            __('filament.field.comment'),
        ];
    }

    public function map($enrollment): array
    {
        return [
            $enrollment->student_id,
            $enrollment->student->full_name_arabic ?? 'N/A',
            $enrollment->student->department->department_name ?? 'N/A',
            $enrollment->student->academicLevel->level_name ?? 'N/A',
            $enrollment->course->course_code ?? 'N/A',
            $enrollment->course->course_name ?? 'N/A',
            $enrollment->course->credit_hours ?? 0,
            $enrollment->enrollment_date?->format('Y-m-d') ?? '',
            $enrollment->status ?? 'N/A',
            $enrollment->is_retake ? __('filament.common.yes') : __('filament.common.no'),
            $enrollment->grade_points ?? 0,
            $enrollment->comment ?? '',
        ];
    }

    public function title(): string
    {
        return __('filament.exports.enrollments_sheet_title');
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
