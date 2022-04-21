<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.0.0
 */
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ResourceType Entity
 *
 * @property string $id
 * @property string $name
 * @property string $slug
 * @property string|null $definition
 * @property string|null $description
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property \App\Model\Entity\Resource[] $resources
 */
class ResourceType extends Entity
{
    protected $_accessible = [
        'name' => false,
        'slug' => false,
        'description' => false,
        'definition' => false,
    ];
}
