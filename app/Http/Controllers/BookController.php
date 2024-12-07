<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Book;
use App\Models\Borrower;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['permission:book.read'], ['only' => ['index', 'datatables', 'export_all_pdf', 'show_image', 'show']]);
        $this->middleware(['permission:book.create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:book.update'], ['only' => ['edit', 'update', 'get_valid_borrowers', 'borrow', 'return']]);
        $this->middleware(['permission:book.delete'], ['only' => ['destroy']]);
        $this->middleware(['permission:my book.read'], ['only' => ['user_books_index', 'user_books_datatables']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('book.books-list');
    }

    public function datatables()
    {
        $books = Book::get();

        return DataTables::of($books)
            ->addIndexColumn()
            ->editColumn('book_name', function ($book) {
                return $book->book_name;
            })
            ->editColumn('author', function ($book) {
                return $book->author;
            })
            ->editColumn('status', function ($book) {
                if($book->status) {
                    $status = "<span class='book-status-$book->id badge badge-success'>" . __('Available') . "</span>";
                } else {
                    $status = "<span class='book-status-$book->id badge badge-danger'>" . __('Unavailable') . "</span>";
                }
                return $status;
            })
            ->addColumn('options', function ($book) {
                return view('book.partials.books-list-options', compact('book'))->render();
            })
            ->rawColumns(['book_name', 'author', 'status', 'options'])
            ->make(true);
    }

    /**
     * Export all resources as PDF.
     */
    public function export_all_pdf()
    {
        $books = Book::all();

        $filename = 'all_books.pdf';

        foreach($books as $book) {
            $book->cover_image_path = storage_path('app/public/' . env('BOOKS_COVER_PATH') . '/' . $book->cover_image);
        }

        // Generate PDF from the view
        $pdf = Pdf::loadView('book.partials.pdf.book-details', [
                'books' => $books,
            ])
            ->setPaper('a4');

        // Return the PDF as a downloadable response
        return $pdf->download($filename);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('book.book-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_name'     => ['required', 'max:255', Rule::unique('books', 'book_name')],
            'author'        => ['required', 'max:255'],
            'description'   => ['nullable', 'max:400'],
            'cover_image'   => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'status'        => ['required'],
        ]);

        $data = $request->all();

        try {

            $book = Book::create([
                'book_name'     => $data['book_name'],
                'author'        => $data['author'],
                'description'   => $data['description'],
                'status'        => $data['status'] == 1 ? 1 : 0,
            ]);

        } catch(\Exception $exception) {
            return redirect()->back()->with('alert-error', __('Something went wrong, please try again later.'));
        }

        // Generate a unique filename using the current timestamp
        $filename = time() . '.' . $request->file('cover_image')->getClientOriginalExtension();

        $directory_path = env('BOOKS_COVER_PATH');

        // Check if the directory exists, if not create it
        if (!Storage::disk('public')->exists($directory_path)) {
            Storage::disk('public')->makeDirectory($directory_path);
        }

        // Store the image using the Storage facade with the new filename
        $path = $request->file('cover_image')->storeAs($directory_path, $filename, 'public');

        // Update the book record with the image filename
        $book->cover_image = $filename;
        $book->save();

        return redirect()->route('books.index')->with('alert-success', __('Book created successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show_image(string $filename)
    {
        // Check if the file exists
        if (!Storage::disk('public')->exists(env('BOOKS_COVER_PATH') . '/' . $filename)) {
            abort(404);
        }

        // Return the file as a response
        return Storage::disk('public')->response(env('BOOKS_COVER_PATH') . '/' . $filename);
    }

    /**
     * Export the specified resource as PDF.
     */
    public function export_pdf(string $id)
    {
        // Check if the book exists
        $book = Book::where('id', $id)->limit(1)->get();

        $filename = 'book.pdf';

        foreach($book as $item) {
            $item->cover_image_path = storage_path('app/public/' . env('BOOKS_COVER_PATH') . '/' . $item->cover_image);
            $filename = Str::slug($item->book_name, '_') . '.pdf';
        }

        // Generate PDF from the view
        $pdf = Pdf::loadView('book.partials.pdf.book-details', [
                'books' => $book,
            ])
            ->setPaper('a4');

        // Return the PDF as a downloadable response
        return $pdf->download($filename);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return view('book.book-show', [
            'book' => $book,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        return view('book.book-edit', [
            'book' => $book,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'book_name'     => ['required', 'max:255', Rule::unique('books', 'book_name')->ignore($book->id)],
            'author'        => ['required', 'max:255'],
            'description'   => ['nullable', 'max:400'],
            'cover_image'   => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'status'        => ['required'],
        ]);

        $data = $request->all();

        try {

            $book->update([
                'book_name'     => $data['book_name'],
                'author'        => $data['author'],
                'description'   => $data['description'],
                'status'        => $data['status'] == 1 ? 1 : 0,
            ]);

        } catch(\Exception $exception) {
            return redirect()->back()->with('alert-error', __('Something went wrong, please try again later.'));
        }

        if($request->hasFile('cover_image')) {

            // Generate a unique filename using the current timestamp
            $new_filename = time() . '.' . $request->file('cover_image')->getClientOriginalExtension();

            $old_cover_image = $book->cover_image;

            $directory_path = env('BOOKS_COVER_PATH');

            // Check if the directory exists, if not create it
            if (!Storage::disk('public')->exists($directory_path)) {
                Storage::disk('public')->makeDirectory($directory_path);
            }

            // Store the image using the Storage facade with the new filename
            $path = $request->file('cover_image')->storeAs($directory_path, $new_filename, 'public');

            // Update the book record with the image filename
            $book->cover_image = $new_filename;
            if($book->save()) {
                $old_file_path = env('BOOKS_COVER_PATH') . '/' . $old_cover_image;
                // Check if the old cover image path exists
                if (Storage::disk('public')->exists($old_file_path)) {
                    // Delete the file
                    Storage::disk('public')->delete($old_file_path);
                }
            }

        }

        return redirect()->route('books.index')->with('alert-success', __('Book updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        // if($book) {
        //     return redirect()->back()->with('alert-warning', __('This book cannot be deleted!'));
        // }

        $file_name = $book->cover_image;

        try {

            $book->delete();

        } catch(\Exception $exception) {
            return redirect()->back()->with('alert-error', __('Something went wrong, please try again later.'));
        }

        $file_path = env('BOOKS_COVER_PATH') . '/' . $file_name;

        // Check if the cover image path exists
        if (Storage::disk('public')->exists($file_path)) {
            // Delete the file
            Storage::disk('public')->delete($file_path);
        }

        return redirect()->route('books.index')->with('alert-success', __('Book deleted successfully'));
    }

    /**
     * Get all valid users that can borrow
     */
    public function get_valid_borrowers()
    {
        $users = User::role('user')
            ->select('id', 'name', 'email')
            ->get();

        return response()->json([
            'success' => true,
            'users' => $users
        ], 200);
    }

    /**
     * Borrow a book
     */
    public function borrow(Request $request, Book $book)
    {
        // Check if the book is already borrowed
        $is_borrowed = Borrower::where('borrowable_id', $book->id)
            ->where('borrowable_type', Book::class)
            ->whereNull('returned_at')
            ->exists();

        if ($is_borrowed) {
            return response()->json([
                'success' => false,
                'message' => __('This book has already been borrowed.')
            ], 400);
        }

        $user = User::find($request->input('user_id'));

        if(empty($user)) {
            return response()->json([
                'success' => false,
                'message' => __('User is not found.')
            ], 400);
        }

        try {

            $borrow = new Borrower();
            $borrow->user_id = $user->id;
            $borrow->borrowable()->associate($book);

            if($borrow->save()) {
                $book->status = 0;
                $book->save();
            }

        } catch(\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => __('Something went wrong, please try again later.'),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Book borrowed successfully'
        ], 200);
    }

    /**
     * return a book
     */
    public function return(User $user, Book $book)
    {
        // Check if borrowed book exists
        $borrowed_book = $book->currentBorrower()->first();

        if(empty($borrowed_book)) {
            return redirect()->back()->with('alert-error', __('No borrower found.'));
        }

        try {

            $borrowed_book->returned_at = now();
            if($borrowed_book->save()) {
                $book->status = 1;
                $book->save();
            }

        } catch(\Exception $exception) {
            return redirect()->back()->with('alert-error', __('Something went wrong, please try again later.'));
        }

        return redirect()->back()->with('alert-success', __('Book returned successfully.'));
    }

    /**
     * Show user's books
     */
    public function user_books_index()
    {
        return view('book.my-books-list');
    }

    public function user_books_datatables()
    {
        $books = Auth::user()
            ->borrowedBooks()
            ->get();

        return DataTables::of($books)
            ->addIndexColumn()
            ->editColumn('book_name', function ($book) {
                return $book->book_name;
            })
            ->editColumn('author', function ($book) {
                return $book->author;
            })
            ->rawColumns(['book_name', 'author'])
            ->make(true);
    }

}
