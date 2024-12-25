@extends('backend.layouts.master')

@section('content')
<style>
    .total_ticket {
    display: inline-block;
    background-color: red;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    text-align: center;
    line-height: 20px;
    font-size: 14px;
    position: relative;
    top: -10px;
    left: 5px;
}
</style>
<div class=" mt-4">
    <h2 class="text-center bg-primary text-white py-2">Hiện trạng tài khoản của bạn</h2>

    <div class="row text-center">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <img src="{{ asset('account_status_1.png') }}" class="img-fluid"
                        style="max-width: 70px;">
                    <h3 class="total_service">0</h3>
                    <p>Dịch vụ đang sử dụng</p>
                    <a href="" class="btn btn-link">Chi tiết</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <img src="{{ asset('account_status_2.png') }}" class="img-fluid"
                        style="max-width: 70px;">
                    <h3 class="total_order">0</h3>
                    <p>Đơn hàng chưa thanh toán</p>
                    <a href="{{ route('customer.order.index', ['status' => 'nopayment']) }}" class="btn btn-link">Chi tiết</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <img src="{{ asset('account_status_3.png') }}" class="img-fluid"
                        style="max-width: 70px;">
                    <h3 class="total_file">0</h3>
                    <p>Tên miền cần cập nhật hồ sơ</p>
                    <a href="" class="btn btn-link">Chi tiết</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <img src="{{ asset('account_status_4.png') }}" class="img-fluid"
                        style="max-width: 70px;">
                    <h3 class="total_service_expire">0</h3>
                    <p>Dịch vụ cần gia hạn</p>
                    <a href="" class="btn btn-link">Chi tiết</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4 text-center">
        <div class="col-md-4 offset-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <img src="{{ asset('account_stratus_user.png') }}" class="img-fluid" style="max-width: 80px;">
                    <h3>
                        Yêu cầu hỗ trợ
                        <sup class="total_ticket">0</sup>
                    </h3>
                    <a href="/" class="btn btn-primary">Lịch sử yêu cầu</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
