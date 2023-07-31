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
            Zone::A => array('CA'),
            Zone::B => array('AU', 'JP', 'KR', 'TH', 'MX'),
            Zone::C => array('EG', 'IL', 'IN', 'DZ', 'LK'),
            Zone::D => array('AR', 'BR', 'CR', 'DO', 'PY', 'PE', 'PA'),
            Zone::E => array('AM', 'AZ', 'GE', 'KZ', 'KG', 'UZ', 'IQ', 'NG', 'NE'),
            Zone::H => array('US'),
            Zone::R => array('AT', 'BE', 'DK', 'FR', 'LU', 'NL'),
            Zone::S => array('ES', 'GR', 'FI', 'PT', 'SE'),
            Zone::T => array('BG', 'CZ', 'EE', 'LT', 'LV', 'RO', 'SL', 'SK'),
            Zone::U => array('NO', 'TR', 'CH'),
            Zone::V => array('MD', 'RU', 'BY', 'UA', 'RS', 'HR', 'AL'),
            Zone::W => array('IT'),
            Zone::X => array('PL'),
            Zone::Y => array('GB'),

            default => array(),
        };

        return $countries;
    }
}
