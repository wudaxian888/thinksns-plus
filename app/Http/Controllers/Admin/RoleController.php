<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Zhiyi\Plus\Models\Role;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Permission;
use Zhiyi\Plus\Http\Controllers\Controller;

class RoleController extends Controller
{
    /**
     * Get roles.
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function roles()
    {
        $roles = Role::all();

        return response()->json($roles)->setStatusCode(200);
    }

    /**
     * 删除用户组.
     *
     * @param Role $role
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function delete(Role $role)
    {
        if ($role->delete()) {
            return response('', 204);
        }

        return response()->json([
            'errors' => ['删除失败'],
        ])->setStatusCode(500);
    }

    /**
     * 创建角色接口.
     *
     * @param Request $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function createRole(Request $request)
    {
        $name = $request->input('name');
        $display_name = $request->input('display_name');
        $description = $request->input('description');

        if (! $name) {
            return response()->json([
                'errors' => ['name' => '名称不能为空'],
            ])->setStatusCode(422);
        } elseif (Role::where('name', 'LIKE', $name)->first()) {
            return response()->json([
                'errors' => ['name' => '名称已经存在'],
            ])->setStatusCode(422);
        }

        $role = new Role();
        $role->name = $name;
        $role->display_name = $display_name;
        $role->description = $description;

        if (! $role->save()) {
            return response()->json([
                'errors' => ['保存失败'],
            ])->setStatusCode(400);
        }

        return response()->json($role)->setStatusCode(201);
    }

    /**
     * 完成获取角色接口.
     *
     * @param Request $request
     * @param Role $role
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function showRole(Request $request, Role $role)
    {
        $allPerms = $request->has('all_perms');
        $hasPerms = $request->has('perms');

        // var_dump($perms === true);exit;

        $perms = [];
        if ($allPerms === true) {
            $perms = Permission::all();
        }

        if ($hasPerms === true) {
            $role->load(['perms']);
        }

        return response()->json([
            'perms' => $perms,
            'role' => $role,
        ])->setStatusCode(200);
    }

    public function updateRole(Request $request, Role $role)
    {
        $perms = $request->input('perms', []);
        $role->perms()->sync($perms);

        return response()->json([
            'message' => '更新成功',
        ])->setStatusCode(201);
    }

    /**
     * 获取全部权限节点.
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function perms()
    {
        $perms = Permission::all();

        return response()->json($perms)->setStatusCode(200);
    }

    /**
     * 创建权限节点.
     *
     * @param Request $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function createPerm(Request $request)
    {
        $name = $request->input('name');
        $display_name = $request->input('display_name');
        $description = $request->input('description');

        if (! $name) {
            return response()->json([
                'errors' => ['name' => '名称不能为空'],
            ])->setStatusCode(422);
        } elseif (Permission::where('name', 'LIKE', $name)->first()) {
            return response()->json([
                'errors' => ['name' => '名称已经存在'],
            ])->setStatusCode(422);
        }

        $perm = new Permission();
        $perm->name = $name;
        $perm->display_name = $display_name;
        $perm->description = $description;

        if (! $perm->save()) {
            return response()->json([
                'errors' => ['保存失败'],
            ])->setStatusCode(400);
        }

        return response()->json($perm)->setStatusCode(201);
    }

    /**
     * 更新权限节点.
     *
     * @param Request    $request
     * @param Permission $perm
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function updatePerm(Request $request, Permission $perm)
    {
        $key = $request->input('key');
        $value = $request->input('value');

        if (! in_array($key, ['display_name', 'description'])) {
            return response()->json([
                'errors' => ['请求不合法'],
            ])->setStatusCode(422);
        }

        $perm->$key = $value;

        if (! $perm->save()) {
            return response()->json([
                'errors' => ['数据更新失败'],
            ])->setStatusCode(500);
        }

        return response()->json([
            'messages' => ['更新成功'],
        ])->setStatusCode(201);
    }

    /**
     * 删除权限节点.
     *
     * @param Permission $perm
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function deletePerm(Permission $perm)
    {
        if ($perm->delete()) {
            return response('', 204);
        }

        return response()->json([
            'errors' => ['删除失败'],
        ])->setStatusCode(500);
    }
}
