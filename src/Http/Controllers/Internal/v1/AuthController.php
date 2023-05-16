<?php

namespace Fleetbase\Http\Controllers\Internal\v1;

use Illuminate\Http\Request;
use Fleetbase\Http\Requests\LoginRequest;
use Fleetbase\Http\Controllers\Controller;
use Fleetbase\Support\Auth;
use Fleetbase\Models\User;
use Fleetbase\Models\Company;
use Fleetbase\Models\CompanyUser;
use Fleetbase\Http\Requests\SwitchOrganizationRequest;
use Fleetbase\Http\Resources\Organization;


class AuthController extends Controller
{
    /**
     * Authenticates a user by email and responds with an auth token.
     *
     * @param \Fleetbase\Http\Requests\LoginRequest $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        $ip = $request->ip();
        $email = $request->input('email');
        $password = $request->input('password');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->error('No user found by this email.', 401);
        }

        if (Auth::isInvalidPassword($password, $user->password)) {
            return response()->error('Authentication failed using password provided.', 401);
        }

        $token = $user->createToken($ip);
        return response()->json(['token' => $token->plainTextToken]);
    }

    /**
     * Takes a request username/ or email and password and attempts to authenticate user
     * will return the user model if the authentication was successful, else will 400
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function session(Request $request)
    {
        if ($request->user() === null) {
            return response()->error('Session has expired.', 401, ['restore' => false]);
        }

        return response()->json(['token' => $request->bearerToken(), 'user' => $request->user()->uuid]);
    }

    /**
     * Logs out the currently authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Auth::logout();

        return response()->json(['Goodbye']);
    }

    // /**
    //  * Send a verification SMS code.
    //  *
    //  * @param \\Illuminate\Http\Request $request
    //  * @return \Illuminate\Http\Response $response
    //  */
    // public function sendVerificationSms(Request $request)
    // {
    //     // Users phone number
    //     $phone = $queryPhone = $request->input('phone');
    //     $countryCode = $request->input('countryCode');
    //     $for = $request->input('driver');

    //     // set phone number
    //     if (!Str::startsWith($queryPhone, '+')) {
    //         $queryPhone = '+' . $countryCode . $phone;
    //     }

    //     // Make sure user exists with phone number
    //     $userExistsQuery = User::where('phone', $queryPhone)->whereNull('deleted_at')->withoutGlobalScopes();

    //     if ($for === 'driver') {
    //         $userExistsQuery->where('type', 'driver');
    //     }

    //     $userExists = $userExistsQuery->exists();

    //     if (!$userExists) {
    //         return response()->error('No user with this phone # found.');
    //     }

    //     // Generate hto
    //     $verifyCode = mt_rand(100000, 999999);
    //     $verifyCodeKey = Str::slug($queryPhone . '_verify_code', '_');

    //     // Store verify code for this number
    //     Redis::set($verifyCodeKey, $verifyCode);

    //     // Send user their verification code
    //     try {
    //         Twilio::message($queryPhone, `Your Fleetbase authentication code is ` . $verifyCode);
    //     } catch (Exception | RestException $e) {
    //         return response()->json(['error' => $e->getMessage()], 400);
    //     }

    //     // 200 OK
    //     return response()->json(['status' => 'OK']);
    // }

    // /**
    //  * Authenticate a user with SMS code.
    //  *
    //  * @param \\Illuminate\Http\Request $request
    //  * @return \Illuminate\Http\Response $response
    //  */
    // public function authenticateSmsCode(Request $request)
    // {
    //     // Users phone number
    //     $phone = $queryPhone = $request->input('phone');
    //     $countryCode = $request->input('countryCode');

    //     // set phone number
    //     if (!Str::startsWith($queryPhone, '+')) {
    //         $queryPhone = '+' . $countryCode . $phone;
    //     }

    //     // Users verfiy code entered
    //     $verifyCode = $request->input('code');
    //     $verifyCodeKey = Str::slug($queryPhone . '_verify_code', '_');

