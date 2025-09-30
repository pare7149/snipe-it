<?php

namespace App\Models;

use App\Models\Asset;

/**
 * Model for Assets.
 *
 * @version    v1.0
 */
class RequestableAsset extends Asset
{
    use CompanyableTrait, RemoveCompanyableTrait {
        RemoveCompanyableTrait::bootCompanyableTrait insteadof CompanyableTrait;
    }
 
}
