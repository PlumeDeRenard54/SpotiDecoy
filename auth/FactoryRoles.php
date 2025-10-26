<?php

namespace iutnc\deefy\auth;

class FactoryRoles
{
    public static function factory(int $entry): String
    {
        return match ($entry) {
            100 => "ADMIN",
            1 => "USER",
            default => "UNKNOWN",
        };
    }
}