<?php
/**
 * User: nek
 * Date: 02/08/13
 * This code is from Nekland
 */

namespace Nekland\Bundle\BaseAdminBundle\Crud\Entity;


interface LockableInterface
{
    /**
     * @return bool
     */
    public function isLocked();

    /**
     * @return bool
     */
    public function isDeletable();
}