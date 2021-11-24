<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Libro;

use Carbon\Carbon;

class LibroController extends Controller{
    public function index(){
        $datosLibro = Libro::all();
        return response()->json($datosLibro);
    }

    public function guardar(Request $request){
        $datosLibro = new Libro;

        
        if($request->hasFile("imagen")){
            $nombreArchvo = $request->file("imagen")->getClientOriginalName();
            $nuevoNombre = Carbon::now()->timestamp."_".$nombreArchvo;
            $carpetaDes ="./upload/";
            $request->file("imagen")->move($carpetaDes, $nuevoNombre);
            
            $datosLibro->titulo = $request->titulo;        
            $datosLibro->imagen = ltrim($carpetaDes,".").$nuevoNombre;
            $datosLibro->save();
        }


        return response()->json($nuevoNombre);
    }

    public function ver($id){
        $datosLibro = new Libro();
        $datosEcontrados = $datosLibro->find($id);

        return response()->json($datosEcontrados);
    }

    public function eliminar($id){

        $datosLibro = Libro::find($id);

        if($datosLibro){
            $ruta = base_path("public").$datosLibro->imagen;

            if(file_exists($ruta)){
                unlink($ruta);
            }
            $datosLibro->delete();
        }


        return response()->json("Registro Borrado");
    }

    public function actualizar(Request $request, $id){
        $datosLibro = Libro::find($id);  

        if($request->hasFile("imagen")){

            if($datosLibro){
                $ruta = base_path("public").$datosLibro->imagen;
    
                if(file_exists($ruta)){
                    unlink($ruta);
                }
                $datosLibro->delete();
            }
            
            $nombreArchvo = $request->file("imagen")->getClientOriginalName();
            $nuevoNombre = Carbon::now()->timestamp."_".$nombreArchvo;
            $carpetaDes ="./upload/";
            $request->file("imagen")->move($carpetaDes, $nuevoNombre);
                              
            $datosLibro->imagen = ltrim($carpetaDes,".").$nuevoNombre;
            $datosLibro->save();
        }

        if($request->input("titulo")){
            $datosLibro->titulo = $request->input("titulo");
        }
        $datosLibro->save();

        return response()->json("Datos actualizados");
    }
}