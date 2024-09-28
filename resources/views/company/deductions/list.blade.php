<h5>Records</h5>
<table class="table mb-0">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Deduction Type</th>
            <th>Description</th>
            <th>Value</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($deductions as $ded)
        <tr>
            <th scope="row">{{$loop->iteration}}</th>
            <td>{{$ded->deduction_type}}</td>
            <td>{{$ded->description}}</td>
            <td>{{$ded->unit == '$' || $ded->unit == '₱' ? $ded->unit : ''}}{{$ded->value}}{{$ded->unit == '%' ? $ded->unit : ''}}</td>
            <td><span class="badge {{$ded->status == 'active' ? 'bg-success' : 'bg-danger'}}">{{$ded->status}}</span></td>
            <td>
                <i data-id='{{$ded->ded_id}}' class='edit-btn bx bx-pencil text-warning font-size-18' style='cursor: pointer;'></i>
                <!-- <i data-id='{{$ded->ded_id}}' class='delete-btn bx bx-trash text-danger font-size-18 ms-1' style='cursor: pointer;'></i> -->
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class='text-center'>No Records Found</td>
        </tr>
        @endforelse
    </tbody>
</table>