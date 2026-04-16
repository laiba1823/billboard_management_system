@extends('vendor.layouts.parent')

@section('title', 'Orders | Vendor')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <main class="page-content">
        <div class="container">
            @if (Session::has('success'))
                <div class="alert alert-success some-space-upNdown" role="alert">
                    <center style="">
                        {{ session('success') }}
                        <br>
                    </center>
                </div>
            @endif

            @if (Session::has('fail'))
                <div class="alert alert-danger some-space-upNdown" role="alert">
                    <center style="">
                        {{ session('fail') }}
                        <br>
                    </center>
                </div>
            @endif

            {{-- Page Title --}}
            <div class="page-header">
                <h1 class="page-header__title">Orders</h1>
            </div>

            {{-- Bread Crumbs --}}
            <div class="page-tools">
                <div class="page-tools__breadcrumbs">
                    <div class="breadcrumbs">
                        <div class="breadcrumbs__container">
                            <ol class="breadcrumbs__list">

                                <li class="breadcrumbs__item">
                                    <a class="breadcrumbs__link" href="{{ route('vendors.dashboard') }}">
                                        <svg class="icon-icon-home breadcrumbs__icon">
                                            <use xlink:href="#icon-home"></use>
                                        </svg>
                                        <svg class="icon-icon-keyboard-right breadcrumbs__arrow">
                                            <use xlink:href="#icon-keyboard-right"></use>
                                        </svg>
                                    </a>
                                </li>

                                <li class="breadcrumbs__item active">
                                    <a class="breadcrumbs__link" href="{{ route('vendors.orders.index') }}">
                                        <span>Orders</span>
                                    </a>
                                </li>

                            </ol>
                        </div>
                    </div>
                </div>

            </div>

            <div class="table-wrapper">
                <div style="height: 70vh" class="table-wrapper__content table-collapse scrollbar-thin scrollbar-visible" data-simplebar="init">
                    <div class="simplebar-wrapper" style="margin: 0px;">
                        <div class="simplebar-height-auto-observer-wrapper">
                            <div class="simplebar-height-auto-observer"></div>
                        </div>
                        <div class="simplebar-mask">
                            <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                <div class="simplebar-content-wrapper" style="height: auto;">
                                    <div class="simplebar-content" style="padding: 0px;">
                                        <table class="table table--lines">
                                            <colgroup>
                                                <col width="90px">
                                                <col width="200px">
                                                <col width="120px">
                                                <col width="120px">
                                                <col width="100px">
                                                <col width="120px">
                                                <col width="100px">
                                                <col>
                                            </colgroup>
                                            <thead class="table__header">
                                                <tr class="table__header-row">
                                                    <th class="d-none d-lg-table-cell"><span>Order No</span></th>
                                                    <th class="table__th-sort"><span class="align-middle">Billboard Title</span></th>
                                                    <th class="table__th-sort"><span class="align-middle">Buyer Name</span></th>
                                                    <th class="table__th-sort"><span class="align-middle">Buyer Location</span></th>
                                                    <th class="table__th-sort"><span class="align-middle">Amount</span></th>
                                                    <th class="table__th-sort"><span class="align-middle">Placed On</span></th>
                                                    <th class="table__th-sort d-none d-sm-table-cell"><span class="align-middle">Status</span></th>
                                                    <th class="table__actions"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($orders as $order)
                                                    <tr class="table__row">
                                                        <td class="d-none d-lg-table-cell table__td"><span class="text-grey">#{{ $order->number }}</span></td>
                                                        <td class="table__td">{{ $order->billboard->title }}</td>
                                                        <td class="table__td">{{ $order->buyer->name }}</td>
                                                        <td class="table__td">{{ $order->buyer->address ?: 'Pakistan' }}</td>
                                                        <td class="table__td"><span>Rs {{ $order->amount }}</span></td>
                                                        <td class="table__td text-nowrap">
                                                            <span class="text-grey">{{ \Carbon\Carbon::parse($order->created_at)->format('jS F Y') }}</span>
                                                        </td>
                                                        <td class="d-none d-sm-table-cell table__td">
                                                            <div class="table__status">
                                                                @if ($order->status == 'completed')
                                                                    <span class="table__status-icon color-green"></span>
                                                                    Completed
                                                                @elseif ($order->status == 'cancelled')
                                                                    <span class="table__status-icon color-red"></span>
                                                                    Cancelled
                                                                @elseif ($order->status == 'accepted')
                                                                    <span class="table__status-icon color-orange"></span>
                                                                    Accepted
                                                                @elseif ($order->status == 'paid')
                                                                    <span class="table__status-icon color-purple"></span>
                                                                    Paid
                                                                @elseif ($order->status == 'pending')
                                                                    <span class="table__status-icon color-blue"></span>
                                                                    Pending
                                                                @else
                                                                    {{ ucfirst($order->status) }}
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td class="table__td table__actions">
                                                            <div class="items-more">
                                                                <button class="items-more__button">
                                                                    <svg class="icon-icon-more">
                                                                        <use xlink:href="#icon-more"></use>
                                                                    </svg>
                                                                </button>
                                                                <div class="dropdown-items dropdown-items--right">
                                                                    <div class="dropdown-items__container">
                                                                        <ul class="dropdown-items__list">
                                                                            <li class="dropdown-items__item"><a target="_blank" class="dropdown-items__link" href="{{ route('public.billboard.show', ['id' => $order->billboard->id]) }}"><span class="dropdown-items__link-icon">
                                                                                        <svg class="icon-icon-view">
                                                                                            <use xlink:href="#icon-view"></use>
                                                                                        </svg></span>View Billboard</a>
                                                                            </li>
                                                                            <li class="dropdown-items__item"><a target="_blank" class="dropdown-items__link" href="{{ route('public.buyer.show', ['id' => $order->buyer->id]) }}"><span class="dropdown-items__link-icon">
                                                                                        <svg class="icon-icon-view">
                                                                                            <use xlink:href="#icon-view"></use>
                                                                                        </svg></span>View Buyer</a>
                                                                            </li>
                                                                            @if($order->status == 'pending')
                                                                            <li class="dropdown-items__item">
                                                                                <form method="post" action="{{ route('vendors.orders.updateStatus', $order->id) }}" style="display: inline;">
                                                                                    @csrf
                                                                                    <input type="hidden" name="status" value="accepted">
                                                                                    <button type="submit" class="dropdown-items__link" style="background: none; border: none; color: inherit; text-align: left; width: 100%; padding: 0;">
                                                                                        <span class="dropdown-items__link-icon">
                                                                                            <svg class="icon-icon-edit">
                                                                                                <use xlink:href="#icon-edit"></use>
                                                                                            </svg>
                                                                                        </span>Accept Order
                                                                                    </button>
                                                                                </form>
                                                                            </li>
                                                                            @endif
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr class="table__row">
                                                        <td colspan="8" class="table__td text-center">No orders found.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection