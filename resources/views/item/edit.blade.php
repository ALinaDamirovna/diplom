@extends('layouts.app')

@section('title', (@$item->id == NULL ? $viewInfo['createTitle'] : $viewInfo['editTitle'] . ': ' . $item->id))

@section('content')

    <style>
        td small {
            display: none;
        }
        @media (max-width: 600px) {
            .table {
                margin-bottom: 0;
            }

            tr {
                display: grid;
                border: 1px solid firebrick;
                margin-bottom: 10px;
            }

            td small {
                background: #333;
                padding: 3px;
                border-radius: 4px;
                color: #fff;
                display: initial;
            }

            .table tr td {
                padding: .25rem .25rem;
                border: none;
            }

            .hidemob {
                display: none;
            }
        }
    </style>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if (@$item->id != NULL)
                        {{ Form::model($item, ['route' => [$viewInfo['mainRoute'] . '.update', $item->id], 'method' => 'PUT', 'files' => true]) }}
                    @else
                        {{ Form::model($item, ['route' => [$viewInfo['mainRoute'] . '.store'], 'method' => 'POST', 'files' => true]) }}
                    @endif
                    @foreach ($fields as $field => $fieldName)
                        <div class="mb-3 row">
                            <label for="example-text-input" class="col-md-2 col-form-label">{{ $fieldName }}</label>
                            <div class="col-md-10">
                                @if ($field == 'id')
                                    {{ Form::text($field, $item[$field], ['class' => 'form-control', 'readonly' => true]) }}
                                @elseif ($fieldTypes[$field] == 'datetime')
                                    <input class="form-control" name="{{ $field }}" type="datetime-local" value="{{ @$item[$field] }}" id="example-datetime-local-input">
                                @elseif ($fieldTypes[$field] == 'number')
                                    {{ Form::number($field, $item[$field], ['class' => 'form-control']) }}
                                @elseif ($fieldTypes[$field] == 'file')
                                    {{ Form::file($field, $item[$field], ['class' => 'form-control']) }}
                                @elseif ($fieldTypes[$field] == 'bool')
                                    {{ Form::checkbox($field, 1, $item[$field], ['class' => 'form-check-input wh30']) }}
                                @elseif ($fieldTypes[$field] == 'readonly')
                                    {{ Form::text($field, $item[$field], ['class' => 'form-control', 'readonly' => true]) }}
                                @elseif ($fieldTypes[$field] == 'textarea_readonly')
                                    {{ Form::textarea($field, $item[$field], ['class' => 'form-control', 'readonly' => true, 'rows' => 3]) }}
                                @elseif ($field == 'category')
                                    {{ Form::select($field, $cats, $item[$field], ['class' => 'form-control']) }}
                                @elseif ($field == 'type' && $fieldTypes[$field] == 'select')
                                    {{ Form::select($field, $types, $item[$field], ['class' => 'form-control']) }}
                                @else
                                    {{ Form::text($field, $item[$field], ['class' => 'form-control']) }}
                                @endif
                            </div>
                        </div>
                    @endforeach

                        @if (class_basename($item) == 'Product')
                            <div class="mt-4 mt-lg-0">
                                <h4 class="mb-3">Опции</h4>
                                @foreach($optionGroups as $group)

                                    <h5 class="mt-3">{{ $group['name'] }}</h5>
                                    @foreach($group['items'] as $id => $name)
                                    <div class="form-check form-check-inline">
                                        {{ Form::checkbox('options['.$id.']', 1, isset($item->options[$id]), ['class' => 'form-check-input wh30', 'id' => 'inlineCheckOp'.$id]) }}
                                        <label class="form-check-label" for="inlineCheckOp{{ $id }}">{{ $name }}</label>
                                    </div>
                                    @endforeach
                                @endforeach
                            </div>

                            <div class="mt-4 mt-lg-0">
                                <h4 class="mb-3">Добавки</h4>
                                @foreach($additions as $id => $name)
                                    <div class="form-check form-check-inline">
                                        {{ Form::checkbox('additions['.$id.']', 1, isset($item->additions[$id]), ['class' => 'form-check-input wh30', 'id' => 'inlineCheckAd'.$id]) }}
                                        <label class="form-check-label" for="inlineCheckAd{{ $id }}">{{ $name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary mt-2">Сохранить</button>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div> <!-- end col -->

        @if (class_basename($item) == 'Order')

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table">
                            <tr class="hidemob">
                                <th>Название</th>
                                <th>Кол-во</th>
                                <th>Опции</th>
                                <th>Допы</th>
                                <th>Сумма</th>
                            </tr>

                            @foreach ($item->products as $product)
                                <tr>
                                    <td><small>Название:</small> {{ @$product->model->name }}</td>
                                    <td><small>Кол-во:</small> {{ $product->quantity }}</td>
                                    <td>
                                        <small>Опции:</small>
                                        @foreach($product->option as $option)
                                            {{ @$option['model']->cat_name }}: {{ @$option['model']->name }}<br>
                                        @endforeach
                                    </td>
                                    <td>
                                        <small>Допы:</small>
                                        @foreach($product->additional as $addition)
                                            x{{ $addition['quantity'] }} {{ @$addition['model']->name }}<br>
                                        @endforeach
                                    </td>
                                    <td><small>Сумма:</small> {{ $product->total_price }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div> <!-- end col -->
        @endif
    </div>
@endsection
