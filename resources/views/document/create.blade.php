@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Upload de Documento') }}</div>

                    <div class="card-body">

                        <form action="{{ route('document.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <div class="form-group">
                                    <label for="name">Nome do Arquivo</label>
                                    <input type="text" class="form-control" id="name" name="name">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group">
                                    <div class="row">
                                        <label for="arquive">Documento</label>
                                    </div>
                                    <input type="file" class="form-control-file" id="arquive" name="document">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col form-group mr-4">
                                    <label for="password">Senha</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                                <div class="col form-group">
                                    <label for="confirm_password">Confirme a Senha</label>
                                    <input type="confirm_password" class="form-control" id="confirm_password"
                                        name="confirm_password">
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
