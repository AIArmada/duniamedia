<?php

declare(strict_types=1);

// Editorial art directions and proposed campaign pathways; inventory is confirmed separately.
return json_decode(<<<'JSON'
{
    "media-prima": {
        "accent": "#d92535",
        "secondary": "#e6a52b",
        "layout": "network",
        "type": "sans",
        "title": "Build reach across the Media Prima ecosystem",
        "intro": "Start with the role each medium plays in your campaign, then connect television, audio and outdoor touchpoints.",
        "routes": [
            [
                "Start with the story",
                "Build a television-led launch around relevant Malay-language programming; use radio to reinforce the message.",
                [
                    "TV3",
                    "Hot FM"
                ]
            ],
            [
                "Make the message visible",
                "Consider landmark outdoor formats alongside digital storytelling for a city-focused brief.",
                [
                    "Big Tree",
                    "SAYS"
                ]
            ],
            [
                "Turn attention into action",
                "Explore demonstration-led shopping content and family-event activations.",
                [
                    "WowShop",
                    "Jom Heboh"
                ]
            ]
        ]
    },
    "astro": {
        "accent": "#c71987",
        "secondary": "#762ca7",
        "layout": "screen",
        "type": "sans",
        "title": "Connect the living room, the commute and the stream",
        "intro": "Choose an entertainment context and extend it into complementary audio or streaming moments.",
        "routes": [
            [
                "Entertainment-led launch",
                "Explore drama, variety and programme sponsorship around relevant viewing moments.",
                [
                    "Astro Ria",
                    "Astro Prima"
                ]
            ],
            [
                "Music and daily listening",
                "Consider youth-oriented music and familiar listening routines when developing radio creative.",
                [
                    "ERA",
                    "Sinar"
                ]
            ],
            [
                "A streaming-first brief",
                "Discuss suitable on-demand or live-streaming formats and digital content extensions.",
                [
                    "sooka",
                    "Gempak"
                ]
            ]
        ]
    },
    "rtm": {
        "accent": "#146a62",
        "secondary": "#dbab31",
        "layout": "regional",
        "type": "sans",
        "title": "Plan nationally. Speak locally.",
        "intro": "Separate national broadcasting from state-level relevance, and select language and region before defining placements.",
        "routes": [
            [
                "National awareness",
                "Explore national television and Malay-language radio for broad informational messages.",
                [
                    "TV1",
                    "Nasional FM"
                ]
            ],
            [
                "Regional relevance",
                "Adapt language, context and timing for East Malaysian audiences.",
                [
                    "Sabah FM",
                    "Sarawak FM"
                ]
            ],
            [
                "Language-specific audiences",
                "Consider distinct radio audiences and verify the most suitable language-led formats.",
                [
                    "Ai FM",
                    "Minnal FM"
                ]
            ]
        ]
    },
    "al-hijrah": {
        "accent": "#694193",
        "secondary": "#318b76",
        "layout": "calm",
        "type": "serif",
        "title": "Build a respectful, values-led media brief",
        "intro": "Keep the audience, cultural context and editorial suitability at the centre of the proposal.",
        "routes": [
            [
                "Family and learning",
                "Discuss education, family wellbeing and culturally appropriate television storytelling.",
                [
                    "TV Al Hijrah"
                ]
            ],
            [
                "Digital extensions",
                "Explore how approved television content might extend to the digital viewing experience.",
                [
                    "AlHijrah Plus"
                ]
            ]
        ]
    },
    "bernama": {
        "accent": "#234b99",
        "secondary": "#3285a7",
        "layout": "press",
        "type": "sans",
        "title": "Communicate with clarity and context",
        "intro": "Separate commercial communications from independent editorial coverage; confirm the approved format before planning.",
        "routes": [
            [
                "Corporate communications",
                "Explore clearly identified commercial interviews or video explainers with appropriate editorial approval.",
                [
                    "Bernama TV"
                ]
            ],
            [
                "Audio and conversation",
                "Discuss an informational radio brief, including the intended topic and spokesperson.",
                [
                    "Bernama Radio"
                ]
            ],
            [
                "Digital distribution",
                "Explore digital news-platform opportunities without implying guaranteed news coverage.",
                [
                    "Bernama Digital"
                ]
            ]
        ]
    },
    "tvs": {
        "accent": "#b22c42",
        "secondary": "#c78c33",
        "layout": "regional",
        "type": "serif",
        "title": "Start with Sarawak's audience",
        "intro": "Shape a locally relevant television brief around language, location, programming and the campaign objective.",
        "routes": [
            [
                "Local storytelling",
                "Develop a Sarawak-focused message that reflects local communities and everyday life.",
                [
                    "TVS"
                ]
            ],
            [
                "Programme and spot planning",
                "Discuss suitable programming context, available television inventory and current rate-card terms.",
                [
                    "TVS"
                ]
            ]
        ]
    },
    "star-media-group": {
        "accent": "#c52b40",
        "secondary": "#e9902f",
        "layout": "press",
        "type": "serif",
        "title": "Connect the written story with the spoken message",
        "intro": "Choose editorial, lifestyle or audio contexts according to the language and audience your campaign needs.",
        "routes": [
            [
                "News and context",
                "Discuss clearly labelled publishing formats for an English-language audience.",
                [
                    "The Star"
                ]
            ],
            [
                "Lifestyle relevance",
                "Explore Malay-language lifestyle content and complementary audio touchpoints.",
                [
                    "mStar",
                    "Suria FM"
                ]
            ],
            [
                "Chinese-language audio",
                "Develop a radio brief around appropriate language, listening context and commercial formats.",
                [
                    "98.8 FM"
                ]
            ]
        ]
    },
    "karangkraf": {
        "accent": "#7636b5",
        "secondary": "#c95c65",
        "layout": "magazine",
        "type": "serif",
        "title": "Choose the reader, then choose the story",
        "intro": "Move beyond generic print advertising by selecting titles with relevant reader interests and content contexts.",
        "routes": [
            [
                "News and education",
                "Explore an informational brief around current affairs or learning.",
                [
                    "Sinar Harian",
                    "SinarBestari"
                ]
            ],
            [
                "At home and at the table",
                "Consider practical lifestyle storytelling around home improvement and cooking.",
                [
                    "Impiana",
                    "Rasa"
                ]
            ],
            [
                "Family and personal interests",
                "Discuss audience-relevant creative for parenting, style or youth-focused content.",
                [
                    "Pa&Ma",
                    "Hijabista",
                    "Remaja"
                ]
            ]
        ]
    },
    "outdoor-media": {
        "accent": "#1b7167",
        "secondary": "#c78a43",
        "layout": "network",
        "type": "sans",
        "title": "Make location the starting point",
        "intro": "Decide the geography, visibility and audience movement before selecting an operator or negotiating a format.",
        "routes": [
            [
                "Landmark visibility",
                "Compare proposed large-format sites using location, sightlines and campaign duration.",
                [
                    "Seni Jaya",
                    "Harumi Jaya"
                ]
            ],
            [
                "Digital outdoor",
                "Discuss screen selection, loop structure and creative requirements before requesting a quotation.",
                [
                    "Novus Media"
                ]
            ],
            [
                "Location-led coverage",
                "Explore the available site portfolio against a specific city or corridor brief.",
                [
                    "EDSB Outdoor"
                ]
            ]
        ]
    },
    "cinema-ads": {
        "accent": "#a32f45",
        "secondary": "#c99736",
        "layout": "screen",
        "type": "sans",
        "title": "Plan the cinema experience, not just the spot",
        "intro": "Consider audience, film-release timing, venue selection and the relationship between screen and lobby creative.",
        "routes": [
            [
                "Big-screen creative",
                "Discuss film-aligned timing, cinema locations and technical delivery requirements.",
                [
                    "GSC",
                    "TGV"
                ]
            ],
            [
                "Extend the experience",
                "Explore approved lobby or promotional formats and the available cinema inventory.",
                [
                    "GSC",
                    "TGV"
                ]
            ]
        ]
    },
    "klia": {
        "accent": "#8b681c",
        "secondary": "#315f84",
        "layout": "journey",
        "type": "sans",
        "title": "Follow the passenger journey",
        "intro": "Build your brief around terminal zones and the moments a traveller encounters along the way.",
        "routes": [
            [
                "International passenger touchpoints",
                "Discuss departures, arrivals and other suitable Terminal 1 placements.",
                [
                    "Terminal 1"
                ]
            ],
            [
                "Terminal 2 visibility",
                "Select appropriate passenger zones and verify current formats and availability.",
                [
                    "Terminal 2"
                ]
            ]
        ]
    },
    "ktm": {
        "accent": "#ba3332",
        "secondary": "#285982",
        "layout": "journey",
        "type": "sans",
        "title": "Follow the commuter, from station to train",
        "intro": "Distinguish repeated rail exposure from high-traffic interchange placements when designing your campaign.",
        "routes": [
            [
                "On the move",
                "Explore train-based formats and confirm routes, specifications and campaign duration.",
                [
                    "KTM Train"
                ]
            ],
            [
                "At the interchange",
                "Discuss station touchpoints, pedestrian flow and available sponsorship options.",
                [
                    "KL Sentral Station"
                ]
            ]
        ]
    },
    "specialist-media": {
        "accent": "#176b95",
        "secondary": "#a76c36",
        "layout": "magazine",
        "type": "serif",
        "title": "Choose the context, not just the audience size",
        "intro": "Match your message to specialist business, independent publishing or live-event contexts.",
        "routes": [
            [
                "Business and decision-makers",
                "Develop a business-focused brief around relevant radio and publishing opportunities.",
                [
                    "BFM",
                    "The Malaysian Reserve",
                    "The Edge"
                ]
            ],
            [
                "Independent digital audiences",
                "Explore clearly labelled commercial content with relevant digital publishers.",
                [
                    "FMT",
                    "iMedia"
                ]
            ],
            [
                "Live audience engagement",
                "Discuss event-specific sponsorship or activation rights with the organiser.",
                [
                    "Nusavue"
                ]
            ]
        ]
    }
}
JSON, true, flags: JSON_THROW_ON_ERROR);
