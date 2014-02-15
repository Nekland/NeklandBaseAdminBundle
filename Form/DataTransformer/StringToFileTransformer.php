<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nekland\Bundle\BaseAdminBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class StringToFileTransformer implements DataTransformerInterface
{
    /**
     * @var string
     */
    private $webDir;

    /**
     * @var string
     */
    private $uploadDir;

    public function __construct($webDir, $uploadDir)
    {
        $this->webDir    = $webDir;
        $this->uploadDir = $uploadDir;
    }

    /**
     * {@inheritDoc}
     */
    public function transform($value)
    {
        if (is_string($value) && !empty($value)) {
            return new File($this->webDir . '/' . $value);
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function reverseTransform($file)
    {
        $value = '';
        if ($file instanceof UploadedFile) {
            $filename = uniqid() . '.' . $file->guessExtension();
            $file->move($this->webDir . '/' .  $this->uploadDir, $filename);
            $value = $this->uploadDir . '/' . $filename;
        }
        return $value;
    }

} 