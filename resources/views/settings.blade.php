@extends('layouts.app')

@section('title', 'Настройки')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="col-auto">
                        <form method="POST" action="/settings" accept-charset="UTF-8" enctype="multipart/form-data">
                            @csrf
                            <fieldset>
                                <legend>Зона 1</legend>
                                <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label">Стоимость</label>
                                    <div class="col-md-10">
                                        <input class="form-control" name="price_1" type="number" value="{{ $config['price_1'] }}">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label">Бесплатно от</label>
                                    <div class="col-md-10">
                                        <input class="form-control" name="free_1" type="number" value="{{ $config['free_1'] }}">
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>Зона 2</legend>
                                <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label">Стоимость</label>
                                    <div class="col-md-10">
                                        <input class="form-control" name="price_2" type="number" value="{{ $config['price_2'] }}">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label">Бесплатно от</label>
                                    <div class="col-md-10">
                                        <input class="form-control" name="free_2" type="number" value="{{ $config['free_2'] }}">
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>Зона 3</legend>
                                <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label">Стоимость</label>
                                    <div class="col-md-10">
                                        <input class="form-control" name="price_3" type="number" value="{{ $config['price_3'] }}">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label">Бесплатно от</label>
                                    <div class="col-md-10">
                                        <input class="form-control" name="free_3" type="number" value="{{ $config['free_3'] }}">
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>Вне зоны</legend>
                                <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label">Стоимость</label>
                                    <div class="col-md-10">
                                        <input class="form-control" name="price_4" type="number" value="{{ $config['price_4'] }}">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label">Бесплатно от</label>
                                    <div class="col-md-10">
                                        <input class="form-control" name="free_4" type="number" value="{{ $config['free_4'] }}">
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <legend>Общее</legend>
                                <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label">Минимальная сумма заказа</label>
                                    <div class="col-md-10">
                                        <input class="form-control" name="min_sum" type="number" value="{{ $config['min_sum'] }}">
                                    </div>
                                </div>
                            </fieldset>
                            <button type="submit" class="btn btn-primary mt-2">Сохранить</button>
                        </form>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
@endsection
