<?php

namespace Doctrine\Tests\Models\JoinedInheritanceTypeClassMetadata;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\InheritanceType;

/**
 * @Entity()
 * @InheritanceType("JOINED")
 */
class Topic
{
    /**
     * @Column(type="integer")
     * @Id @GeneratedValue
     */
    public $id;
}
