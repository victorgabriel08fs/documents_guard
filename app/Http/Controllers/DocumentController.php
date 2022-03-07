<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentRequest;
use App\Models\Document;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('document.auth');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('document.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDocumentRequest  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $roles = [
            'name' => ['required', 'string'],
            'password' => ['required', 'min:8', 'max:8', 'confirmed'],
            'document' => ['required', 'file', 'max:1000'] //max in kB
        ];

        $feedback = [
            'name.required' => 'O campo nome é obrigatório',
            'password.required' => 'O campo senha é obrigatório',
            'document.required' => 'O campo documento é obrigatório',
            'password.min' => 'O campo :attribute precisa ter no mínimo 8',
            'password.max' => 'O campo :attribute deve ter no máximo 8',
            'document.max' => 'O tamanho máximo do arquivo é de 1 MB',
            'password.confirmed' => 'A senha e sua confirmação não condizem'
        ];

        $request->validate($roles, $feedback);

        if ($request->document) {
            $document_urn = $request->file('document')->store('documents/' . auth()->user()->id);
            $data['path'] = $document_urn;
        }

        $data['name'] = $request->name;
        $data['password'] = bcrypt($request->password);
        $data['user_id'] = auth()->user()->id;

        $data['hash'] = Hash::make(auth()->user()->id);

        Document::create($data);

        return redirect()->route('document.create')->withErrors(['success' => 'Envio confirmado! Guarda seu código para ter acesso a ele: ' . $data['hash']]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function show(Document $document)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function edit(Document $document)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDocumentRequest  $request
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDocumentRequest $request, Document $document)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        //
    }

    public function authDownload(Request $request)
    {
        $document = Document::where('hash', $request->hash)->get()->first();
        if ($document->id) {
            if (Hash::check($request->password, $document->password))
                return redirect()->route('download', ['document' => $document]);
            else
                return redirect()->route('document.index')->withErrors(['error' => 'ID ou senha inválidos!']);
        }
        return redirect()->route('home')->withErrors(['error' => 'Arquivo não encontrado!']);
    }

    public function download(Document $document)
    {
        return Storage::download($document->path);
    }
}
