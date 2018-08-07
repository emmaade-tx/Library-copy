<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Book;
use Goutte\Client;

class BookController extends Controller
{
    private $goutteClient;
    private $guzzleClient;

    public function __construct(Client $goutteClient, \GuzzleHttp\Client $guzzleClient)
    {
        $this->goutteClient = $goutteClient;
        $this->guzzleClient = $guzzleClient;
    }
    /**
     * Show the list of Books.
     *
     * @return \Illuminate\Http\Response
     */
    public function book(Request $request)
    {
        $search = $request->search;
        $order = $request->order;

        if ($search == null &&  $order == null) {
            $books = Book::all();
        } else if (isset($search)) {
            $books = Book::where('title', 'LIKE', '%' . $search . '%')->get();
            $books = Book::all();
            // $books = $this->binarySearch($books, $search);
            $books = $this->linearSearch($books, $search);
        } else {
            $books = Book::all();
            // Book::where()->orderBy()
            $books = $this->sortLettersWitharrayFunction($books, $order);
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

    private function getTitles($books)
    {
        $titles = [];
        foreach ($books as $value) {
            array_push($titles, $value->title);
        }

        return $titles;
    }

    private function linearSearch($books, $search)
    {
       $titles = $this->getTitles($books);
       $searchedBook = [];
        foreach ($books as $value) {
            if ($value->title == $search) {
                array_push($searchedBook, $value);
            }
        }
        return $searchedBook;
    }

    private function binarySearch($books, $search)
    {
        $sortedBooks = $this->sortLettersWitharrayFunction($books, 'asc');
        $titles = $this->getTitles($sortedBooks);
        $min = 0;
        $max = count($titles) - 1;

        while ($min <= $max) {
            $mid = floor(($min + $max)/2);
            if ($titles[$mid] === $search) {
                 $foundTitle = $titles[$mid];
                 $searchedBook = [];
                 foreach ($books as $value) {
                     if ($value->title === $foundTitle) {
                         array_push($searchedBook, $value);
                     }
                 }
                 return $searchedBook;
            }
            if ($search < $titles[$mid]) {
                $max = $mid - 1;
            } else {
                $min = $mid + 1;
            }
        }

        return [];
    }

    private function sortLettersWitharrayFunction($books, $order)
    {
        $titles = $this->getTitles($books);
        $sortedBooks = [];
        if ($order === 'asc' ) {
            sort($titles);
        } else {
            rsort($titles);
        }
        foreach ($titles as $val) {
            foreach ($books as $value) {
                if ($value->title === $val) {
                    array_push($sortedBooks, $value);
                }
            }
        }

        return $sortedBooks;
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

    private function scrapeTitleAndUrl()
    {
        $crawler = $this->createClient();

        return $crawler->filter('.image_container')->each(function ($node) {
            return explode('=', $node->html());
        });
    }

    private function scrapePrice()
    {
        $crawler = $this->createClient();
        return $crawler->filter('.price_color')->each(function ($node) {
            $result = explode('£', $node->html());
            return $result[1];
        });
    }

    // private function scrapeDescription()
    // {
    //     $crawler = $this->createClient();
    //     return $crawler->filter('div')->each(function ($node) {
    //         $node->text();
    //     });
    // }

    private function createClient()
    {
        $this->goutteClient->setClient($this->guzzleClient);
        
        return $this->goutteClient->request('GET', 'http://books.toscrape.com/');
    }

    public function scrape()
    {
        foreach ($this->scrapeTitleAndUrl() as $key => $value) {
            $split = explode(" ", $value[2]);

            foreach ($this->scrapePrice() as $k => $val) {
                if ($k == $key) {
                    array_push($value, $val);
                }
            }
            Book::create([
                'title' => str_replace('"', '', $value[3]),
                'url' => str_replace('"', '', $split[0]),
                'price' => $value[5],
                'description' => 'This is a description',
            ]);
        }

        // return Response::json(['message' => 'Data succesfully scraped']);

        $books = Book::all();

        return view('book', compact('books'))->with('message', 'Book succesfully scraped');
    }
}
