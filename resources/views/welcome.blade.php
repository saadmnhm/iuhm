<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">

<style>


    .btn_{
        height: 100vh;
        text-align: center;
        align-content: center;
    }
    .btn{
        border: 1px solid #648454;
        background: #f2ffed;
        color: #648454;
        padding: 15px 35px;
        border-radius: 37Px;
    }
    .btn:hover{
        background: #648454;
        color: #f2ffed;
        transition: all 0.3s ease-in-out;
        border: 1px solid #f2ffed;
    }
</style>

<div class="btn_">
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
  Launch static backdrop modal
</button>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="height: 300px; border-radius:20px;">
      <div class="modal-header " style="border-bottom:none;">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center align-content-center">
        <a href="{{ route('form.dashboard') }}" target="_blank" class="btn btn-primary">Espace Candidat</a>
        <a href="{{ route('admin.login') }}"  target="_blank" class="btn btn-primary">Espace Admin</a>
      </div>
      <p>hna aykon sign in o sign up</p>
    </div>
  </div>
</div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>