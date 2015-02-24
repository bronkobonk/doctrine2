<?php

namespace Doctrine\Tests\Models\JoinedInheritanceTypeClassMetadata;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;

/**
 * @Entity()
 */
class CompanyGroup extends Group
{
    /**
     * @Column(type="integer")
     */
    public $companyId;
}
