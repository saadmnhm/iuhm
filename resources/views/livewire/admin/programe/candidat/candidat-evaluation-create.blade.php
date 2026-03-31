<div>
<style>


.container {
	width: 900px;
	margin: auto;
	background: #fff;
	border: 1px solid #dbe2ea;
	border-radius: 12px;
	padding: 20px;
	box-shadow: 0 8px 24px rgba(15, 61, 102, 0.08);
}

.header {
	text-align: center;
	margin-bottom: 20px;
	border-bottom: 2px solid #c5d9eb;
	padding-bottom: 10px;
}

h2 {
	font-size: 20px;
	font-weight: bold;
}

.section {
	margin-top: 20px;
}

h3 {
	font-size: 16px;
	margin-bottom: 10px;
}

.form-line {
	display: flex;
	margin-bottom: 15px;
	align-items: center;
}

.form-line label {
	min-width: 230px;
}

.line-value {
	flex: 1;
	border-bottom: 1px dotted black;
	min-height: 24px;
	display: flex;
	align-items: center;
	padding: 0 4px;
	font-weight: 600;
}

.line-input {
	flex: 1;
	border: none;
	border-bottom: 1px dotted black;
	min-height: 24px;
	padding: 0 4px;
	font-size: 14px;
	background: transparent;
}

.line-input:focus {
	outline: none;
	border-bottom: 1px solid #000;
}

table {
	width: 100%;
	border-collapse: collapse;
	margin-top: 10px;
}

table th,
table td {
	border: 1px solid black;
	padding: 8px;
	vertical-align: top;
	font-size: 14px;
}

table th {
	background: #f5f5f5;
	text-align: center;
}

.note-cell {
	width: 90px;
	text-align: center;
}

.note-input {
	width: 70px;
	text-align: center;
	border: 1px solid #bbb;
	padding: 4px;
	border-radius: 4px;
}

.error {
	color: #b91c1c;
	font-size: 12px;
	margin-top: 4px;
}

.total-line {
	margin-top: 12px;
	font-weight: bold;
	font-size: 15px;
	background: #f0f7ff;
	border: 1px solid #c7ddf5;
	padding: 8px 10px;
	border-radius: 8px;
}

.comment-box {
	width: 100%;
	min-height: 120px;
	border: 1px dotted black;
	margin-top: 10px;
	padding: 10px;
	font-size: 14px;
}

.actions {
	display: flex;
	justify-content: flex-end;
	gap: 10px;
	margin-top: 18px;
	border-top: 1px solid #dbe2ea;
	padding-top: 14px;
}

.btn {
	border: 1px solid #cbd5e1;
	background: #fff;
	padding: 8px 12px;
	border-radius: 8px;
	font-size: 14px;
	text-decoration: none;
	color: #111827;
	cursor: pointer;
}

.btn-primary {
	background: #0f3d66;
	border-color: #0f3d66;
	color: #fff;
}

.flash {
	margin-bottom: 12px;
	border: 1px solid #86efac;
	background: #f0fdf4;
	color: #166534;
	padding: 10px;
	border-radius: 8px;
	font-weight: 700;
}

@media print {
	body {
		padding: 0;
	}

	.container {
		width: 100%;
	}

	.actions {
		display: none;
	}
}
</style>


