<?php

/*
 * This file is part of the NekLandBaseAdminBundle package.
 *
 * (c) Nekland <http://nekland.fr/>
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
     * @param  array $array1
     * @param  array $array2
     * @return array
     */
    public static function array_merge_recursive(array $array1, array $array2)
    {
        $merged    = array();
        $knownKeys = array();

        foreach ($array1 as $key => $value) {
            if (!empty($array2[$key])) {

                if (is_array($array2[$key]) && is_array($array1[$key])) {
                    // If there are both array, then reload the array_merge
                    $merged[$key] = Utils::array_merge_recursive($array1[$key], $array2[$key]);
                } else {
                    $merged[$key] = $array2[$key];
                }

            } else {
                $merged[$key] = $value;
            }
            $knownKeys[] = $key;
        }

        foreach ($array2 as $key => $value) {
            if (!in_array($key, $knownKeys)) {
                $merged[$key] = $array2[$key];
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
