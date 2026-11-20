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

    // Livewire property backing this editor ("content" for articles, sections,
    // actualités…, "description" for projects).
    const MODEL = @js($model ?? 'content');

    // Commands that act on the current text selection. They must never run on
    // a caret that the browser moved to the beginning of the paragraph.
    const SELECTION_COMMANDS = [
        'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript',
        'forecolor', 'background', 'brush', 'fontname', 'fontsize', 'formatblock',
        'justifyleft', 'justifycenter', 'justifyright', 'justifyfull',
        'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent',
        'removeformat', 'link', 'insertimage', 'inserthorizontalrule',
    ];

    const normalize = (html) => (html || '').replace(/\s+/g, ' ').trim();

    Alpine.data('iuhmEditor', () => ({
        editor: null,
        _syncTimer: null,
        /** Last content known to be stored server-side (echo detection). */
        _serverValue: '',
        _pushing: false,
        /** Last real text selection, used to repair a lost range. */
        _lastRange: null,
        _lastRangeAt: 0,
        _rememberSelection: null,

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
                        change: () => this.scheduleSync(),
                    },
                });

                const raw = wire.get(MODEL);
                if (raw) this.editor.value = raw;
                this._serverValue = normalize(raw);

                this.trackSelection();

                wire.$watch(MODEL, (val) => this.pullFromServer(val));
            });
        },

        /**
         * Jodit focuses the editing area right before running a command. When
         * the browser range was lost in the meantime (Livewire re-render,
         * colour/style dropdown click…), that focus puts the caret at the very
         * beginning of the paragraph and the style lands there instead of on
         * the selected phrase. Remember the last real selection and put it
         * back before such commands.
         */
        trackSelection() {
            this._rememberSelection = () => {
                const selection = window.getSelection();
                if (!this.editor || !selection || !selection.rangeCount || selection.isCollapsed) return;

                const range = selection.getRangeAt(0);
                if (!this.editor.editor.contains(range.commonAncestorContainer)) return;

                this._lastRange = range.cloneRange();
                this._lastRangeAt = Date.now();
            };

            document.addEventListener('selectionchange', this._rememberSelection);
            document.addEventListener('mousedown', this._rememberSelection, true);

            this.editor.events.on('beforeCommand', (command) => {
                if (!this._lastRange) return;
                if (!SELECTION_COMMANDS.includes(String(command).toLowerCase())) return;
                if (Date.now() - this._lastRangeAt > 5000) return;
                if (!this.editor.editor.contains(this._lastRange.startContainer)) return;

                const selection = window.getSelection();
                if (!selection) return;
                if (selection.rangeCount && !selection.isCollapsed) return;

                selection.removeAllRanges();
                selection.addRange(this._lastRange);
            });
        },

        /** Current non-collapsed range inside the editor, if any. */
        currentRange() {
            const selection = window.getSelection();
            if (!this.editor || !selection || !selection.rangeCount || selection.isCollapsed) return null;

            const range = selection.getRangeAt(0);
            return this.editor.editor.contains(range.commonAncestorContainer) ? range.cloneRange() : null;
        },

        /** True while the author is selecting/formatting inside the editor. */
        isAuthoring() {
            if (!this.editor) return false;

            const active = document.activeElement;
            if (active && this.editor.container.contains(active)) return true;

            // Jodit renders its dropdowns/dialogs (text styles, colour swatches,
            // font list…) in a popup root outside `.jodit-container`, so a click
            // on one of those swatches does not keep focus inside the container.
            const popups = document.querySelectorAll('.jodit-popup, .jodit-dialog');
            for (const popup of popups) {
                if (popup.getClientRects().length > 0) return true;
            }

            return !!this.currentRange();
        },

        scheduleSync() {
            clearTimeout(this._syncTimer);
            this._syncTimer = setTimeout(() => this.pushToServer(), 400);
        },

        async pushToServer() {
            if (!this.editor) return;

            // A round-trip is already running: retry once it settles.
            if (this._pushing) {
                this.scheduleSync();
                return;
            }

            const wire = this.$wire;
            const focused = this.editor.container.contains(document.activeElement);
            const range = this.currentRange();

            this._pushing = true;
            try {
                const compressed = await compressBase64Images(this.editor.value);
                await wire.set(MODEL, compressed);
                this._serverValue = normalize(compressed);
            } catch (e) {
                window.iuhmShowEditorError(
                    e && e.message && e.message.toLowerCase().includes('payload')
                        ? 'Image trop lourde même après compression. Veuillez réduire la taille de l\'image.'
                        : 'Erreur de synchronisation. Veuillez réessayer.'
                );
            } finally {
                this._pushing = false;
            }

            // The Livewire round-trip must never cost the author the phrase
            // they had selected.
            if (focused) {
                this.$nextTick(() => {
                    if (!this.editor) return;
                    this.editor.focus();

                    if (range && this.editor.editor.contains(range.startContainer)) {
                        const selection = window.getSelection();
                        selection.removeAllRanges();
                        selection.addRange(range);
                    }
                });
            }
        },

        /**
         * Livewire sends the server value back on every render. Writing it into
         * the editor while the author is formatting replaces the DOM nodes and
         * collapses the selection, which made the next style click apply at the
         * start of the paragraph. Only accept genuine external changes.
         */
        pullFromServer(val) {
            if (!this.editor) return;

            const incoming = normalize(val);

            if (incoming === normalize(this.editor.value)) {
                this._serverValue = incoming;
                return;
            }

            // Echo of our own sync (compressed images make it differ from the
            // editor HTML) or an older snapshot: keep the local content.
            if (incoming === this._serverValue) return;

            // Never touch the editor DOM while the author is working in it.
            if (this.isAuthoring()) return;

            this._serverValue = incoming;
            this.editor.value = val || '';
        },

        destroy() {
            clearTimeout(this._syncTimer);

            if (this._rememberSelection) {
                document.removeEventListener('selectionchange', this._rememberSelection);
                document.removeEventListener('mousedown', this._rememberSelection, true);
                this._rememberSelection = null;
            }

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
