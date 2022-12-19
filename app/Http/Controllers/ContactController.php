<?php

namespace App\Http\Controllers;

use App\Models\Contacts;
use Illuminate\Http\Request;
use Hash;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:contact-list|contact-create|contact-edit|contact-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:contact-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:contact-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:contact-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Contacts::latest()->paginate(5);
        return view('contacts.index', compact('data'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public static function contact_code()
    {
        $abc = array(1 => 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $y = date("y");
        $m = date("n");
        $h = date("G");
        $d = date("s");
        $r = rand(1, 9);
        $r2 = rand(11, 99);

        $kd = $y . $d . $abc[$h] . sprintf("%03s", $r . $r2) . $abc[$m];

        return $kd;
    }

    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'email' => 'required|email|unique:contacts,email',
            'phone' => 'required',
        ]);

        $input = $request->all();
        $input['slug'] = $this->contact_code();

        contacts::create($input);

        return redirect()->route('contact.index')
            ->with('success', 'contacts created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\contacts  $contacts
     * @return \Illuminate\Http\Response
     */
    public function show(contacts $contacts)
    {
        return view('contacts.show', compact('contacts'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\contacts  $contacts
     * @return \Illuminate\Http\Response
     */
    public function edit(contacts $contacts)
    {
        return view('contacts.edit', compact('contacts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\contacts  $contacts
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, contacts $contacts)
    {
        request()->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);

        $contacts->update($request->all());

        return redirect()->route('contacts.index')
            ->with('success', 'contacts updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\contacts  $contacts
     * @return \Illuminate\Http\Response
     */
    public function destroy(contacts $contacts)
    {
        $contacts->delete();

        return redirect()->route('contacts.index')
            ->with('success', 'contacts deleted successfully');
    }
}
