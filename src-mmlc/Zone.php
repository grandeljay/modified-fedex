<?php

namespace Grandeljay\Fedex;

enum Zone
{
    case A;
    case B;
    case C;
    case D;
    case E;
    case H;
    case R;
    case S;
    case T;
    case U;
    case V;
    case W;
    case X;
    case Y;

    public static function fromString(string $zone_letter): Zone
    {
        $zone_letter = strtoupper($zone_letter);

        foreach (Zone::cases() as $zone) {
            if ($zone_letter === $zone->name) {
                return $zone;
            }
        }

        throw new \Exception(
            sprintf(
                '"%s" is not a valid Zone.',
                $zone_letter
            )
        );
    }

    public static function fromCountry(string $country_code): ?Zone
    {
        foreach (self::cases() as $zone) {
            $countries = self::getCountries($zone);

            if (in_array($country_code, $countries, true)) {
                return $zone;
            }
        }

        return null;
    }

    public static function getCountries(Zone $zone): array
    {
        $countries = match ($zone) {
            Zone::A => ['CA'],
            Zone::B => ['AU', 'JP', 'KR', 'TH', 'MX'],
            Zone::C => ['EG', 'IL', 'IN', 'DZ', 'LK'],
            Zone::D => ['AR', 'BR', 'CR', 'DO', 'PY', 'PE', 'PA'],
            Zone::E => ['AM', 'AZ', 'GE', 'KZ', 'KG', 'UZ', 'IQ', 'NG', 'NE'],
            Zone::H => ['US'],
            Zone::R => ['AT', 'BE', 'DK', 'FR', 'LU', 'NL'],
            Zone::S => ['ES', 'GR', 'FI', 'PT', 'SE'],
            Zone::T => ['BG', 'CZ', 'EE', 'LT', 'LV', 'RO', 'SL', 'SK'],
            Zone::U => ['NO', 'TR', 'CH'],
            Zone::V => ['MD', 'RU', 'BY', 'UA', 'RS', 'HR', 'AL'],
            Zone::W => ['IT'],
            Zone::X => ['PL'],
            Zone::Y => ['GB'],

            default => [],
        };

        return $countries;
    }
}
