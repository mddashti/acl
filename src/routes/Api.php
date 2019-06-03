<?php

$router->group(
	['prefix' => '' ],
	function () use ($router) {

		$router->get('test', 'HomeController@test');
		$router->get('',  ['middleware' => 'jwt.auth', 'uses' => 'HomeController@index']);
		$router->get('/main', ['middleware' => 'jwt.auth', 'uses' => 'HomeController@main']);

		// Register Groups Routes
		$router->group(
			['prefix' => 'auth'],
			function () use ($router) {
				$router->post('login', 'AuthController@authenticate');
				$router->post('logout', 'AuthController@logout');
			}
		);

		$router->group(
			['prefix' => 'users', 'middleware' => 'jwt.auth'],
			function () use ($router) {
				$router->get('', 'UserController@getUsers');
				$router->post('', 'UserController@createUser');
				$router->post('with-role', 'UserController@createUserWithRole');
				$router->get('current', 'UserController@getCurrentUser');
				$router->get('{user}', 'UserController@getUser');
				$router->delete('{user}', 'UserController@deleteUser');
				$router->patch('{user}', 'UserController@editUser');
				$router->patch('{user}/with-role', 'UserController@editUserWithRole');
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
				$router->get('{user}/verify/{password}', 'UserController@verifyPassword');
				// \AG
			}
		);

		$router->group( //type is 0 as default
			// ['prefix' => 'roles', 'middleware' => 'jwt.auth'],
			['prefix' => 'roles'],
			function () use ($router) {
				$router->get('', 'RoleController@getRoles');
				$router->post('', 'RoleController@postRole');
				$router->get('{role}', 'RoleController@getRole');
				$router->delete('{role}', 'RoleController@deleteRole');
				$router->patch('{role}', 'RoleController@editRole');
				$router->get('/{role}/users', 'UserController@getUsersByRole');
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
			['prefix' => 'tags', 'middleware' => 'jwt.auth'],
			function () use ($router) {
				$router->post('', 'TagController@postTag');
				$router->get('', 'TagController@getTags');
				$router->delete('{tag}', 'TagController@deleteTag');
				$router->patch('{tag}', 'TagController@editTag');
			}
		);

		$router->group( //type is 1
			['prefix' => 'positions', 'middleware' => 'jwt.auth'],
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
			['prefix' => 'permissions', 'middleware' => 'jwt.auth'],
			function () use ($router) {
				$router->get('tree-kendo', 'PermissionController@getTreeKendoPermissions');
				$router->get('tree', 'PermissionController@getTreePermissions');
				$router->get('{permission}/isHaveNode', 'PermissionController@isHaveNode');
				$router->post('', 'PermissionController@storePermission');
				$router->delete('{permission}', 'PermissionController@deletePermission');
			}
		);

		$router->group(
			['prefix' => 'departments', 'middleware' => 'jwt.auth'],
			function () use ($router) {
				$router->get('', 'DepartmentController@getDepartments');
				$router->get('tree', 'DepartmentController@getTreeDepartments');
				$router->get('{department}/isHaveNode', 'DepartmentController@isHaveNode');
				$router->post('', 'DepartmentController@storeDepartment');
				$router->delete('{department}', 'DepartmentController@deleteDepartment');
			}
		);
	}
);