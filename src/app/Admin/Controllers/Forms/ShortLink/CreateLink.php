<?php

namespace App\Admin\Controllers\Forms\ShortLink;

use Illuminate\Http\Request;
use App\Models\Enum\OrderMethod;
use Encore\Admin\Widgets\StepForm;

class CreateLink extends StepForm
{
    /**
     * The form title.
     *
     * @var string
     */
    public $title = 'Генератор ссылок';

    /**
     * The description of form.
     *
     * @var string
     */
    public $description = 'Генератор ссылок';

    /**
     * Handle the form request.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request)
    {
        // dd($request->all());

        admin_success('данные:' . json_encode($request->all(), JSON_UNESCAPED_SLASHES));

        return back();
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        $this->url('init_link', 'Исходная ссылка')
            ->required()
            ->placeholder('https://barocco.by...')
            ->rules(['url', 'required']);

        $this->select('source', 'Способ заказа')
            ->options(OrderMethod::getOptionsForSelect());

        $this->text('out_link', 'Сгенерированная ссылка')
            ->required()
            ->placeholder('Сгенерированная ссылка')
            ->rules(['url', 'required'])
            ->setScript($this->getScript());
    }

    /**
     * Js code
     */
    protected function getScript(): string
    {
        $utms = [];
        foreach (OrderMethod::getValues() as $value) {
            $utms[$value] = OrderMethod::getUtmSources($value);
        }
        $utms = json_encode($utms);

        return <<<JS
        let state = { initLinkInput: '', sourceSelect: null };
        const utms = $utms;

        const outLinkInput = document.querySelector('input[name="out_link"]');
        const generateLink = function (state) {
            try {
                const url = new URL(state.initLinkInput);
                const utm = utms[state.sourceSelect] ?? null;

                url.searchParams.delete('utm_source');
                url.searchParams.delete('utm_medium');
                url.searchParams.delete('utm_campaign');

                if (utm) {
                    url.searchParams.append('utm_source', utm[0]);
                    url.searchParams.append('utm_medium', utm[1]);
                    url.searchParams.append('utm_campaign', utm[2]);
                }

                outLinkInput.value = url.href;
            } catch (error) {
                return;
            }
        }

        const initLinkInput = document.querySelector('input[name="init_link"]');
        initLinkInput.addEventListener('paste', (event) => {
            state.initLinkInput = (event.clipboardData || window.clipboardData).getData('text');
            generateLink(state);
        });
        initLinkInput.addEventListener('keyup', (event) => {
            if (!event.key || (event.key.length === 1 && event.keyCode >= 48 && event.keyCode <= 90) || event.keyCode === 8 || event.keyCode === 46) {
                state.initLinkInput = event.target.value;
                generateLink(state);
            }
        });

        const sourceSelect = document.querySelector('select[name="source"]');
        sourceSelect.onchange = (event) => {
            state.sourceSelect = event.target.value;
            generateLink(state);
        };
        JS;
    }
}
