<?php

declare(strict_types=1);

namespace App\Shared\Support;

class PageHero
{
    public const MEDIA = 'media';

    public const SPLIT = 'split';

    public const UTILITY = 'utility';

    /**
     * The single source of truth for the site-wide page hero treatment.
     *
     * MEDIA   full-bleed cover, reversed header: Work, Solutions, Academy, About
     * SPLIT   cream hero with a supporting image: Home, Services, Creators, Portfolio, Insights
     * UTILITY compact text-only hero: forms, search, directories, legal and detail pages
     */
    public static function mode(): string
    {
        return match (true) {
            request()->routeIs('work.*', 'solutions', 'academy', 'about') => self::MEDIA,
            request()->routeIs('home', 'services', 'creators.index', 'portfolio', 'insights') => self::SPLIT,
            default => self::UTILITY,
        };
    }

    public static function isMediaLed(): bool
    {
        return self::mode() === self::MEDIA;
    }
}
