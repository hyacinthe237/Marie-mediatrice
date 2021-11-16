<?php

namespace App\Http\Controllers\Views\Backend;

use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Repositories\RoleRepository;

class RoleController extends Controller
{
    /**
      * @var roleRepository $userRepository
      */
     protected $roleRepository;

     /**
      * userController constructor.
      *
      * @param roleRepository $roleRepository
      */
     public function __construct(roleRepository $roleRepository)
     {
         $this->roleRepository = $roleRepository;
     }

     /**
      * Display a listing of the resource.
      * @return Response
      */
     public function index(Request $request)
     {
         $roles = $this->roleRepository->page(self::BACKEND_PAGINATE);

         return view('backend.users.roles.index', compact('roles'));
     }

     /**
      * Show the form for creating a new resource.
      * @return Response
      */
     public function create()
     {
         return view('backend.users.roles.create');
     }

     /**
      * Store a newly created resource in storage.
      * @param  Request $request
      * @return Response
      */
     public function store(roleRequest $request)
     {
        try {
             $input = $request->all();
             $role = $this->roleRepository->store($input);
             if ($role){
                 return redirect()->route('admin.roles.edit', $role->id)
                     ->withSuccess('Le rôle a bien été créé !');
             }

             return redirect()->back()
             ->withInputs($request->all())
             ->withErrors([
                 'error' => "Erreur lors de la creation du rôle"
             ]);

         } catch (\Exception $e) {
             return redirect()->back()
             ->withInputs($request->all())
             ->withErrors($e->getMessage());
         }
     }

     /**
      * Show the specified resource.
      * @return Response
      */
     public function show($id)
     {
         $role = $this->roleRepository->findByField('id',$id);

         if(is_null($role) ) {
             return abort(404);
         }

         return view('backend.users.roles.show', compact('role'));
     }

     /**
      * Show the form for editing the specified resource.
      * @return Response
      */
     public function edit($id)
     {
         $role = $this->roleRepository->find($id);
         if (!$role) return redirect()->route('admin.roles');

         return view('backend.users.roles.edit', compact('role'));
     }

     /**
      * Update the specified resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function update(roleRequest $request, $id)
     {
         try
         {
             $role = $this->roleRepository->find($id);

             if (!$role) return redirect()->route('admin.roles');

             $role = $this->roleRepository->update($role->id, $request->all());

             return redirect()->route('admin.roles.edit', $role->id)
                 ->withSuccess('Le rôle a bien été mis à jour !');

         } catch (\Exception $e) {
             \Log::error($e->getMessage());
             return response()->json(['message'=>'internal server error'],500);
         }
     }

     /**
      * Remove the specified resource from storage.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function destroy($id)
     {
         try {
             $role = $this->roleRepository->find($id);

             $delete = $this->roleRepository->delete($id);
             if ($delete) {
                 return response()->json(['message'=>'the user has been deleted'],200);
             }
         }catch (\Exception $e) {
             \Log::error($e->getMessage());
             return response()->json(['message'=>'internal server error'],500);
         }
     }

}
