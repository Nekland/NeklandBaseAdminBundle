<?php

/*
 * This file is part of the NekLandBaseAdminBundle package.
 *
 * (c) Nekland <http://nekland.fr/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
