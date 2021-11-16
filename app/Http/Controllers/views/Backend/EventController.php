<?php

namespace App\Http\Controllers\Views\Backend;

use Auth;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{

    /**
     * list of Events
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function index(Request $request)
    {
        try {

            $status = null;
            $keywords = $request->keywords;
            $users = User::get();

            $events = Event::with('host', 'organization')
            ->when($keywords, function($query) use ($keywords) {
                return $query->where('title', 'like', '%' . $keywords . '%');
            });


            if ($request->has('status') && $request->status != '') {
                $status = $request->status;

                if ($request->status == "1") {
                    $events = $events->where('status', true);
                } else {
                    $events = $events->where('status', false);
                }
            }

            $events = $events->orderBy('id', 'desc')->paginate(self::BACKEND_PAGINATE);

            return view('backend.events.index', compact('events', 'keywords', 'status', 'users'));
        }
        catch (Exception $e) {
            return redirect()->back()->withErrors($e);
        }
    }



    /**
     * Create new page
     *
     * @return view()
     */
    public function create()
    {
        $organizations = Organization::get();
        return view('backend.events.create', compact('organizations'));
    }


    /**
     * Store an Event
     *
     * @param  Request $request
     *
     * @return redirect()
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'date'  => 'required'
            ]);

            if($validator->fails())
                return redirect()->back()
                        ->withInputs($request->all())
                        ->withErrors(['validator' => 'Title, date and organization are required']);

            $event = Event::create([
                'title'           => $request->title,
                'organization_id' => Auth::user()->organization->id,
                'host'            => Auth::user()->id,
                'date'            => Carbon::parse($request->date),
                'address'         => $request->address,
                'hours'           => $request->hours,
                'minutes'         => $request->minutes,
                'status'          => $request->status,
                'description'     => $request->description
            ]);

            return redirect()->route('admin.events.edit', $event->code)
                    ->with('message', 'The event has been created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
            ->withInputs($request->all())
            ->withErrors($e->getMessage());
        }

    }


    /**
     * Display an event for editing
     *
     * @param  string $code
     *
     * @return view()
     */
    public function edit ($code)
    {
        $event = Event::whereCode($code)->first();
        $organizations = Organization::get();
        if (!$event) return redirect()->route('events.index');

        return view('backend.events.edit', compact('event', 'organizations'));
    }

    /**
     * Show an event
     *
     * @param  string $code
     *
     * @return view()
     */
    public function show ($code)
    {
        $event = Event::whereCode($code)->first();
        if (!$event) return redirect()->route('events.index');

        return view('backend.events.show', compact('event'));
    }



    /**
     * Update a event
     * @param  Request $request [description]
     * @param  [type]  $code    [description]
     * @return [type]           [description]
     */
    public function update(Request $request, $code)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'date'  => 'required',
            ]);

            if($validator->fails())
                return redirect()->back()->withErrors(['validator' => 'Title, date and organization are required']);

            $date = '';
            $event = Event::whereCode($code)->first();
            if (!$event) return redirect()->back();

            if (Str::contains($request->date, '-'))
                $date = Carbon::createFromFormat('d-m-Y', $request->date);

            if (Str::contains($request->date, '/'))
                $date = Carbon::createFromFormat('d/m/Y', $request->date);

            if ($request->status == "0") {
                $event->status = false;
                $event->save();
            }

            if ($request->status == "1") {
                $event->status = true;
                $event->save();
            }

            $event->title            = $request->title ? $request->title : $event->title;
            $event->organization_id  = Auth::user()->organization->id;
            $event->host             = Auth::user()->id;
            $event->date             = $date !== '' ? $date : $event->date;
            $event->address          = $request->address ? $request->address : $event->address;
            $event->hours            = $request->hours ? $request->hours : $event->hours;
            $event->minutes          = $request->minutes ? $request->minutes : $event->minutes;
            $event->description      = $request->description ? $request->description : $event->description;
            $event->update();

            return redirect()->route('admin.events.edit', $event->code)
                ->with('message', 'The event has been updated successfully !');
        }
        catch (Exception $e) {
            return redirect()->back()->withErrors($e);
        }

    }

    /**
     * Remove an event
     *
     * @param  string  $code
     * @return \Illuminate\Http\Response
     */
    public function destroy ($id)
    {
        try {
            $event = Event::findOrFail($id);
            // if (!$event) return redirect()->back();

            $event->delete();

            return redirect()->route('admin.events.index')
                    ->with('message', 'The event has been deleted successfully!');

        }catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['message'=>'internal server error'], 500);
        }
    }

}
