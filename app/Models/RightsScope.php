<?php

namespace App\Models;

class RightsScope extends BaseModel
{
    protected static function booted(): void
    {
        static::saving(function (RightsScope $scope): void {
            $references = array_filter([
                $scope->location_id,
                $scope->host_structure_id,
                $scope->asset_id,
                $scope->media_property_id,
                $scope->placement_id,
            ]);

            if (count($references) !== 1) {
                throw new \InvalidArgumentException('A rights scope must reference exactly one controlled resource.');
            }
        });
    }
}
