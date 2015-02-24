<?php

namespace Doctrine\Tests\Models\JoinedInheritanceTypeClassMetadata;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @Entity()
 */
class CompanyTopic extends Topic
{
    /**
     * @Column(type="integer")
     */
    public $productId;

    /**
     * @ManyToOne(targetEntity="CompanyGroup")
     * @JoinColumn(name="group_id", referencedColumnName="id")
     */
    public $group;
}
