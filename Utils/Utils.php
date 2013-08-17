<?php

/*
 * This file is part of the NekLandBaseAdminBundle package.
 *
 * (c) Nekland <http://http://nekland.fr/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nekland\Bundle\BaseAdminBundle\Utils;

class Utils
{
    /**
     * Merge an array recursively
     *
     *
     * @param  array $a1
     * @param  array $a2
     * @return array
     */
    public static function array_merge_recursive(array $a1, array $a2)
    {
        $merged    = array();
        $knownKeys = array();

        foreach ($a1 as $key => $value) {
            if (!empty($a2[$key])) {

                if (is_array($a2[$key]) && is_array($a1[$key])) {
                    // If there are both array, then reload the array_merge
                    $merged[$key] = Utils::array_merge_recursive($a1[$key], $a2[$key]);
                } else {
                    $merged[$key] = $a2[$key];
                }

            } else {
                $merged[$key] = $value;
            }
            $knownKeys[] = $key;
        }

        foreach ($a2 as $key => $value) {
            if (!in_array($key, $knownKeys)) {
                $merged[$key] = $a2[$key];
            }
        }

        return $merged;
    }

    /**
     * Obtains an object class name without namespaces
     *
     * @param  Object $obj
     * @return string
     */
    public static function getRealClass($obj)
    {
        $classname = get_class($obj);

        if (preg_match('@\\\\([\w]+)$@', $classname, $matches)) {
            $classname = $matches[1];
        }

        return $classname;
    }
}
