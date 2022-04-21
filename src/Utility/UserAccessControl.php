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
 * @since         2.2.0
 */
namespace App\Utility;

use App\Model\Entity\Role;
use Cake\Http\Exception\InternalErrorException;
use Cake\Validation\Validation;

/**
 * Class UserAccessControl
 *
 * Immutable object that allows taking a snapshot of the current
 * user id and role for a given action.
 *
 * @package App\Utility
 */
class UserAccessControl
{
    /**
     * @var string|null
     */
    private $userId;

    /**
     * @var string
     */
    private $roleName;

    /**
     * @var string|null
     */
    private $username;

    /**
     * UserAccessControl constructor.
     *
     * @param string $roleName The role name
     * @param string|null $userId the user uuid
     * @param string|null $username the user email
     */
    public function __construct(string $roleName, ?string $userId = null, ?string $username = null)
    {
        if (isset($userId) && !Validation::uuid($userId)) {
            throw new InternalErrorException('Invalid UserControl user id.');
        }
        if (isset($username) && !Validation::email($username)) {
            throw new InternalErrorException('Invalid UserControl username.');
        }
        $this->userId = $userId;
        $this->roleName = $roleName;
        $this->username = $username;
    }

    /**
     * Get the user id
     *
     * @return string|null
     */
    public function getId()
    {
        return $this->userId;
    }

    /**
     * Get the user role name
     *
     * @return string
     */
    public function roleName()
    {
        return $this->roleName;
    }

    /**
     * @return null|string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Check if the user is an admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->roleName() === Role::ADMIN;
    }

    /**
     * Check if the given user is the current user.
     *
     * @param string $userId the user uuid
     * @return bool
     */
    public function is(string $userId)
    {
        return $this->getId() === $userId;
    }

    /**
     * Convert the UserAccessControl data in array
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'userId' => $this->userId,
            'rolename' => $this->roleName,
        ];
    }
}
