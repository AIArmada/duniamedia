#!/usr/bin/env python3
"""Generate the bespoke owner, channel and priority-deck Blade views."""

import os
import textwrap

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
VIEWS = os.path.join(ROOT, 'resources/views/pages')

OWNERS = {
    'astro': {
        'title': 'Pay-TV, streaming, radio and data',
        'body': 'Astro Media Solutions reaches Malaysian homes through pay-TV, sooka, radio and digital brands. The group combines premium content, addressable audiences and first-party data, making it the natural home for launch campaigns, sustained reach and brand-safe entertainment.',
        'features': [
            ('Television', 'Astro Awani, Astro Ria, Astro Prima, Astro Oasis, Astro Vanavil and Astro Tutor TV.'),
            ('Radio', 'ERA, Sinar, THR Gegar, Zayan, Lite FM, Mix FM, Hitz FM, MY FM, Melody FM, GoXuan and Raaga.'),
            ('Digital and OTT', 'Awani, Gempak, sooka and the group’s digital publishing footprint.'),
            ('Talent and on-ground', 'Creator-led formats, celebrity talent and activation teams.'),
        ],
    },
    'rtm': {
        'title': 'The national public broadcaster',
        'body': 'Radio Televisyen Malaysia reaches the widest free-to-air audience in the country through TV1, TV2, OKEY, national radio, state radio and RTMKlik. It is the right partner for national service messages, government campaigns and mass-reach brand activity.',
        'features': [
            ('Television', 'TV1, TV2 and OKEY across news, entertainment, education and family programming.'),
            ('National radio', 'Nasional FM, Klasik FM, TraXX FM, Ai FM, Minnal FM and Asyik FM.'),
            ('State radio', 'Fourteen state stations connecting brands to local audiences.'),
            ('Digital', 'RTMKlik and the broadcaster’s online news and video extensions.'),
        ],
    },
    'karangkraf': {
        'title': 'Malaysia’s Malay publishing group',
        'body': 'Karangkraf connects brands with Malay readers and families through Sinar Harian, SinarPlus, Sinar Bestari, Sinar Daily and the IdeAktif magazine portfolio. Print, digital and social work together as one editorial system.',
        'features': [
            ('News', 'Sinar Harian, SinarPlus and Sinar Daily.'),
            ('Education and family', 'Sinar Bestari and the IdeAktif titles.'),
            ('Print formats', 'Display, advertorials, classifieds and special packages.'),
            ('Digital', 'News portals, social publishing and branded content.'),
        ],
    },
    'star-media-group': {
        'title': 'Publishing and radio across Malaysia',
        'body': 'Star Media Group brings together The Star, mStar, Suria FM and 988 FM. The group spans English and Malay publishing with Chinese and Malay radio, giving brands a rare mix of news credibility, entertainment and drive-time reach.',
        'features': [
            ('Publishing', 'The Star, mStar and the group’s digital news platforms.'),
            ('Radio', 'Suria FM for Malay families and 988 FM for Chinese audiences.'),
            ('Digital', 'News, lifestyle and social extensions across the group.'),
            ('Integrated', 'Cross-platform packages across print, digital and audio.'),
        ],
    },
    'bernama': {
        'title': 'The national news agency',
        'body': 'Bernama is Malaysia’s national news agency, with a wire service, Bernama.com, Bernama TV and Bernama Radio. It provides credible news environments for corporate announcements, government messages and brand storytelling.',
        'features': [
            ('Wire and portal', 'National news distribution and BERNAMA.com.'),
            ('Television', 'Bernama TV news, programmes and interviews.'),
            ('Radio', 'Bernama Radio news, talk and branded segments.'),
            ('Production', 'Editorial, video, photography and archival services.'),
        ],
    },
    'klia': {
        'title': 'Airport media at Malaysia’s gateway',
        'body': 'KLIA reaches international and domestic travellers across Terminal 1, Terminal 2 and the ERL rail network. Static lightboxes, digital screens, wraps, baggage carousels and transit formats put brands in front of a high-value travelling audience.',
        'features': [
            ('Terminal 1', 'Arrivals, departures, contact pier and satellite building inventory.'),
            ('Terminal 2', 'International departures, arrivals and the total coverage network.'),
            ('ERL', 'KLIA Ekspres station and in-train digital screens.'),
            ('Formats', 'Static, digital, wraps, carousels and experiential.'),
        ],
    },
    'ktm': {
        'title': 'Rail and station media',
        'body': 'KTM and KL Sentral connect brands with commuters across the Klang Valley and northern rail corridors. Station domination, train wraps, platform formats and the KL Sentral East and West wings deliver repeated exposure to a mobile urban audience.',
        'features': [
            ('KTM', 'Train and station advertising across the network.'),
            ('KL Sentral', 'East and West Wing station sponsorship and production.'),
            ('Formats', 'Wraps, panels, pillars, turnstiles and digital screens.'),
            ('Audience', 'Daily commuters, transit users and city workers.'),
        ],
    },
    'tvs': {
        'title': 'Sarawak’s own television station',
        'body': 'TVS is Sarawak’s free-to-air television station, combining local news, entertainment, culture and sport. It gives brands a credible route into East Malaysian audiences, with additional outdoor inventory in Miri.',
        'features': [
            ('Television', 'Local news, entertainment, culture and live coverage.'),
            ('Sponsorship', 'Programme and spot-buy packages.'),
            ('Outdoor', 'Miri billboard inventory.'),
            ('Audience', 'Sarawak and East Malaysian viewers.'),
        ],
    },
    'al-hijrah': {
        'title': 'Islamic family media',
        'body': 'TV AlHijrah serves Muslim families with talkshows, magazines, religious programming and digital extensions. It is a trusted environment for values-led brands, Ramadan campaigns and family-focused messages.',
        'features': [
            ('Television', 'TV AlHijrah talkshows, magazines and religious programmes.'),
            ('Digital', 'AlHijrah Plus and social extensions.'),
            ('Seasonal', 'Ramadan and Syawal packages.'),
            ('Audience', 'Muslim families and SME audiences.'),
        ],
    },
    'specialist-media': {
        'title': 'Independent publishers and event partners',
        'body': 'The specialist roster extends Dunia Media beyond the large groups: BFM for business radio, FMT and Malaysiakini for independent digital news, The Malaysian Reserve and The Edge for business publishing, iMedia for multi-language blitzes and Nusavue for concert-led sponsorship.',
        'features': [
            ('Business', 'BFM, The Malaysian Reserve and The Edge.'),
            ('Independent digital', 'FMT and Malaysiakini.'),
            ('Multi-language', 'iMedia’s BM, Chinese and English publisher network.'),
            ('Events', 'Nusavue concert and entertainment partnerships.'),
        ],
    },
    'outdoor-media': {
        'title': 'Outdoor operator network',
        'body': 'The outdoor roster brings together specialist operators including Seni Jaya, Novus Media, Harumi Jaya and EDSB Outdoor. Inventory spans large-format billboards, transit, street furniture and digital out-of-home across Malaysia.',
        'features': [
            ('Large format', 'Prime billboards and landmark sites.'),
            ('Transit', 'Roadside, rail and commuter formats.'),
            ('Digital OOH', 'LED screens and programmatic-ready inventory.'),
            ('Operator access', 'One brief across multiple outdoor specialists.'),
        ],
    },
    'cinema-ads': {
        'title': 'Cinema advertising',
        'body': 'Cinema reaches audiences in a premium, attentive environment. Through GSC and TGV, brands can use big-screen spots, lobby formats and film-linked partnerships for launches, festive campaigns and youth-led releases.',
        'features': [
            ('GSC', 'Big-screen and lobby inventory.'),
            ('TGV', 'Cinema spots and in-cinema formats.'),
            ('Audience', 'Urban, youth and family moviegoers.'),
            ('Creative', 'Film-linked and launch-led packages.'),
        ],
    },
}

