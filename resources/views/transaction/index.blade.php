<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chart Of Account') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex space-x-4">
                        <div class="w-1/5">
                            <a href="{{ route('transaction.create') }}"
                               class="block px-8 py-2 bg-blue-300 text-center text-sm font-semibold mb-2 rounded-md">
                                Add
                            </a>
                        </div>

                        <div class="w-1/5">
                            <button onclick="openModal()"
                                    class="block px-8 py-2 bg-yellow-300 text-center text-sm font-semibold mb-2 rounded-md">
                                Import
                            </button>
                        </div>
                    </div>
                    <br>
                    <table class="w-full border border-gray-300 bg-white shadow-md rounded-lg">
                        <thead>
                            <tr class="bg-gray-200 text-gray-700">
                                <th class="px-4 py-2 border">Date</th>
                                <th class="px-4 py-2 border">COA Code</th>
                                <th class="px-4 py-2 border">COA Name</th>
                                <th class="px-4 py-2 border">Description</th>
                                <th class="px-4 py-2 border">Debit</th>
                                <th class="px-4 py-2 border">Credit</th>
                                <th class="px-4 py-2 border">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
                                <tr class="hover:bg-gray-100">
                                    <td class="px-4 py-2 border">{{ $transaction->date }}</td>
                                    <td class="px-4 py-2 border">{{ $transaction->chartOfAccount->code }}</td>
                                    <td class="px-4 py-2 border">{{ $transaction->chartOfAccount->name }}</td>
                                    <td class="px-4 py-2 border">{{ $transaction->description }}</td>
                                    <td class="px-4 py-2 border">{{ $transaction->debit }}</td>
                                    <td class="px-4 py-2 border">{{ $transaction->credit }}</td>
                                    <td class="px-4 py-2 border text-center">
                                        <a href="{{ route('transaction.edit', $transaction->id) }}"
                                            class="px-8 py-2 bg-yellow-400 text-center text-sm font-semibold rounded-full">Edit</a>
                                        <button
                                            class="px-8 py-2 bg-red-400 text-center text-sm font-semibold rounded-full"
                                            onclick="handleDelete('{{ $transaction->id }}')">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

 <!-- Modal -->
 <div id="modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-xl font-semibold mb-4">Upload File</h2>
        <form id="uploadForm" enctype="multipart/form-data">
        <div class="mb-4">
            <input required type="file" name="file" id="file"
                   class="block w-full text-sm text-gray-600 border border-gray-300 rounded-md cursor-pointer p-2">
        </div>
        <div class="mb-2">
            <a href="{{ url('data/TemplateImport.xlsx') }}">Click here for download the template</a>
        </div>
        <div class="mt-4 flex justify-end">
            <button onclick="closeModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">
                Close
            </button>
            <button type="button" id="import-submit" class="px-4 py-2 bg-blue-300 text-gray-800 ml-2 rounded-lg hover:bg-gray-400">
                Submit
            </button>
        </div>
        </form>

    </div>
</div>



    @push('js')
        <script>
            $("#import-submit").on("click",function(){
                closeModal();
                showLoading();
                let formData = new FormData();
                formData.append("file", $("#file")[0].files[0]);
                $.ajax({
                    type:"POST",
                    url:"{{ route('transaction.import') }}",
                    data:formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    processData: false,
                    contentType: false,
                    success:function(){
                        hideLoading();
                        Swal.fire({
                            title: 'Success!',
                            text: 'Data imported successfully!',
                            icon: 'success'
                        })
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    },
                    error: function(xhr){
                        hideLoading();
                        console.log(xhr)
                        console.log($('meta[name="csrf-token"]').attr('content'));
                        Swal.fire({
                            title: 'Failed!',
                            text: 'Something went errors, please try again!',
                            icon: 'error'
                        })
                    }
                });
            });

            function openModal() {
                document.getElementById('modal').classList.remove('hidden');
            }

            function closeModal() {
                document.getElementById('modal').classList.add('hidden');
            }
            function handleDelete(id) {
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
                            url: "{{ route('transaction.destroy', ':id') }}".replace(':id', id),
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                $.ajax({
                                    url: "{{ route('transaction.index') }}",
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
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Something went errors, please try again later!',
                                    icon: 'error'
                                })
                            }
                        });
                    }
                });
            }
        </script>
    @endpush

</x-app-layout>
