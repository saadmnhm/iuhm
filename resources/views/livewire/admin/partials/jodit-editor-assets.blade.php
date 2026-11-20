{{--
    Jodit WYSIWYG editor for Livewire 3 + Alpine.

    Usage (the wrapper MUST have wire:ignore, otherwise Livewire morphs the editor DOM):

        <div wire:ignore x-data="iuhmEditor('content')">
            <textarea x-ref="editorTextarea"></textarea>
        </div>

    Options (2nd argument):
        iuhmEditor('description', { live: false })
            live: true  (default) -> sync to the server 400 ms after the last change
            live: false           -> value is stored client-side in the Livewire state and
                                     sent with the next action (e.g. save). No round-trip per
                                     edit, so no re-render and no selection loss.

    Without an argument the property falls back to $model ?? 'content'.
--}}
@push('head-scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit@4/es2021/jodit.fat.min.css">
<script src="https://cdn.jsdelivr.net/npm/jodit@4/es2021/jodit.fat.min.js"></script>
<style>
    /* ── Design tokens ── */
    .jodit-container {
        --iuhm-ink: #0B1528;
        --iuhm-text: #4A5568;
        --iuhm-link: #214f95;
        --iuhm-accent: #82E682;
        --iuhm-accent-ink: #23803B;
        --iuhm-surface: #f8fafc;
        --iuhm-surface-alt: #F0F2F5;
        --iuhm-border: #e2e8f0;
    }

    /* ── Container & toolbar ── */
    .jodit-container:not(.jodit_inline) { border: none !important; border-radius: 0 !important; }
    .jodit-toolbar__box:not(:empty) { background: var(--iuhm-surface) !important; border-bottom: 1px solid var(--iuhm-border) !important; }
    .jodit-toolbar-button__button:hover,
    .jodit-toolbar-button_variant_initial.jodit-toolbar-button_isActive_true > .jodit-toolbar-button__button {
        background: rgba(130, 230, 130, .15) !important;
        color: var(--iuhm-accent-ink) !important;
    }
    .jodit-toolbar-button__icon svg { fill: currentColor; }
    .jodit-status-bar { display: none !important; }

    /* ── Editor body ── */
    .jodit-wysiwyg { min-height: 460px !important; padding: 2rem 2.25rem !important; line-height: 1.85 !important; color: var(--iuhm-text) !important; font-family: inherit !important; font-size: 16px !important; }
    .jodit-wysiwyg h2 { font-size: 1.875rem; font-weight: 700; color: var(--iuhm-ink); margin: 2.5rem 0 1.25rem; line-height: 1.2; }
    .jodit-wysiwyg h3 { font-size: 1.375rem; font-weight: 700; color: var(--iuhm-ink); margin: 2rem 0 .75rem; }
    .jodit-wysiwyg p { margin-bottom: 1.25rem; }
    .jodit-wysiwyg a { color: var(--iuhm-link); }
    .jodit-wysiwyg blockquote { background: var(--iuhm-surface-alt); border: none; border-left: 6px solid var(--iuhm-accent); border-radius: 1.5rem; padding: 1.5rem 2rem; margin: 2rem 0; color: var(--iuhm-ink); font-size: 1.05rem; font-style: normal; }
    .jodit-wysiwyg img { max-width: 100%; height: auto; border-radius: 1.5rem; }
    .jodit-wysiwyg .iuhm-img-row { display: flex; gap: .75rem; flex-wrap: wrap; margin: 1.5rem 0; }
    .jodit-wysiwyg .iuhm-img-row img { flex: 1 1 0; min-width: 0; width: auto; max-width: 100%; border-radius: 1rem; object-fit: cover; }
    .jodit-wysiwyg hr { border: none; border-top: 2px solid var(--iuhm-border); margin: 2rem 0; }
    .jodit-wysiwyg table { width: 100%; border-collapse: collapse; margin: 1.5rem 0; }
    .jodit-wysiwyg table td, .jodit-wysiwyg table th { border: 1px solid var(--iuhm-border); padding: .6rem .9rem; }
    .jodit-wysiwyg table th { background: var(--iuhm-surface); font-weight: 700; }

    [x-cloak] { display: none !important; }

    /* ── Error toast (created on demand by iuhmShowEditorError) ── */
    #iuhm-editor-toast {
        display: none; position: fixed; right: 1.5rem; bottom: 1.5rem; z-index: 9999;
        align-items: center; gap: .75rem; max-width: 420px;
        padding: 1rem 1.25rem; border-radius: .875rem;
        background: #1e293b; color: #fff; font-size: .875rem; line-height: 1.45;
        box-shadow: 0 8px 28px rgba(0, 0, 0, .3);
    }
    #iuhm-editor-toast.visible { display: flex; }
    #iuhm-editor-toast svg { flex-shrink: 0; color: #f87171; }
    #iuhm-editor-toast .toast-msg { flex: 1; }
    #iuhm-editor-toast .toast-close { background: none; border: none; color: #94a3b8; cursor: pointer; font-size: 1.2rem; line-height: 1; padding: 0 0 0 .5rem; }
    #iuhm-editor-toast .toast-close:hover { color: #fff; }
