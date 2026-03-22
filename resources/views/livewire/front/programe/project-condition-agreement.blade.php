
<div class="agreement-wrapper">
<style>
.agreement-wrapper {
    background: #f5f5f5;
    direction: rtl;
    padding: 20px 0;
}

.page {
    padding: 30px 40px;
    background: #ffffff;
    max-width: 900px;
    margin: 0 auto;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    position: relative;
    font-family: inherit;
}

.header {
    text-align: center;
    margin-bottom: 5px;
}

.header img {
    width: 80px;
}

.date {
    font-size: 14px;
    margin-bottom: 10px;
    position: absolute;
    top: 50px;
}

h1 {
    text-align: center;
    font-size: 18px;
    text-decoration: underline;
    margin: 0px 0;
    color: #1f3864;
}

.section-title {
    font-weight: bold;
    text-decoration: underline;
    margin: 5px 0 5px;
    text-align: center;
    color:  #1f3864;
}

p {
    line-height: 1.4;
    font-size: 16px;
    margin: 0px;
}

.info {
    margin-top: 5px;
}

.info-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
}

.label {
    min-width: 150px;
    font-size: 17px;
    font-weight: 600;
}

.line {
    flex: 1;
    margin-right: 10px;
    font-weight: 600;
    text-align: center;
    font-family: "Manrope", sans-serif;
}

.list {
    margin-top: 10px;
    padding-right: 20px;
}

.list li {
    margin-bottom: 5px;
}

.checkbox {
    margin-top: 15px;
    text-align: center;
}

.checkbox span {
    margin-left: 20px;
}

.signature {
    margin-top: 15px;
    text-align: left;
    font-weight: 800;
    font-size: 17px;
}


