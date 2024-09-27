<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;



use App\Models\Book;
use App\Models\Comment;
use Illuminate\Http\Request;

class BookController extends Controller
{

    public function index()
    {
        $books = Book::with(['comments' => function($query) {
            $query->selectRaw('book_id, AVG(rating) as avg_rating')
                  ->groupBy('book_id');
        }])->paginate(8);
    
        $books->each(function($book) {
            $book->avg_rating = optional($book->comments->first())->avg_rating;
        });
    
        return view('home', compact('books'));
    }

    public function search(Request $request){
        $query = $request->input('query');
        $books = Book::where('book_name', 'like', '%' . $query . '%')->orWhere('author_name', 'like', '%' . $query . '%')->paginate(8);
        return view('home', compact('books'));
    }
    


    // Show a book with its comments
    public function show($id)
    {
        $book = Book::with('comments')->findOrFail($id);
        return view('books.show', compact('book'));
    }

    // Store a new comment
    public function storecomment(Request $request)
{
    // Validate that the book_id and comment are provided
    $request->validate([
        'book_id' => 'required|exists:books,id',
        'comment' => 'required|string|max:255',
    ]);

    $userId = Auth::id(); // Get the authenticated user's ID
    $bookId = $request->book_id;


    // Check if a record already exists for this user and book
    $comment = Comment::where('user_id', $userId)
                      ->where('book_id', $bookId)
                      ->first();

    // If a record exists, update it
    if ($comment) {
        $comment->comment = $request->comment; // Update the comment
        $comment->save();
    } else {
        // Create a new comment record
        Comment::create([
            'user_id' => $userId,
            'book_id' => $bookId,
            'comment' => $request->comment,
        ]);

        // Increase the comment count for the book
        Book::where('id', $bookId)->increment('comment');
    }

    return redirect()->back()->with('success', 'Your comment has been submitted successfully!');
}

public function storerating(Request $request)
{
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'book_id' => 'required|exists:books,id',
    ]);

    $comment = Comment::updateOrCreate(
        [
            'user_id' => Auth::id(),
            'book_id' => $request->book_id,
        ],
        [
            'rating' => $request->rating,
        ]
    );

    return redirect()->back()->with('success', 'Rating submitted successfully!');
}



}

