<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::all();
        return response()->json([
            "data" => $users,
            "status" => Response::HTTP_OK
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users|max:255',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                "message" => "Campos inválidos",
                "errors" => $errors,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $validated = $validator->validated();

        $validated["password"] = Hash::make($validated["password"]);

        $new_user = User::create($validated);

        return response()->json([
            "data" => $new_user,
            "status" => Response::HTTP_CREATED,
            "message" => "Nuevo usuario creado correctamente!"
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response()->json([
            "data" => $user,
            "status" => Response::HTTP_OK
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
        $validator = Validator::make($request->all(), [
            'name' => [Rule::requiredIf($request->has("name")), "max:255"],
            'email' => [Rule::requiredIf($request->has("email")), "email", 'unique:users,email,' . $user->id, 'max:255'],
            'password' => [Rule::requiredIf($request->has("password"))],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                "message" => "Campos inválidos",
                "errors" => $errors,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $validated = $validator->validated();

        if ($request->has("password")) {
            $validated["password"] = Hash::make($validated["password"]);
        }

        // $new_user = User::create($validated);

        $user->update($validated);

        return response()->json([
            "data" => $user,
            "status" => Response::HTTP_OK,
            "message" => "Usuario editado correctamente!"
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        //
        return response()->json([
            "data" => $user,
            "status" => Response::HTTP_OK,
            "message" => "Usuario eliminado correctamente!"
        ], Response::HTTP_OK);
    }
}
