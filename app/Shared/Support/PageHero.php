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
     * MEDIA   full-bleed cover, reversed header: Work, About, Contact
     * SPLIT   cream hero with a supporting image: Home, Services, Solutions, Creators, Portfolio
     * UTILITY compact text-only hero: forms, search, directories, legal and detail pages
     */
    public static function mode(): string
    {
        return match (true) {
            request()->routeIs('work.*', 'about', 'contact') => self::MEDIA,
            request()->routeIs('home', 'services', 'solutions', 'creators.index', 'portfolio') => self::SPLIT,
            default => self::UTILITY,
        };
    }

    public static function isMediaLed(): bool
    {
        return self::mode() === self::MEDIA;
    }
}
