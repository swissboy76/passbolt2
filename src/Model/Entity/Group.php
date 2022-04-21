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
 * @since         2.0.0
 */
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Group Entity
 *
 * @property string $id
 * @property string|null $name
 * @property bool $deleted
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $created_by
 * @property string $modified_by
 *
 * @property \App\Model\Entity\User[] $users
 * @property \App\Model\Entity\GroupsUser[] $groups_users
 * @property \App\Model\Entity\User|null $modifier
 * @property \App\Model\Entity\GroupsUser|null $my_group_user
 * @property \App\Model\Entity\Permission[] $permissions
 */
class Group extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'id' => false,
        'name' => false,
        'deleted' => false,
        'created' => false,
        'modified' => false,
        'created_by' => false,
        'modified_by' => false,

        // Associated entities
        'groups_users' => false,
    ];

    /**
     * Check whether a group contains a user based on its groups users.
     *
     * @param array|\App\Model\Entity\User $user the user object or array.
     * @return \App\Model\Entity\GroupsUser|bool groupUser if found, false otherwise
     */
    public function hasUser($user)
    {
        if (!isset($this->groups_users)) {
            return false;
        }

        $userId = is_array($user) ? $user['id'] : $user->id;

        foreach ($this->groups_users as $groupUser) {
            if ($groupUser->user_id === $userId) {
                return $groupUser;
            }
        }

        return false;
    }
}
