<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\TransactionHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class TransactionHistoryController extends Controller
{
    public function index(Request $request, $status = null)
    {
        $title = "Lịch sử";
        $user = Auth::user(); // Lấy thông tin người dùng hiện tại

        if ($request->ajax()) {
            // Sử dụng 'with' để eager load bảng 'users' liên kết với 'transaction_histories'
            $data = TransactionHistory::with('user') // eager load thông tin người dùng
                ->select('transaction_histories.*');

            // Nếu user là admin (role_id = 1), lấy tất cả dữ liệu, nếu không chỉ lấy dữ liệu của người dùng đó
            if ($user->role_id != 1) {
                // Lọc theo user_id nếu không phải admin
                $data = $data->where('user_id', $user->id);
            }

            // Tiến hành truy vấn và trả về dữ liệu cho DataTables
            return DataTables::of($data)
                // Thêm filter cho 'user_name' để tìm kiếm theo tên người dùng và email
                ->filterColumn('user_name', function ($query, $keyword) {
                    $query->whereHas('user', function ($q) use ($keyword) {
                        $q->where('full_name', 'like', "%$keyword%")
                            ->orWhere('email', 'like', "%$keyword%");
                    });
                })
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('Y-m-d H:i:s');
                })
                ->editColumn('user_id', function ($row) {
                    return '<p>' . $row->user->full_name . '( ' . $row->user->email . ' ) </p>';
                })
                ->editColumn('amount', function ($row) {
                    if ($row->status == 1) {
                        return '<span style="color: green;">' . number_format($row->amount) . '</span>';
                    } else {
                        return '<span style="color: red;">- ' . number_format($row->amount) . '</span>';
                    }
                })
                ->editColumn('detail', function ($row) {
                    return '<a href="' . route('order.show', $row->id) . '" class="btn btn-primary btn-sm edit"> Chi tiết </a>';
                })
                ->addColumn('action', function ($row) use ($user) {
                    // Nếu user không phải admin (role_id khác 1), trả về rỗng cho cột action
                    if ($user->role_id != 1) {
                        return ''; // Không hiển thị action
                    }
                    // Nếu là admin, hiển thị hành động xóa
                    return '<div style="display: flex;">
                            <a href="#" class="btn btn-danger btn-sm delete"
                               onclick="event.preventDefault(); document.getElementById(\'delete-form-' . $row->id . '\').submit();">
                               <i class="fas fa-trash btn-delete" title="Xóa"></i>
                            </a>
                            <form id="delete-form-' . $row->id . '" action="' . route('order.delete', $row->id) . '" method="POST" style="display:none;">
                                ' . csrf_field() . '
                            </form>
                        </div>';
                })
                ->rawColumns(['action', 'detail', 'amount', 'user_id'])
                ->make(true);
        }

        $page = 'Lịch sử giao dịch';
        return view('backend.history.index', compact('title', 'page'));
    }
}