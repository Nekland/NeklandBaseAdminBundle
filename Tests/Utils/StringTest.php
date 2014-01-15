<?php

/*
 * This file is part of the NekLandBaseAdminBundle package.
 *
 * (c) Nekland <http://nekland.fr/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nekland\Bundle\BaseAdminBundle\Tests\Utils;


use Nekland\Bundle\BaseAdminBundle\Utils\String;

class StringTest extends \PHPUnit_Framework_TestCase
{
    public function testUpFirstLetter()
    {
        $str = String::upFirstLetter('test with french is better: "ça dépend du point de vue"');
        $this->assertTrue($str === 'Test with french is better: "ça dépend du point de vue"');
        // Defining data
        $classA = $this->getMock('ThingA');
        $classB = $this->getMock('ThingB');

        $a1 = array(
            'truc' => array(
                $classA,
                'weirdKey' => 'alreadyHere'
            ),
            'foo' => 'bar'
        );

        $a2 = array(
            'truc' => array(
                $classB
            ),
            'hello' => 'world'
        );

        // Real usage
        $final = Utils::array_merge_recursive($a1, $a2);

        // Test if all is alright in the result :)
        $this->assertTrue(get_class($final['truc'][0]) == get_class($classB));
        $this->assertTrue($final['foo'] == 'bar');
        $this->assertTrue($final['hello'] == 'world');
        $this->assertTrue(!empty($final['truc']['weirdKey']) && $final['truc']['weirdKey']);
    }
} 