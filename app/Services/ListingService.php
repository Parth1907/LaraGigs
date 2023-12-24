<?php

namespace App\Services;

use App\Models\Listing;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class ListingService
{
    /** FilterListings is used to Returns query of filter listings on basis of either tag or multi-field search which includes title, description, tags or location 
     *  
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder;
     */
    public function filterListings(array $filters)
    {
        $query = Listing::query();

        if ($filters['tag'] ?? false) {
            $query->where('tags', 'like', '%' . request('tag') . '%');
        }
        if ($filters['search'] ?? false) {
            $query->where('title', 'like', '%' . request('search') . '%')
                ->orWhere('description', 'like', '%' . request('search') . '%')
                ->orWhere('tags', 'like', '%' . request('search') . '%')
                ->orWhere('location', 'like', '%' . request('search') . '%');
        }
        return $query;
    }

    /**
     * CreateListing is used To return the create function of listing
     *
     * @param [type] $data
     * @return void
     */
    public function createListing($data)
    {
        try {
            return Listing::create($data);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('An error occured during creating the listing: ' . $th->getMessage());
            return redirect()->back()->with('error', 'An error occured during the listing creation.');
        }
    }

    /**
     * UpdateListing is used to To return the create function of listing
     *
     * @param [type] $data
     * @return void
     */
    public function updateListing($data, $listing)
    {
        try {
            //code...
            $listing->update($data);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('An error occured during updating the listing: ' . $th->getMessage());
            return redirect()->back()->with('error', 'An error occured during the listing updation.');
        }
    }

    /**
     * Delete function is used To delete a listing
     *
     * @param [type] $listing
     * @return void
     */
    public function delete($listing)
    {
        try {
            //code...
            // Authorization check
            $this->authorizeUser($listing);
    
            // Delete the listing
            $listing->delete();
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('An error occured during deleting the listing: ' . $th->getMessage());
            return redirect()->back()->with('error', 'An error occured during the listing deletion.');
        }
    }

    /**
     * AuthorizeUser is used To authorize whether the current user has access for editing or updating
     *
     * @param Listing $listing
     * @return void
     */
    public function authorizeUser(Listing $listing)
    {
        if ($listing->user_id != Auth::id()) {
            abort(403, 'Unauthorized Action');
        }
    }

    public function getUserListings() {
        return Auth::user()->listings()->get();
    }

    public function listingDatatable($draw, $start, $limit, $search_arr, $search, $columnIndex_arr, $columnIndex, $columnName_arr, $columnName, $columnSortOrder, $len)
    {
        try {
            //code...
            $data=[];

            $query=Listing::query();

            if (!empty($search)) 
            {   
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', '%' . $search . '%')
                          ->orWhere('description', 'like', '%' . $search . '%')
                          ->orWhere('tags', 'like', '%' . $search . '%')
                          ->orWhere('location', 'like', '%' . $search . '%');
                });
            }    
                $query->orderBy('created_at','desc');
                $totalRecords=$query->count();//total records
                $jobs=$query->skip($start)->take($limit)->get();//pagination code
                
                foreach($jobs as $job) {
                    $jobData= [
                        'id'            =>$job->id,
                        'title'         =>$job->title,   
                        'logo'          =>$job->logo,
                        'tags'          =>$job->tags,
                        'company'       =>$job->company,
                        'location'      =>$job->location,
                        'description'   =>$job->description,
                        'email'         =>$job->email,
                        'website'       =>$job->website
                    ];
                    $data[]=$jobData;
                }

                $response = [
                    'draw'              =>intval($draw),
                    'recordsTotal'      =>$totalRecords,
                    'recordsFiltered'   =>$totalRecords,
                    'data'              =>$data
                ];

                return $response;
            
        } catch (Exception $e) {
            //throw $th;
            Log::error('Exception encountered in ListingService->listingDatatable. Exception: '.$e);
            return null;
        }
    }
}