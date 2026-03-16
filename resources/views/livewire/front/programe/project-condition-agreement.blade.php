<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            @if (session()->has('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="ri-shield-check-line me-2 text-success"></i>
                        Conditions avant soumission
                    </h5>
                    <small class="text-muted">Projet: {{ $project?->project_name }}</small>
                </div>

                <div class="card-body p-4">
                    <div class="alert alert-warning mb-4">
                        <strong>مهم:</strong> يجب عليك قراءة الشروط التالية والموافقة عليها قبل البدء في تعبئة الاستمارات.
                    </div>

                    <div class="border rounded-3 p-3" dir="rtl" style="background:#fafafa; line-height: 1.95;">
                        <h6 class="fw-bold">شروط المشاركة</h6>
                        <ul class="mb-0 ps-3">
                            <li>أقر أن جميع المعلومات التي سأقدمها صحيحة وكاملة.</li>
                            <li>أتعهد باحترام مراحل التقديم وعدم إرسال معلومات مضللة.</li>
                            <li>أفهم أن أي تصريح غير صحيح قد يؤدي إلى رفض طلبي.</li>
                            <li>أوافق على معالجة معطياتي في إطار دراسة طلبي فقط.</li>
                        </ul>
                    </div>

                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" id="acceptConditions" wire:model="acceptConditions">
                        <label class="form-check-label" for="acceptConditions">
                            J'ai lu et j'accepte les conditions ci-dessus.
                        </label>
                    </div>
                    @error('acceptConditions')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror

                    <div class="mt-4">
                        <label class="form-label fw-semibold">Idée du projet *</label>
                        <textarea wire:model="projectIdea" rows="4" class="form-control" placeholder="Décrivez brièvement votre idée de projet..."></textarea>
                        @error('projectIdea')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <a href="{{ route('user.project.detail', $projectId) }}" class="btn btn-light border">Retour</a>
                        <button wire:click="agreeAndContinue" class="btn btn-success">
                            <i class="ri-check-line me-1"></i> Accepter et continuer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