CHANNELS = {
    'media-prima-tv9': ('Mass-Malay entertainment and news', 'TV9 combines drama, entertainment, news and studio formats for Malay family audiences. It is a strong environment for household brands, FMCG and programme sponsorship.', ['Drama and entertainment', 'News and studio formats', 'Family audiences', 'Sponsorship packages']),
    'media-prima-8tv': ('Chinese-language entertainment and news', '8TV reaches urban Chinese audiences through drama, variety, news and lifestyle programming. It is a core channel for Chinese-language brand campaigns and festive moments.', ['Drama and variety', 'Chinese news', 'Lifestyle formats', 'Festive packages']),
    'media-prima-hot-fm': ('Malay youth radio and on-ground', 'Hot FM pairs Malay youth music and personality-led shows with on-ground activations and social reach. Zoomerz and station campaigns put brands directly in front of young listeners.', ['Youth radio', 'On-ground activations', 'Social amplification', 'Talent-led formats']),
    'media-prima-wowshop': ('24-hour TV commerce', 'WowShop is Media Prima’s TV home-shopping network, built around live demonstrations, call-to-action formats and commerce. It suits product launches, direct-response campaigns and retail partnerships.', ['Live TV commerce', 'Product demonstrations', 'Direct response', 'Retail campaigns']),
    'media-prima-podcast-utama': ('Video podcast extension of Buletin TV3', 'Podcast Utama brings Buletin TV3’s news authority into long-form video conversations. Brands can sponsor episodes, integrate into the set or use host-led segments.', ['Video podcast', 'Host-led interviews', 'News authority', 'Social cut-downs']),
    'media-prima-jom-heboh': ('Malaysia’s touring family carnival', 'Jom Heboh is Media Prima’s long-running outdoor carnival, touring major locations nationwide. Sponsorship puts brands inside a high-footfall family event with stage, booth and media integration.', ['Nationwide tour', 'Family audiences', 'Booth and stage', 'Media integration']),
    'media-prima-klcc-snorkels': ('Landmark KLCC out-of-home', 'Big Tree’s KLCC Snorkels is one of Kuala Lumpur’s most recognisable OOH assets, with “O” and “D” snorkel formats reaching locals and tourists around the Twin Towers.', ['Landmark OOH', 'KLCC audience', 'Large-format creative', 'Long-dwell exposure']),
    'rtm-tv1': ('Malaysia’s flagship national channel', 'TV1 is the country’s flagship free-to-air channel, carrying national news, entertainment, education and live events. It is the broadest mass-reach environment in the RTM portfolio.', ['National news', 'Mass reach', 'Live events', 'Government and brand campaigns']),
    'rtm-tv2': ('The second national channel', 'TV2 serves a broad Malaysian audience with entertainment, drama, news and cultural programming. It adds reach and frequency alongside TV1.', ['Entertainment', 'Drama', 'News', 'Cultural programming']),
    'rtm-okey': ('Kids, family and learning', 'OKEY is RTM’s family and learning channel, with children’s programmes, education and entertainment. It reaches parents and young audiences in a safe environment.', ['Kids and family', 'Education', 'Safe environment', 'Family brands']),
    'rtm-nasional-fm': ('National Malay radio', 'Nasional FM is RTM’s national Malay radio network, combining music, news, talk and government messaging. It delivers broad national reach and local relevance.', ['National reach', 'Malay audiences', 'News and talk', 'Government messaging']),
    'astro-astro-awani': ('24-hour news and current affairs', 'Astro AWANI is Malaysia’s 24-hour news channel, with TV, digital and social extensions. It offers credible news environments for corporate, government and issue-led campaigns.', ['24-hour news', 'Current affairs', 'Digital and social', 'Studio interviews']),
    'star-media-group-988-fm': ('Chinese-language radio and digital', '988 FM is Star RFM’s Chinese-language station, pairing music and personality-led shows with display, social, live video and streaming. It reaches Chinese listeners nationwide.', ['Chinese radio', 'Drive-time', 'Digital extensions', 'Social and streaming']),
    'star-media-group-suria-fm': ('Malay radio for families', 'Suria FM is Star Media Group’s Malay radio station, focused on family, music and drive-time audiences. It combines radio with display, social and branded content.', ['Malay radio', 'Family audiences', 'Drive-time', 'Digital extensions']),
    'karangkraf-sinar-harian': ('Malay daily news', 'Sinar Harian is Karangkraf’s Malay daily, reaching readers across print, digital and social. It offers display, advertorial, classified and special-format advertising.', ['Print display', 'Advertorials', 'Classifieds', 'Digital news']),
    'bernama-tv': ('National news agency television', 'Bernama TV extends the national news agency into television, with news bulletins, interviews, programmes and branded content. It is a credible environment for corporate and government messages.', ['News bulletins', 'Interviews', 'Programmes', 'Branded content']),
    'tvs-tvs': ('Sarawak’s free-to-air station', 'TVS is Sarawak’s own television station, with local news, entertainment, culture and sport. It connects brands with East Malaysian audiences and Sarawakian communities.', ['Local news', 'Entertainment', 'Culture', 'Sport']),
    'klia-terminal-1': ('KLIA Terminal 1 media', 'Terminal 1 inventory spans arrivals, departures, the contact pier and the satellite building. Formats include static lightboxes, digital screens, wraps, baggage carousels and experiential sites.', ['Arrivals and departures', 'Digital screens', 'Wraps and carousels', 'High-value travellers']),
    'klia-terminal-2': ('KLIA Terminal 2 media', 'Terminal 2 covers international departures, arrivals and the total coverage network. It reaches a high volume of travellers through static and digital formats.', ['International departures', 'Arrivals', 'Digital network', 'Transit audience']),
    'klia-erl': ('ERL rail and station screens', 'The ERL KLIA Ekspres network connects the airport to Kuala Lumpur with station totems and in-train digital screens. It reaches travellers immediately before and after their flight.', ['Station totems', 'In-train screens', 'Airport travellers', 'KL city connection']),
    'ktm-kl-sentral-station': ('KL Sentral station sponsorship', 'KL Sentral is Malaysia’s busiest transit interchange. East and West Wing domination, turnstiles, pillars and platform formats deliver repeated exposure to daily commuters.', ['Station domination', 'Turnstiles and pillars', 'Platform formats', 'Commuter audience']),
}

