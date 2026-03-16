  <style>
    :root {
      --cream: #faf8f4;
      --ink: #1a1a1a;
      --rust: #b84c2e;
      --gold: #c9a84c;
      --light-rule: #d4cfc6;
      --field-bg: #f5f2ec;
      --label-color: #5a5040;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      background: #e8e4dc;
      font-family: 'EB Garamond', Georgia, serif;
      color: var(--ink);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      padding: 40px 20px;
    }

    /* ── HEADER ── */

    .logo-block {
      width: 155px;
    }

    .logo-block img{
      width: 100%;
    }

    .logo-ring {
      width: 72px; height: 72px;
      border: 2px solid var(--rust);
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      background: white;
      overflow: hidden;
    }

    .logo-ring svg { width: 52px; height: 52px; }

    .assoc-name {
      text-align: center;
      font-size: 10px;
      letter-spacing: 0.15em;
      text-transform: uppercase;
      color: var(--label-color);
      line-height: 1.5;
    }

    .arabic-assoc {
      font-family: 'Cairo', sans-serif;
      font-size: 11px;
      color: var(--rust);
    }

    .title-block {
      flex: 2;
      text-align: center;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    .title-box {
      border: 1.5px solid var(--ink);
      padding: 10px 28px;
      display: inline-block;
      position: relative;
    }

    .title-box h1 {
      font-size: 17px;
      letter-spacing: 0.2em;
      text-transform: uppercase;
      font-weight: 600;
      color: var(--ink);
      white-space: nowrap;
    }

    .photo-slot {
      width: 110px;
      height: 111px;
      border-radius: 76px;
      display: flex;
      overflow: hidden;
    }

    .photo-slot input[type="file"] {
      position: absolute; inset: 0; opacity: 0; cursor: pointer;
    }

    .photo-slot img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    /* ── DATE BAR ── */
    .date-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
      padding-bottom: 10px;
      border-bottom: 1px solid var(--light-rule);
    }

    .date-bar .ar { font-family: 'Cairo', sans-serif; font-size: 13px; color: var(--label-color); }
    .date-bar .fr { font-size: 13px; color: var(--label-color); }

    .date-line {
      flex: 1;
      border-bottom: 1px dotted var(--ink);
      margin: 0 12px;
      height: 1px;
      align-self: flex-end;
      margin-bottom: 2px;
    }

    /* ── FORM FIELDS ── */
    .form-body { display: flex; flex-direction: column; gap: 0; }

    .field-row {
      display: flex;
      align-items: stretch;
      border-bottom: 1px solid var(--light-rule);
      min-height: 42px;
    }

    .field-row:last-child { border-bottom: none; }

    .label-fr {
      min-width: 160px;
      padding: 10px 0 8px;
      font-size: 13.5px;
      color: var(--ink);
    }

    .label-fr span.colon { color: var(--rust); }

    .field-input {
      flex: 1;
      border-bottom: 1px dotted var(--gold);
      margin: 10px 12px 8px;
      outline: none;
      background: transparent;
      font-family: 'EB Garamond', serif;
      font-size: 14px;
      color: var(--ink);
      align-self: flex-end;
      height: 22px;
      text-align: center;
    }

    .field-input:focus {
      border-bottom-color: var(--rust);
    }

    .label-ar {
      min-width: 140px;
      padding: 10px 0 8px;
      font-family: 'Cairo', sans-serif;
      font-size: 13px;
      color: var(--label-color);
      text-align: right;
      direction: rtl;
    }

    /* ── SEXE ROW ── */
    .sexe-row {
      display: flex;
      align-items: center;
      border-bottom: 1px solid var(--light-rule);
      min-height: 44px;
      gap: 0;
    }

    .sexe-row .label-fr { min-width: 160px; padding: 10px 0; }

    .sexe-options {
      flex: 1;
      display: flex;
      align-items: center;
      gap: 32px;
      padding: 0 12px;
    }

    .radio-opt {
      display: flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      font-size: 13.5px;
    }

    .radio-opt input { display: none; }

    .radio-box {
      width: 16px; height: 16px;
      border: 1.5px solid var(--ink);
      background: var(--field-bg);
      display: flex; align-items: center; justify-content: center;
      transition: background 0.2s;
    }

    .radio-opt input:checked + .radio-box {
      background: var(--rust);
      border-color: var(--rust);
    }

    .radio-opt input:checked + .radio-box::after {
      content: '';
      width: 6px; height: 6px;
      background: white;
      display: block;
    }

    .radio-ar { font-family: 'Cairo', sans-serif; font-size: 12px; color: var(--label-color); }
    .sexe-row .label-ar { min-width: 140px; }

    /* ── ADDRESS (tall) ── */
    .field-row.tall { min-height: 64px; }
    .field-row.tall .field-input {
      height: auto;
      align-self: stretch;
      margin-top: 8px;
      padding-bottom: 4px;
      resize: none;
    }

    /* ── PROJET SECTION ── */
    .section-title {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 24px;
      margin-bottom: 6px;
      padding-bottom: 4px;
      border-bottom: 1.5px solid var(--rust);
    }

    .section-title .fr {
      font-size: 14px;
      font-style: italic;
      color: var(--ink);
    }

    .section-title .ar {
      font-family: 'Cairo', sans-serif;
      font-size: 14px;
      color: var(--rust);
      direction: rtl;
    }

    .textarea-field {
      width: 100%;
      min-height: 80px;
      border: none;
      border-bottom: 1px dotted var(--gold);
      background: transparent;
      font-family: 'EB Garamond', serif;
      font-size: 14px;
      color: var(--ink);
      resize: vertical;
      padding: 6px 0;
      outline: none;
      line-height: 2;
      background-image: repeating-linear-gradient(
        transparent,
        transparent 27px,
        var(--light-rule) 27px,
        var(--light-rule) 28px
      );
    }

    .textarea-field:focus { border-bottom-color: var(--rust); }

    /* ── INDH QUESTION ── */
    .question-block {
      margin-top: 24px;
      border-top: 1px solid var(--light-rule);
      padding-top: 16px;
    }

    .question-block p {
      font-size: 13.5px;
      color: var(--ink);
      margin-bottom: 4px;
    }

    .question-block p.ar {
      font-family: 'Cairo', sans-serif;
      font-size: 13px;
      color: var(--label-color);
      text-align: right;
      direction: rtl;
    }

    /* ── FOOTER LOGOS ── */
    .footer-logos {
      margin-top: 36px;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 32px;
      padding-top: 16px;
      border-top: 1px solid var(--light-rule);
      opacity: 0.7;
    }

    .footer-logos span {
      font-size: 10px;
      letter-spacing: 0.08em;
      color: var(--label-color);
      text-transform: uppercase;
      text-align: center;
      font-style: italic;
    }

    @media print {
      body { background: white; padding: 0; }
      .page { box-shadow: none; width: 100%; }
    }
  </style>

  <table>
    <tr>

      <td>
        <div class="logo-block">
          <img src="{{asset('assets/site/images/iuhm_logo.png')}}" alt="Logo IUHM ">
        </div>
      </td>

      <td>
      <div class="photo-slot" title="Cliquer pour ajouter une photo">
        <img src="{{ asset('uploads/' . $candidat->profile_image) }}" alt="Photo"/>
      </div>
      </td>
      
    </tr>
    <tr>
      <td>
        <div class="title-block">
          <div class="title-box">
            <h1>Fiche de Renseignement</h1>
          </div>
        </div>
      </td>
    </tr>
  </table>


  <!-- DATE -->
  <div class="date-bar">
    <span class="ar">التاريخ</span>
    <div class="date-line"></div>
    <span class="fr">Date</span>
    <input class="field-input" style="max-width:180px; margin-right:0;" type="date"/>
  </div>



  <!-- FORM FIELDS -->
  <div class="form-body">

    <div class="field-row">
      <div class="label-fr">Nom <span class="colon">:</span></div>
      <p class="field-input">{{ $candidat->nom }}</p>
      <div class="label-ar">: النسب</div>
    </div>

    <div class="field-row">
      <div class="label-fr">Prénom <span class="colon">:</span></div>
      <p class="field-input">{{ $candidat->prenom }}</p>
      <div class="label-ar">: الاسم</div>
    </div>

    <div class="field-row">
      <div class="label-fr">CIN <span class="colon">:</span></div>
      <p class="field-input">{{ $candidat->cin }}</p>
      <div class="label-ar">: رقم البطاقة الوطنية</div>
    </div>

    <!-- SEXE -->
    <div class="sexe-row">
      <div class="label-fr">Sexe <span class="colon">:</span></div>
      <p class="field-input">{{ $candidat->gender }}</p>
      <div class="label-ar">: الجنس</div>
    </div>

    <div class="field-row">
      <div class="label-fr">Date de naissance <span class="colon">:</span></div>
      <p class="field-input">{{ $candidat->date_naissance->format('d/m/Y') }}</p>
      <div class="label-ar">: تاريخ الازدياد</div>
    </div>

    <div class="field-row">
      <div class="label-fr">Téléphone <span class="colon">:</span></div>
      <p class="field-input">{{ $candidat->phone }}</p>
      <div class="label-ar">: الهاتف</div>
    </div>

    <div class="field-row tall">
      <div class="label-fr">Adresse <span class="colon">:</span></div>
      <textarea class="field-input" rows="2">{{ $candidat->address_details }}</textarea>
      <div class="label-ar">: العنوان</div>
    </div>

    <div class="field-row">
      <div class="label-fr">Niveau d'étude <span class="colon">:</span></div>
      <p class="field-input">{{ $candidat->niveau_etude }}</p>
      <div class="label-ar">المستوى الدراسي</div>
    </div>

    <div class="field-row">
      <div class="label-fr">Spécialité <span class="colon">:</span></div>
      <p class="field-input">{{ $candidat->specialite }}</p>
      <div class="label-ar">: التخصص</div>
    </div>
  </div>

  <!-- PROJET -->
  <div class="section-title">
    <span class="fr">L'idée du projet</span>
    <span class="ar">فكرة المشروع</span>
  </div>
  <textarea class="textarea-field" rows="4" placeholder="Décrivez votre idée de projet..."></textarea>

  <!-- INDH QUESTION -->
  <div class="question-block">
    <p>Comment vous avez su l'initiative urbaine et programme de l'INDH?</p>
    <p class="ar">كيف تعرفت على جمعية المبادرة الحضرية وبرنامج المبادرة الوطنية لتنمية البشرية</p>
    <textarea class="textarea-field" rows="2" style="margin-top:8px;"></textarea>
  </div>

  <!-- FOOTER -->
  <div class="footer-logos">
    <span>Plateforme des Jeunes<br/>aux Ressources</span>
    <span>المبادرة الوطنية<br/>للتنمية البشرية</span>
    <span>Zettat<br/>Espace de proximité<br/>pour l'emploi</span>
  </div>


<script>
  function previewPhoto(event, input) {
    const file = event.target.files[0];
    if (!file) return;
    const img = document.getElementById('photo-preview');
    img.src = URL.createObjectURL(file);
    img.style.display = 'block';
    input.previousElementSibling.style.display = 'none';
  }
</script>