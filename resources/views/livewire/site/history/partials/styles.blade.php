<style>
    /* ==========================================================================
       Histoire — editorial exhibition design system (page scoped)
       ========================================================================== */
    [data-history-page] {
        --hist-ink: #0a1526;
        --hist-ink-2: #12233f;
        --hist-paper: #f7f4ee;
        --hist-paper-2: #f1ebe1;
        --hist-card: #fffdf9;
        --hist-line: rgba(10, 21, 38, .12);
        --hist-line-strong: rgba(10, 21, 38, .24);
        --hist-muted: #5c6472;
        --hist-body: #3d4757;
        --hist-emerald: #0f6b2a;
        --hist-emerald-deep: #0a471b;
        --hist-mint: #82e682;
        --hist-serif: 'Iowan Old Style', 'Palatino Linotype', Palatino, 'Book Antiqua', Georgia, 'Times New Roman', serif;
        --hist-ease: cubic-bezier(.16, 1, .3, 1);

        background: var(--hist-paper);
        color: var(--hist-ink);
        overflow-x: clip;
        -webkit-font-smoothing: antialiased;
    }

    [data-history-page] ::selection {
        background: var(--hist-emerald);
        color: #fff;
    }

    @media (prefers-reduced-motion: no-preference) {
        html:has([data-history-page]) {
            scroll-behavior: smooth;
        }
    }

    [data-history-page] section[id] {
        scroll-margin-top: 5.5rem;
    }

    [data-history-page] [id^='chapitre-'] {
        scroll-margin-top: 6rem;
    }

    [data-history-page] .hist-shell {
        width: 100%;
        max-width: 78rem;
        margin-inline: auto;
        padding-inline: clamp(1.25rem, 4vw, 2.75rem);
    }

    [data-history-page] .hist-section {
        position: relative;
        padding-block: clamp(4rem, 8vw, 7.5rem);
    }

    [data-history-page] .hist-section--paper { background: var(--hist-paper); }
    [data-history-page] .hist-section--paper2 { background: var(--hist-paper-2); }

    /* ---------- Typography ---------- */
    [data-history-page] .hist-display {
        font-family: var(--hist-serif);
        font-weight: 500;
        letter-spacing: -.015em;
        line-height: 1.03;
        text-wrap: balance;
    }

    html[lang='ar'] [data-history-page] .hist-display {
        font-family: 'Cairo', sans-serif;
        font-weight: 700;
        letter-spacing: 0;
        line-height: 1.3;
    }

    [data-history-page] .hist-kicker {
        display: inline-flex;
        align-items: center;
        gap: .7rem;
        font-size: .68rem;
        font-weight: 600;
        letter-spacing: .24em;
        text-transform: uppercase;
        color: var(--hist-emerald);
    }

    [data-history-page] .hist-kicker::before {
        content: "";
        width: 1.75rem;
        height: 1px;
        background: currentColor;
        opacity: .55;
    }

    [dir='rtl'] [data-history-page] .hist-kicker {
        letter-spacing: .06em;
    }

    [data-history-page] .hist-head {
        display: grid;
        gap: 1.35rem;
    }

    [data-history-page] .hist-head__title {
        font-size: clamp(2.1rem, 4vw, 3.4rem);
    }

    [data-history-page] .hist-head__desc {
        max-width: 36rem;
        font-size: 1rem;
        line-height: 1.9;
        color: var(--hist-muted);
    }

    @media (min-width: 900px) {
        [data-history-page] .hist-head {
            grid-template-columns: minmax(0, 1.1fr) minmax(0, .9fr);
            align-items: end;
            gap: 3rem;
        }

        [data-history-page] .hist-head__desc {
            margin-top: 0;
            padding-bottom: .35rem;
        }
    }

    /* ---------- Buttons ---------- */
    [data-history-page] .hist-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .6rem;
        min-height: 3rem;
        padding: .8rem 1.55rem;
        border-radius: 999px;
        font-size: .875rem;
        font-weight: 600;
        line-height: 1;
        text-align: center;
        transition: transform .4s var(--hist-ease), background-color .3s ease,
            color .3s ease, border-color .3s ease, box-shadow .4s ease;
    }

    [data-history-page] .hist-btn svg {
        width: 1rem;
        height: 1rem;
        flex: none;
        transition: transform .4s var(--hist-ease);
    }

    [data-history-page] .hist-btn:hover {
        transform: translateY(-2px);
    }

    [data-history-page] .hist-btn:hover svg {
        transform: translateX(3px);
    }

    [dir='rtl'] [data-history-page] .hist-btn svg {
        transform: scaleX(-1);
    }

    [dir='rtl'] [data-history-page] .hist-btn:hover svg {
        transform: scaleX(-1) translateX(3px);
    }

    [data-history-page] .hist-btn--mint {
        background: var(--hist-mint);
        color: var(--hist-emerald-deep);
    }

    [data-history-page] .hist-btn--mint:hover {
        background: #a7ff8f;
        box-shadow: 0 16px 34px -22px rgba(130, 230, 130, .95);
    }

    [data-history-page] .hist-btn--ghost {
        border: 1px solid rgba(255, 255, 255, .3);
        color: #fff;
    }

    [data-history-page] .hist-btn--ghost:hover {
        border-color: rgba(255, 255, 255, .6);
        background: rgba(255, 255, 255, .07);
    }

    [data-history-page] .hist-btn--ink {
        background: var(--hist-ink);
        color: #fff;
    }

    [data-history-page] .hist-btn--ink:hover {
        background: var(--hist-ink-2);
        box-shadow: 0 18px 34px -24px rgba(10, 21, 38, .9);
    }

    [data-history-page] .hist-btn--outline {
        border: 1px solid var(--hist-line-strong);
        color: var(--hist-ink);
    }

    [data-history-page] .hist-btn--outline:hover {
        border-color: var(--hist-ink);
        background: rgba(10, 21, 38, .04);
    }

    /* ---------- Hero ---------- */
    [data-history-page] .hist-hero {
        position: relative;
        isolation: isolate;
        display: flex;
        align-items: center;
        overflow: hidden;
        min-height: min(92svh, 60rem);
        padding-block: clamp(2.5rem, 6vw, 4rem) clamp(4.5rem, 8vw, 6.5rem);
        background: var(--hist-ink);
        color: #fff;
    }

    [data-history-page] .hist-hero__backdrop {
        position: absolute;
        inset: 0;
        z-index: -1;
    }

    [data-history-page] .hist-hero__backdrop img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: .3;
        filter: grayscale(1) contrast(1.06) brightness(.9);
        transform: scale(1.03);
    }

    [data-history-page] .hist-hero__shade {
        position: absolute;
        inset: 0;
        background:
            radial-gradient(80% 70% at 82% 30%, rgba(130, 230, 130, .12), transparent 62%),
            radial-gradient(70% 80% at 8% 100%, rgba(33, 79, 149, .34), transparent 66%),
            linear-gradient(102deg, rgba(7, 15, 29, .97) 22%, rgba(7, 15, 29, .86) 56%, rgba(7, 15, 29, .72) 100%);
    }

    [data-history-page] .hist-grain {
        position: absolute;
        inset: 0;
        pointer-events: none;
        opacity: .18;
        mix-blend-mode: soft-light;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.82' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='160' height='160' filter='url(%23n)' opacity='.6'/%3E%3C/svg%3E");
    }

    [data-history-page] .hist-hero__inner {
        position: relative;
        width: 100%;
    }

    [data-history-page] .hist-crumbs {
        display: flex;
        align-items: center;
        gap: .55rem;
        margin-bottom: clamp(2rem, 5vw, 3.25rem);
        font-size: .66rem;
        font-weight: 600;
        letter-spacing: .2em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, .45);
    }

    [data-history-page] .hist-crumbs a {
        color: rgba(255, 255, 255, .72);
        transition: color .3s ease;
    }

    [data-history-page] .hist-crumbs a:hover {
        color: #fff;
    }

    [data-history-page] .hist-crumbs [aria-current='page'] {
        color: var(--hist-mint);
    }

    [data-history-page] .hist-hero__grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: clamp(2.75rem, 6vw, 4.5rem);
        align-items: end;
    }

    @media (min-width: 1024px) {
        [data-history-page] .hist-hero__grid {
            grid-template-columns: minmax(0, 1.08fr) minmax(0, .92fr);
        }
    }

    [data-history-page] .hist-hero__eyebrow {
        color: var(--hist-mint);
    }

    [data-history-page] .hist-hero__title {
        margin-top: 1.15rem;
        font-size: clamp(2.9rem, 7.2vw, 6.2rem);
        color: #fff;
    }

    [data-history-page] .hist-hero__lead {
        max-width: 34rem;
        margin-top: 1.5rem;
        font-size: clamp(1rem, 1.35vw, 1.15rem);
        line-height: 1.85;
        color: rgba(255, 255, 255, .76);
    }

    [data-history-page] .hist-hero__actions {
        display: flex;
        flex-wrap: wrap;
        gap: .75rem;
        margin-top: clamp(1.75rem, 3vw, 2.5rem);
    }

    [data-history-page] .hist-hero__meta {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1.25rem 2rem;
        max-width: 32rem;
        margin-top: clamp(2.25rem, 5vw, 3.25rem);
        padding-top: 1.4rem;
        border-top: 1px solid rgba(255, 255, 255, .15);
    }

    [data-history-page] .hist-hero__meta dt {
        font-size: .62rem;
        font-weight: 600;
        letter-spacing: .22em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, .45);
    }

    [data-history-page] .hist-hero__meta dd {
        margin-top: .45rem;
        font-size: .92rem;
        font-weight: 600;
        color: #fff;
    }

    [data-history-page] .hist-plate {
        width: 100%;
        max-width: 30rem;
        margin: 0;
        justify-self: start;
    }

    @media (min-width: 1024px) {
        [data-history-page] .hist-plate {
            justify-self: end;
        }
    }

    [data-history-page] .hist-plate__frame {
        position: relative;
        padding: .7rem;
        border: 1px solid rgba(255, 255, 255, .2);
        border-radius: 2px;
        background: rgba(255, 255, 255, .04);
        backdrop-filter: blur(2px);
    }

    [data-history-page] .hist-plate__frame img {
        display: block;
        width: 100%;
        aspect-ratio: 4 / 3;
        object-fit: cover;
        border-radius: 1px;
        filter: grayscale(.35) contrast(1.04) sepia(.14);
    }

    [data-history-page] .hist-plate__frame::after {
        content: "";
        position: absolute;
        inset: .7rem;
        pointer-events: none;
        background: linear-gradient(180deg, transparent 42%, rgba(5, 12, 24, .82));
    }

    [data-history-page] .hist-plate__year {
        position: absolute;
        inset-inline-start: 1.6rem;
        inset-block-end: 1.5rem;
        z-index: 1;
        font-size: clamp(3rem, 6vw, 4.4rem);
        line-height: 1;
        color: #fff;
        text-shadow: 0 24px 40px rgba(0, 0, 0, .45);
    }

    [data-history-page] .hist-plate__caption {
        display: flex;
        gap: .75rem;
        margin-top: .95rem;
        font-size: .74rem;
        line-height: 1.65;
        color: rgba(255, 255, 255, .6);
    }

    [data-history-page] .hist-plate__caption span {
        flex: none;
        font-weight: 700;
        letter-spacing: .12em;
        color: var(--hist-mint);
    }

    [data-history-page] .hist-scroll {
        position: absolute;
        inset-block-end: 1.1rem;
        inset-inline-start: 50%;
        transform: translateX(-50%);
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        gap: .55rem;
        font-size: .58rem;
        font-weight: 600;
        letter-spacing: .28em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, .48);
    }

    [data-history-page] .hist-scroll span {
        display: block;
        width: 1px;
        height: 2.4rem;
        background: linear-gradient(180deg, rgba(255, 255, 255, .7), transparent);
        transform-origin: top;
        animation: hist-scroll-cue 2.6s ease-in-out infinite;
    }

    @keyframes hist-scroll-cue {
        0%, 100% { transform: scaleY(.3); opacity: .35; }
        50% { transform: scaleY(1); opacity: 1; }
    }

    @media (max-height: 720px) {
        [data-history-page] .hist-scroll { display: none; }
    }

    @media (max-width: 1023px) {
        [data-history-page] .hist-scroll { display: none; }
    }

    /* ---------- Intro / editorial prose ---------- */
    [data-history-page] .hist-intro {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: clamp(2rem, 5vw, 4.5rem);
    }

    @media (min-width: 1024px) {
        [data-history-page] .hist-intro {
            grid-template-columns: minmax(0, .82fr) minmax(0, 1.18fr);
            align-items: start;
        }

        [data-history-page] .hist-intro__aside {
            position: sticky;
            top: 7rem;
        }
    }

    [data-history-page] .hist-intro__title {
        margin-top: 1.1rem;
        font-size: clamp(2rem, 3.6vw, 3.15rem);
    }

    [data-history-page] .hist-intro__note {
        margin-top: 1.5rem;
        font-size: .68rem;
        font-weight: 600;
        letter-spacing: .18em;
        text-transform: uppercase;
        color: var(--hist-muted);
    }

    [data-history-page] .hist-prose {
        font-size: clamp(1rem, 1.15vw, 1.1rem);
        line-height: 1.95;
        color: var(--hist-body);
        max-width: 42rem;
    }

    [data-history-page] .hist-prose > * + * {
        margin-top: 1.4em;
    }

    [data-history-page] .hist-prose > p:first-of-type {
        font-size: clamp(1.12rem, 1.5vw, 1.3rem);
        line-height: 1.85;
        color: var(--hist-ink);
    }

    [data-history-page] .hist-prose > p:first-of-type::first-letter {
        float: left;
        padding-inline-end: .12em;
        padding-block-start: .06em;
        font-family: var(--hist-serif);
        font-size: 3.5em;
        font-weight: 500;
        line-height: .8;
        color: var(--hist-emerald);
    }

    [dir='rtl'] [data-history-page] .hist-prose > p:first-of-type::first-letter {
        float: none;
        padding: 0;
        font-size: inherit;
        font-family: inherit;
        line-height: inherit;
        color: inherit;
    }

    [data-history-page] .hist-prose h2,
    [data-history-page] .hist-prose h3 {
        font-family: var(--hist-serif);
        font-weight: 500;
        letter-spacing: -.01em;
        color: var(--hist-ink);
        line-height: 1.25;
        margin-top: 2em;
    }

    [data-history-page] .hist-prose h2 { font-size: clamp(1.5rem, 2.4vw, 2rem); }
    [data-history-page] .hist-prose h3 { font-size: clamp(1.25rem, 1.8vw, 1.5rem); }

    [data-history-page] .hist-prose ul,
    [data-history-page] .hist-prose ol {
        padding-inline-start: 1.25rem;
        display: grid;
        gap: .5rem;
    }

    [data-history-page] .hist-prose ul { list-style: disc; }
    [data-history-page] .hist-prose ol { list-style: decimal; }

    [data-history-page] .hist-prose blockquote {
        margin-inline: 0;
        padding: 1.15rem 1.35rem;
        border-inline-start: 2px solid var(--hist-mint);
        background: rgba(255, 255, 255, .6);
        font-family: var(--hist-serif);
        font-size: 1.1em;
        font-style: italic;
        color: var(--hist-ink);
    }

    [data-history-page] .hist-prose a {
        color: var(--hist-emerald);
        text-decoration: underline;
        text-underline-offset: 3px;
    }

    [data-history-page] .hist-prose img {
        border-radius: 6px;
        max-width: 100%;
        height: auto;
    }

    /* ---------- Timeline ---------- */
    [data-history-page] .hist-jump {
        display: flex;
        flex-wrap: wrap;
        gap: .5rem;
        margin-top: clamp(2rem, 4vw, 2.75rem);
        padding: 0;
        list-style: none;
    }

    [data-history-page] .hist-jump a {
        display: inline-flex;
        align-items: center;
        padding: .5rem .95rem;
        border: 1px solid var(--hist-line);
        border-radius: 999px;
        font-size: .72rem;
        font-weight: 600;
        letter-spacing: .1em;
        color: var(--hist-muted);
        transition: color .3s ease, background-color .3s ease, border-color .3s ease;
    }

    [data-history-page] .hist-jump a:hover {
        border-color: var(--hist-line-strong);
        color: var(--hist-ink);
    }

    [data-history-page] .hist-jump a.is-active {
        background: var(--hist-ink);
        border-color: var(--hist-ink);
        color: #fff;
    }

    [data-history-page] .hist-timeline {
        position: relative;
        display: flex;
        flex-direction: column;
        gap: clamp(2.25rem, 5vw, 4rem);
        margin-top: clamp(2.75rem, 6vw, 4.5rem);
    }

    [data-history-page] .hist-timeline__rail {
        position: absolute;
        inset-block: .75rem;
        inset-inline-start: 11px;
        width: 1px;
        background: var(--hist-line);
    }

    [data-history-page] .hist-timeline__rail > span {
        position: absolute;
        inset: 0;
        transform: scaleY(0);
        transform-origin: top;
        background: linear-gradient(180deg, var(--hist-emerald), var(--hist-mint));
    }

    [data-history-page] .hist-chapter {
        position: relative;
        padding-inline-start: 3rem;
    }

    [data-history-page] .hist-chapter__marker {
        position: absolute;
        inset-inline-start: 11px;
        top: 2.1rem;
        display: grid;
        place-items: center;
        transform: translateX(-50%);
    }

    [dir='rtl'] [data-history-page] .hist-chapter__marker {
        transform: translateX(50%);
    }

    [data-history-page] .hist-chapter__dot {
        display: block;
        width: .7rem;
        height: .7rem;
        border-radius: 999px;
        border: 1px solid var(--hist-line-strong);
        background: var(--hist-card);
        box-shadow: 0 0 0 6px var(--hist-paper-2);
        transition: background-color .45s ease, border-color .45s ease, box-shadow .45s ease;
    }

    [data-history-page] .hist-chapter.is-active .hist-chapter__dot {
        background: var(--hist-emerald);
        border-color: var(--hist-emerald);
        box-shadow: 0 0 0 6px var(--hist-paper-2), 0 0 0 11px rgba(15, 107, 42, .1);
    }

    [data-history-page] .hist-chapter__panel {
        position: relative;
        padding: clamp(1.5rem, 2.6vw, 2.4rem);
        border: 1px solid rgba(10, 21, 38, .08);
        border-radius: 14px;
        background: var(--hist-card);
        box-shadow: 0 26px 54px -46px rgba(10, 21, 38, .55);
        transition: transform .6s var(--hist-ease), box-shadow .6s var(--hist-ease), border-color .4s ease;
    }

    [data-history-page] .hist-chapter__panel:hover {
        transform: translateY(-4px);
        border-color: rgba(10, 21, 38, .16);
        box-shadow: 0 36px 64px -42px rgba(10, 21, 38, .42);
    }

    [data-history-page] .hist-chapter__year {
        font-size: clamp(2.3rem, 3.8vw, 3.3rem);
        line-height: 1;
        color: var(--hist-ink);
    }

    [data-history-page] .hist-chapter__year.is-word {
        font-size: clamp(1.6rem, 2.5vw, 2.15rem);
        line-height: 1.1;
        overflow-wrap: anywhere;
    }

    [data-history-page] .hist-chapter__label {
        margin-top: .85rem;
    }

    [data-history-page] .hist-chapter__title {
        margin-top: .5rem;
        font-size: clamp(1.35rem, 2vw, 1.8rem);
    }

    [data-history-page] .hist-chapter__text {
        margin-top: 1rem;
        font-size: .97rem;
        line-height: 1.85;
        color: var(--hist-body);
    }

    [data-history-page] .hist-chapter__figure {
        margin: 1.5rem 0 0;
    }

    [data-history-page] .hist-chapter__figure img {
        display: block;
        width: 100%;
        aspect-ratio: 16 / 10;
        object-fit: cover;
        border-radius: 8px;
        filter: saturate(.9) contrast(1.02);
        transition: filter .6s ease, transform 1s var(--hist-ease);
    }

    [data-history-page] .hist-chapter__figure:hover img {
        filter: saturate(1.04);
        transform: scale(1.01);
    }

    [data-history-page] .hist-chapter__figure figcaption {
        margin-top: .65rem;
        font-size: .68rem;
        font-weight: 600;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: var(--hist-muted);
    }

    [data-history-page] .hist-tags {
        display: flex;
        flex-wrap: wrap;
        gap: .4rem;
        margin-top: 1.35rem;
        padding: 0;
        list-style: none;
    }

    [data-history-page] .hist-tags li {
        padding: .32rem .7rem;
        border: 1px solid rgba(15, 107, 42, .16);
        border-radius: 999px;
        background: rgba(15, 107, 42, .06);
        font-size: .66rem;
        font-weight: 600;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--hist-emerald);
    }

    @media (min-width: 1024px) {
        [data-history-page] .hist-timeline__rail {
            inset-inline-start: 50%;
        }

        [data-history-page] .hist-chapter {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            column-gap: clamp(3rem, 6vw, 6rem);
            padding-inline-start: 0;
        }

        [data-history-page] .hist-chapter__marker {
            inset-inline-start: 50%;
            top: 2.45rem;
        }

        [data-history-page] .hist-chapter.is-left .hist-chapter__panel { grid-column: 1; }
        [data-history-page] .hist-chapter.is-right .hist-chapter__panel { grid-column: 2; }
    }

    /* ---------- President / story ---------- */
    [data-history-page] .hist-president {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: clamp(2.25rem, 5vw, 4.5rem);
    }

    @media (min-width: 1024px) {
        [data-history-page] .hist-president {
            grid-template-columns: minmax(0, .78fr) minmax(0, 1.22fr);
            align-items: center;
        }
    }

    [data-history-page] .hist-president__media {
        position: relative;
        margin: 0;
        max-width: 25rem;
    }

    [data-history-page] .hist-president__frame {
        position: relative;
        padding: .7rem;
        border: 1px solid var(--hist-line);
        background: var(--hist-card);
    }

    [data-history-page] .hist-president__frame::before {
        content: "";
        position: absolute;
        inset-block-start: -1rem;
        inset-inline-end: -1rem;
        width: 48%;
        height: 48%;
        border-block-start: 1px solid var(--hist-mint);
        border-inline-end: 1px solid var(--hist-mint);
        pointer-events: none;
    }

    [data-history-page] .hist-president__frame img {
        display: block;
        width: 100%;
        aspect-ratio: 4 / 5;
        object-fit: cover;
        object-position: center 18%;
        filter: grayscale(.22) contrast(1.03);
    }

    [data-history-page] .hist-president__tag {
        position: absolute;
        inset-block-end: 1.4rem;
        inset-inline-start: -.9rem;
        padding: .65rem .95rem;
        background: var(--hist-ink);
        color: #fff;
        font-size: .62rem;
        font-weight: 600;
        letter-spacing: .18em;
        text-transform: uppercase;
    }

    [data-history-page] .hist-president__caption {
        display: flex;
        flex-direction: column;
        gap: .12rem;
        margin-top: 1.1rem;
        font-size: .78rem;
        color: var(--hist-muted);
    }

    [data-history-page] .hist-president__caption strong {
        font-size: .86rem;
        font-weight: 700;
        color: var(--hist-ink);
    }

    [data-history-page] .hist-president__body .hist-display {
        margin-top: 1.1rem;
        font-size: clamp(1.9rem, 3.4vw, 3rem);
    }

    [data-history-page] .hist-quote {
        margin: 1.5rem 0 0;
        padding-inline-start: 1.25rem;
        border-inline-start: 2px solid var(--hist-mint);
        font-family: var(--hist-serif);
        font-style: italic;
        font-size: clamp(1.15rem, 1.8vw, 1.4rem);
        line-height: 1.65;
        color: var(--hist-ink);
    }

    html[lang='ar'] [data-history-page] .hist-quote {
        font-family: 'Cairo', sans-serif;
        font-style: normal;
    }

    [data-history-page] .hist-president__body > p {
        margin-top: 1.1rem;
        font-size: .97rem;
        line-height: 1.9;
        color: var(--hist-body);
    }

    [data-history-page] .hist-president__sign {
        display: flex;
        align-items: center;
        gap: .9rem;
        margin-top: 1.75rem;
    }

    [data-history-page] .hist-president__sign svg {
        width: 4.5rem;
        height: auto;
        color: var(--hist-emerald);
    }

    [data-history-page] .hist-president__sign p {
        font-size: .78rem;
        line-height: 1.5;
        color: var(--hist-muted);
    }

    [data-history-page] .hist-president__sign strong {
        display: block;
        font-size: .86rem;
        color: var(--hist-ink);
    }

    /* ---------- Legacy numbers ---------- */
    [data-history-page] .hist-legacy {
        position: relative;
        overflow: hidden;
        padding-block: clamp(4rem, 8vw, 6.5rem);
        background: var(--hist-ink);
        color: #fff;
    }

    [data-history-page] .hist-legacy::before {
        content: "";
        position: absolute;
        inset: 0;
        background:
            radial-gradient(65% 60% at 82% 8%, rgba(130, 230, 130, .11), transparent 62%),
            radial-gradient(60% 65% at 4% 100%, rgba(33, 79, 149, .38), transparent 68%);
    }

    [data-history-page] .hist-legacy .hist-shell {
        position: relative;
    }

    [data-history-page] .hist-legacy .hist-kicker { color: var(--hist-mint); }

    [data-history-page] .hist-legacy__title {
        margin-top: 1.1rem;
        font-size: clamp(2rem, 3.6vw, 3rem);
        color: #fff;
    }

    [data-history-page] .hist-legacy__desc {
        max-width: 34rem;
        margin-top: 1rem;
        font-size: 1rem;
        line-height: 1.85;
        color: rgba(255, 255, 255, .65);
    }

    [data-history-page] .hist-legacy__grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        margin-top: clamp(2.5rem, 5vw, 3.75rem);
        border-top: 1px solid rgba(255, 255, 255, .14);
    }

    [data-history-page] .hist-legacy__item {
        display: flex;
        flex-direction: column-reverse;
        gap: .7rem;
        padding: clamp(1.5rem, 3vw, 2rem) clamp(.9rem, 2vw, 1.4rem);
        border-bottom: 1px solid rgba(255, 255, 255, .14);
    }

    [data-history-page] .hist-legacy__item dt {
        font-size: .62rem;
        font-weight: 600;
        letter-spacing: .18em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, .5);
    }

    [data-history-page] .hist-legacy__item dd {
        font-size: clamp(2.3rem, 4.6vw, 3.8rem);
        line-height: 1;
        color: #fff;
        font-variant-numeric: tabular-nums;
    }

    @media (min-width: 900px) {
        [data-history-page] .hist-legacy__grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        [data-history-page] .hist-legacy__item {
            border-bottom: 0;
            border-inline-start: 1px solid rgba(255, 255, 255, .14);
        }

        [data-history-page] .hist-legacy__item:first-child {
            border-inline-start: 0;
            padding-inline-start: 0;
        }
    }

    /* ---------- Gallery ---------- */
    [data-history-page] .hist-gallery {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: clamp(.75rem, 1.4vw, 1.15rem);
        margin-top: clamp(2.25rem, 4vw, 3.25rem);
    }

    [data-history-page] .hist-gallery__item {
        position: relative;
        display: block;
        overflow: hidden;
        padding: 0;
        border: 0;
        border-radius: 5px;
        background: var(--hist-ink);
        cursor: zoom-in;
    }

    [data-history-page] .hist-gallery__item img {
        display: block;
        width: 100%;
        height: 100%;
        aspect-ratio: 4 / 3;
        object-fit: cover;
        filter: saturate(.88) contrast(1.02);
        transition: transform 1.15s var(--hist-ease), filter .6s ease;
    }

    [data-history-page] .hist-gallery__item:hover img {
        transform: scale(1.045);
        filter: saturate(1.02);
    }

    [data-history-page] .hist-gallery__overlay {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.05rem 1.15rem;
        background: linear-gradient(180deg, transparent 42%, rgba(6, 14, 28, .78));
        color: #fff;
        opacity: 0;
        transform: translateY(8px);
        transition: opacity .45s ease, transform .45s var(--hist-ease);
    }

    [data-history-page] .hist-gallery__item:hover .hist-gallery__overlay,
    [data-history-page] .hist-gallery__item:focus-visible .hist-gallery__overlay {
        opacity: 1;
        transform: none;
    }

    [data-history-page] .hist-gallery__title {
        font-size: .74rem;
        font-weight: 600;
        letter-spacing: .12em;
        text-transform: uppercase;
        text-align: start;
    }

    [data-history-page] .hist-gallery__overlay svg {
        width: 1rem;
        height: 1rem;
        flex: none;
    }

    @media (min-width: 768px) {
        [data-history-page] .hist-gallery {
            grid-template-columns: minmax(0, 1.55fr) minmax(0, 1fr);
            grid-template-rows: auto auto;
        }

        [data-history-page] .hist-gallery__item:nth-child(1) { grid-column: 1; grid-row: 1; }
        [data-history-page] .hist-gallery__item:nth-child(2) { grid-column: 2; grid-row: 1 / span 2; }
        [data-history-page] .hist-gallery__item:nth-child(3) { grid-column: 1; grid-row: 2; }

        [data-history-page] .hist-gallery__item:nth-child(1) img { aspect-ratio: 16 / 9; }
        [data-history-page] .hist-gallery__item:nth-child(2) img { aspect-ratio: 3 / 4; height: 100%; object-position: center 20%; }
        [data-history-page] .hist-gallery__item:nth-child(3) img { aspect-ratio: 16 / 7; }
    }

    @media (hover: none) {
        [data-history-page] .hist-gallery__overlay {
            opacity: 1;
            transform: none;
        }
    }

    /* ---------- Lightbox ---------- */
    [data-history-page] .hist-lightbox {
        position: fixed;
        inset: 0;
        z-index: 80;
        display: grid;
        place-items: center;
        padding: clamp(1rem, 4vw, 3rem);
        background: rgba(5, 12, 24, .95);
        opacity: 0;
        visibility: hidden;
        transition: opacity .35s ease, visibility 0s linear .35s;
    }

    [data-history-page] .hist-lightbox.is-open {
        opacity: 1;
        visibility: visible;
        transition: opacity .35s ease, visibility 0s;
    }

    [data-history-page] .hist-lightbox__figure {
        display: flex;
        flex-direction: column;
        gap: .9rem;
        max-width: min(68rem, 92vw);
        max-height: 84vh;
        margin: 0;
        transform: scale(.97);
        transition: transform .45s var(--hist-ease);
    }

    [data-history-page] .hist-lightbox.is-open .hist-lightbox__figure {
        transform: none;
    }

    [data-history-page] .hist-lightbox__figure img {
        max-width: 100%;
        max-height: 72vh;
        margin-inline: auto;
        object-fit: contain;
        border-radius: 3px;
    }

    [data-history-page] .hist-lightbox__figure figcaption {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        font-size: .72rem;
        font-weight: 600;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, .72);
    }

    [data-history-page] .hist-lightbox__btn {
        position: absolute;
        display: grid;
        place-items: center;
        width: 2.9rem;
        height: 2.9rem;
        border: 1px solid rgba(255, 255, 255, .24);
        border-radius: 999px;
        background: rgba(255, 255, 255, .06);
        color: #fff;
        backdrop-filter: blur(6px);
        transition: background-color .3s ease, border-color .3s ease, transform .3s ease;
    }

    [data-history-page] .hist-lightbox__btn:hover {
        background: rgba(255, 255, 255, .16);
        border-color: rgba(255, 255, 255, .5);
    }

    [data-history-page] .hist-lightbox__btn svg {
        width: 1.1rem;
        height: 1.1rem;
    }

    [dir='rtl'] [data-history-page] .hist-lightbox__btn svg {
        transform: scaleX(-1);
    }

    [data-history-page] .hist-lightbox__close {
        inset-block-start: clamp(1rem, 3vw, 2rem);
        inset-inline-end: clamp(1rem, 3vw, 2rem);
    }

    [data-history-page] .hist-lightbox__prev,
    [data-history-page] .hist-lightbox__next {
        inset-block-start: 50%;
        transform: translateY(-50%);
    }

    [data-history-page] .hist-lightbox__prev { inset-inline-start: clamp(.4rem, 2vw, 1.5rem); }
    [data-history-page] .hist-lightbox__next { inset-inline-end: clamp(.4rem, 2vw, 1.5rem); }

    [data-history-page] .hist-lightbox__prev:hover,
    [data-history-page] .hist-lightbox__next:hover {
        transform: translateY(-50%) scale(1.06);
    }

    @media (max-width: 640px) {
        [data-history-page] .hist-lightbox__figure {
            padding-block-end: 3.9rem;
        }

        [data-history-page] .hist-lightbox__figure img {
            max-height: 58vh;
        }

        [data-history-page] .hist-lightbox__prev,
        [data-history-page] .hist-lightbox__next {
            inset-block-start: auto;
            inset-block-end: max(1rem, env(safe-area-inset-bottom));
            transform: none;
        }

        [data-history-page] .hist-lightbox__prev { left: calc(50% - 3.5rem); right: auto; }
        [data-history-page] .hist-lightbox__next { right: calc(50% - 3.5rem); left: auto; }

        [dir='rtl'] [data-history-page] .hist-lightbox__prev { right: calc(50% - 3.5rem); left: auto; }
        [dir='rtl'] [data-history-page] .hist-lightbox__next { left: calc(50% - 3.5rem); right: auto; }

        [data-history-page] .hist-lightbox__prev:hover,
        [data-history-page] .hist-lightbox__next:hover {
            transform: scale(1.06);
        }
    }

    /* ---------- Closing call to action ---------- */
    [data-history-page] .hist-cta {
        position: relative;
        overflow: hidden;
        padding-block: clamp(4rem, 8vw, 6.5rem);
        background: var(--hist-ink);
        color: #fff;
    }

    [data-history-page] .hist-cta__bg {
        position: absolute;
        inset: 0;
        pointer-events: none;
    }

    [data-history-page] .hist-cta__bg img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: .16;
        filter: grayscale(1);
    }

    [data-history-page] .hist-cta__shade {
        position: absolute;
        inset: 0;
        background: linear-gradient(100deg, rgba(6, 14, 28, .97) 30%, rgba(6, 14, 28, .78) 100%);
    }

    [data-history-page] .hist-cta__inner {
        position: relative;
        max-width: 46rem;
    }

    [data-history-page] .hist-cta .hist-kicker { color: var(--hist-mint); }

    [data-history-page] .hist-cta__title {
        margin-top: 1.1rem;
        font-size: clamp(2.1rem, 4.2vw, 3.5rem);
        color: #fff;
    }

    [data-history-page] .hist-cta__desc {
        margin-top: 1.15rem;
        max-width: 34rem;
        font-size: 1rem;
        line-height: 1.85;
        color: rgba(255, 255, 255, .68);
    }

    [data-history-page] .hist-cta__actions {
        display: flex;
        flex-wrap: wrap;
        gap: .75rem;
        margin-top: 2rem;
    }

    /* ---------- Motion ---------- */
    @media (prefers-reduced-motion: no-preference) {
        [data-history-page] .hist-hero__copy > * {
            animation: hist-fade-up 1s var(--hist-ease) both;
        }

        [data-history-page] .hist-hero__copy > *:nth-child(1) { animation-delay: .05s; }
        [data-history-page] .hist-hero__copy > *:nth-child(2) { animation-delay: .14s; }
        [data-history-page] .hist-hero__copy > *:nth-child(3) { animation-delay: .26s; }
        [data-history-page] .hist-hero__copy > *:nth-child(4) { animation-delay: .38s; }
        [data-history-page] .hist-hero__copy > *:nth-child(5) { animation-delay: .5s; }

        [data-history-page] .hist-plate {
            animation: hist-plate-in 1.25s var(--hist-ease) .42s both;
        }

        [data-history-page] .hist-hero__backdrop img {
            animation: hist-slow-zoom 20s ease-out both;
        }

        [data-history-page] .hist-crumbs {
            animation: hist-fade-up 1s var(--hist-ease) both;
        }
    }

    @keyframes hist-fade-up {
        from { opacity: 0; transform: translate3d(0, 26px, 0); }
        to { opacity: 1; transform: none; }
    }

    @keyframes hist-plate-in {
        from { opacity: 0; transform: translate3d(0, 34px, 0) scale(.985); }
        to { opacity: 1; transform: none; }
    }

    @keyframes hist-slow-zoom {
        from { transform: scale(1.14); }
        to { transform: scale(1.03); }
    }

    .has-js [data-history-page] [data-reveal] {
        opacity: 0;
        transform: translate3d(0, 28px, 0);
        transition: opacity .95s var(--hist-ease), transform .95s var(--hist-ease);
        transition-delay: var(--reveal-delay, 0ms);
        will-change: opacity, transform;
    }

    .has-js [data-history-page] [data-reveal].is-visible {
        opacity: 1;
        transform: none;
        will-change: auto;
    }

    @media (prefers-reduced-motion: reduce) {
        .has-js [data-history-page] [data-reveal] {
            opacity: 1 !important;
            transform: none !important;
            transition: none !important;
        }

        [data-history-page] *,
        [data-history-page] *::before,
        [data-history-page] *::after {
            animation-duration: .001ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: .001ms !important;
        }
    }

    /* The layout reserves 4rem of body background under <main>; keep it inky
       so the closing section flows into the footer without a white band. */
    body:has([data-history-page]) {
        background-color: #0a1526;
    }

    [data-history-page] :focus-visible {
        outline: 2px solid var(--hist-emerald);
        outline-offset: 3px;
    }
    [data-history-page] .hist-hero :focus-visible,
    [data-history-page] .hist-legacy :focus-visible,
    [data-history-page] .hist-cta :focus-visible,
    [data-history-page] .hist-lightbox :focus-visible {
        outline-color: var(--hist-mint);
    }
</style>
