<div class="container mt-3">
    <div class="row">
        @foreach ($permission->chunk(4) as $permissionGroup) {{-- Group permissions into chunks of 4 --}}
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title">Permissions</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissionGroup as $permission)
                                    <tr>
                                        <td>{{ $permission->id }}</td>
                                        <td>{{ $permission->name }}</td>
                                        <td>
                                            <form action="{{ route('pr.destroy', $permission->id) }}" method="POST">
                                                @can('update permission')
                                                    <a href="{{ route('pr.edit', $permission->id) }}"
                                                        class="btn btn-success btn-sm">Edit</a>
                                                @endcan
                                                @csrf
                                                @method('DELETE')
                                                @can('delete permission')
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                @endcan
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
