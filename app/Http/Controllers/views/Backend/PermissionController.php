<?php

namespace App\Http\Controllers\Views\Backend;

use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionRequest;
use App\Repositories\PermissionRepository;

class PermissionController extends Controller
{
    /**
      * @var permissionRepository $userRepository
      */
     protected $permissionRepository;

     /**
      * userController constructor.
      *
      * @param permissionRepository $permissionRepository
      */
     public function __construct(permissionRepository $permissionRepository)
     {
         $this->permissionRepository = $permissionRepository;
     }

     /**
      * Display a listing of the resource.
      * @return Response
      */
     public function index(Request $request)
     {
         $permissions = $this->permissionRepository->page(self::BACKEND_PAGINATE);

         return view('backend.users.permissions.index', compact('permissions'));
     }

     /**
      * Show the form for creating a new resource.
      * @return Response
      */
     public function create()
     {
         return view('backend.users.permissions.create');
     }

     /**
      * Store a newly created resource in storage.
      * @param  Request $request
      * @return Response
      */
     public function store(permissionRequest $request)
     {
        try {
             $input = $request->all();
             $permission = $this->permissionRepository->store($input);
             if ($permission){
                 return redirect()->route('admin.permissions.edit', $permission->id)
                     ->withSuccess('La permission a bien été créée !');
             }

             return redirect()->back()
             ->withInputs($request->all())
             ->withErrors([
                 'error' => "Erreur lors de la creation de la permission"
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
         $permission = $this->permissionRepository->findByField('id',$id);

         if(is_null($permission) ) {
             return abort(404);
         }

         return view('backend.users.permissions.show', compact('permission'));
     }

     /**
      * Show the form for editing the specified resource.
      * @return Response
      */
     public function edit($id)
     {
         $permission = $this->permissionRepository->find($id);
         if (!$permission) return redirect()->route('admin.permissions');

         return view('backend.users.permissions.edit', compact('permission'));
     }

     /**
      * Update the specified resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function update(permissionRequest $request, $id)
     {
         try
         {
             $permission = $this->permissionRepository->find($id);

             if (!$permission) return redirect()->route('admin.roles');

             $permission = $this->permissionRepository->update($permission->id, $request->all());

             return redirect()->route('admin.permissions.edit', $permission->id)
                 ->withSuccess('La permission a bien été mise à jour !');

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
             $permission = $this->permissionRepository->find($id);

             $delete = $this->permissionRepository->delete($id);
             if ($delete) {
                 return response()->json(['message'=>'the permission has been deleted'],200);
             }
         }catch (\Exception $e) {
             \Log::error($e->getMessage());
             return response()->json(['message'=>'internal server error'],500);
         }
     }

}
