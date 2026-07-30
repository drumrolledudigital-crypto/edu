<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Subject;
use App\Services\AuditLoggerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function index()
    {
        return view('admin.books.index');
    }

    public function list()
    {
        $books = Book::with('subject')->orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $books
        ]);
    }

    public function store(Request $request, AuditLoggerService $logger)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'short_description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'pdf_file' => 'nullable|file|mimes:pdf|max:20480',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        $data['slug'] = Str::slug($request->title);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('books/covers', 'public');
        }

        if ($request->hasFile('pdf_file')) {
            $data['pdf_file'] = $request->file('pdf_file')->store('books/pdfs', 'public');
        }

        $book = Book::create($data);

        $logger->log('Book', 'Create', "Book '{$book->title}' was created.", null, $book->toArray());

        return response()->json([
            'status' => 'success',
            'message' => 'Book created successfully',
            'data' => $book->load('subject')
        ]);
    }

    public function show(Book $book)
    {
        return response()->json([
            'status' => 'success',
            'data' => $book->load('subject')
        ]);
    }

    public function update(Request $request, Book $book, AuditLoggerService $logger)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'short_description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'pdf_file' => 'nullable|file|mimes:pdf|max:20480',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $oldData = $book->toArray();
        $data = $validator->validated();
        $data['slug'] = Str::slug($request->title);

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('books/covers', 'public');
        }

        if ($request->hasFile('pdf_file')) {
            if ($book->pdf_file && Storage::disk('public')->exists($book->pdf_file)) {
                Storage::disk('public')->delete($book->pdf_file);
            }
            $data['pdf_file'] = $request->file('pdf_file')->store('books/pdfs', 'public');
        }

        $book->update($data);

        $logger->log('Book', 'Update', "Book '{$book->title}' was updated.", $oldData, $book->refresh()->toArray());

        return response()->json([
            'status' => 'success',
            'message' => 'Book updated successfully',
            'data' => $book->load('subject')
        ]);
    }

    public function destroy(Book $book, AuditLoggerService $logger)
    {
        $oldData = $book->toArray();
        $title = $book->title;

        if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
            Storage::disk('public')->delete($book->cover_image);
        }
        if ($book->pdf_file && Storage::disk('public')->exists($book->pdf_file)) {
            Storage::disk('public')->delete($book->pdf_file);
        }

        $book->delete();

        $logger->log('Book', 'Delete', "Book '{$title}' was deleted.", $oldData);

        return response()->json([
            'status' => 'success',
            'message' => 'Book deleted successfully'
        ]);
    }

    public function changeStatus(Request $request, AuditLoggerService $logger)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:books,id',
            'status' => 'required|in:active,inactive'
        ]);

        Book::whereIn('id', $request->ids)->update(['status' => $request->status]);

        $logger->log('Book', 'StatusUpdate', "Status of " . count($request->ids) . " books changed to {$request->status}.", null, ['ids' => $request->ids, 'status' => $request->status]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status updated successfully for ' . count($request->ids) . ' record(s).'
        ]);
    }

    public function bulkDelete(Request $request, AuditLoggerService $logger)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:books,id',
        ]);

        $books = Book::whereIn('id', $request->ids)->get();
        foreach ($books as $book) {
            if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }
            if ($book->pdf_file && Storage::disk('public')->exists($book->pdf_file)) {
                Storage::disk('public')->delete($book->pdf_file);
            }
        }

        $count = count($request->ids);
        Book::whereIn('id', $request->ids)->delete();

        $logger->log('Book', 'BulkDelete', "{$count} books were deleted.", null, ['ids' => $request->ids]);

        return response()->json([
            'status' => 'success',
            'message' => "{$count} book(s) deleted successfully."
        ]);
    }
}
