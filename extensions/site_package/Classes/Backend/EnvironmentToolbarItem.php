<?php

namespace Werkraum\SitePackage\Backend;

use TYPO3\CMS\Backend\Toolbar\ToolbarItemInterface;
use TYPO3\CMS\Core\Core\Environment;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2020 Simon Fischer <simon.fischer@werkraum.net>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

final class EnvironmentToolbarItem implements ToolbarItemInterface
{
    public function checkAccess(): bool
    {
        return true;
    }

    public function getItem(): string
    {
        $context = Environment::getContext();
        $contextDescription = (string)$context;
        if ($context->isDevelopment()) {
            $contextFgColor = '#ffff7b';
        } elseif ($context->isProduction()) {
            $contextFgColor = 'lightgreen';
        } else {
            $contextFgColor = 'lightgrey';
        }
        return <<<HTML
<span class="toolbar-item-link">
    <span
     class="toolbar-item-icon"
      style="font-weight: bold; text-transform: uppercase; color: {$contextFgColor};">
        {$contextDescription}
</span>
</span>
HTML;
    }

    public function hasDropDown(): bool
    {
        return false;
    }

    public function getDropDown(): string
    {
        return '';
    }

    public function getAdditionalAttributes(): array
    {
        return [];
    }

    public function getIndex(): int
    {
        return 0;
    }
}