DECKS = {
    'rtm-tv-radio-2025': {
        'title': 'One broadcaster, every Malaysian screen',
        'body': 'RTM’s 2025 rate card covers TV1, TV2, OKEY, national and state radio, sponsorship packages, capsules, crawlers and digital extensions. It is the broadest public-service media system in the country, with formats for mass reach, government messaging and local community campaigns.',
        'stats': [('Channels', 'TV + radio + digital'), ('Reach', 'National'), ('Buying', 'Per spot, package or sponsorship'), ('Tax', '8% SST')],
        'features': [('TV zone matrix', 'Published 7s to 60s rates across zones A–U.'), ('Sponsorship', 'Programme, capsule, talkshow and live coverage packages.'), ('Radio', 'National and state stations with spot and programme buys.'), ('Digital', 'RTMKlik and online extensions.')],
    },
    'omnia-content-terhangat-2026': {
        'title': 'Media Prima Omnia, planned by market',
        'body': 'Content Terhangat 2026 packages Media Prima’s TV, digital and content inventory into four campaign tiers, tailored across twelve industry and language tracks. It is built for brands that want a coordinated group campaign rather than a single-channel buy.',
        'stats': [('Tiers', '4 packages'), ('Terms', '3–12 months'), ('Investment', 'RM200k–800k'), ('Tracks', '12 market tracks')],
        'features': [('Campaign Planner', 'Bronze, Silver, Gold and Platinum tiers with event bonus value.'), ('Inventory menu', 'Programme, drama, news, digital and sponsorship entitlements.'), ('Market tracks', 'Industry and language variants across the group.'), ('Governance', 'Rates exclude 8% SST and production.')],
    },
    'sinar-karangkraf-ratecard-2025-2026': {
        'title': 'Sinar Harian and the Karangkraf portfolio',
        'body': 'The Karangkraf media kit covers Sinar Harian, SinarPlus, Sinar Bestari, Sinar Daily and the IdeAktif titles across print, digital and social. It is a single route into Malay readers, families and lifestyle audiences.',
        'stats': [('Titles', 'News + lifestyle'), ('Formats', 'Print, digital, social'), ('Buying', 'Per insertion or package'), ('Tax', '8% SST')],
        'features': [('Print display', 'Full, half and quarter page formats in colour and mono.'), ('Advertorials', 'Editorial-style formats with production guidance.'), ('Classifieds', 'Liners, words and logo options.'), ('Digital', 'Portal, social and branded content.')],
    },
    'media-prima-audio-trade-show-2025': {
        'title': 'One booking form, five radio stations',
        'body': 'Media Prima Audio’s trade-show packages combine Hot FM, Kool FM, Molek FM, Fly FM and Eight FM in a single booking form. It is designed for fast, multi-station radio campaigns with clear package pricing.',
        'stats': [('Stations', '5'), ('Formats', 'Radio packages'), ('Buying', 'Per package'), ('Tax', '8% SST')],
        'features': [('Station mix', 'Malay, Chinese and English audiences.'), ('Packages', '1 to 26-week radio campaigns.'), ('Add-ons', 'Digital and on-ground options.'), ('Booking', 'Single form across stations.')],
    },
    'astro-upfront-2027': {
        'title': 'Astro Upfront27',
        'body': 'Upfront27 brings Astro’s TV, streaming, radio, talent and on-ground inventory into one 2027 sales kit. Early-bird bonuses reward commitments made by the October–November deadlines.',
        'stats': [('TV', 'Astro networks'), ('Audio', 'Astro radio'), ('Digital', 'sooka and Awani'), ('Validity', 'Utilise by 31 Dec 2027')],
        'features': [('TV packages', 'Best of TV, Best of Raya, news and programme packages.'), ('Audio', 'Impact 360 and station-led audio packages.'), ('Talent', 'Creator and celebrity-led formats.'), ('On-ground', 'Booths, activations and event integrations.')],
    },
    'bernama-media-kit-2026': {
        'title': 'The national news agency, unpacked',
        'body': 'Bernama’s 2026 media kit covers the wire, BERNAMA.com, Bernama TV, Bernama Radio, editorial services, production, archives and images. It is a single source for credible news distribution and national reach.',
        'stats': [('Platforms', 'Wire, portal, TV, radio'), ('Services', 'Editorial and production'), ('Reach', 'National'), ('Buying', 'Per item or package')],
        'features': [('Editorial', 'Press releases, interviews, features and coverage.'), ('Broadcast', 'TV spots, radio and talkshow formats.'), ('Production', 'Video, audio, photography and studio services.'), ('Archive', 'Archival footage and image licensing.')],
    },
    'tvs-sponsorship-spot-buy-2026': {
        'title': 'TVS Sarawak, on air and on the ground',
        'body': 'TVS combines Sarawak’s free-to-air television with Miri billboard inventory. The 2026 card covers spot buys, sponsorship, news and programme formats, plus the reach and OTT detail from the TVS profile deck.',
        'stats': [('TV', 'TVS'), ('Outdoor', 'Miri billboard'), ('Audience', 'Sarawak'), ('Buying', 'Spot, sponsorship, package')],
        'features': [('Spot buy', '30s and longer TVC placements.'), ('Sponsorship', 'Programme and news-linked packages.'), ('Miri billboard', 'Outdoor inventory in Sarawak.'), ('Profile', 'Reach, OTT and news-belt detail.')],
    },
    'klia-t1-airport-2024-premium': {
        'title': 'KLIA Terminal 1, end to end',
        'body': 'Terminal 1 inventory covers arrivals, departures, the contact pier and the satellite building, with static lightboxes, digital screens, wraps, carousels and experiential sites. It reaches international and domestic travellers at the start and end of their journey.',
        'stats': [('Traffic', '2.9M–3.5M monthly'), ('Terminal', 'T1'), ('Formats', 'Static, digital, wraps'), ('Tax', '8% SST')],
        'features': [('Arrivals', 'BDC Impact, Arrivals Impact Duo and baggage carousels.'), ('Departures', 'Immigration, satellite pier and aerostation formats.'), ('Digital', 'Screens and charging network.'), ('Transit', 'KL Sentral, ERL and train station domination.')],
    },
    'klia-t2-airport-2024': {
        'title': 'KLIA Terminal 2, total coverage',
        'body': 'Terminal 2 inventory spans international departures, arrivals and the total coverage network. Static and digital formats reach a high volume of travellers across the full airport journey.',
        'stats': [('Terminal', 'T2'), ('Formats', 'Static and digital'), ('Audience', 'International travellers'), ('Tax', '8% SST')],
        'features': [('Departures', 'Digital and static departures inventory.'), ('Arrivals', 'Arrivals halls and baggage areas.'), ('Network', 'Total coverage across T2.'), ('Combined', 'T1 and T2 packages available.')],
    },
}


