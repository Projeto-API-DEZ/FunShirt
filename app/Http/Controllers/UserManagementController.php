<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(/*Request $request*/)
    {
        /*
        $query = User::with('customer');  
        // 意思：先准备好查询用户，先把每个用户的“客户资料”也一起查好（避免 N+1 查询）

        if ($request->filled('type')) {
        $query->where('user_type', $request->type);   
        }
        // 意思：如果用户在网页上选择了类型（比如 ?type=C），就只显示该类型的用户（C/F/A）

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        // 意思：如果输入了搜索内容，就去查名字或邮箱里包含这些文字的用户（模糊搜索）

            $users = $query->paginate(15)->withQueryString();  
            // 意思：每页显示15条记录，并且带上分页 + 保留筛选条件（点下一页时，搜索条件不会丢）
            
            return view('admin.users.index', compact('users'));
            // 把用户列表数据传给后台页面模板去显示
        */
        return "index manager";
    }

    public function create()
    {
        return "create manager";
    }

    public function store(Request $request)
    {
        return "store manager";
    }

    public function show(User $user)
    {
        return "show manager";
    }

    public function edit(User $user)
    {
        return "edit manager";
    }

    public function update(Request $request, User $user)
    {
        return "update manager";
    }

    public function destroy(User $user)
    {
        return "destroy manager";
    }
    public function toggleBlock(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('alert-danger', 'Self-blocking is restricted.');
        }
        // 重要保护：管理员不能把自己封禁！防止把自己锁在外面

        $user->blocked = !$user->blocked;   // 切换状态（封禁 ↔ 正常）
        $user->save();

        $status = $user->blocked ? 'suspended' : 're-activated';
        return back()->with('alert-success', "User account has been {$status} successfully.");
    }

}