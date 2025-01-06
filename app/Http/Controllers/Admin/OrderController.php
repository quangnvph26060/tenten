<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\DataTables;

class OrderController extends Controller
{
    public function index(Request $request, $status = null)
    {
        $title = "Đơn hàng";

        if ($request->ajax()) {
            $data = Order::where('status', $status)->select('*');
            return DataTables::of($data)
                ->editColumn('code', function ($row) {
                    return '<a href="' . route('order.show', $row->id) . '" class=" text-primary "> '. $row->code .'</a>';
                })
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('Y-m-d H:i:s');
                })
                ->editColumn('amount', function ($row) {
                    return number_format($row->amount);
                })
                ->editColumn('status', function ($row) {
                    return $row->status == 'payment'
                    ? '<span style="color: orange;">Đã thanh toán</span>'
                    : ($row->status == 'active'
                        ? '<span style="color: green;">Đã duyệt</span>'
                        : '<span style="color: red;">Chưa thanh toán</span>');
                })
                ->editColumn('payment', function ($row) {
                    return number_format($row->payment);
                })
                // ->editColumn('detail', function ($row) {
                //     return '<a href="' . route('order.show', $row->id) . '" class="btn btn-primary btn-sm edit"> Chi tiết </a>';
                // })->rawColumns(['detail'])
                ->addColumn('action', function ($row) {
                    return $row->status == 'payment'
                    ? '<div style="display: flex;">
                            <a href="#" class="btn btn-orange btn-sm delete"
                                onclick="confirmActive(event, ' . $row->id . ')">
                               Duyệt
                            </a>
                            <form id="active-form-' . $row->id . '" action="' . route('order.active', $row->id) . '" method="POST" style="display:none;">
                                ' . csrf_field() . '
                            </form>
                        </div>'
                    : ($row->status == 'pending'
                        ? '<span style="color: orange;">Chờ duyệt</span>'
                        : '<div style="display: flex;">
                                <a href="#" class="btn btn-danger btn-sm delete"
                                    onclick="confirmDelete(event, ' . $row->id . ')">
                                    <i class="fas fa-trash btn-delete" title="Xóa"></i>
                                </a>
                                <form id="delete-form-' . $row->id . '" action="' . route('order.delete', $row->id) . '" method="POST" style="display:none;">
                                    ' . csrf_field() . '
                                </form>
                            </div>');


                })->rawColumns(['action', 'status', 'code'])
                ->make(true);
        }
        $page = 'Đơn hàng';
        return view('backend.order.index', compact('title', 'page'));
    }

    public function show($id)
    {
        $title = "Chi tiết đơn hàng";
        $page = "Đơn hàng";
        $order = Order::findOrFail($id);
        return view('backend.order.show', compact('order', 'title', 'page'));
    }

    public function delete($id){
        $order = Order::find($id);
        $order->orderDetail()->delete();
        $order->delete();
        return redirect()->back()->with('success', 'Đơn hàng đã xóa thành công');
    }

    public function active($id){
        $order = Order::find($id);
        if($order->order_type == 2){
            $renewService = $order->orderDetail;
                $renewService->each(function ($service) {
                    $orderdetail = OrderDetail::find($service->orderdetail_id);
                    $orderdetail->update([
                        'price' => $orderdetail->price + $service->price,
                        'number' => $orderdetail->number + $service->number
                    ]);
                    $ordernew = Order::find($orderdetail->order_id);
                    $ordernew->update([
                        'amount' => $ordernew->orderDetail->sum('price'),
                    ]);
                });

        }
        $order->update([
            'status' => 'active',
            'active_at' => now(),
        ]);
        $order->orderDetail()->update([
            'status' => 'active',
            'active_at' => now(),
        ]);
        return redirect()->route('order.show', ['id' => $id])->with('success', 'Đơn hàng đã được kích hoạt');
    }
}
