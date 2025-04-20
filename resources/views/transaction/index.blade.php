<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transaction') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <a href="{{ route('transaction.create') }}"
                           class="px-8 py-2 bg-blue-300 text-sm font-semibold">
                            Add
                        </a>
                    </div>
    
                    <table id="transactionTable" class="w-full border border-gray-300 bg-white rounded-lg text-center">
                        <thead>
                            <tr class="bg-gray-200 text-gray-700">
                                <th class="px-4 py-3 cursor-pointer hover:text-blue-600">Date<span class="text-xs"> ▼</span></th>
                                <th class="px-4 py-2 cursor-pointer hover:text-blue-600">COA Code<span class="text-xs"> ▼</span></th>
                                <th class="px-4 py-2 cursor-pointer hover:text-blue-600">COA Name<span class="text-xs"> ▼</span></th>
                                <th class="px-4 py-2 cursor-pointer hover:text-blue-600">Description<span class="text-xs"> ▼</span></th>
                                <th class="px-4 py-2 cursor-pointer hover:text-blue-600">Debit<span class="text-xs"> ▼</span></th>
                                <th class="px-4 py-2 cursor-pointer hover:text-blue-600">Credit<span class="text-xs"> ▼</span></th>
                                <th class="px-4 py-2 cursor-pointer hover:text-blue-600">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    {{-- tambah CSS datatables --}}
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="{{ asset('css/datatables-custom.css') }}">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

        <style>
            .dataTables_wrapper .dataTables_filter input {
                @apply px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400;
            }
            .dataTables_wrapper .dataTables_length select {
                @apply px-2 py-1 border border-gray-300 rounded-md;
            }
        </style>
    @endpush

    @push('js')
        {{-- tambah JS datatables --}}
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

        <script>
            // inisiasi datatables
            $(document).ready(function () {
                $('#transactionTable').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    pagingType: "full_numbers",
                    lengthMenu: [10, 25, 50, 100],
                    ajax: "{{ route('transaction.data') }}",
                    columns: [
                        { data: 'date', name: 'date' },
                        { data: 'coa_code', name: 'chart_of_accounts.code' },
                        { data: 'coa_name', name: 'chart_of_accounts.name' },
                        { data: 'description', name: 'description' },
                        { data: 'debit', name: 'debit' },
                        { data: 'credit', name: 'credit' },
                        { data: 'action', name: 'action', orderable: false, searchable: false },
                    ],
                    dom:
                        "<'flex flex-wrap items-center justify-between mb-4'<'flex items-center space-x-2'l><'flex justify-end'f>>" +
                        "tr" +
                        "<'flex flex-wrap items-center justify-between mt-4'<'text-sm text-gray-600'i><'flex justify-end'p>>",
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search...",
                        lengthMenu: "Show _MENU_ entries",
                        paginate: {
                            previous: "‹",
                            next: "›",
                            first: "«",
                            last: "»"
                        }
                    },

                    drawCallback: function () {
                        // Style pagination buttons
                        $('.dataTables_paginate').addClass('flex space-x-2');
                        $('.dataTables_paginate a').addClass('px-3 py-1 rounded border border-gray-300 hover:bg-blue-500 hover:text-white');
                        $('.dataTables_paginate .current').addClass('bg-blue-500 font-bold');
                    }
                });
            });
        
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
