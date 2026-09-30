<?php

declare(strict_types=1);

namespace App\Modules\Customer\Types;

enum CustomerDocumentType: string
{
    case IdentityCard = 'identity_card';
    case Passport = 'passport';
    case DrivingLicense = 'driving_license';
    case ProofOfAddress = 'proof_of_address';
}
