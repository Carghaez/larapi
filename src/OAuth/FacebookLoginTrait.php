<?php
namespace Carghaez\Larapi\OAuth;

use Illuminate\Http\Request;
use League\OAuth2\Server\Exception\OAuthServerException;

trait FacebookLoginTrait
{
    /**
     * Logs a App\User in using a Facebook token via Passport
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     * @throws \League\OAuth2\Server\Exception\OAuthServerException
     */
    public function loginFacebook(Request $request)
    {
        try {
            /**
             * Check if the 'social_token' as passed.
             */
            if ($request->get('social_token')) {

                $fbUser = Socialite::driver('facebook')
                    ->fields([
                        'name',
                        'first_name',
                        'last_name',
                        'email',
                        'gender',
                        'link',
                        'verified'
                    ])->userFromToken($request->get('social_token'));

                if (!$fbUser) {
                    throw OAuthServerException::serverError('Wrong or expired facebook token');
                }

                /**
                 * Check if the user has already signed up.
                 */
                if (is_null($fbModel = config('auth.providers.users.model_facebook'))) {
                    throw OAuthServerException::serverError('Unable to determine user facebook model from configuration');
                }

                $profileFacebook = $fbModel::find($fbUser->id)->first();

                if (empty($profileFacebook)) {
                    throw OAuthServerException::serverError('Unable to determine user from facebook id');
                }

                $userModel = config('auth.providers.users.model');

                $user = $userModel::find($fbUser->user_id)->first();

                return $user;
            }
        } catch (\Exception $e) {
            throw OAuthServerException::accessDenied($e->getMessage());
        }
        return null;
    }
}
