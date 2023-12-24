<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Listing;
use \Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use ListingService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class ListingController extends Controller
{
	// inserting service using dependency injection
	// protected $listingService;
	// public function __construct(ListingService $listingService)
	// {
	//     $this->listingService = $listingService;
	// }

	/**
	 * index function is used to search using tag or a multi-field searchbar
	 *
	 * @param Request $request
	 * @return \Illuminate\View\View
	 */
	public function index(Request $request)
	{
		$filters 		= $request->only(['tag', 'search']);
		// $listingsQuery = $this->listingService->filterListings($filters); //using dependency injection
		$listingsQuery 	= ListingService::filterListings($filters); //using facade
		$listings 		= $listingsQuery->latest()->paginate(6);
		return view('listings.index', compact('listings'));
	}


	/** Show function is used to show single listing 
	 * 
	 * @param Listing $listing 
	 * @return \Illuminate\View\View
	 */
	public function show(Listing $listing)
	{
		return view('listings.show', [
			'listing' => $listing
		]);
	}

	/** Create function is used to Show create form 
	 * 
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return view('listings.create');
	}

	/**
	 * Store function is used to Store form data for listing in database
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store(Request $request)
	{
		try {
			$formFields 			= $request->validate([
				'title' 			=> 'required',
				'company' 			=> ['required', Rule::unique('listings', 'company')],
				'location' 			=> 'required',
				'website' 			=> 'required',
				'email' 			=> ['required', 'email'],
				'tags' 				=> 'required',
				'description' 		=> 'required',
			]);

			if ($request->hasFile('logo')) 
				// $formFields['logo'] = $this->listingService->uploadLogo($request->file('logo'));
				$formFields['logo'] = $request->file('logo')->store('logos', 'public');

			$user = Auth::user();
			$formFields['user_id'] = $user->id;
			ListingService::createListing($formFields);
			// $this->listingService->createListing($formFields);
			return redirect('/')->with('message', 'Listing created succesfully!');
		} 
		catch (ValidationException $e) {
			Log::error('ValidationException caught: ' . $e->getMessage());
			return redirect()->back()->withErrors($e->validator->errors())->withInput();
		}
		catch (\Throwable $th) {
			Log::error('An error occured during creating the listing: ' . $th->getMessage());
			return redirect()->back()->with('error', 'The following error occured during the listing creation: '.$th->getMessage())->withInput();
		}
		
	}

	/**
	 * Edit function is used To show the editing form
	 *
	 * @param Listing $listing
	 * @return \Illuminate\View\View
	 */
	public function edit(Listing $listing)
	{
		return view('listings.edit', ['listing' => $listing]);
	}

	/**
	 * Update function is used To update listing data in database
	 *
	 * @param Request $request
	 * @param Listing $listing
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update(Request $request, Listing $listing)
	{

		try {
			// Make sure logged in user is owner
			ListingService::authorizeUser($listing); //calling using facade
			// $this->listingService->authorizeUser($listing);

			$formFields 			= $request->validate([
				'title' 			=> 'required',
				'company' 			=> ['required'],
				'location' 			=> 'required',
				'website' 			=> 'required',
				'email' 			=> ['required', 'email'],
				'tags' 				=> 'required',
				'description' 		=> 'required'
			]);

			if ($request->hasFile('logo')) 
				$formFields['logo'] = $request->file('logo')->store('logos', 'public');
			ListingService::updateListing($formFields, $listing);
			return redirect('/listings/datatable')->with('message', 'Listing updated succesfully!');
		} 
		catch (ValidationException $e) {
			Log::error('ValidationException caught: ' . $e->getMessage());
			return redirect()->back()->withErrors($e->validator->errors())->withInput();
		}
		catch (\Throwable $th) {
			Log::error('An error occured during updating the listing: ' . $th->getMessage());
			return redirect()->back()->with('error', 'The following error occured during the listing updation: '.$th->getMessage())->withInput();
		}


	}

	/**
	 * Delete function is used To delete a listing from database
	 *
	 * @param Listing $listing
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function destroy(Listing $listing)
	{
		try {
			//code...
			ListingService::delete($listing); //using facade
			// $this->listingService->delete($listing);
			return redirect('/')->with('message', 'Listing deleted successfully');
		} 
		catch (\Throwable $th) {
			//throw $th;
			Log::error('An error occured during deleting the listing: ' . $th->getMessage());
			return redirect()->back()->with('error', 'The following error occured during the listing deletion: '.$th->getMessage());
		}
		
	}

	// Manage listings
	/**
	 * Manage function is used To view the manage listings page
	 *
	 * @return \Illuminate\View\View
	 */
	public function manage()
	{
		return view('listings.manage', [
			'listings' => auth()->user()
								->listings()
								->get()
		]);
	}

	/**
	 * Datatable function is used To get the data for datatable
	 *
	 * @param Request $$request
	 * @return void
	 */

	// private $drawCounter=0;
	public function datatable(Request $request)
	{
		try 
		{
			// dd('testing');

			$draw				= $request->get('draw');
			$start				= $request->get('request');
			$limit				= $request->get('length') ;
			$search_arr			= $request->get('search');
			$columnIndex_arr	= $request->get('order');
			$columnName_arr		= $request->get('columns');
			$search				= $search_arr['value'];
			$columnIndex		= $columnIndex_arr[0]['column'];
			$columnName			= $columnName_arr[$columnIndex]['data'];
			$columnSortOrder	= $columnIndex_arr[0]['dir'];
			$len				= $start;
			
			$response=ListingService::listingDatatable($draw,$start,$limit,$search_arr,$search,$columnIndex_arr,$columnIndex,$columnName_arr,$columnName,$columnSortOrder,$len);
			// dd($response);
			if(is_array($response))
				echo json_encode($response);
			else	
				echo json_encode($response=[
					"draw"					=>intval(1),
					"iTotalRecords"			=>0,
					"iTotalDisplayRecords"	=>0,
					"aaData"				=>[]
				]);
		}
		catch(Exception $e) 
		{
			Log::error('Exception encountered. ListingController->datatable Exception: ' . $e);
			return $this->sendError('Exception encountered. Please try again later.', [], 500);
		}
	}

	public function list()
	{
        try
        {	
			// dd('hello');

            return view('listings.datatable');
        }
        catch(Exception $e)
        {
            Log::error('Exception encountered. ListingController->getDatatable Exception: '.$e);
			return back()->with('message','Exception encountered. Please try again later.');
        }
	}

	public function sendError($message, $data = [], $statusCode = 500)
		{
		    $response = [
		        'error' => $message,
		    ];
		
		    if (!empty($data)) {
		        $response['data'] = $data;
		    }
		
		    return response()->json($response, $statusCode);
		}

}