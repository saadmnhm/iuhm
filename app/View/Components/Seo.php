<?php

namespace App\View\Components;

use App\Support\Seo\SeoData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

class Seo extends Component
{
    /**
     * Note: the property cannot be named `$data` because Laravel reserves that
     * name on class components (it is filtered out of the public view data).
     */
    public SeoData $seo;

    public function __construct(SeoData|Model|null $data = null, ?string $title = null, ?string $description = null)
    {
        if ($data instanceof Model) {
            $this->seo = SeoData::forModel($data);
        } elseif ($data instanceof SeoData) {
            $this->seo = $data;
        } else {
            $this->seo = SeoData::forPage($title ?? (string) config('app.name'), $description);
        }
    }

    public function render()
    {
        return view('components.seo');
    }

    /**
     * Encode JSON-LD safely for inline output.
     */
    public function jsonLd(): string
    {
        return (string) json_encode(
            $this->seo->structuredData(),
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG
        );
    }
}
