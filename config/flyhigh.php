<?php

/**
 * FlyHigh project provenance — © 2026 FebianFelix.
 * Canonical origin: https://github.com/FebianFelix/WebsiteLaravel_FlyHighProject
 * See LICENSE and NOTICE. Attribution does not substitute access control.
 */
return [
    'owner' => 'FebianFelix',
    'origin' => 'https://github.com/FebianFelix/WebsiteLaravel_FlyHighProject',
    'copyright' => '© 2026 FebianFelix. All rights reserved.',
    'hold_minutes' => (int) env('FLYHIGH_HOLD_MINUTES', 15),
    'demo_payment_enabled' => (bool) env('FLYHIGH_DEMO_PAYMENTS', false),
];
