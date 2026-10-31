<?php

namespace App\Support;

class ClientWork
{
    private static ?array $data = null;

    public static function data(): array
    {
        if (self::$data === null) {
            $path = resource_path('data/client-work.json');
            self::$data = json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
        }

        return self::$data;
    }

    public static function clients(): array
    {
        return self::data()['clients'];
    }

    public static function campaigns(): array
    {
        return self::data()['campaigns'];
    }

    public static function media(): array
    {
        return self::data()['media'];
    }

    public static function stats(): array
    {
        $campaigns = self::campaigns();
        $clients = self::clients();
        $years = array_map(static fn (array $campaign): int => (int) $campaign['year'], $campaigns);

        return [
            'clients' => count($clients),
            'campaigns' => count($campaigns),
            'channels' => count(array_unique(array_column($campaigns, 'channel'))),
            'from' => min($years),
            'to' => max($years),
            'years' => max($years) - min($years) + 1,
            'government' => count(array_filter($clients, static fn (array $client): bool => $client['sector'] === 'government')),
            'business' => count(array_filter($clients, static fn (array $client): bool => $client['sector'] === 'business')),
        ];
    }

    public static function clientBySlug(string $slug): ?array
    {
        foreach (self::clients() as $client) {
            if ($client['slug'] === $slug) {
                return $client;
            }
        }

        return null;
    }

    public static function campaignCounts(): array
    {
        $counts = [];

        foreach (self::campaigns() as $campaign) {
            $counts[$campaign['client']] = ($counts[$campaign['client']] ?? 0) + 1;
        }

        return $counts;
    }

    public static function featuredClients(int $limit = 10): array
    {
        $counts = self::campaignCounts();
        $clients = self::clients();

        usort($clients, static function (array $a, array $b) use ($counts): int {
            $aFeatured = (int) ($counts[$a['slug']] ?? 0);
            $bFeatured = (int) ($counts[$b['slug']] ?? 0);

            if ($aFeatured !== $bFeatured) {
                return $bFeatured <=> $aFeatured;
            }

            return strcmp($a['name'], $b['name']);
        });

        return array_slice($clients, 0, $limit);
    }

    public static function services(): array
    {
        return array_values(array_unique(array_column(self::campaigns(), 'service')));
    }

    public static function channels(): array
    {
        return array_values(array_unique(array_column(self::campaigns(), 'channel')));
    }

    public static function years(): array
    {
        $years = array_values(array_unique(array_map(static fn (array $campaign): int => (int) $campaign['year'], self::campaigns())));
        rsort($years);

        return $years;
    }
}
