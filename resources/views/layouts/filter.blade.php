@if (isset($filter))
    <div id="accordion" style="float: left; width: 100%;">
        <div class="card mb-0">
            <div class="card-header" id="headingOne" style="position:relative; background-color: #1b2c3f;">

                <a data-bs-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" class="text-dark" style="position: absolute; width: 100%; height: 100%; top: 0; left: 0; z-index: 1;">
                </a>
                <h5 class="m-0 font-size-14" style="position: relative; color: #eee;">
                    <b>Фильтр</b>

                    @can('create-' . $viewInfo['mainRoute'])
                        <a href="{{ route($viewInfo['mainRoute'] . '.create') }}" class="btn btn-sm btn-outline-success float-end" style="position: relative; z-index: 2;">Создать</a>
                    @endcan

                    @if($viewInfo['mainRoute'] == 'clients')
                        @can('create-serts')
                            <a href="{{ route($viewInfo['mainRoute'] . '.create') }}" class="btn btn-outline-success float-end">Создать сертификат</a>
                        @endcan
                    @endif
                </h5>
            </div>

            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion" style="">
                <div class="card-body" style="border: 1px solid #1b2c3f; border-radius: 0 0 0.4rem 0.4rem;">
                    <form action="{{ route($viewInfo['mainRoute'].'.index') }}" method="GET">
                        <div class="row">
                            @foreach($filter as $field => $fdata)
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">{{ $fdata['title'] }}</label>

                                    @if ($fdata['type'] == 'daterange')
                                        <div class="input-daterange input-group" id="datepicker_{{ $field }}" data-date-format="yyyy-mm-dd" data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker_{{ $field }}'>
                                            {{ Form::text($field . '_from', @$fdata['value']['from'], ['class' => 'form-control', 'placeholder' => $fdata['title'] . ' От']) }}
                                            {{ Form::text($field . '_to', @$fdata['value']['to'], ['class' => 'form-control', 'placeholder' => $fdata['title'] . ' До']) }}
                                        </div>

                                    @elseif ($fdata['type'] == 'range')
                                        <div class="input-range input-group">
                                            {{ Form::text($field . '_from', @$fdata['value']['from'], ['class' => 'form-control', 'placeholder' => $fdata['title'] . ' От']) }}
                                            {{ Form::text($field . '_to', @$fdata['value']['to'], ['class' => 'form-control', 'placeholder' => $fdata['title'] . ' До']) }}
                                        </div>

                                    @elseif ($fdata['type'] == 'select')
                                        {{ Form::select($field, $fdata['options'], @$fdata['value'], ['class' => 'form-control']) }}

                                    @else
                                        <div class="input-group">
                                            {{ Form::text($field, @$fdata['value'], ['class' => 'form-control']) }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <button type="submit" class="btn btn-outline-success">Применить</button>
                        <a href="{{ route($viewInfo['mainRoute'].'.index') }}" class="btn btn-outline-warning">Очистить</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

<style type="text/css">
    #accordion input, #accordion select {
        border-color: #1b2c3f;
    }
</style>
