<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Dokter;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use TheSeer\Tokenizer\Exception;

class DokterController extends Controller
{
    public function login(Request $request)
    {
        try {
            //validasi
            $request->validate([
                'email'=>'email|required',
                'password' => 'required'
            ]);

            //mengecek credential (login)
            $credentials = request(['email','password']);
            if(!Auth::attempt($credentials)){
                return ResponseFormatter::error([
                    'message' => 'Unauthorized'
                ], 'Authentication Failed', 500);
            }

            $dokter = Dokter::where('email', $request->email)->first();
            if ( ! Hash::check($request->password, $dokter->password, [])) {
                throw new \Exception('Invalid Credentials');
            }

            $tokenResult = $dokter->createToken('authToken')->plainTextToken;
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'dokter' => $dokter
            ],'Authenticated');
            
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error,
            ],'Authentication Failed', 500);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => $this->passwordRules()
            ]);

            Dokter::create([
                'NIK' => $request->NIK,
                'no_STR' => $request->no_STR,
                'no_SIP' => $request->no_SIP,
                'nama_dokter' => $request->nama_dokter,
                'email' => $request->email,
                'telp' => $request->telp,
                'password' => Hash::make($request->password),
                'tanggal_lahir'=>$request->tanggal_lahir,
                'rumah_sakit'=>$request->rumah_sakit,
            ]);

            $dokter = Dokter::where('email', $request->email)->first();

            $tokenResult = $dokter->createToken('authToken')->plainTextToken;

            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'dokter' => $dokter
            ],'User Registered');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error,
            ],'Authentication Failed', 500);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken()->delete();

        return ResponseFormatter::success($token,'Token Revoked');
    }

    public function fetch(Request $request)
    {
        return ResponseFormatter::success(
            $request->user(),'Data Profile User berhasil diambil'
        );
    }

    public function updateProfile(Request $request)
    {
        $data = $request->all();

        $dokter = Auth::dokter();
        $dokter->update($data);

        return ResponseFormatter::success($dokter,'Profile Updated');
    }

    public function updatePhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|max:2048',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(['error'=>$validator->errors()], 'Update Photo Fails', 401);
        }

        if ($request->file('file')) {

            $file = $request->file->store('assets/user', 'public');

            //store your file into database
            $user = Auth::user();
            $user->profile_photo_path = $file;
            $user->update();

            return ResponseFormatter::success([$file],'File successfully uploaded');
        }
    }
}
