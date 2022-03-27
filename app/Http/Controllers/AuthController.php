<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Exceptions\OAuthServerException;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Psr\Http\Message\ServerRequestInterface;

class AuthController extends AccessTokenController
{
    use ApiResponser;
    /**
     * Issues a token to user with valid username/password
     * @param Psr\Http\Message\ServerRequestInterface 
     * @return \Illuminate\Http\JsonResponse
     */
    public function issueToken(ServerRequestInterface $request)
    {
        try {
                //validate the request
                $validator = Validator::make($request->getParsedBody(),[
                    'grant_type' => "required",
                    'client_id' => "required",
                    'client_secret' => "required",
                    'username' => "required",
                    'password' => "required",
                ]);
                if($validator->fails())
                {
                    //Return failed validation message
                    return $this->response($validator->errors(), Response::HTTP_BAD_REQUEST);
                }
                //get username
                $username = $request->getParsedBody()['username'];

                //get user
                $user = User::where('email', '=', $username)->first();

                //generate token
                $tokenResponse = parent::issueToken($request);

                //convert response to json string
                $content = $tokenResponse->getContent();

                //convert json to array
                $data = json_decode($content, true);

                if(isset($data["error"]))
                    throw new OAuthServerException($data["error"],$tokenResponse);


                return $this->response('Access granted', Response::HTTP_OK, $data);
        }
        catch (ModelNotFoundException $e) { 
            // email not found
            //return error message
            return $this->response('User not found', Response::HTTP_NOT_FOUND);
        }
        catch (OAuthServerException $e) {
             //password not correct..token not granted
            //return error message
            return $this->response('Invalid user credentials', Response::HTTP_BAD_REQUEST);
        }
        catch (Exception $e) {
            ////return error message
            return $this->response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    /**
     * Refreshes an access token using a refresh_token provided during access token
     * @param \Psr\Http\Message\ServerRequestInterface
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshToken(ServerRequestInterface $request)
    {
        
        try {
                //validate the request
                $validator = Validator::make($request->getParsedBody(),[
                    'grant_type' => "required",
                    'client_id' => "required",
                    'client_secret' => "required",
                    'refresh_token' => "required",
                ]);
                if($validator->fails())
                {
                    //Return failed validation message
                    return $this->response($validator->errors(), Response::HTTP_BAD_REQUEST);
                }
                
                //generate token
                $tokenResponse = parent::issueToken($request);

                //convert response to json string
                $content = $tokenResponse->getContent();

                //convert json to array
                $data = json_decode($content, true);

                if(isset($data["error"]))
                    throw new OAuthServerException($data["error"],$tokenResponse);


                return $this->response('Token refreshed.', Response::HTTP_OK, $data);
        }
        catch (OAuthServerException $e) {
            //password not correct..token not granted
            //return error message
            return $this->response($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
        catch (Exception $e) {
            ////return error message
            return $this->response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Logs out a user from an application and requires a user's username and password to login back
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $token = auth()->user()->token();

        /* --------------------------- revoke access token -------------------------- */
        $token->revoke();
        $token->delete();

        /* -------------------------- revoke refresh token -------------------------- */
        $refreshTokenRepository = app(RefreshTokenRepository::class);
        // $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($token->id);

        return $this->response('Logged out successfully', Response::HTTP_OK);
    }
    /**
     * Logs out a user from an application
     * @return \Illuminate\Http\JsonResponse
     */
    public function logoutPartial()
    {
        $token = auth()->user()->token();
  
        /* --------------------------- revoke access token -------------------------- */
        $token->revoke();

        return $this->response('Logged out successfully', Response::HTTP_OK);
    }
}
