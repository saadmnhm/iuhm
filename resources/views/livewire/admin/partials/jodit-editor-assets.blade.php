@push('head-scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit@4/es2021/jodit.fat.min.css">
<script src="https://cdn.jsdelivr.net/npm/jodit@4/es2021/jodit.fat.min.js"></script>
<style>
    /* ── Jodit container ── */
    .jodit-container:not(.jodit_inline) { border: none !important; border-radius: 0 !important; }
    .jodit-toolbar__box:not(:empty) { background: #f8fafc !important; border-bottom: 1px solid #e2e8f0 !important; }
    .jodit-toolbar-button__button:hover, .jodit-toolbar-button_variant_initial.jodit-toolbar-button_isActive_true > .jodit-toolbar-button__button { background: rgba(130,230,130,.15) !important; color: #23803B !important; }
    .jodit-toolbar-button__icon svg { fill: currentColor; }

    /* ── Editor body ── */
    .jodit-wysiwyg { min-height: 460px !important; padding: 2rem 2.25rem !important; line-height: 1.85 !important; color: #4A5568 !important; font-family: inherit !important; font-size: 16px !important; }
    .jodit-wysiwyg h2 { font-size: 1.875rem; font-weight: 700; color: #0B1528; margin-top: 2.5rem; margin-bottom: 1.25rem; line-height: 1.2; }
    .jodit-wysiwyg h3 { font-size: 1.375rem; font-weight: 700; color: #0B1528; margin-top: 2rem; margin-bottom: 0.75rem; }
    .jodit-wysiwyg p { margin-bottom: 1.25rem; }
    .jodit-wysiwyg a { color: #214f95; }
    .jodit-wysiwyg blockquote { background: #F0F2F5; border-radius: 1.5rem; border-left: 6px solid #82E682; border-top: none; border-right: none; border-bottom: none; padding: 1.5rem 2rem; margin: 2rem 0; color: #0B1528; font-size: 1.05rem; font-style: normal; }
    .jodit-wysiwyg img { max-width: 100%; border-radius: 1.5rem; }
    .jodit-wysiwyg .iuhm-img-row { display: flex; gap: 0.75rem; flex-wrap: wrap; margin: 1.5rem 0; }
    .jodit-wysiwyg .iuhm-img-row img { flex: 1 1 0; min-width: 0; width: auto; max-width: 100%; border-radius: 1rem; object-fit: cover; }
    .jodit-wysiwyg hr { border: none; border-top: 2px solid #e2e8f0; margin: 2rem 0; }
    .jodit-wysiwyg table { width: 100%; border-collapse: collapse; margin: 1.5rem 0; }
    .jodit-wysiwyg table td, .jodit-wysiwyg table th { border: 1px solid #e2e8f0; padding: 0.6rem 0.9rem; }
    .jodit-wysiwyg table th { background: #f8fafc; font-weight: 700; }
    .jodit-status-bar { display: none !important; }
    [x-cloak] { display: none !important; }
    /* ── Error toast ── */
    #iuhm-editor-toast { position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 9999; align-items: center; gap: 0.75rem; background: #1e293b; color: #fff; padding: 1rem 1.25rem; border-radius: 0.875rem; box-shadow: 0 8px 28px rgba(0,0,0,.3); max-width: 420px; font-size: 0.875rem; line-height: 1.45; visibility: hidden; }
    #iuhm-editor-toast.visible { visibility: visible; }
    #iuhm-editor-toast.hidden { visibility: hidden; }
    #iuhm-editor-toast svg { flex-shrink: 0; color: #f87171; }
    #iuhm-editor-toast .toast-msg { flex: 1; }
    #iuhm-editor-toast .toast-close { background: none; border: none; color: #94a3b8; cursor: pointer; font-size: 1.2rem; line-height: 1; padding: 0 0 0 0.5rem; }
    #iuhm-editor-toast .toast-close:hover { color: #fff; }
</style>

<script>
document.addEventListener('alpine:init', () => {
    if (window.__iuhmEditorRegistered) return;
    window.__iuhmEditorRegistered = true;

    Alpine.data('iuhmEditor', () => ({
        editor: null,
        _syncTimer: null,
        init() {
            const wire     = this.$wire;
            const textarea = this.$refs.editorTextarea;
            this.$nextTick(() => {
                this.editor = Jodit.make(textarea, {
                    language: 'fr',
                    minHeight: 460,
                    toolbarAdaptive: true,
                    showWordsCounter: false,
                    showCharsCounter: false,
                    showXPathInStatusbar: false,
                    buttons: [
                        'paragraph',
                        '|', 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript',
                        '|', 'brush', 'font', 'fontsize', 'forecolor', 'background',
                        '|', 'ul', 'ol', 'indent', 'outdent',
                        '|', 'align',
                        '|', 'link', 'image', 'video', 'table', 'file',
                        '|', 'blockquote', 'hr', 'symbols',
                        '|', 'selectall', 'cut', 'copy', 'paste', 'copyformat', 'eraser',
                        '|', 'undo', 'redo',
                        '|', 'fullsize', 'source',
                    ],
                    uploader: {
                        insertImageAsBase64URI: true,
                    },
                    image: {
                        dialogWidth: 560,
                        openOnDblClick: true,
                    },
                    table: {
                        allowCellResize: true,
                    },
                    events: {
                        change: () => {
                            // Debounce the Livewire round-trip: syncing on every
                            // keystroke re-rendered the component and could steal
                            // focus/selection while the author was formatting text.
                            clearTimeout(this._syncTimer);
                            this._syncTimer = setTimeout(async () => {
                                if (!this.editor) return;

                                const compressed = await compressBase64Images(this.editor.value);
                                try {
                                    await wire.set('content', compressed);
                                } catch (e) {
                                    window.iuhmShowEditorError(
                                        e && e.message && e.message.toLowerCase().includes('payload')
                                            ? 'Image trop lourde même après compression. Veuillez réduire la taille de l\'image.'
                                            : 'Erreur de synchronisation. Veuillez réessayer.'
                                    );
                                }
                            }, 350);
                        },
                    },
                });

                const raw = wire.get('content');
                if (raw) this.editor.value = raw;

                const normalize = (html) => (html || '').replace(/\s+/g, ' ').trim();

                wire.$watch('content', (val) => {
                    if (!this.editor) return;
                    if (normalize(val) === normalize(this.editor.value)) return;

                    // Never rewrite the editor while it is focused: assigning
                    // `value` collapses the caret and the current text selection,
                    // which made toolbar styles apply to the beginning of the
                    // paragraph instead of the selected phrase.
                    if (this.editor.container.contains(document.activeElement)) return;

                    this.editor.value = val || '';
                });
            });
        },
        destroy() {
            clearTimeout(this._syncTimer);
            if (this.editor) {
                this.editor.destruct();
                this.editor = null;
            }
        },
    }));
});

// Compress base64 images in editor content to reduce Livewire payload
async function compressBase64Images(html) {
    const parser = new DOMParser();
    const doc = parser.parseFromString(html, 'text/html');
    const imgs = doc.querySelectorAll('img[src^="data:image"]');
    if (!imgs.length) return html;
    await Promise.all(Array.from(imgs).map(img => new Promise(resolve => {
        const MAX_W = 1200;
        const image = new Image();
        image.onload = () => {
            let w = image.naturalWidth, h = image.naturalHeight;
            if (w > MAX_W) { h = Math.round(h * MAX_W / w); w = MAX_W; }
            const canvas = document.createElement('canvas');
            canvas.width = w; canvas.height = h;
            canvas.getContext('2d').drawImage(image, 0, 0, w, h);
            img.src = canvas.toDataURL('image/jpeg', 0.72);
            resolve();
        };
        image.onerror = resolve;
        image.src = img.src;
    })));
    return doc.body.innerHTML;
}

// Error toast helper
window.iuhmShowEditorError = function (msg) {
    const el = document.getElementById('iuhm-editor-toast');
    if (!el) return;
    el.querySelector('.toast-msg').textContent = msg;
    el.classList.add('visible');
    el.classList.remove('hidden');
    clearTimeout(window.__iuhmToastTimer);
    window.__iuhmToastTimer = setTimeout(() => {
        el.classList.add('hidden');
        el.classList.remove('visible');
    }, 7000);
};

// Livewire commit hook – catch server-side errors
document.addEventListener('livewire:init', () => {
    if (window.__iuhmErrorHookRegistered) return;
    window.__iuhmErrorHookRegistered = true;
    Livewire.hook('commit', ({ fail }) => {
        fail(({ status }) => {
            window.iuhmShowEditorError(
                (status === 413 || status === 500)
                    ? 'Image trop lourde. Veuillez utiliser une image plus petite avant de l\'insérer.'
                    : 'Erreur lors de la sauvegarde (code ' + status + '). Veuillez réessayer.'
            );
        });
    });
});
</script>
@endpush
