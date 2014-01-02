<?php

/*
 * This file is part of the NekLandBaseAdminBundle package.
 *
 * (c) Nekland <http://nekland.fr/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nekland\Bundle\BaseAdminBundle\Crud\Configuration;

use Symfony\Component\Yaml\Parser as YamlParser;

class ConfigurationLoader
{
    public function load($path)
    {
        if (!is_file($path)) {
            throw new ConfigurationException(sprintf('Impossible to load configuration. File %s does not exists.', $path));
        }
        $parser = new YamlParser();

        return $parser->parse(file_get_contents($path));
    }
}
