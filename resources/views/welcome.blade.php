@extends('layouts.app')

@section('title', 'Главная')

@section('content')


    @can('view-transactions')
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Последние транзакции</h4>

                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap mb-0">
                                <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">ID клиента</th>
                                    <th scope="col">Бонус</th>
                                    <th scope="col">Тип</th>
                                    <th scope="col">Дата создания</th>
                                    <th scope="col">Дедлайн</th>
                                    <th scope="col">ID заказа</th>
                                    <th scope="col">ID магазина</th>

                                    @if (Gate::allows('edit-transactions') || Gate::allows('delete-transactions'))
                                        <th scope="col">Действия</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($lastTransactions as $el)
                                    <tr>
                                        <td>{{ $el->ID }}</td>
                                        <td>{{ $el->CLIENT_ID }}</td>
                                        <td>{{ $el->BONUS }}</td>
                                        <td>{{ $el->TYPE }}</td>
                                        <td>{{ $el->DATE_CREATE }}</td>
                                        <td>{{ $el->DATE_DEADLINE }}</td>
                                        <td>{{ $el->ORDER_ID }}</td>
                                        <td>{{ $el->SHOP_ID }}</td>

                                        @if (Gate::allows('edit-transactions') || Gate::allows('delete-transactions'))
                                            <td style="display: flex;">
                                                @can('edit-transactions')
                                                    <a href="{{ route('transactions.edit', $el->ID) }}" class="btn btn-outline-success btn-sm">Edit</a>
                                                @endcan
                                                @can('delete-transactions')
                                                    <form action="{{ route('transactions.destroy', $el->ID) }}" method="POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button class="btn btn-outline-danger btn-sm" style="margin-left: 5px;">Delete</button>
                                                    </form>
                                                @endcan
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- end card-body -->
                </div>
                <!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    @endcan
    @can('view-clients')
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Последние клиенты</h4>

                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap mb-0">
                                <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">ФИО</th>
                                    <th scope="col">Телефон</th>
                                    <th scope="col">Штрихкод</th>
                                    <th scope="col">Дата рожд</th>
                                    <th scope="col">Бонус</th>
                                    <th scope="col">Сумма</th>
                                    <th scope="col">Процент</th>
                                    <th scope="col">Дата создания</th>
                                    <th scope="col">Дата редактирования</th>
                                    <th scope="col">VIP</th>
                                    @if (Gate::allows('edit-clients') || Gate::allows('delete-clients'))
                                        <th scope="col">Действия</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($lastClients as $el)
                                    <tr>
                                        <td>{{ $el->ID }}</td>
                                        <td>{{ $el->FIO }}</td>
                                        <td>{{ $el->PHONE }}</td>
                                        <td>{{ $el->BARCODE }}</td>
                                        <td>{{ $el->HAPPY_BIRTHDAY }}</td>
                                        <td>{{ $el->BONUS }}</td>
                                        <td>{{ $el->SUM }}</td>
                                        <td>{{ $el->PERCENT }}</td>
                                        <td>{{ $el->DATE_CREATE }}</td>
                                        <td>{{ $el->DATE_EDIT }}</td>
                                        <td>{{ $el->PROP_VIP }}</td>

                                        @if (Gate::allows('edit-clients') || Gate::allows('delete-clients'))
                                            <td style="display: flex;">
                                                @can('edit-clients')
                                                    <a href="{{ route('clients.edit', $el->ID) }}" class="btn btn-outline-success btn-sm">Edit</a>
                                                @endcan
                                                @can('delete-clients')
                                                    <form action="{{ route('clients.destroy', $el->ID) }}" method="POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button class="btn btn-outline-danger btn-sm" style="margin-left: 5px;">Delete</button>
                                                    </form>
                                                @endcan
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- end card-body -->
                </div>
                <!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    @endcan
@endsection
