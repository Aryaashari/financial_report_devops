<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <a href="{{ route('category.create') }}"
                        class="px-8 py-2 bg-blue-300 text-center text-sm font-semibold mb-2">Add</a>
                    <table class="w-full border border-gray-300 bg-white shadow-md rounded-lg">
                        <thead>
                            <tr class="bg-gray-200 text-gray-700">
                                <th class="px-4 py-2 border">Name</th>
                                <th class="px-4 py-2 border">Type</th>
                                <th class="px-4 py-2 border">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr class="hover:bg-gray-100">
                                    <td class="px-4 py-2 border">{{ $category->name }}</td>
                                    <td class="px-4 py-2 border text-center"><span
                                            class="px-2 py-1 text-white {{ $category->type == \App\Enums\CategoryType::INCOME->value ? 'bg-green-500 rounded-full' : 'bg-red-500 rounded-full' }} text-xs">{{ $category->type }}</span>
                                    </td>
                                    <td class="px-4 py-2 border text-center">
                                        <a href="{{ route('category.edit', $category->name) }}"
                                            class="px-8 py-2 bg-yellow-400 text-center text-sm font-semibold rounded-full">Edit</a>
                                        <button
                                            class="px-8 py-2 bg-red-400 text-center text-sm font-semibold rounded-full"
                                            onclick="handleDelete('{{ $category->name }}')">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>




    @push('js')
        <script>
            function handleDelete(name) {
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        showLoading();
                        $.ajax({
                            type: "DELETE",
                            url: "{{ route('category.destroy', ':name') }}".replace(':name', name),
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                $.ajax({
                                    url: "{{ route('category.index') }}",
                                    type: "GET",
                                    success: function(data) {
                                        hideLoading();
                                        $("tbody").html($(data).find("tbody")
                                            .html());
                                        Swal.fire({
                                            title: 'Success!',
                                            text: 'Your data has been deleted successfully!',
                                            icon: 'success'
                                        })
                                    },
                                    error: function() {
                                        hideLoading();
                                        Swal.fire({
                                            title: 'Error!',
                                            text: 'Something went errors, please try again later!',
                                            icon: 'error'
                                        })
                                    }
                                });
                            },
                            error: function(xhr) {
                                hideLoading();
                                if (xhr.status == 409) {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: xhr.responseJSON.message,
                                        icon: 'error'
                                    })
                                } else {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'Something went errors, please try again later!',
                                        icon: 'error'
                                    })
                                }
                            }
                        });
                    }
                });
            }
        </script>
    @endpush

</x-app-layout>
