@include('livewire.admin.partials.jodit-editor-assets')

<div>
    <h1>Harness éditeur</h1>
    <div wire:ignore x-data="iuhmEditor('content')">
        <textarea x-ref="editorTextarea"></textarea>
    </div>
    <pre id="out" style="white-space: pre-wrap; background:#f1f5f9; padding:12px; margin-top:16px;"></pre>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        setInterval(() => {
            const area = document.querySelector('.jodit-wysiwyg');
            const out = document.getElementById('out');
            if (area && out) out.textContent = area.innerHTML;
        }, 250);
    });
</script>
