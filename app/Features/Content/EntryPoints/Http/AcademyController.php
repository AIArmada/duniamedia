<?php

declare(strict_types=1);

namespace App\Features\Content\EntryPoints\Http;

use App\Features\Content\Models\Training;
use Illuminate\View\View;

final readonly class AcademyController
{
    public function __invoke(): View
    {
        return view('pages.academy', [
            'trainings' => Training::query()->published()->get(),
            'pageTitle' => 'Academy — AI & Corporate Training | Dunia Media',
            'metaDescription' => 'Practical AI, content and business-technology workshops for founders, marketing teams and corporate groups. Programmes are arranged on request.',
        ]);
    }
}
