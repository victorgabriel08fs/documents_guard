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
                                    <label for="arquive">Documento</label>
                                    <input type="file" class="form-control-file" id="arquive" name="document">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group">
                                    <label for="password">Senha</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
