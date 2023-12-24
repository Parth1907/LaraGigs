<x-layout>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                lengthMenu: [5, 10, 25, 50],
                pageLength: 5,
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "/listings/datatable",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"},
                    // "dataType": "json"
                },
                columns: [ 
                    {data: "id"},
                    {data: "title"},
                    {data: "logo"},
                    {data: "tags"},
                    {data: "company"},
                    {data: "location"},
                    {data: "email"},
                    {data: "website"},
                ], 
                "bDestroy": true
            });
        });
    </script>
    <x-card>
        <header>
            <h1 class="my-6 text-center text-3xl font-bold uppercase">
                Manage Table
            </h1>
        </header>

        {{-- @unless ($listings->isEmpty()) --}}
            <table id="dataTable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>Job_Id</th>
                        <th>Title</th>
                        <th>Logo</th>
                        <th>Tag</th>
                        <th>Company</th>
                        <th>Location</th>
                        <th>Email</th>
                        <th>Website</th>
                    </tr>
                </thead>
                
            {{-- @else
                <tr class="border-gray-300">
                    <td class="border-b border-t border-gray-300 px-4 py-8 text-lg">
                        <p class="text-center">No Listings Found</p>
                    </td>
                </tr>
            @endunless --}}
        </table>
    </x-card>
</x-layout>
