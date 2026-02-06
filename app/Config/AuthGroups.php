<?php

declare(strict_types=1);

/**
 * This file is part of CodeIgniter Shield.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Config;

use CodeIgniter\Shield\Config\AuthGroups as ShieldAuthGroups;

class AuthGroups extends ShieldAuthGroups
{
    /**
     * --------------------------------------------------------------------
     * Default Group
     * --------------------------------------------------------------------
     * The group that a newly registered user is added to.
     */
    public string $defaultGroup = 'student';

    /**
     * --------------------------------------------------------------------
     * Groups
     * --------------------------------------------------------------------
     * An associative array of the available groups in the system, where the keys
     * are the group names and the values are arrays of the group info.
     *
     * Whatever value you assign as the key will be used to refer to the group
     * when using functions such as:
     *      $user->addGroup('superadmin');
     *
     * @var array<string, array<string, string>>
     *
     * @see https://codeigniter4.github.io/shield/quick_start_guide/using_authorization/#change-available-groups for more info
     */
    public array $groups = [
        'superadmin' => [
            'title'       => 'Super Admin',
            'description' => 'Complete control of the site.',
        ],
        'admin' => [
            'title'       => 'Admin',
            'description' => 'Day to day administrators of the site.',
        ],
        'frontline' => [
            'title'       => 'Frontline Admin',
            'description' => 'Manages admissions and programs.',
        ],
        'finance' => [
            'title'       => 'Finance Admin',
            'description' => 'Manages payments and invoices.',
        ],
        'instructor' => [
            'title'       => 'Instructor',
            'description' => 'Site instructors.',
        ],
        'student' => [
            'title'       => 'Student',
            'description' => 'General users of the site. Often customers.',
        ],
        'staff' => [
            'title'       => 'Staff',
            'description' => 'Has access to beta-level features.',
        ],
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions
     * --------------------------------------------------------------------
     * The available permissions in the system.
     *
     * If a permission is not listed here it cannot be used.
     */
    public array $permissions = [
        'admin.access'        => 'Can access the sites admin area',
        'admin.settings'      => 'Can access the main site settings',
        'users.manage-admins' => 'Can manage other admins',
        'users.create'        => 'Can create new non-admin users',
        'users.edit'          => 'Can edit existing non-admin users',
        'users.delete'        => 'Can delete existing non-admin users',
        'beta.access'         => 'Can access beta-level features',
        'dashboard.access'    => 'Can access the dashboard',
        'admission.manage'    => 'Can manage admissions (CRUD)',
        'admission.view'      => 'Can view admissions (read-only)',
        'program.manage'      => 'Can manage programs (CRUD)',
        'program.view'        => 'Can view programs (read-only)',
        'payment.manage'      => 'Can manage payments (CRUD)',
        'payment.view'        => 'Can view payments (read-only)',
        'invoice.manage'      => 'Can manage invoices (CRUD)',
        'invoice.view'        => 'Can view invoices (read-only)',
        'classroom.manage'    => 'Can manage classrooms (CRUD)',
        'classroom.view'      => 'Can view classrooms (read-only)',
        'student.manage'      => 'Can manage student records (view)',
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions Matrix
     * --------------------------------------------------------------------
     * Maps permissions to groups.
     *
     * This defines group-level permissions.
     */
    public array $matrix = [
        'superadmin' => [
            'admin.*',
            'users.*',
            'beta.*',
            'dashboard.*',
            'admission.*',
            'program.*',
            'payment.*',
            'invoice.*',
            'classroom.*',
            'student.manage',
        ],
        'admin' => [
            'admin.access',
            'users.create',
            'users.edit',
            'users.delete',
            'beta.access',
            'dashboard.access',
            'admission.manage',
            'program.manage',
            'payment.manage',
            'invoice.manage',
            'classroom.manage',
        ],
        'frontline' => [
            'dashboard.access',
            'admission.manage',
            'program.manage',
            'classroom.manage',
        ],
        'finance' => [
            'dashboard.access',
            'payment.manage',
            'invoice.manage',
        ],
        'staff' => [
            'dashboard.access',
            'admission.view',
            'program.view',
            'payment.view',
            'invoice.view',
            'classroom.view',
        ],
        'instructor' => [
            'dashboard.access',
            'admission.view',
            'program.view',
            'classroom.view',
        ],
        'student' => [],
    ];
}
