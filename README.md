## Configuration Instructions

1. Update the `.env` file with your social login credentials:
   ```env
   GOOGLE_CLIENT_ID=your-google-client-id
   GOOGLE_CLIENT_SECRET=your-google-client-secret
   GOOGLE_REDIRECT_URI=http://localhost/api/auth/google/callback
   ```

2. Update the `config/auth.php` file with your Passport client credentials:
   ```php
   'passport' => [
       'client_id' => env('PASSPORT_CLIENT_ID'),
       'client_secret' => env('PASSPORT_CLIENT_SECRET'),
       'access_token_expiration' => env('ACCESS_TOKEN_EXPIRATION', 60),
       'refresh_token_expiration' => env('REFRESH_TOKEN_EXPIRATION', 365),
       'providers' => [
           'google',
       ],
   ],
   ```

## Example Return When Completing the Social Login Flow

When you complete the social login flow, you will receive a JSON response similar to the following:

```json
{
    "access_token": "your-access-token",
    "refresh_token": "your-refresh-token",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "johndoe@example.com",
        "created_at": "2023-01-01T00:00:00.000000Z",
        "updated_at": "2023-01-01T00:00:00.000000Z"
    }
}
```

## Authentication Flow

To start the authentication flow, the user must go to `http://localhost/api/auth/google/redirect`. Here is a step-by-step explanation of the authentication flow:

1. **Redirection**: The user is redirected to the Google authentication page.
2. **Google login**: The user logs in with their Google account, and Google redirects back to your application with an authorization code.
3. **Socialite/Google Handshake**: Socialite get the user information from Google
4. **Internal Request to `oauth/token` Route**: The application makes an internal request to the `oauth/token` route to obtain an access token and a refresh token.
5. **User Registration or User Update**: The application checks if the user already exists in the database. If the user exists, their information is updated. If the user does not exist, a new user is created.
6. **Token and Token Refresh Generation**: The OAuth server generates an access token and a refresh token.
7. **Return Data**: The application returns the access token, refresh token, and user information as a JSON response.
