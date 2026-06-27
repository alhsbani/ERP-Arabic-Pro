<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of documents.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $documents = Document::where('company_id', $user->company_id)
            ->when($request->category, function ($query) use ($request) {
                $query->where('category', $request->category);
            })
            ->paginate(15);

        return view('documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new document.
     */
    public function create()
    {
        return view('documents.create');
    }

    /**
     * Store a newly created document in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|max:10240',
            'category' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $file = $request->file('file');
        $filePath = $file->store('documents', 'private');

        $document = Document::create([
            'name' => $request->name,
            'description' => $request->description,
            'file_path' => $filePath,
            'file_type' => $file->extension(),
            'file_size' => $file->getSize(),
            'category' => $request->category,
            'company_id' => $user->company_id,
            'uploaded_by' => $user->id,
            'is_public' => $request->is_public ?? false,
        ]);

        return redirect()->route('documents.show', $document)->with('success', __('messages.document_uploaded'));
    }

    /**
     * Display the specified document.
     */
    public function show(Document $document)
    {
        return view('documents.show', compact('document'));
    }

    /**
     * Download document.
     */
    public function download(Document $document)
    {
        return Storage::download($document->file_path, $document->name . '.' . $document->file_type);
    }

    /**
     * Delete document.
     */
    public function destroy(Document $document)
    {
        Storage::delete($document->file_path);
        $document->delete();

        return redirect()->route('documents.index')->with('success', __('messages.document_deleted'));
    }
}
