<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    protected function ensureAdmin(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
    }

    public function index(Request $request): View
    {
        $this->ensureAdmin();

        $query = User::query()->with('customer');

        $search = trim((string) $request->string('search'));
        $role = (string) $request->string('role');
        $status = (string) $request->string('status');
        $gender = (string) $request->string('gender');
        $verification = (string) $request->string('verification');
        $sort = (string) $request->string('sort', 'name_asc');
        $perPageInput = strtolower(trim((string) $request->string('per_page', '20')));

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (in_array($role, ['C', 'F', 'A'], true)) {
            $query->where('user_type', $role);
        }

        if ($status === 'active') {
            $query->where('blocked', false);
        } elseif ($status === 'blocked') {
            $query->where('blocked', true);
        }

        if (in_array($gender, ['M', 'F'], true)) {
            $query->where('gender', $gender);
        }

        if ($verification === 'verified') {
            $query->whereNotNull('email_verified_at');
        } elseif ($verification === 'pending') {
            $query->whereNull('email_verified_at');
        }

        match ($sort) {
            'name_desc' => $query->orderByDesc('name'),
            'created_asc' => $query->orderBy('created_at'),
            'created_desc' => $query->orderByDesc('created_at'),
            'email_asc' => $query->orderBy('email'),
            'email_desc' => $query->orderByDesc('email'),
            default => $query->orderBy('name'),
        };

        $perPage = match ($perPageInput) {
            '10' => 10,
            '20' => 20,
            '50' => 50,
            '100' => 100,
            'all' => null,
            default => 20,
        };

        $users = $perPage === null
            ? $query->get()
            : $query->paginate($perPage)->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'filters' => [
                'search' => $search,
                'role' => $role,
                'status' => $status,
                'gender' => $gender,
                'verification' => $verification,
                'sort' => $sort,
                'per_page' => $perPageInput === '' ? '20' : $perPageInput,
            ],
        ]);
    }

    public function create(): View
    {
        $this->ensureAdmin();

        return view('admin.users.create', [
            'user' => new User(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'gender' => ['required', Rule::in(['M', 'F'])],
            'user_type' => ['required', Rule::in(['C', 'F', 'A'])],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'photo_file' => ['nullable', 'image', 'max:2048'],
            'nif' => ['nullable', 'string', 'size:9'],
            'address' => ['nullable', 'string'],
            'default_payment_type' => ['nullable', Rule::in(['Visa', 'PayPal', 'MB WAY'])],
            'default_payment_ref' => ['nullable', 'string', 'max:255'],
        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->gender = $validated['gender'];
        $user->user_type = $validated['user_type'];
        $user->blocked = false;
        $user->password = Hash::make($validated['password']);

        if ($request->hasFile('photo_file')) {
            $user->photo_url = $request->file('photo_file')->store('photos', 'public');
        }

        $user->save();

        $this->syncCustomerDetails($user, $validated);

        return redirect()
            ->route('admin.users.edit', $user)
            ->with('status', 'User created successfully.');
    }

    public function show(User $user): View
    {
        $this->ensureAdmin();

        return view('admin.users.show', [
            'user' => $user->load('customer'),
        ]);
    }

    public function edit(User $user): View
    {
        $this->ensureAdmin();

        return view('admin.users.update', [
            'user' => $user->load('customer'),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->ensureAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'gender' => ['required', Rule::in(['M', 'F'])],
            'user_type' => ['required', Rule::in(['C', 'F', 'A'])],
            'photo_file' => ['nullable', 'image', 'max:2048'],
            'nif' => ['nullable', 'string', 'size:9'],
            'address' => ['nullable', 'string'],
            'default_payment_type' => ['nullable', Rule::in(['Visa', 'PayPal', 'MB WAY'])],
            'default_payment_ref' => ['nullable', 'string', 'max:255'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->gender = $validated['gender'];
        $user->user_type = $validated['user_type'];

        if ($request->hasFile('photo_file')) {
            if ($user->hasUploadedPhoto()) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->normalizedPhotoPath());
            }

            $user->photo_url = $request->file('photo_file')->store('photos', 'public');
        }

        $user->save();

        $this->syncCustomerDetails($user, $validated);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('status', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->ensureAdmin();

        if ($user->id === auth()->id()) {
            return back()->withErrors([
                'admin_user' => 'You cannot delete your own administrator account from this screen.',
            ]);
        }

        if ($user->customer) {
            $user->customer->delete();
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'User deleted successfully.');
    }

    public function toggleBlock(User $user): RedirectResponse
    {
        $this->ensureAdmin();

        if ($user->id === auth()->id()) {
            return back()->withErrors([
                'admin_user' => 'You cannot block or unblock your own administrator account.',
            ]);
        }

        $user->blocked = ! $user->blocked;
        $user->save();

        return redirect()
            ->route('admin.users.edit', $user)
            ->with('status', $user->blocked ? 'User blocked successfully.' : 'User unblocked successfully.');
    }

    protected function syncCustomerDetails(User $user, array $validated): void
    {
        $hasCustomerData = collect([
            $validated['nif'] ?? null,
            $validated['address'] ?? null,
            $validated['default_payment_type'] ?? null,
            $validated['default_payment_ref'] ?? null,
        ])->contains(fn ($value) => filled($value));

        if (! $user->customer && ! $hasCustomerData && $user->user_type !== 'C') {
            return;
        }

        Customer::query()->updateOrCreate(
            ['id' => $user->id],
            [
                'nif' => $validated['nif'] ?? null,
                'address' => $validated['address'] ?? null,
                'default_payment_type' => $validated['default_payment_type'] ?? null,
                'default_payment_ref' => $validated['default_payment_ref'] ?? null,
            ]
        );
    }
}