</style>

<script>
(() => {
    'use strict';

    /* ─────────────────────────── Constants ─────────────────────────── */

    const DEFAULT_MODEL = @js($model ?? 'content');
    const SYNC_DELAY_MS = 400;

    // Image compression
    const IMG_MAX_WIDTH = 1200;
    const IMG_QUALITY = 0.72;
    const IMG_MIN_LENGTH = 150_000; // data-URI length below which we leave the image alone
    const IMG_COMPRESSIBLE = /^data:image\/(png|jpe?g|webp|bmp);/i; // never touch gif/svg (animation, vectors)

    // Commands that act on the current selection. They must never run on a caret
    // the browser moved to the start of the paragraph.
    const SELECTION_COMMANDS = new Set([
        'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript',
        'forecolor', 'background', 'brush', 'fontname', 'fontsize', 'formatblock',
        'justifyleft', 'justifycenter', 'justifyright', 'justifyfull',
        'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent',
        'removeformat', 'link', 'insertimage', 'inserthorizontalrule',
    ]);

    const normalize = (html) => (html || '').replace(/\s+/g, ' ').trim();

    /* ─────────────────────────── Error toast ─────────────────────────── */

    function hideToast() {
        const el = document.getElementById('iuhm-editor-toast');
        if (el) el.classList.remove('visible');
    }

    window.iuhmShowEditorError = function (msg) {
        let el = document.getElementById('iuhm-editor-toast');

        if (!el) {
            el = document.createElement('div');
            el.id = 'iuhm-editor-toast';
            el.setAttribute('role', 'alert');
            el.setAttribute('aria-live', 'assertive');
            el.innerHTML =
                '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" ' +
                'stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
                '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>' +
                '<span class="toast-msg"></span>' +
                '<button type="button" class="toast-close" aria-label="Fermer">&times;</button>';
            document.body.appendChild(el);
            el.querySelector('.toast-close').addEventListener('click', hideToast);
        }

        el.querySelector('.toast-msg').textContent = msg;
        el.classList.add('visible');
        clearTimeout(window.__iuhmToastTimer);
        window.__iuhmToastTimer = setTimeout(hideToast, 7000);
    };

    /* ─────────────────────────── Image compression ─────────────────────────── */

    function compressImage(img) {
        return new Promise((resolve) => {
            const source = img.src;
            const image = new Image();

            image.onload = () => {
                try {
                    let w = image.naturalWidth;
                    let h = image.naturalHeight;
                    if (w > IMG_MAX_WIDTH) {
                        h = Math.round((h * IMG_MAX_WIDTH) / w);
                        w = IMG_MAX_WIDTH;
                    }

                    const canvas = document.createElement('canvas');
                    canvas.width = w;
                    canvas.height = h;

                    const ctx = canvas.getContext('2d');
                    // JPEG has no alpha channel: without this, transparent PNGs turn black.
                    ctx.fillStyle = '#fff';
                    ctx.fillRect(0, 0, w, h);
                    ctx.drawImage(image, 0, 0, w, h);

                    const compressed = canvas.toDataURL('image/jpeg', IMG_QUALITY);
                    // Only keep the result when it is actually lighter.
                    if (compressed.length < source.length) img.src = compressed;
                } catch (_) {
                    /* keep the original image */
                }
                resolve();
            };

            image.onerror = () => resolve();
            image.src = source;
        });
    }

    async function compressBase64Images(html) {
        if (!html || !html.includes('data:image')) return html;

        const doc = new DOMParser().parseFromString(html, 'text/html');
        const imgs = Array.from(doc.querySelectorAll('img[src^="data:image"]'))
            .filter((img) => IMG_COMPRESSIBLE.test(img.src) && img.src.length > IMG_MIN_LENGTH);

        if (!imgs.length) return html;

        await Promise.all(imgs.map(compressImage));
        return doc.body.innerHTML;
    }

    /* ─────────────────────────── Alpine component ─────────────────────────── */

    function registerEditor() {
        if (window.__iuhmEditorRegistered) return;
        window.__iuhmEditorRegistered = true;

        Alpine.data('iuhmEditor', (model = DEFAULT_MODEL, options = {}) => ({
            editor: null,
            _syncTimer: null,
            _serverValue: '',        // last content known to be stored server-side (echo detection)
            _pushing: false,
            _applyingRemote: false,  // true while we write a server value into the editor
            _destroyed: false,
            _selection: null,        // last caret/selection (cloned Range) inside the editor
            _rememberSelection: null,
            _rememberOnMouseDown: null,

            init() {
                const wire = this.$wire;
                const textarea = this.$refs.editorTextarea;

                this.$nextTick(() => {
                    if (this._destroyed || !textarea) return;

                    if (typeof Jodit === 'undefined') {
                        window.iuhmShowEditorError('Impossible de charger l\'éditeur. Rechargez la page.');
                        return;
                    }

                    this.editor = Jodit.make(textarea, {
                        language: 'fr',
                        minHeight: 460,
                        toolbarAdaptive: true,
                        showWordsCounter: false,
                        showCharsCounter: false,
                        showXPathInStatusbar: false,

                        // Paste: strip Word / web junk instead of prompting every time.
                        askBeforePasteHTML: false,
                        askBeforePasteFromWord: false,
                        defaultActionOnPaste: 'insert_clear_html',

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
                        uploader: { insertImageAsBase64URI: true },
                        image: { dialogWidth: 560, openOnDblClick: true },
                        table: { allowCellResize: true },
                        events: {
                            change: () => {
                                if (!this._applyingRemote) this.scheduleSync();
                            },
                        },
                    });

                    const raw = wire.get(model);
                    if (raw) this.setEditorValue(raw);
                    this._serverValue = normalize(raw);

                    this.trackSelection();
                    wire.$watch(model, (val) => this.pullFromServer(val));
                });
            },

            /** Write a value into the editor without echoing it back to the server. */
            setEditorValue(html) {
                this._applyingRemote = true;
                try {
                    this.editor.value = html || '';
                } finally {
                    // Jodit may emit "change" asynchronously: release the flag after it.
                    setTimeout(() => { this._applyingRemote = false; }, 0);
                }
            },

            /**
             * Remember where the caret/selection is inside the editor.
             *
             * Jodit focuses the editing area right before running a command. When
             * the browser range was lost in the meantime (Livewire re-render,
             * focus moved to another field, dropdown click…), that focus puts the
             * caret at the very beginning of the editor and the command lands on
             * the first paragraph instead of where the author was working.
             *
             * The remembered range may be collapsed: a caret is a valid position
             * too. It is only put back when the browser no longer has any range
             * inside the editor — never over a selection or caret the author has
             * just placed (that was what made list buttons target the first
             * paragraph after clicking an article/list item).
             */
            trackSelection() {
                this._rememberSelection = () => {
                    const range = this.editorRange();
                    if (!range) return;
                    this._selection = range;
                };

                document.addEventListener('selectionchange', this._rememberSelection);

                // Clicking a toolbar button or a dropdown item must not lose the
                // caret. Jodit already prevents the focus change; recording the
                // range on mousedown (capture phase) keeps our snapshot in sync
                // even when the browser does not fire `selectionchange` for it.
                // If the editor had already lost focus (the author clicked an
                // article/list item, another field…), put the caret back *before*
                // Jodit focuses the area, otherwise the browser resets it to the
                // first paragraph and the command formats that one.
                this._rememberOnMouseDown = (event) => {
                    const target = event.target;
                    if (!(target instanceof Element)) return;
                    if (!target.closest('.jodit-toolbar__box, .jodit-popup, .jodit-dialog, .jodit-status-bar')) return;

                    this._rememberSelection();
                    if (!this.editorRange()) this.restoreRememberedSelection();
                };
                document.addEventListener('mousedown', this._rememberOnMouseDown, true);

                this.editor.events.on('beforeCommand', (command) => {
                    if (!SELECTION_COMMANDS.has(String(command).toLowerCase())) return;

                    // A live range inside the editor is the author's current
                    // position, collapsed or not. Never replace it with an older
                    // selection: that is what made `ul`/`ol` format the first
                    // paragraph after the author moved the caret elsewhere.
                    if (this.editorRange()) return;

                    this.restoreRememberedSelection();
                });
            },

            /** Any live range inside the editor (collapsed carets included), cloned. */
            editorRange() {
                const selection = window.getSelection();
                if (!this.editor || !selection || !selection.rangeCount) return null;

                const range = selection.getRangeAt(0);
                return this.editor.editor.contains(range.commonAncestorContainer) ? range.cloneRange() : null;
            },

            /** Current non-collapsed range inside the editor, if any (cloned). */
            currentRange() {
                const selection = window.getSelection();
                if (!this.editor || !selection || !selection.rangeCount || selection.isCollapsed) return null;

                const range = selection.getRangeAt(0);
                return this.editor.editor.contains(range.commonAncestorContainer) ? range.cloneRange() : null;
            },

            /** Put the remembered caret/selection back inside the editor. */
            restoreRememberedSelection() {
                const saved = this._selection;
                if (!saved || !this.restoreRange(saved)) return false;

                this._selection = saved.cloneRange();
                return true;
            },

            /** Apply a range through Jodit's selection API (focuses if needed). */
            restoreRange(range) {
                if (!this.editor || !range) return false;

                const start = range.startContainer;
                const end = range.endContainer;
                if (!start || !end || !start.isConnected || !end.isConnected) return false;
                if (!this.editor.editor.contains(start) || !this.editor.editor.contains(end)) return false;

                try {
                    this.editor.selection.selectRange(range);
                    return true;
                } catch (_) {
                    return false;
                }
            },

            /** True while the author is selecting/formatting inside the editor. */
            isAuthoring() {
                if (!this.editor) return false;

                const active = document.activeElement;
                if (active && this.editor.container.contains(active)) return true;

                // Jodit renders its dropdowns/dialogs (text styles, colour swatches,
                // font list…) in a popup root outside `.jodit-container`, so a click
                // on one of those swatches does not keep focus inside the container.
                for (const popup of document.querySelectorAll('.jodit-popup, .jodit-dialog')) {
                    if (popup.getClientRects().length > 0) return true;
                }

                return !!this.currentRange();
            },

            scheduleSync() {
                clearTimeout(this._syncTimer);
                this._syncTimer = setTimeout(() => this.pushToServer(), SYNC_DELAY_MS);
            },

            async pushToServer() {
                if (!this.editor || this._destroyed) return;

                // A round-trip is already running: retry once it settles.
                if (this._pushing) {
                    this.scheduleSync();
                    return;
                }

                const html = this.editor.value;
                if (normalize(html) === this._serverValue) return; // nothing new

                const live = options.live !== false;
                const wire = this.$wire;
                const focused = this.editor.container.contains(document.activeElement);
                // Keep the caret too, not only a text selection: the round-trip
                // must not move a collapsed caret back to the first paragraph.
                const range = this.editorRange() || (this._selection && this._selection.cloneRange());

                this._pushing = true;
                try {
                    const compressed = await compressBase64Images(html);
                    await wire.set(model, compressed, live);
                    this._serverValue = normalize(compressed);
                } catch (e) {
                    const message = String((e && e.message) || '').toLowerCase();
                    window.iuhmShowEditorError(
                        message.includes('payload')
                            ? 'Image trop lourde même après compression. Veuillez réduire la taille de l\'image.'
                            : 'Erreur de synchronisation. Veuillez réessayer.'
                    );
                } finally {
                    this._pushing = false;
                }

                // The Livewire round-trip must never cost the author the phrase
                // they had selected or the caret they had placed.
                if (live && focused) {
                    this.$nextTick(() => {
                        if (!this.editor || this._destroyed) return;

                        // The author moved on to another control while the
                        // request was in flight: do not steal the focus back.
                        const active = document.activeElement;
                        if (active && active !== document.body && !this.editor.container.contains(active)) return;

                        const selection = window.getSelection();
                        const stillThere = selection && selection.rangeCount &&
                            this.editor.editor.contains(selection.getRangeAt(0).commonAncestorContainer);

                        if (!this.editor.selection.isFocused()) {
                            this.editor.focus();
                        }

                        if (!stillThere && range) {
                            this.restoreRange(range);
                        }
                    });
                }
            },

            /**
             * Livewire sends the server value back on every render. Writing it into
             * the editor while the author is formatting replaces the DOM nodes and
             * collapses the selection. Only accept genuine external changes.
             */
            pullFromServer(val) {
                if (!this.editor || this._destroyed) return;

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
                this.setEditorValue(val);
            },

            destroy() {
                this._destroyed = true;
                clearTimeout(this._syncTimer);

                if (this._rememberSelection) {
                    document.removeEventListener('selectionchange', this._rememberSelection);
                    this._rememberSelection = null;
                }

                if (this._rememberOnMouseDown) {
                    document.removeEventListener('mousedown', this._rememberOnMouseDown, true);
                    this._rememberOnMouseDown = null;
                }

                if (this.editor) {
                    this.editor.destruct();
                    this.editor = null;
                }
            },
        }));
    }

    // Works whether this script runs before or after Alpine has booted (wire:navigate, late stacks…).
    if (window.Alpine) registerEditor();
    else document.addEventListener('alpine:init', registerEditor);

    /* ─────────────────────────── Livewire error hook ─────────────────────────── */

    function registerLivewireHook() {
        if (window.__iuhmErrorHookRegistered) return;
        window.__iuhmErrorHookRegistered = true;

        Livewire.hook('commit', ({ fail }) => {
            fail(({ status }) => {
                // Only react on pages that actually contain an editor.
                if (!document.querySelector('.jodit-container')) return;

                let message;
                if (status === 413) {
                    message = 'Image trop lourde. Veuillez utiliser une image plus petite avant de l\'insérer.';
                } else if (status === 419) {
                    message = 'Votre session a expiré. Rechargez la page pour continuer (copiez votre texte avant).';
                } else if (status >= 500) {
                    message = 'Erreur serveur. Si vous venez d\'insérer une image, essayez-en une plus légère.';
                } else {
                    message = 'Erreur lors de la sauvegarde (code ' + status + '). Veuillez réessayer.';
                }
                window.iuhmShowEditorError(message);
            });
        });
    }

    if (window.Livewire) registerLivewireHook();
    else document.addEventListener('livewire:init', registerLivewireHook);
})();
</script>
@endpush
