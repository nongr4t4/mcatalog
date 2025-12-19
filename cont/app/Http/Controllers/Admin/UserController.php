<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount(['orders', 'cartItems']);

        if ($request->filled('search')) {
            $term = trim($request->input('search'));
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%");
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();
            
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['orders' => function($query) {
            $query->latest()->take(10);
        }, 'cartItems']);
        
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = [
            'admin' => 'Администратор',
            'user' => 'Пользователь',
        ];
        
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,user',
            'password' => 'nullable|min:6',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'role']);
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar_path'] = $path;
        }

        $user->update($data);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Пользователь обновлен успешно');
    }

    public function destroy(User $user)
    {
     // Заборона видалення самого себе
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Нельзя удалить свой аккаунт');
        }

        // === ВИПРАВЛЕННЯ ПОМИЛКИ ===
        // Видаляємо всі замовлення користувача перед видаленням самого користувача.
        // Це спрацює, бо order_items мають каскадне видалення при видаленні order.
        $user->orders()->delete();
        
        // Також очистимо корзину (хоча в БД є каскад, це для надійності)
        $user->cartItems()->delete();

        // Видалення аватара
        if ($user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        // Тепер можна безпечно видалити користувача
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Пользователь удален успешно');
    }
}
