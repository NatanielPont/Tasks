<?php
namespace App\Http\Controllers;
use App\Http\Requests\newsletters\NewsletterIndex;
use App\Http\Requests\Positions\PositionsIndex;
use Newsletter;

/**
 * Class NewslettersController.
 */
class NewslettersController extends Controller
{
    /**
     * Index.
     *
     * @param PositionsIndex $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(NewsletterIndex $request)
    {
        $newsletter = collect(Newsletter::getMembers());
        return view('newsletters.index', compact('newsletter'));
    }
}
