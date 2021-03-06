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
        return redirect()->route('fallback');
    }
    public function findDocument()
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

        function hashGenerator()
        {
            $alfabeto = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'x', 'w', 'y', 'z'];
            $hash = '';
            for ($i = 0; $i < 25; $i++) {
                if (rand(0, 1))
                    $hash = $hash . $alfabeto[rand(0, 25)];
                else
                    $hash = $hash . strval(rand(0, 9));
            }
            $document = Document::where('hash', $hash)->get()->first();
            if ($document)
                hashGenerator();
            else
                return $hash;
        }

        $data['hash'] = hashGenerator();

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
        return redirect()->route('fallback');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function edit(Document $document)
    {
        return redirect()->route('fallback');
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
        return redirect()->route('fallback');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        return redirect()->route('fallback');
    }

    public function authDownload(Request $request)
    {
        $document = Document::where('hash', $request->hash)->get()->first();
        if (!$document || $document->user_received) {
            return redirect()->route('document.find')->withErrors(['error' => 'Este arquivo não existe ou não pode mais ser acessado!']);
        }
        if ($document && Storage::exists($document->path)) {
            if (Hash::check($request->password, $document->password))
                return redirect()->route('download', ['document' => $document]);
            else
                return redirect()->route('document.index')->withErrors(['error' => 'ID ou senha inválidos!']);
        }
        return redirect()->route('document.find')->withErrors(['error' => 'Arquivo não encontrado!']);
    }

    public function download(Document $document)
    {
        function extension($value)
        {
            $extension = '';
            for ($i = 0; $i < strlen($value); $i++)
                if ($value[$i] == '.')
                    $pos = $i;
            for ($i = $pos; $i < strlen($value); $i++)
                $extension = $extension . $value[$i];
            return $extension;
        }

        $document->user_received = auth()->user()->id;
        $document->save();
        $data['name'] = $document->name;
        $data['path'] = $document->path;
        $document->delete();
        return Storage::download($data['path'], $data['name'] . extension($data['path']));
    }
}