</style>


    <div class="page">

        <div class="date">
            التاريخ : {{ now()->format('Y-m-d') }}
        </div>

    <div class="header">
        <img src="{{asset('assets/site/images/iuhm_logo.png')}}" alt="Logo IUHM ">
        <h1>التزام المستفيد(ة)</h1>
    </div>

    <p><strong>البرنامج : </strong> دعم ريادة الأعمال للشباب</p>
    <p><strong>الجمعية : </strong> المبادرة الحضرية</p>
    <p><strong>المكان : </strong> منصة الشباب حي المحمدي</p>
    <p><strong>البرنامج بدعم من : </strong> المبادرة الوطنية للتنمية البشرية</p>

    <div class="section-title">هدف الوثيقة</div>
    <p>
        تهدف هذه الوثيقة إلى توضيح التزام المستفيد بالمشاركة في أنشطة التكوين والمواكبة المحددة
        في إطار برنامج دعم ريادة الأعمال للشباب، الذي تشرف عليه جمعية المبادرة الحضرية،
        بدعم من المبادرة الوطنية للتنمية البشرية.
    </p>

    <div class="section-title">التزامات المستفيد</div>
    <p>أنا الموقع أدناه</p>
    <div class="info">
        <div class="info-row">
            <div class="label">الاسم الكامل : </div>
            <div class="line">{{$candidat->nom}} {{$candidat->prenom}} </div>
        </div>
        <div class="info-row">
            <div class="label">رقم البطاقة الوطنية : </div>
            <div class="line">{{$candidat->cin}}</div>
        </div>
        <div class="info-row">
            <div class="label">العنوان : </div>
            <div class="line">{{ $candidat->selected_region }} , {{ $candidat->selected_city }} , {{ $candidat->selected_prefecture }} , {{ $candidat->address_detail }}</div>
        </div>
        <div class="info-row">
            <div class="label">رقم الهاتف : </div>
            <div class="line">{{$candidat->phone}}</div>
        </div>
        <div class="info-row">
            <div class="label">تاريخ الازدياد : </div>
            <div class="line">{{$candidat->date_naissance->format('d/m/Y')}}</div>
        </div>
    </div>
    <p>تعّهد بما يلي</p>
    <ul class="list">
        <li>المشاركة الفعالة في جميع جلسات التكوين والمواكبة المحددة في إطار البرنامج</li>
        <li>احترام الجدول الزمني الذي وضعته الجمعية، مع ضمان الحضور والانضباط</li>
        <li>توثيق الساعات التدريبية من خلال التوقيع على ورقة الحضور</li>
        <li>تطبيق المعارف المكتسبة لتطوير مشروعه الريادي</li>
        <li>إبلاغ الجمعية في حالة وجود عائق أو صعوبة</li>
    </ul>

    <div class="section-title">التزامات جمعية المبادرة الحضرية</div>

    <ul class="list">
        <li>ضمان مواكبة المستفيدين قبل وبعد إنشاء مشاريعهم</li>
        <li>تقديم تكوينات تهدف إلى تعزيز القدرات الريادية للمشاركين</li>
        <li>توفير بيئة مالئمة للمواكبة والتوجيه <strong>دون االلتزام بتقديم موارد أو تمويل</strong></li>
    </ul>

    <div class="section-title">تفويض التقاط الصور واستخدامها</div>

    <p>
        من خلال توقيعي على هذه الوثيقة، أوافق على السماح للجمعية باستخدام صوري
        أو تسجيلات فيديو لأغراض غير تجارية.
    </p>

    <div class="checkbox">
        <label style="cursor: pointer;font-size: 15px; color: grey;">
            <input type="checkbox" wire:model="acceptConditions"> 
            أوافق على جميع الشروط المذكورة أعلاه (J'accepte toutes les conditions ci-dessus)
        </label>
        @error('acceptConditions')
            <div style="color: red; font-size: 13px; margin-top: 5px;">{{ $message }}</div>
        @enderror
    </div>

    <div style="margin-top: 20px;">
        <label style="font-weight: bold; display: block; margin-bottom: 5px;">Idée du projet (فكرة المشروع) *</label>
        <textarea wire:model="projectIdea" rows="4" style="width: 100%; border: 1px solid #ccc; border-radius: 5px; padding: 10px;" placeholder="Décrivez brièvement votre idée de projet / صف بإيجاز فكرة مشروعك"></textarea>
        @error('projectIdea')
            <div style="color: red; font-size: 13px; margin-top: 5px;">{{ $message }}</div>
        @enderror
    </div>

    <div style="margin-top: 20px;">
        <label style="font-weight: bold; display: block; margin-bottom: 5px;">Comment avez-vous connu ce projet / organisme ? (كيف عرفت بهذا المشروع / المؤسسة ؟) *</label>
        <textarea wire:model="howKnew" rows="2" style="width: 100%; border: 1px solid #ccc; border-radius: 5px; padding: 10px;" placeholder="Réseaux sociaux, amis... / شبكات التواصل الاجتماعي، الأصدقاء..."></textarea>
        @error('howKnew')
            <div style="color: red; font-size: 13px; margin-top: 5px;">{{ $message }}</div>
        @enderror
    </div>

    <div style="margin-top: 30px; display: flex; justify-content: space-between; gap: 10px;">
        <a href="{{ route('user.project.detail', $projectId) }}" style="padding: 10px 20px; text-decoration: none; color: #333; border: 1px solid #ccc; border-radius: 5px; background: #fff;">Retour (رجوع)</a>
        <button wire:click="agreeAndContinue" style="padding: 10px 20px; cursor: pointer; color: #fff; background: #28a745; border: none; border-radius: 5px; font-weight: bold;">
            Accepter et continuer (أوافق وأتابع)
        </button>
    </div>

    <div class="footer-logos" style="margin-top: 40px; text-align: center;">
        @if($project->logo1)
            <img src="{{ asset('uploads/' . $project->logo1) }}" alt="Logo 1" style="display: inline-block; max-height: 50px; margin: 0 10px;">
        @endif
        @if($project->logo2)
            <img src="{{ asset('uploads/' . $project->logo2) }}" alt="Logo 2" style="display: inline-block; max-height: 50px; margin: 0 10px;">
        @endif
        @if($project->logo3)
            <img src="{{ asset('uploads/' . $project->logo3) }}" alt="Logo 3" style="display: inline-block; max-height: 50px; margin: 0 10px;">
        @endif
    </div>
</div>
</div>
