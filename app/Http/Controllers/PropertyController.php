<?php

namespace App\Http\Controllers;

use App\DataTables\PropertyDataTable;
use App\Http\Requests;
use App\Http\Requests\CreatePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use App\Models\Masterfile;
use App\Repositories\PropertyRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use League\Csv\Reader;
use Response;

class PropertyController extends AppBaseController
{
    /** @var  PropertyRepository */
    private $propertyRepository;

    public function __construct(PropertyRepository $propertyRepo)
    {
        $this->middleware('auth');
        $this->propertyRepository = $propertyRepo;
    }

    /**
     * Display a listing of the Property.
     *
     * @param PropertyDataTable $propertyDataTable
     * @return Response
     */
    public function index(PropertyDataTable $propertyDataTable)
    {
        return $propertyDataTable->render('properties.index',[
            'landlords'=>Masterfile::where('b_role',landlord)->get()
        ]);
    }

    /**
     * Show the form for creating a new Property.
     *
     * @return Response
     */
    public function create()
    {
        return view('properties.create');
    }

    /**
     * Store a newly created Property in storage.
     *
     * @param CreatePropertyRequest $request
     *
     * @return Response
     */
    public function store(CreatePropertyRequest $request)
    {
        $input = $request->all();

        $property = $this->propertyRepository->create($input);

        Flash::success('Property saved successfully.');

        return redirect(route('properties.index'));
    }

    /**
     * Display the specified Property.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $property = $this->propertyRepository->findWithoutFail($id);

        if (empty($property)) {
            Flash::error('Property not found');

            return redirect(route('properties.index'));
        }

        return response()->json($property);
    }

    /**
     * Show the form for editing the specified Property.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $property = $this->propertyRepository->findWithoutFail($id);

        if (empty($property)) {
            Flash::error('Property not found');

            return redirect(route('properties.index'));
        }

        return view('properties.edit')->with('property', $property);
    }

    /**
     * Update the specified Property in storage.
     *
     * @param  int              $id
     * @param UpdatePropertyRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePropertyRequest $request)
    {
        $property = $this->propertyRepository->findWithoutFail($id);

        if (empty($property)) {
            Flash::error('Property not found');

            return redirect(route('properties.index'));
        }

        $property = $this->propertyRepository->update($request->all(), $id);

        Flash::success('Property updated successfully.');

        return redirect(route('properties.index'));
    }

    /**
     * Remove the specified Property from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $property = $this->propertyRepository->findWithoutFail($id);

        if (empty($property)) {
            Flash::error('Property not found');

            return redirect(route('properties.index'));
        }

        $this->propertyRepository->delete($id);

        Flash::success('Property deleted successfully.');

        return redirect(route('properties.index'));
    }


}
