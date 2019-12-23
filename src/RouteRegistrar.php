<?php

namespace Niyam\ACL;

use Illuminate\Contracts\Routing\Registrar as Router;

class RouteRegistrar
{
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function all()
    {
        $this->router->group(
            ['prefix' => '', 'middleware' => ['web', 'throttle:600,1']],
            function ($router) {
                $router->get('', 'HomeController@index');
                $router->get('/vendor-expert', 'HomeController@vendorExpert');
                $router->get('/home', 'HomeController@home');
                $router->get('/documentation', 'HomeController@documentation');
                $router->get('/documentation/{id}', 'HomeController@documentationShow');
                // $router->get('{user}/verify/{password}', 'UserController@verifyPassword');
                $router->get('/test', 'HomeController@test');
                $router->get('/panel', 'UserController@panel');
                $router->get('/panels', 'UserController@index');
                $router->get('/password', 'HomeController@password');
                $router->post('/password', 'HomeController@passwordChange');

                $router->get('position-tag', 'PositionTagController@index');


                // $router->group(
                //     ['prefix' => 'panels'],
                //     function () use ($router) {
                //         $router->get('', 'UserController@index');
                //     }
                // );

                // Api Groups Routes
                $router->group(
                    ['prefix' => 'api'],
                    function () use ($router) {
                        $router->get('/documentation', 'DocumentationController@index');
                        $router->post('login', 'AuthController@apiAuthenticate');
                    }
                );

                // Register Groups Routes
                // Route::group(['middleware' => ['web']], function () {
                $router->group(
                    ['prefix' => 'auth'],
                    function () use ($router) {
                        $router->any('login', 'AuthController@authenticate');
                        $router->get('logout', 'AuthController@logout');
                        $router->get('recovery', ['uses' => 'AuthController@recoveryPassword']);
                        $router->post('recovery', ['uses' => 'AuthController@changePassword']);
                    }
                );

                $router->group(
                    ['prefix' => 'users'],
                    function () use ($router) {
                        $router->get('', 'UserController@getUsers');
                        $router->post('', 'UserController@createUser');
                        $router->post('with-role', 'UserController@createUserWithRole');
                        $router->get('current', 'UserController@getCurrentUser');
                        $router->get('{user}', 'UserController@getUser');
                        $router->delete('{user}', 'UserController@deleteUser');
                        $router->patch('{user}', 'UserController@editUser');
                        $router->patch('{user}/with-role', 'UserController@editUserWithRole');
                        $router->post('{user}/with-role', 'UserController@addRolesToUser');
                        $router->get('{user}/permissions', 'UserController@getUserPermissions');
                        $router->get('{user}/permissions/{permission}', 'UserController@hasPermission');
                        $router->post('{user}/roles', 'RoleController@assignRoleForUser');
                        $router->get('{user}/roles/{role}', 'UserController@hasRole');
                        $router->post('{user}/roles/{role}', 'RoleController@syncRolesForUser');
                        $router->post('{user}/roles', 'RoleController@syncRolesForUser');
                        $router->delete('{user}/roles/{role}', 'RoleController@removeRoleForUser');
                        // AG
                        $router->get('{user}/avatar', 'UserController@getAvatar');
                        $router->get('{user}/signature', 'UserController@getSignature');
                        $router->get('{user}/avatar/view', 'UserController@getAvatar');
                        $router->get('{user}/signature/view', 'UserController@getSignature');
                        $router->get('{user}/roles', 'UserController@roles');
                        $router->get('{user}/positions', 'UserController@positions');
                        $router->get('{user}/permissions', 'UserController@permissions');
                        $router->post('/list', 'UserController@createUserList');
                        // \AG
                    }
                );

                $router->group( //type is 0 as default
                    ['prefix' => 'roles'],
                    function () use ($router) {
                        $router->get('', 'RoleController@getRoles');
                        $router->post('', 'RoleController@postRole');
                        $router->get('{role}', 'RoleController@getRole');
                        $router->delete('{role}', 'RoleController@deleteRole');
                        $router->patch('{role}', 'RoleController@editRole');
                        // $router->get('/{role}/users', 'UserController@getUsersByRole');
                        $router->get('/{role}/users', 'UserController@usersRole');
                        $router->post('/{role}/users', 'RoleController@assignRoleToUsers');
                        $router->delete('/{role}/users/{user}', 'RoleController@revokeUserRole');
                        $router->post('/{role}/permissions', 'RoleController@givePermissionTo');
                        $router->get('/{role}/permissions', 'RoleController@getRolePermissions');
                        $router->delete('/{role}/permissions/{permission}', 'RoleController@revokePermissionTo');
                        $router->delete('/{role}/permissions', 'RoleController@revokePermissionTo');
                        $router->post('/{role}/permissions', 'RoleController@syncPermissions');
                        // AG
                        $router->get('/{role}/relation', 'RoleController@getRelation');
                        // \AG
                    }
                );

                $router->group(
                    ['prefix' => 'tags'],
                    function () use ($router) {
                        $router->post('', 'TagController@postTag');
                        $router->get('', 'TagController@getTags');
                        $router->delete('{tag}', 'TagController@deleteTag');
                        $router->patch('{tag}', 'TagController@editTag');
                    }
                );

                $router->group( //type is 1
                    ['prefix' => 'positions'],
                    function () use ($router) {
                        $router->get('', 'RoleController@getRoles');
                        $router->get('tree', 'RoleController@getTreeRoles');
                        $router->get('tree-kendo', 'RoleController@getTreeKendoRoles');
                        $router->post('', 'RoleController@postRole');
                        $router->patch('{position}', 'RoleController@editRole');
                        $router->get('{position}/isHaveNode', 'RoleController@isHaveNode');
                        $router->delete('{position}', 'RoleController@deleteRole');
                        $router->get('{position}/users', 'UserController@getUsersByRole');
                        $router->post('{position}/users', 'RoleController@assignRoleToUsers');
                        $router->delete('{position}/users/{user}', 'RoleController@revokeUserRole');
                        $router->get('with-tags', 'RoleController@getPositionWithTags');
                        $router->get('owners/{owner}/tags/{tag}', 'RoleController@getPositionsByOwnerTag');
                        $router->post('owners/{owner}/tags/{tag}', 'RoleController@postPositionsByOwnerTag');
                        // AG
                        $router->delete('owners/{owner}/tags/{tag}', 'RoleController@deleteRoleTag');
                    }
                );

                $router->group(
                    ['prefix' => 'permissions'],
                    function () use ($router) {
                        $router->get('', 'PermissionController@getPermissions');
                        $router->get('tree-kendo', 'PermissionController@getTreeKendoPermissions');
                        $router->get('tree', 'PermissionController@getTreePermissions');
                        $router->get('{permission}/isHaveNode', 'PermissionController@isHaveNode');
                        $router->post('', 'PermissionController@storePermission');
                        $router->delete('{permission}', 'PermissionController@deletePermission');
                    }
                );

                $router->group(
                    ['prefix' => 'departments'],
                    function () use ($router) {
                        $router->get('{department}/positions', 'DepartmentController@getPositions');
                        $router->get('', 'DepartmentController@getDepartments');
                        $router->get('tree', 'DepartmentController@getTreeDepartments');
                        $router->get('{department}/isHaveNode', 'DepartmentController@isHaveNode');
                        $router->post('', 'DepartmentController@storeDepartment');
                        $router->delete('{department}', 'DepartmentController@deleteDepartment');
                    }
                );

                $router->group(
                    ['prefix' => 'department-tag'],
                    function () use ($router) {
                        $router->get('', 'DepartmentTagController@index');
                        $router->get('/{department_id}', 'DepartmentTagController@show');
                        $router->post('', 'DepartmentTagController@store');
                        // $router->delete('{department}', 'DepartmentController@deleteDepartment');
                    }
                );

                $router->group(
                    ['prefix' => 'notifications'],
                    function () use ($router) {
                        $router->get('sms', 'NotificationController@sms');
                        $router->get('emails', 'NotificationController@emails');
                    }
                );
            }
        );
    }
}
