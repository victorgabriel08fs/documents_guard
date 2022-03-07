@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Documento') }}</div>

                    <div class="card-body">

                        <form action="{{ route('auth.download') }}" method="post">
                            @csrf
                            <div class="row mb-3">
                                <div class="form-group">
                                    <label for="hash">ID</label>
                                    <input type="text" class="form-control" id="hash" name="hash">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group">
                                    <label for="password">Senha</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Enviar</button>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
