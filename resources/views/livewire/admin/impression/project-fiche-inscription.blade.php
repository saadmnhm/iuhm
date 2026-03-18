<style>
 *{

    --label-color: #679bcc;
  
 }

  /* ── HEADER ── */

  .logo-block img{
    width: 125px;
    position: relative;
    left: 97%;
  }
  .title-block {
    margin: 10px 0;
    display: flex;
    justify-content: center;
  }

  .title-box {
    padding: 3px 5px;
    border: 1px solid #679bcc;
  }

  .title-box h1 {
    font-size: 15px;
    letter-spacing: 2px;
    text-transform: uppercase;
    font-weight: 500;
    margin: 5px 0;
    color: var(--label-color);
  }

  .photo-slot{
    width: 110px;
    height: 111px;
    border-radius: 76px;
    overflow: hidden;
    margin-right: 50px;

  }


  /* ── FORM FIELDS ── */
  .form-body {
    display: flex;
    flex-direction: column;
    gap: 0;
    padding: 15px 85px;
  }

  .field-row {
    display: flex;
    align-items: stretch;
    min-height: 42px;
  }

  .field-row:last-child { border-bottom: none; }

  .label-fr {
    min-width: 160px;
    padding: 10px 0 8px;
    font-size: 13.5px;
    font-weight: 600;
    align-content: center;
    color: var(--label-color);
  }
  .content{
    padding: 10px 0px;
  }

  .field-input {
    flex: 1;
    margin: 19px 10px;
    font-size: 14px;
    text-align: center;
    font-weight: 600;
    color: #33396f;
  }
  .label-ar {
    min-width: 140px;
    padding: 10px 0 8px;
    font-size: 15px;
    color: var(--label-color);
    text-align: right;
    direction: rtl;
    font-weight: 600;
    align-content: center;
  }


</style>

<div class="content">
  
  <table width="100%" class="header-table">
    <tr>

      <td>
        <div class="logo-block">
            <img src="{{asset('assets/site/images/iuhm_logo.png')}}" alt="Logo IUHM ">
        </div>
      </td>

      <td style="text-align: right;">
        <img src="{{ asset('uploads/' . $candidat->profile_image) }}" alt="Photo" class="photo-slot"/>
      </td>
      
    </tr>
  </table>

  <div class="title-block">
    <div class="title-box">
      <h1>Fiche de Renseignement</h1>
    </div>
  </div>
  <!-- FORM FIELDS -->
  <div class="form-body">

    <div class="field-row">
      <div class="label-fr">Nom <span class="colon">:</span></div>
      <p class="field-input">{{ $candidat->nom }}</p>
      <div class="label-ar"> النسب :</div>
    </div>

    <div class="field-row">
      <div class="label-fr">Prénom <span class="colon">:</span></div>
      <p class="field-input">{{ $candidat->prenom }}</p>
      <div class="label-ar"> الاسم :</div>
    </div>

    <div class="field-row">
      <div class="label-fr">CIN <span class="colon">:</span></div>
      <p class="field-input">{{ $candidat->cin }}</p>
      <div class="label-ar"> رقم البطاقة الوطنية :</div>
    </div>

    <div class="field-row">
      <div class="label-fr">Sexe <span class="colon">:</span></div>
      <p class="field-input">{{ $candidat->gender }}</p>
      <div class="label-ar"> الجنس :</div>
    </div>

    <div class="field-row">
      <div class="label-fr">Date de naissance <span class="colon">:</span></div>
      <p class="field-input">{{ $candidat->date_naissance->format('d/m/Y') }}</p>
      <div class="label-ar"> تاريخ الازدياد :</div>
    </div>

    <div class="field-row">
      <div class="label-fr">Téléphone <span class="colon">:</span></div>
      <p class="field-input">{{ $candidat->phone }}</p>
      <div class="label-ar"> الهاتف :</div>
    </div>

    <div class="field-row">
      <div class="label-fr">Adresse <span class="colon">:</span></div>
      <p class="field-input">{{ $candidat->selected_region }} , {{ $candidat->selected_city }} , {{ $candidat->selected_prefecture }} , {{ $candidat->address_detail }}</p>
      <div class="label-ar"> العنوان :</div>
    </div>

    <div class="field-row">
      <div class="label-fr">Niveau d'étude <span class="colon">:</span></div>
      <p class="field-input">{{ $candidat->niveau_etude }}</p>
      <div class="label-ar">المستوى الدراسي :</div>
    </div>

    <div class="field-row">
      <div class="label-fr">Spécialité <span class="colon">:</span></div>
      <p class="field-input">{{ $candidat->specialite }}</p>
      <div class="label-ar"> التخصص :</div>
    </div>

    <div class="section">
            <div class="label-fr">Comment vous avez su l'initiative urbaine et ce programme ?<span class="colon">:</span></div>
            <div class="label-ar">كيف تعرفت على بالمبادرة الحضرية وهذا البرنامج؟ :</div>
        <p class="field-input">{{ $agreement->how_knew ?? 'Non spÃ©cifiÃ©.' }}</p>
    </div>

    <div class="section">
        <div class="field-row" style="justify-content: space-between;">
                <div class="label-fr">IDEE DE PROJET <span class="colon">:</span></div>
                <div class="label-ar"> فكرة المشروع :</div>
        </div>
        <p class="field-input" style="margin: 0;">{{ $agreement->project_idea ?? 'Aucune idÃ©e de projet renseignÃ©e.' }}</p>
    </div>

  </div>


    <div class="footer-logos">
        @if($project->logo1)
            <img src="{{ asset('uploads/' . $project->logo1) }}" alt="Logo 1">
        @endif
        @if($project->logo2)
            <img src="{{ asset('uploads/' . $project->logo2) }}" alt="Logo 2">
        @endif
        @if($project->logo3)
            <img src="{{ asset('uploads/' . $project->logo3) }}" alt="Logo 3">
        @endif
    </div>
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


