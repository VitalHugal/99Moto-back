<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\UserCoordinate;

class UserCoordinatesController extends Controller
{
    protected $coordinate_user;

    public function __construct(UserCoordinate $coordinate_user)
    {
        $this->coordinate_user = $coordinate_user;
    }

    public function coordinatesUsers(Request $request)
    {
        $coordinate_user = $request->validate(
            $this->coordinate_user->rulesCoordinatesUsers(),
            $this->coordinate_user->feedbackCoordinatesUsers()
        );

        $coordinate_user = $this->coordinate_user->create([
            'user_coordinates_latitudine' => $request->user_coordinates_latitudine,
            'user_coordinates_longitudine' => $request->user_coordinates_longitudine,
        ]);

        return response()->json($coordinate_user);
    }
    
    public function deleteCoordinatesUsers($id)
    {
        $deeleteCoordinatesUsers = $this->coordinate_user->find($id);

        if ($deeleteCoordinatesUsers === null) {
            return  response()->json(['error' => "Nenhum resultado encontrado."]);
        }

        $deeleteCoordinatesUsers->delete();

        return response()->json(["sucesso" => 'Coordenadas do usuário excluida com sucesso.']);
    }
}