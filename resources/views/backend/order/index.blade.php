@extends('backend.layouts.master')

@section('content')
    <div class="content">
        <!-- Bảng danh sách danh mục -->
        <div class="category-list">
            <div class="card-tools mb-3" id="add-category-btn">
                <a href="{{ route('client.create') }}" class="btn btn-primary btn-sm">Thêm mới (+)</a>
            </div>
            <table class="table table-striped table-hover" id="categoryTable">
                <thead>
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Chi tiết</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái thanh toán</th>
                        <th>Ngày đặt hàng</th>
                        <th>Thanh toán</th>
                        <th>Xóa hóa đơn</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        #add-category-btn {
            display: flex;
            justify-content: flex-end;
            align-items: center; */
            /* text-align: end; */
            padding: 10px;
            margin-right: 100px;
        }


        td a {
            padding: 8px 11px !important;
            border-radius: 5px;
            color: white;
            display: inline-block;
        }

        .edit {
            background: #ffc107;
            margin: 0px 15px;
        }

        .delete {
            background: #dc3545;
            padding: 8px 12px !important;
        }
    </style>

@endpush

@push('scripts')

    <script type="text/javascript">
        $(document).ready(function() {
            $('#categoryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('order.index', ['status' => request()->status]) }}',
                columns: [
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'detail',
                        name: 'detail'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },

                    {
                        data: 'status',
                        name: 'identity_number'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'payment',
                        name: 'payment'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                columnDefs: [{
                        width: '16%',
                        targets: 0
                    },
                    {
                        width: '16%',
                        targets: 1
                    },
                    {
                        width: '15%',
                        targets: 1
                    },
                    {
                        width: '15%',
                        targets: 1
                    },

                    {
                        width: '20%',
                        targets: 1
                    },
                    {
                        width: '15%',
                        targets: 1
                    },

                ],
                pagingType: "full_numbers", // Kiểu phân trang
                language: {
                    paginate: {
                        previous: '&laquo;', // Nút trước
                        next: '&raquo;' // Nút sau
                    },
                    lengthMenu: "Hiển thị _MENU_ mục mỗi trang",
                    zeroRecords: "Không tìm thấy dữ liệu",
                    info: "Hiển thị _START_ đến _END_ của _TOTAL_ mục",
                    infoEmpty: "Không có dữ liệu để hiển thị",
                    infoFiltered: "(lọc từ _MAX_ mục)"
                },
                dom: '<"row"<"col-md-6"l><"col-md-6"f>>t<"row"<"col-md-6"i><"col-md-6"p>>',
                lengthMenu: [10, 25, 50, 100],
            });
        });
    </script>
@endpush
