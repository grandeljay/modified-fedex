<?php

namespace Grandeljay\Fedex\Field;

class Field
{
    public static function getFieldClasses(array $classes = []): string
    {
        if (isset($_GET['factor']) && \is_numeric($_GET['factor'])) {
            $classes[] = 'factor-active';
        }

        return \implode(' ', $classes);
    }
}