def php_array(items):
    lines = []
    for item in items:
        escaped = item.replace("'", "’")
        lines.append(f"                        '{escaped}',")
    return '\n'.join(lines)


def write(path, content):
    os.makedirs(os.path.dirname(path), exist_ok=True)
    with open(path, 'w', encoding='utf-8') as handle:
        handle.write(textwrap.dedent(content).lstrip())
    print('wrote', os.path.relpath(path, ROOT))


def owner_view(slug, data):
    features = '\n'.join(
        "                        ['title' => '" + title.replace("'", "’") + "', 'body' => '" + body.replace("'", "’") + "'],"
        for title, body in data['features']
    )
    return f"""@extends('layouts.app')

@section('content')
    @php
        $ownerStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => '{data['title']}',
                    'body' => '{data['body']}',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What the group offers',
                    'items' => [
{features}
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.owners._shared')
@endsection
"""


def channel_view(key, data):
    title, body, features = data
    feature_lines = '\n'.join(
        "                        ['title' => '" + item.replace("'", "’") + "'],"
        for item in features
    )
    return f"""@extends('layouts.app')

@section('content')
    @php
        $channelStoryBlocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => '{title}',
                    'body' => '{body}',
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What this channel offers',
                    'items' => [
{feature_lines}
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.channels._shared')
@endsection
"""


