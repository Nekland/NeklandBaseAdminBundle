<?php

/*
 * This file is part of the NekLandBaseAdminBundle package.
 *
 * (c) Nekland <http://nekland.fr/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nekland\Bundle\BaseAdminBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class NeklandDatetimeType extends DateTimeType
{
    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'nekland_admin_datetime';
    }
}
