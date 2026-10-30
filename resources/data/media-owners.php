<?php

$brand = static fn (string $name, ?string $logo = null): array => array_filter(
    ['name' => $name, 'logo' => $logo],
    static fn ($value) => $value !== null,
);

$category = static fn (string $label, array $brands): array => ['label' => $label, 'brands' => $brands];

return [
    [
        'name' => 'Media Prima Berhad', 'logo' => 'media-prima-trim.webp', 'mark' => 'MEDIA PRIMA', 'accent' => '#e52735',
        'categories' => [
            $category('Television networks', [$brand('TV3', 'mp-tv3.png'), $brand('TV9', 'mp-tv9.png'), $brand('8TV', 'mp-8tv.png'), $brand('ntv7', 'mp-ntv7.png')]),
            $category('Radio', [$brand('Hot FM', 'mp-hotfm-v2.png'), $brand('Kool FM', 'mp-koolfm.png'), $brand('Molek FM', 'mp-molekfm.png'), $brand('Fly FM', 'mp-flyfm.png'), $brand('Eight FM', 'mp-ei8htfm.png')]),
            $category('Digital publishing', [$brand('REV Media', 'mp-rev.png'), $brand('OHBULAN!', 'mp-ob.png'), $brand('SirapLimau', 'mp-siraplimau.png'), $brand('SAYS', 'mp-says.png'), $brand('Mashable South East Asia', 'mp-mashable-sea-v2.png'), $brand('Rojak', 'mp-rj.png'), $brand('Says Seismik', 'mp-seismik.png'), $brand('Juice', 'mp-juice.png'), $brand('XTRA', 'mp-xtra.png'), $brand('MyResipi', 'mp-myresipi.png')]),
            $category('Out-of-home', [$brand('Big Tree', 'mp-bigtree.png'), $brand('UPD', 'mp-upd.png'), $brand('Kurnia Outdoor', 'mp-kurnia.png'), $brand('BTSJ', 'mp-btsj.png'), $brand('TRC', 'mp-trc.png'), $brand('GOTCHA', 'mp-gotcha.png')]),
            $category('TV home shopping', [$brand('WowShop', 'mp-wowshop.png')]),
            $category('Streaming', [$brand('Tonton', 'mp-tonton-v2.png')]),
            $category('News and press', [$brand('Berita Harian', 'mp-bh.png'), $brand('Harian Metro', 'mp-hmetro.png'), $brand('NSTP', 'mp-nstp.png')]),
            $category('Apps', [$brand('Waktu Solat', 'waktu-solat-app.jpg')]),
        ],
    ],
    [
        'name' => 'Astro Media Solutions', 'logo' => 'astro-media-solutions.webp', 'mark' => 'astro media solutions', 'tone' => 'astro', 'accent' => '#be2ca9',
        'categories' => [
            $category('Television', [$brand('Astro Awani', 'astro-awani.webp'), $brand('Astro Ria', 'astro-tv-ria.webp'), $brand('Astro Prima', 'astro-tv-prima.webp'), $brand('Astro Oasis', 'astro-tv-oasis.webp'), $brand('Astro Vanavil', 'astro-tv-vaanavil.webp'), $brand('Astro Tutor TV', 'astro-tv-tutor-tv.webp')]),
            $category('Radio', [$brand('ERA', 'astro-radio-era.webp'), $brand('Sinar', 'astro-radio-sinar.webp'), $brand('THR Gegar', 'astro-radio-gegar.webp'), $brand('Zayan', 'astro-radio-zayan.webp'), $brand('Lite FM', 'astro-radio-lite.webp'), $brand('Mix FM', 'astro-radio-mix.webp'), $brand('Hitz FM', 'astro-radio-hitz.webp'), $brand('MY FM', 'astro-radio-my.webp'), $brand('Melody FM', 'astro-radio-melody.webp'), $brand('GoXuan', 'astro-radio-goxuan.webp'), $brand('Raaga', 'astro-radio-raaga.webp')]),
            $category('Digital', [$brand('Awani', 'astro-awani.webp'), $brand('Gempak', 'astro-digital-gempak.webp'), $brand('RojakDaily', 'rojakdaily.png'), $brand('Stadium Astro', 'astro-digital-stadium.webp'), $brand('Pa & Ma', 'ideaktiv-pama.webp'), $brand('Remaja', 'ideaktiv-remaja.webp'), $brand('Rasa', 'ideaktiv-rasa.webp'), $brand('Wanita'), $brand('Keluarga', 'ideaktiv-keluarga.webp')]),
            $category('Celebrities and KOLs', [$brand('Thinker Studios'), $brand('Rocketfuel')]),
            $category('OTT streaming', [$brand('sooka', 'sooka-logo.png')]),
        ],
    ],
    [
        'name' => 'Radio Televisyen Malaysia (RTM)', 'logo' => 'rtm-trim.webp', 'mark' => 'RTM', 'accent' => '#178264',
        'categories' => [
            $category('Television', [$brand('TV1', 'rtm-tv1.png'), $brand('TV2', 'rtm-tv2.png'), $brand('OKEY', 'rtm-okey.png')]),
            $category('National radio', [$brand('Nasional FM', 'rtm-nasional-fm.png'), $brand('Klasik FM', 'rtm-radio-klasik.png'), $brand('TraXX FM', 'rtm-traxx-fm.png'), $brand('Ai FM', 'rtm-ai-fm.png'), $brand('Minnal FM', 'rtm-minnal-fm.png'), $brand('Asyik FM', 'rtm-asyik-fm.png')]),
            $category('State radio', [$brand('Sabah FM', 'rtm-sabah-fm.png'), $brand('Sarawak FM', 'rtm-sarawak-fm.png'), $brand('Perlis FM', 'rtm-perlis-fm.png'), $brand('Kedah FM', 'rtm-kedah-fm.png'), $brand('Mutiara FM', 'rtm-mutiara-fm.png'), $brand('Perak FM', 'rtm-perak-fm.png'), $brand('Kelantan FM', 'rtm-kelantan-fm.png'), $brand('Terengganu FM', 'rtm-terengganu-fm.png'), $brand('Melaka FM', 'rtm-melaka-fm.png'), $brand('Negeri FM', 'rtm-negeri-fm.png'), $brand('Johor FM', 'rtm-johor-fm.png'), $brand('Selangor FM', 'rtm-selangor-fm.png'), $brand('Pahang FM', 'rtm-pahang-fm.png'), $brand('KL FM', 'rtm-kl-fm.png')]),
            $category('Digital', [$brand('RTMKlik', 'rtmklik-app.png')]),
        ],
    ],
    [
        'name' => 'Al Hijrah Media Corporation', 'logo' => 'alhijrah-trim.webp', 'mark' => 'ALHIJRAH', 'accent' => '#713d9b',
        'categories' => [$category('Television and digital', [$brand('TV Al Hijrah', 'alhijrah.webp'), $brand('AlHijrah Plus', 'alhijrah-plus-app.jpg')])],
    ],
    [
        'name' => 'Bernama', 'logo' => 'bernama-trim.webp', 'mark' => 'BERNAMA', 'accent' => '#234b99',
        'categories' => [$category('News platforms', [$brand('Bernama TV', 'bernama-tv.png'), $brand('Bernama Radio', 'bernama-radio.png'), $brand('Bernama Digital')])],
    ],
    [
        'name' => 'TVS (TV Sarawak)', 'logo' => 'tvs.png', 'mark' => 'TVS', 'accent' => '#bb293c',
        'categories' => [$category('Television', [$brand('TVS', 'tvs.png')])],
    ],
    [
        'name' => 'Star Media Group', 'logo' => 'star-media-group-logo.jpg', 'mark' => 'STAR MEDIA GROUP', 'accent' => '#c6213b',
        'categories' => [
            $category('News and publishing', [$brand('The Star', 'star-the-star.png'), $brand('mStar', 'star-mstar.png')]),
            $category('Radio', [$brand('Suria FM', 'star-suria.png'), $brand('98.8 FM', 'star-988.png')]),
        ],
    ],
    [
        'name' => 'Karangkraf', 'logo' => 'karangkraf-rebrand-2025.webp', 'mark' => 'KARANGKRAF', 'visual' => 'karangkraf', 'accent' => '#7b37ca',
        'categories' => [
            $category('News', [$brand('Sinar Harian', 'sinar-harian.svg'), $brand('SinarPlus', 'sinar-plus.svg'), $brand('SinarDaily', 'sinar-daily.svg'), $brand('SinarBestari', 'sinar-bestari.svg')]),
            $category('IdeAktif', [
                $brand('IdeAktif', 'nu-ideaktiv.png'), $brand('Impiana', 'ideaktiv-impiana.webp'), $brand('Rasa', 'ideaktiv-rasa.webp'), $brand('Remaja', 'ideaktiv-remaja.webp'), $brand('Nona', 'ideaktiv-nona.webp'), $brand('Pa&Ma', 'ideaktiv-pama.webp'), $brand('Hijabista', 'ideaktiv-hijabista.webp'), $brand('Maskulin', 'ideaktiv-maskulin.webp'), $brand('Mingguan Wanita', 'ideaktiv-mingguan-wanita.webp'), $brand('Libur', 'ideaktiv-libur.webp'), $brand('Umpan', 'ideaktiv-umpan.webp'), $brand('Pengantin', 'ideaktiv-pengantin.webp'), $brand('Media Hiburan', 'ideaktiv-media-hiburan.webp'),
            ]),
        ],
    ],
    [
        'name' => 'Outdoor Media', 'mark' => 'OUTDOOR MEDIA', 'accent' => '#238174',
        'categories' => [$category('Outdoor operators', [$brand('Seni Jaya', 'seni-jaya.svg'), $brand('Novus Media', 'novus-media.jpg'), $brand('Harumi Jaya', 'harumi-jaya.jpg'), $brand('EDSB Outdoor', 'edsb-outdoor.png')])],
    ],
    [
        'name' => 'Cinema Ads', 'mark' => 'CINEMA ADS', 'accent' => '#a33047',
        'categories' => [$category('Cinema networks', [$brand('GSC', 'gsc.png'), $brand('TGV', 'tgv.png')])],
    ],
    [
        'name' => 'KLIA', 'logo' => 'malaysia-airports.png', 'mark' => 'KLIA', 'accent' => '#b98b2e',
        'categories' => [$category('Airport terminals', [$brand('Terminal 1'), $brand('Terminal 2')])],
    ],
    [
        'name' => 'KTM Advertising Space', 'logo' => 'ktmb.png', 'mark' => 'KTM ADVERTISING', 'accent' => '#cf342f',
        'categories' => [$category('Rail media', [$brand('KTM Train'), $brand('KL Sentral Station')])],
    ],
];
