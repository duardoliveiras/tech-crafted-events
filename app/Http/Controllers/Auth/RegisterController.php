<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\University;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    static $diskName = 'ImagesSaved';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone_number' => ['string', 'max:20'],
            'birthdate' => ['date', 'before:' . now()->subYears(12)->format('Y-m-d')],
            'university_id' => 'required|exists:university,id',
        ]);
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\Models\User
     */
    protected function create(array $data): User
    {
        $imageUrl = '';

        if (optional($data['image_url'])) {
            $imageFile = $data['image_url'];

            if ($imageFile->isValid()) {
                $imagePath = $this->uploadImage($imageFile);
                $imageUrl = $imagePath;
            }
        }

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone_number' => $data['phone_number'],
            'birthdate' => $data['birthdate'],
            'university_id' => $data['university_id'],
            'image_url' => $imageUrl
        ]);
    }

    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect($this->redirectPath());
    }

    public function registerComplete(Request $request)
    {
        $user =
            User::updateOrCreate([
                    'email' => $request['email']]
                , [
                    'name' => $request['name'],
                    'email' => $request['email'],
                    'phone_number' => $request['phone_number'],
                    'birthdate' => $request['birthdate'],
                    'university_id' => $request['university_id'],
                    'image_url' => $request['image_url'],
                    'provider' => $request['provider'],
                    'provider_token' => $request['provider_token']
                ]);

        Auth::login($user);
        return redirect('/home');

    }

    public function showRegistrationForm()
    {
        $universities = University::all();
        return view('auth.register', compact('universities'));
    }

    private function uploadImage(UploadedFile $imageFile, $path = 'user'): string
    {
        $fileName = $imageFile->hashName();
        $imageFile->storeAs($path, $fileName, self::$diskName);
        return $fileName;
    }
}
