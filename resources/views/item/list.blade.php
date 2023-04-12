@extends('layouts.app')

@section('title', @$viewInfo['listTitle'])

@section('content')

    <style>
        td img {
            transition: all 0.3s ease 0s;
        }
        td img:hover {
            transform: translate(-100px, -80px) scale(5);
        }
    </style>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">


                    <h4 class="card-title">{{ @$viewInfo['listSubTitle'] }}
                    </h4>
                    @if (@$viewInfo['listDesc'])
                        <p class="card-title-desc">{{ @$viewInfo['listDesc'] }}</p>
                    @endif

                    @if (isset($filter))
                        @include('layouts.filter', ['filter' => @$filter])
                    @elseif(class_basename(@$list[0]) != 'Order')
{{--                        @can('create-' . $viewInfo['mainRoute'])--}}
                            <a href="{{ route($viewInfo['mainRoute'] . '.create') }}" class="btn btn-outline-success float-end">Создать</a>
{{--                        @endcan--}}
                    @endif

                    <div class="table-rep-plugin">
                        <div class="table-responsivex mb-0" data-pattern="priority-columns">
                            <table id="tech-companies-1" class="table table-striped">
                                <thead>
                                <tr>
                                    @foreach($fields as $field => $fieldName)
                                        <th data-priority="1">{{ $fieldName }}</th>
                                    @endforeach
{{--                                    @if (Gate::allows('edit-' . $viewInfo['mainRoute']) || Gate::allows('delete-' . $viewInfo['mainRoute']))--}}
                                        <th data-priority="2">Действия</th>
{{--                                    @endif--}}
                                </tr>

                                </thead>
                                <tbody>
                                @foreach($list as $el)
                                    <tr>
                                        @foreach($fields as $field => $fieldName)
                                            @if ($field == 'file')
                                                <td style="padding: 0px;"><img src="{{ $el[$field] }}" height="50" alt=""></td>
                                            @else
                                                <td>{{ $el[$field] }}</td>
                                            @endif
                                        @endforeach
{{--                                        @if (Gate::allows('edit-' . $viewInfo['mainRoute']) || Gate::allows('delete-' . $viewInfo['mainRoute']) || Gate::allows('remove-bonus-' . $viewInfo['mainRoute']))--}}
                                            <td style="text-align:right;">
                                                <div style="display: flex;">
{{--                                                @if(Gate::allows('edit-' . $viewInfo['mainRoute']) || Gate::allows('remove-bonus-' . $viewInfo['mainRoute']))--}}
                                                    <a href="{{ route($viewInfo['mainRoute'] . '.edit', $el->id) }}" class="btn  btn-outline-success btn-sm">Редактировать</a>
{{--                                                @endif--}}
                                                    @if (class_basename($el) != 'Order')
{{--                                                @can('delete-' . $viewInfo['mainRoute'])--}}
                                                    <form action="{{ route($viewInfo['mainRoute'] . '.destroy', $el->id) }}" method="POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button class="btn btn-outline-danger btn-sm" style="margin-left: 5px;">Удалить</button>
                                                    </form>
{{--                                                @endcan--}}
                                                    @endif

                                                    @if (class_basename($el) == 'Product')
                                                        <form action="{{ route($viewInfo['mainRoute'] . '.stop', $el->id) }}" method="POST">
                                                            @csrf
                                                            <button class="btn <?=$el->in_stop?'btn-outline-danger':'btn-outline-default'?> btn-sm" style="margin-left: 5px;"><i class="ri-stop-circle-fill"></i></button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
{{--                                        @endif--}}
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>

                    {{ $list->withQueryString()->links() }}
                </div>

            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection

@section('addjs')
    <!-- Responsive Table js -->
    <script src="/assets/libs/admin-resources/rwd-table/rwd-table.min.js"></script>

    <!-- Init js -->
    <script src="/assets/js/pages/table-responsive.init.js"></script>
@endsection
