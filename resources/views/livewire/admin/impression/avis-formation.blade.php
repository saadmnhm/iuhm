<style>
    body {
        margin: 0;
        direction: rtl;
        font-family: Arial, sans-serif;
    }

    .page {
        padding: 10px 40px;
    }

    .header-table {
        width: 100%;
        margin-bottom: 20px;
    }

    .header-table td {
        vertical-align: middle;
    }

    .header-logo {
        width: 80px;
    }

    .header-title {
        text-align: center;
    }

    .header-title h1 {
        font-size: 18px;
        margin: 0;
        color: #1f3864;
    }

    .header-title p {
        font-size: 14px;
        margin: 5px 0 0;
        color: #555;
    }

    .info-section {
        margin-bottom: 20px;
    }

    .info-table {
        width: 100%;
        border-collapse: collapse;
    }

    .info-table td {
        padding: 5px 0;
        font-size: 14px;
    }

    .info-label {
        font-weight: bold;
        width: 120px;
    }

    .review-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .review-table th, .review-table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: right;
        font-size: 14px;
    }

    .review-table th {
        background-color: #f5f5f5;
        font-weight: bold;
    }

    .review-table .center {
        text-align: center;
    }

    .review-table .th-center {
        text-align: center;
        width: 100px;
    }

    .feedback-section {
        margin-top: 20px;
    }

    .feedback-title {
        font-weight: bold;
        font-size: 15px;
        margin-bottom: 10px;
    }

    .feedback-content {
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 5px;
        font-size: 14px;
        min-height: 80px;
    }

    .rating-section {
        margin-top: 20px;
        font-size: 15px;
        font-weight: bold;
    }
</style>

<div class="page">
    <table class="header-table">
        <tr>
            <td style="width: 20%;">
                <img src="{{ asset('assets/site/images/iuhm_logo.png') }}" alt="Logo IUHM" class="header-logo">
            </td>
            <td style="width: 60%;" class="header-title">
                <h1>استمارة تقييم التكوين</h1>
                <p>{{ $project->project_name ?? $project->name }}</p>
            </td>
            <td style="width: 20%;"></td>
        </tr>
    </table>

    <div class="info-section">
        <table class="info-table">
            <tr>
                <td class="info-label">الاسم الكامل:</td>
                <td>{{ $candidat->nom }} {{ $candidat->prenom }}</td>
                <td class="info-label">رقم البطاقة الوطنية:</td>
                <td>{{ $candidat->cin }}</td>
            </tr>
            <tr>
                <td class="info-label">تاريخ التقييم:</td>
                <td colspan="3">{{ $submission->updated_at ? $submission->updated_at->format('Y-m-d') : now()->format('Y-m-d') }}</td>
            </tr>
        </table>
    </div>

    @php
        $answers = is_array($submission->formation_review_answers) ? $submission->formation_review_answers : json_decode($submission->formation_review_answers ?? '{}', true) ?? [];

        $getAnswerText = function($qKey) use ($answers) {
            $val = $answers[$qKey] ?? null;
            if ($val == 1) return 'غير راضٍ';
            if ($val == 2) return 'راضٍ';
            if ($val == 3) return 'راضٍ جداً';
            return '-';
        };
    @endphp

    <table class="review-table">
        <thead>
            <tr>
                <th>عناصر التقييم</th>
                <th class="th-center">التقييم</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th colspan="2" style="background-color: #e9ecef;">محتوى التكوين</th>
            </tr>
            <tr>
                <td>كان محتوى التكوين واضحاً ومُنظْماً بشكل جيد</td>
                <td class="center">{{ $getAnswerText('q1') }}</td>
            </tr>
            <tr>
                <td>كانت المواضيع المطروحة ذات صلة ومفيدة لمشروعي الريادي</td>
                <td class="center">{{ $getAnswerText('q2') }}</td>
            </tr>

            <tr>
                <th colspan="2" style="background-color: #e9ecef;">المكون / المكونة</th>
            </tr>
            <tr>
                <td>كان المكون ملماً جيدا بالمواضيع التي تم تناولها</td>
                <td class="center">{{ $getAnswerText('q3') }}</td>
            </tr>
            <tr>
                <td>كانت الشروحات والأمثلة المقدمة واضحة ومناسبة</td>
                <td class="center">{{ $getAnswerText('q4') }}</td>
            </tr>

            <tr>
                <th colspan="2" style="background-color: #e9ecef;">التنظيم</th>
            </tr>
            <tr>
                <td>كان التنظيم العام للتكوين مرضيا</td>
                <td class="center">{{ $getAnswerText('q5') }}</td>
            </tr>
            <tr>
                <td>كانت وسائل التكوين مفيدة وذات صلة</td>
                <td class="center">{{ $getAnswerText('q6') }}</td>
            </tr>

            <tr>
                <th colspan="2" style="background-color: #e9ecef;">النتائج</th>
            </tr>
            <tr>
                <td>لبى هذا التكوين توقعاتي</td>
                <td class="center">{{ $getAnswerText('q7') }}</td>
            </tr>
            <tr>
                <td>أشعر بثقة أكبر لتطوير أو إدارة مشروعي الريادي</td>
                <td class="center">{{ $getAnswerText('q8') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="rating-section">
        التقييم العام للتكوين:
        <span style="font-size: 18px; color: #1f3864;">{{ $submission->formation_review_rating ?: '-' }} / 5</span>
    </div>

    <div class="feedback-section">
        <div class="feedback-title">التعليقات أو الاقتراحات:</div>
        <div class="feedback-content">
            {{ $submission->formation_review_feedback ?: 'لا يوجد تعليق.' }}
        </div>
    </div>
</div>