def deck_view(slug, data):
    stats = '\n'.join(
        "                        ['label' => '" + label.replace("'", "’") + "', 'value' => '" + value.replace("'", "’") + "'],"
        for label, value in data['stats']
    )
    features = '\n'.join(
        "                        ['title' => '" + title.replace("'", "’") + "', 'body' => '" + body.replace("'", "’") + "'],"
        for title, body in data['features']
    )
    return f"""@extends('layouts.app')

@section('content')
    @php
        $blocks = [
            [
                'type' => 'rich_text',
                'payload' => [
                    'title' => '{data['title']}',
                    'body' => '{data['body']}',
                ],
            ],
            [
                'type' => 'stat_grid',
                'payload' => [
                    'items' => [
{stats}
                    ],
                ],
            ],
            [
                'type' => 'feature_list',
                'payload' => [
                    'title' => 'What is inside',
                    'items' => [
{features}
                    ],
                ],
            ],
        ];
    @endphp

    @include('pages.offers._shared')
@endsection
"""


def main():
    for slug, data in OWNERS.items():
        write(os.path.join(VIEWS, 'owners', f'{slug}.blade.php'), owner_view(slug, data))

    for key, data in CHANNELS.items():
        write(os.path.join(VIEWS, 'channels', f'{key}.blade.php'), channel_view(key, data))

    for slug, data in DECKS.items():
        write(os.path.join(VIEWS, 'offers', f'{slug}.blade.php'), deck_view(slug, data))

    print(f'Generated {len(OWNERS)} owner, {len(CHANNELS)} channel and {len(DECKS)} deck views.')


if __name__ == '__main__':
    main()