    //     // Generate hto
    //     $storedVerifyCode = Redis::get($verifyCodeKey);

    //     // Verify
    //     if ($verifyCode !== '000999' && $verifyCode !== $storedVerifyCode) {
    //         return response()->error('Invalid verification code');
    //     }

    //     // Remove from redis
    //     Redis::del($verifyCodeKey);

    //     // get user for phone number
    //     $user = User::where('phone', $queryPhone)->first();

    //     // Attempt authentication
    //     if ($user) {
    //         // Set authenticatin user
    //         Auth::login($user);

    //         // Generate token
    //         try {
    //             $token = $user->createToken($user->phone)->plainTextToken;
    //         } catch (Exception $e) {
    //             return response()->error($e->getMessage());
    //         }

    //         if ($user->type === 'driver') {
    //             $user->load(['driver']);
    //         }

    //         // Send message to notify users authentication
    //         return response()->json([
    //             'token' => $token,
    //             'user' => $user,
    //         ]);
    //     }

    //     // If unable to authenticate user, respond with error
    //     return response()->json('Authentication failed', 401);
    // }

    // /**
    //  * Allow user to verify SMS code.
    //  *
    //  * @param \\Illuminate\Http\Request $request
    //  * @return \Illuminate\Http\Response $response
    //  */
    // public function verifySmsCode(Request $request)
    // {
    //     // Users phone number
    //     $phone = $request->input('phone');

    //     // Users verfiy code entered
    //     $verifyCode = $request->input('code');
    //     $verifyCodeKey = Str::slug($phone . '_verify_code', '_');

    //     // Generate hto
    //     $storedVerifyCode = Redis::get($verifyCodeKey);

    //     // Verify
    //     if ($verifyCode === $storedVerifyCode) {
    //         // Remove from redis
    //         Redis::del($verifyCodeKey);

    //         // 200 OK
    //         return response()->json([
    //             'status' => 'OK',
    //             'message' => 'Code verified',
    //         ]);
    //     }

    //     // 400 ERROR
    //     return response()->error('Invalid verification code');
    // }



    // /**
    //  * Creates a new company and user account
    //  *
    //  * @param \Fleetbase\Http\Requests\SigUpRequest $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function signUp(SignUpRequest $request)
    // {
    //     $userDetails = $request->input('user');
    //     $companyDetails = $request->input('company');

    //     $newUser = Authy::register($userDetails, $companyDetails);

    //     $token = $newUser->createToken($request->ip());

    //     return response()->json(['token' => $token->plainTextToken]);
    // }



    // /**
    //  * Initializes a password reset using a verification code.
    //  *
    //  * @param \Fleetbase\Http\Requests\Internal\UserForgotPasswordRequest $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function createPasswordReset(UserForgotPasswordRequest $request)
    // {
    //     $user = User::where('email', $request->input('email'))->first();

    //     // create verification code
    //     $verificationCode = VerificationCode::create([
    //         'subject_uuid' => $user->uuid,
    //         'subject_type' => Utils::getMutationType($user),
    //         'for' => 'password_reset',
    //         'expires_at' => Carbon::now()->addMinutes(15),
    //         'status' => 'active'
    //     ]);

    //     // notify user of password reset
    //     $user->notify(new UserForgotPassword($verificationCode));

    //     return response()->json(['status' => 'ok']);
    // }

    // /**
    //  * Reset password.
    //  *
    //  * @param \Fleetbase\Http\Requests\Internal\ResetPasswordRequest $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function resetPassword(ResetPasswordRequest $request)
    // {
    //     // get verification code
    //     $verificationCode = VerificationCode::where('code', $request->input('code'))->with(['subject'])->first();

    //     if ($verificationCode->uuid !== $request->input('link')) {
    //         return response()->error('Invalid password reset request!');
    //     }