<div class="container">
	@if(session('success'))
		<div class="flash">{{ session('success') }}</div>
	@endif
	@error('save')
		<p class="error" style="margin-bottom: 12px;">{{ $message }}</p>
	@enderror

	<form wire:submit.prevent="save">
		<div class="header">
			<h2>Grille d'evaluation - Entretien de positionnement INDH</h2>
		</div>

		<div class="section">
			<h3>• Informations de base</h3>

			<div class="form-line">
				<label>Nom du porteur de projet :</label>
				<span class="line-value">{{ trim(($candidat?->nom ?? '') . ' ' . ($candidat?->prenom ?? '')) ?: 'N/A' }}</span>
			</div>

			<div class="form-line">
				<label>Projet / Idee :</label>
				<span class="line-value">{{ $project?->name ?? $project?->project_name ?? 'N/A' }}</span>
			</div>

			<div class="form-line">
				<label>Date de l'entretien :</label>
				<input type="date" wire:model.defer="dateEntretien" class="line-input" required>
			</div>
			@error('dateEntretien')
				<p class="error">{{ $message }}</p>
			@enderror

			<div class="form-line">
				<label>Evaluateur :</label>
				<span class="line-value">{{ $evaluateurName }}</span>
			</div>
		</div>

		<div class="section">
			<h3>• Criteres d'evaluation</h3>

			<table>
				<thead>
					<tr>
						<th>Axe / Critere</th>
						<th>Sous-criteres</th>
						<th>Poids (%)</th>
						<th>Note (1-5)</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Pertinence de l'idee du projet</td>
						<td>- Alignement avec les priorites du territoire<br>- Innovation<br>- Impact socio-economique</td>
						<td>10%</td>
						<td class="note-cell">
							<input type="number" min="1" max="5" step="1" inputmode="numeric" wire:model.defer="criteriaNotes.pertinence" class="note-input" required oninput="if(this.value==='')return;this.value=Math.min(5,Math.max(1,parseInt(this.value,10)||1));">
							@error('criteriaNotes.pertinence')
								<p class="error">{{ $message }}</p>
							@enderror
						</td>
					</tr>
					<tr>
						<td>Experience dans le domaine</td>
						<td>- Experience professionnelle<br>- Competences techniques</td>
						<td>20%</td>
						<td class="note-cell">
							<input type="number" min="1" max="5" step="1" inputmode="numeric" wire:model.defer="criteriaNotes.experience" class="note-input" required oninput="if(this.value==='')return;this.value=Math.min(5,Math.max(1,parseInt(this.value,10)||1));">
							@error('criteriaNotes.experience')
								<p class="error">{{ $message }}</p>
							@enderror
						</td>
					</tr>
					<tr>
						<td>Niveau d'etude / Diplome</td>
						<td>- Niveau academique<br>- Pertinence des etudes</td>
						<td>10%</td>
						<td class="note-cell">
							<input type="number" min="1" max="5" step="1" inputmode="numeric" wire:model.defer="criteriaNotes.niveau_etude" class="note-input" required oninput="if(this.value==='')return;this.value=Math.min(5,Math.max(1,parseInt(this.value,10)||1));">
							@error('criteriaNotes.niveau_etude')
								<p class="error">{{ $message }}</p>
							@enderror
						</td>
					</tr>
					<tr>
						<td>Capacite financiere / Fonds propres</td>
						<td>- Apport personnel<br>- Fonds de roulement</td>
						<td>10%</td>
						<td class="note-cell">
							<input type="number" min="1" max="5" step="1" inputmode="numeric" wire:model.defer="criteriaNotes.capacite_financiere" class="note-input" required oninput="if(this.value==='')return;this.value=Math.min(5,Math.max(1,parseInt(this.value,10)||1));">
							@error('criteriaNotes.capacite_financiere')
								<p class="error">{{ $message }}</p>
							@enderror
						</td>
					</tr>
					<tr>
						<td>Statut d'activite</td>
						<td>- Projet deja en activite ou non<br>- Anciennete</td>
						<td>10%</td>
						<td class="note-cell">
							<input type="number" min="1" max="5" step="1" inputmode="numeric" wire:model.defer="criteriaNotes.statut_activite" class="note-input" required oninput="if(this.value==='')return;this.value=Math.min(5,Math.max(1,parseInt(this.value,10)||1));">
							@error('criteriaNotes.statut_activite')
								<p class="error">{{ $message }}</p>
							@enderror
						</td>
					</tr>
					<tr>
						<td>Infrastructure physique (Local)</td>
						<td>- Possession d'un local<br>- Adequation du lieu</td>
						<td>10%</td>
						<td class="note-cell">
							<input type="number" min="1" max="5" step="1" inputmode="numeric" wire:model.defer="criteriaNotes.infrastructure" class="note-input" required oninput="if(this.value==='')return;this.value=Math.min(5,Math.max(1,parseInt(this.value,10)||1));">
							@error('criteriaNotes.infrastructure')
								<p class="error">{{ $message }}</p>
							@enderror
						</td>
					</tr>
					<tr>
						<td>Viabilite et faisabilite</td>
						<td>- Faisabilite technique<br>- Faisabilite commerciale<br>- Plan financier</td>
						<td>20%</td>
						<td class="note-cell">
							<input type="number" min="1" max="5" step="1" inputmode="numeric" wire:model.defer="criteriaNotes.viabilite_faisabilite" class="note-input" required oninput="if(this.value==='')return;this.value=Math.min(5,Math.max(1,parseInt(this.value,10)||1));">
							@error('criteriaNotes.viabilite_faisabilite')
								<p class="error">{{ $message }}</p>
							@enderror
						</td>
					</tr>
					<tr>
						<td>Disponibilite et engagement</td>
						<td>- En travail<br>- Etudiant</td>
						<td>20%</td>
						<td class="note-cell">
							<input type="number" min="1" max="5" step="1" inputmode="numeric" wire:model.defer="criteriaNotes.disponibilite" class="note-input" required oninput="if(this.value==='')return;this.value=Math.min(5,Math.max(1,parseInt(this.value,10)||1));">
							@error('criteriaNotes.disponibilite')
								<p class="error">{{ $message }}</p>
							@enderror
						</td>
					</tr>
				</tbody>
			</table>

			<div class="total-line">
				Motivation: {{ $motivationScore }} / 100 | Profil et competences: {{ $profileScore }} / 100 |
				Viabilite: {{ $viabilityScore }} / 100 | Note globale: {{ $this->totalScore }} / 300
			</div>
		</div>

		<div class="section">
			<h3>• Commentaires de l'evaluateur</h3>
			<textarea wire:model.defer="evaluationComment" class="comment-box" required></textarea>
			@error('evaluationComment')
				<p class="error">{{ $message }}</p>
			@enderror
		</div>

		<div class="actions">
			<a href="{{ route('admin.project.print.evaluation', ['id' => $candidatId, 'projectId' => $projectId]) }}" target="_blank" class="btn">Imprimer la grille</a>
			<a href="{{ route('admin.candidat.submissions', ['id' => $candidatId, 'projectId' => $projectId]) }}" class="btn">Annuler</a>
			<button type="submit" class="btn btn-primary">Enregistrer</button>
		</div>
	</form>
</div>
</div>
