<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Book;

class BookController extends Controller
{
    /**
     * Show the list of Books.
     *
     * @return \Illuminate\Http\Response
     */
    public function book(Request $request)
    {
        $search = $request->search;
        // dd($search);
        if ($search == null) {
            $books = Book::all();
        } else {
            $books = Book::all();



            $books = Book::where('title', 'LIKE', '%' . $search . '%')->get();
        }

        return view('book', compact('books'));
    }

    private function quickSort($books)
    {
        if (count($books) <= 1) {
            return $books;
        } else {
            $pivot = $books[0];
            $left = [];
            $right = [];
            for ($i = 0; $i < $books ; $i++) {
                if ($books[$i] < $pivot) {
                    $left = $books[$i];
                } else {
                    $right = $books[$i];
                }
            }
            $left = quickSort($left);
            $right = quickSort($right);
            $pivot =[$pivot];

            return array_merge($left, $pivot, $right);
        }
    }

    /**
     * View Books.
     *
     * @return \Illuminate\Http\Response
     */
    public function view(Request $request)
    {
    	$book = Book::find($request->id);

        return view('view-book', compact('book'));
    }

    /**
     * Edit Book form.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
    	$book = Book::find($request->id);

        return view('edit-book', compact('book'));
    }

    /**
     * update Books.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
    	$book = Book::where('id', $request->id)->update([
    		'title' => $request->title,
    		'description' => $request->description,
    		'price' => $request->price
    	]);

		if ($book) {
			return redirect()->route('book')->with('message', 'saved succesfully');
		} else {
            return redirect()->route('book')->with('message', 'Something went wrong');
        }
    }

    public function scrape()
    {
        $client = $client = new \GuzzleHttp\Client();

        dd($client);
    }
}