    //     // if no subject error
    //     if (!isset($verificationCode->subject)) {
    //         return response()->error('Invalid password reset request!');
    //     }

    //     // reset users password
    //     $verificationCode->subject->changePassword($request->input('password'));

    //     // verify code by deleting so its unusable
    //     $verificationCode->delete();

    //     return response()->json(['status' => 'ok']);
    // }

    /**
     * Takes a request username/ or email and password and attempts to authenticate user
     * will return the user model if the authentication was successful, else will 400
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getUserOrganizations(Request $request)
    {
        $user = $request->user();
        $companies = Company::whereHas(
            'users',
            function ($q) use ($user) {
                $q->where('users.uuid', $user->uuid);
            }
        )->get();

        return Organization::collection($companies);
    }

    /**
     * Allows a user to simply switch their organization.
     *
     * @param \Fleetbase\Http\Requests\SwitchOrganizationRequest $request
     * @return \Illuminate\Http\Response
     */
    public function switchOrganization(SwitchOrganizationRequest $request)
    {
        $nextOrganization = $request->input('next');
        $user = $request->user();

        if ($nextOrganization === $user->company_uuid) {
            return response()->json([
                'errors' => ['User is already on this organizations session']
            ]);
        }

        if (!CompanyUser::where(['user_uuid' => $user->uuid, 'company_uuid' => $nextOrganization])->exists()) {
            return response()->json([
                'errors' => ['You do not belong to this organization']
            ]);
        }

        $user->assignCompanyFromId($nextOrganization);
        Authy::setSession($user);

        return response()->json(['status' => 'ok']);
    }

    // /**
    //  * Allows a user to join an organization.
    //  *
    //  * @param \Fleetbase\Http\Requests\JoinOrganizationRequest $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function joinOrganization(JoinOrganizationRequest $request)
    // {
    //     $company = Company::where('public_id', $request->input('next'))->first();
    //     $user = $request->user();

    //     if ($company->uuid === $user->company_uuid) {
    //         return response()->json([
    //             'errors' => ['User is already on this organizations session']
    //         ]);
    //     }

    //     $company->addUser($user);
    //     $user->assignCompany($company);
    //     Authy::setSession($user);

    //     return response()->json(['status' => 'ok']);
    // }

    // /**
    //  * Allows user to create a new organization.
    //  *
    //  * @param \Illuminate\Http\Request $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function createOrganization(Request $request)
    // {
    //     $user = $request->user();
    //     $company = Company::create(array_merge($request->only(['name', 'description', 'phone', 'email', 'currency', 'country', 'timezone']), ['owner_uuid' => $user->uuid]));
    //     $company->createAsStripeCustomer([
    //         'email' => $user->email,
    //         'description' => 'Customer for ' . $company->name
    //     ]);
    //     $company->addUser($user);

    //     $user->assignCompany($company);
    //     Authy::setSession($user);

    //     return new Organization($company);
    // }

    // /**
    //  * Takes a request username/ or email and password and attempts to authenticate user
    //  * will return the user model if the authentication was successful, else will 400
    //  *
    //  * @param \Illuminate\Http\Request $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function checkExtensionAccess(Request $request)
    // {
    //     // $extension = $request->input('extension');
    //     $company = Company::where('uuid', session('company'))->first();

    //     if (!$company) {
    //         return response()->error('No organization authenticated.', 'session_ended');
    //     }

    //     // check if subscribed
    //     $isSubscribed = $company->subscribed('standard');
    //     $isGenericTrialing = $company->onGenericTrial();
    //     $isTrialing = $company->onTrial();

    //     // @todo any special guards or permission checks or cases for extension handle below

    //     // check if company has standard subscription
    //     return response()->json([
    //         'can_access' => $isGenericTrialing || $isTrialing || $isSubscribed
    //     ]);
    // }

    // /**
    //  * Options for CORS requests
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function options()
    // {
    //     return response()->make();
    // }
}
