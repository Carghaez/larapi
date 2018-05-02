<?php

/*
 * This file is part of the Larapi package.
 *
 * (c) Gaetano Carpinato <gaetanocarpinato@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carghaez\Larapi\Auth;

use App;
use Carghaez\Larapi\Auth\Exceptions\InvalidCredentialsException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * LoginProxy.
 *
 * @author Gaetano Carpinato <gaetanocarpinato@gmail.com>
 */
class LoginProxy
{
    const REFRESH_TOKEN = 'refreshToken';

    private $apiConsumer;

    private $auth;

    private $cookie;

    private $db;

    private $request;

    /**
     * Constructor.
     *
     */
    public function __construct()
    {
        $this->apiConsumer = \App::make('apiconsumer');
        $this->auth = \App::make('auth');
        $this->cookie = \App::make('cookie');
        $this->db = \App::make('db');
        $this->request = \App::make('request');
    }

    /**
     * Attempt to create an access token using user credentials.
     *
     * @param string $email
     * @param string $password
     */
    public function attemptLogin($email, $password)
    {
        $user = $this->db->table('users')->where('email', $email)->orderBy('id', 'desc')->first();
        if (is_null($user)) {
            throw new InvalidCredentialsException();
        }

        $results = $this->proxy('password', [
            'username' => $email,
            'password' => $password . $user->salt
        ]);
        $results['user_id'] = $user->id;

        return $results;
    }

    /**
     * Attempt to create an access token using user credentials.
     *
     * @param string $email
     * @param string $password
     */
    public function attemptSocialLogin($driver, $email, $token)
    {
        if ($driver !== 'facebook') {
            throw new InvalidCredentialsException();
        }

        $user = $this->db->table('users')->where('email', $email)->orderBy('id', 'desc')->first();
        if (is_null($user)) {
            throw new InvalidCredentialsException();
        }

        $results = $this->proxy($driver . '_login', [
            'social_token' => $token,
        ]);
        $results['user_id'] = $user->id;

        return $results;
    }

    /**
     * Attempt to refresh the access token used a refresh token that
     * has been saved in a cookie.
     *
     * @return mixed
     */
    public function attemptRefresh()
    {
        $refreshToken = $this->request->cookie(self::REFRESH_TOKEN);

        return $this->proxy('refresh_token', [
            'refresh_token' => $refreshToken
        ]);
    }

    /**
     * Proxy a request to the OAuth server.
     *
     * @param string $grantType what type of grant type should be proxied
     * @param array $data the data to send to the server
     *
     * @return mixed
     */
    public function proxy($grantType, array $data = [])
    {
        $password_client = $this->db
            ->table('oauth_clients')
            ->where('name', 'Laravel Password Grant Client')
            ->first();

        if (!$password_client) {
            throw new HttpException(501, 'Missing OAuth Client');
        }

        $data = array_merge($data, [
            'client_id'     => $password_client->id,
            'client_secret' => $password_client->secret,
            'grant_type'    => $grantType,
            'scope'         => '*'
        ]);

        $response = $this->apiConsumer->post('/oauth/token', $data);

        if (!$response->isSuccessful()) {
            throw new InvalidCredentialsException($response->getContent());
        }

        $result = json_decode($response->getContent());

        // Create a refresh token cookie
        $this->cookie->queue(
            self::REFRESH_TOKEN,
            $result->refresh_token,
            864000, // 10 days
            null,
            null,
            false,
            true // HttpOnly
        );

        return [
            'access_token' => $result->access_token,
            'expires_in' => $result->expires_in
        ];
    }

    /**
     * Logs out the user. We revoke access token and refresh token.
     * Also instruct the client to forget the refresh cookie.
     */
    public function logout()
    {
        $user = $this->auth->user();
        if (!$user) {
            return;
        }
        $accessToken = $this->auth->user()->token();

        $refreshToken = $this->db
            ->table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        $accessToken->revoke();

        $this->cookie->queue($this->cookie->forget(self::REFRESH_TOKEN));
    }
}
