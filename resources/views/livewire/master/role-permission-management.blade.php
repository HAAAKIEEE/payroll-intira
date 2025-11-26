<div class="p-4">
    <h2 class="text-xl font-bold mb-4">Manage Role Permissions</h2>

    @if (session('success'))
    <div class="bg-green-100 text-green-700 p-2 rounded mb-3">
        {{ session('success') }}
    </div>
    @endif
    @if ($roles->isEmpty())
    <p class="text-red-500">Belum ada role di database.</p>
    @endif
    <div class="mb-4">
        <label for="role" class="block font-semibold mb-2">Pilih Role:</label>
        <select wire:model.lazy="selectedRoleId" class="border rounded p-2 w-64">
            <option value="">-- Pilih Role --</option>
            @foreach ($roles as $role)
            <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
            @endforeach
        </select>

    </div>

    <p class="mt-2 text-sm text-gray-500">Selected Role ID: {{ $selectedRoleId }}</p>

    @if ($selectedRoleId)
    <div>
        <h3 class="font-semibold mb-2">Permissions:</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
            @foreach ($permissions as $permission)
            <label wire:key="perm-{{ $permission->id }}" class="flex items-center space-x-2 border p-2 rounded">
                <input type="checkbox" wire:click="togglePermission('{{ $permission->name }}')" {{
                    in_array($permission->name, $rolePermissions) ? 'checked' : '' }}
                >
                <span>{{ $permission->name }}</span>
            </label>
            @endforeach

        </div>
    </div>
    @endif
</div>