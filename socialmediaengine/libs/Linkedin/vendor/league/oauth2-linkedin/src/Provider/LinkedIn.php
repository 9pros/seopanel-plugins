<?php

namespace League\OAuth2\Client\Provider;

use Exception;
use InvalidArgumentException;
use League\OAuth2\Client\Grant\AbstractGrant;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\Exception\LinkedInAccessDeniedException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\LinkedInAccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Client;

class LinkedIn extends AbstractProvider
{
    use BearerAuthorizationTrait;

    /**
     * Default scopes
     *
     * @var array
     */
    public $defaultScopes = ['r_liteprofile', 'r_emailaddress'];

    /**
     * Requested fields in scope, seeded with default values
     *
     * @var array
     * @see https://developer.linkedin.com/docs/fields/basic-profile
     */
    protected $fields = [
        'id', 'firstName', 'lastName', 'localizedFirstName', 'localizedLastName',
        'profilePicture(displayImage~:playableStreams)',
    ];

    /**
     * Constructs an OAuth 2.0 service provider.
     *
     * @param array $options An array of options to set on this provider.
     *     Options include `clientId`, `clientSecret`, `redirectUri`, and `state`.
     *     Individual providers may introduce more options, as needed.
     * @param array $collaborators An array of collaborators that may be used to
     *     override this provider's default behavior. Collaborators include
     *     `grantFactory`, `requestFactory`, and `httpClient`.
     *     Individual providers may introduce more collaborators, as needed.
     */
    public function __construct(array $options = [], array $collaborators = [])
    {
        if (isset($options['fields']) && !is_array($options['fields'])) {
            throw new InvalidArgumentException('The fields option must be an array');
        }

        parent::__construct($options, $collaborators);
    }


    /**
     * Creates an access token from a response.
     *
     * The grant that was used to fetch the response can be used to provide
     * additional context.
     *
     * @param  array $response
     * @param  AbstractGrant $grant
     * @return AccessTokenInterface
     */
    protected function createAccessToken(array $response, AbstractGrant $grant)
    {
        return new LinkedInAccessToken($response);
    }

    /**
     * Get the string used to separate scopes.
     *
     * @return string
     */
    protected function getScopeSeparator()
    {
        return ' ';
    }

    /**
     * Get authorization url to begin OAuth flow
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return 'https://www.linkedin.com/oauth/v2/authorization';
    }

    /**
     * Get access token url to retrieve token
     *
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return 'https://www.linkedin.com/oauth/v2/accessToken';
    }

    /**
     * Get provider url to fetch user details
     *
     * @param  AccessToken $token
     *
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        $query = http_build_query([
            'projection' => '(' . implode(',', $this->fields) . ')'
        ]);

        return 'https://api.linkedin.com/v2/me?' . urldecode($query);
    }

    /**
     * Get provider url to fetch user details
     *
     * @param  AccessToken $token
     *
     * @return string
     */
    public function getResourceOwnerEmailUrl(AccessToken $token)
    {
        $query = http_build_query([
            'q' => 'members',
            'projection' => '(elements*(state,primary,type,handle~))'
        ]);

        return 'https://api.linkedin.com/v2/clientAwareMemberHandles?' . urldecode($query);
    }

    /**
     * Get the default scopes used by this provider.
     *
     * This should not be a complete list of all scopes, but the minimum
     * required for the provider user interface!
     *
     * @return array
     */
    protected function getDefaultScopes()
    {
        return $this->defaultScopes;
    }

    /**
     * Check a provider response for errors.
     *
     * @param  ResponseInterface $response
     * @param  array $data Parsed response data
     * @return void
     * @throws IdentityProviderException
     * @see https://developer.linkedin.com/docs/guide/v2/error-handling
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        $this->checkResponseUnauthorized($response, $data);

        if ($response->getStatusCode() >= 400) {
            throw new IdentityProviderException(
                $data['message'] ?: $response->getReasonPhrase(),
                $data['status'] ?: $response->getStatusCode(),
                $response
            );
        }
    }

    /**
     * Check a provider response for unauthorized errors.
     *
     * @param  ResponseInterface $response
     * @param  array $data Parsed response data
     * @return void
     * @throws LinkedInAccessDeniedException
     * @see https://developer.linkedin.com/docs/guide/v2/error-handling
     */
    protected function checkResponseUnauthorized(ResponseInterface $response, $data)
    {
        if (isset($data['status']) && $data['status'] === 403) {
            throw new LinkedInAccessDeniedException(
                $data['message'] ?: $response->getReasonPhrase(),
                $response->getStatusCode(),
                $response
            );
        }
    }

    /**
     * Generate a user object from a successful user details request.
     *
     * @param array $response
     * @param AccessToken $token
     * @return LinkedInResourceOwner
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        // If current accessToken is not authorized with r_emailaddress scope,
        // getResourceOwnerEmail will throw LinkedInAccessDeniedException, it will be caught here,
        // and then the email will be set to null
        // When email is not available due to chosen scopes, other providers simply set it to null, let's do the same.
        try {
            $email = $this->getResourceOwnerEmail($token);
        } catch (LinkedInAccessDeniedException $exception) {
            $email = null;
        }

        return new LinkedInResourceOwner($response, $email);
    }

    /**
     * Returns the requested fields in scope.
     *
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Attempts to fetch resource owner's email address via separate API request.
     *
     * @param  AccessToken $token [description]
     * @return string|null
     * @throws IdentityProviderException
     */
    public function getResourceOwnerEmail(AccessToken $token)
    {
        $emailUrl = $this->getResourceOwnerEmailUrl($token);
        $emailRequest = $this->getAuthenticatedRequest(self::METHOD_GET, $emailUrl, $token);
        $emailResponse = $this->getParsedResponse($emailRequest);

        return $this->extractEmailFromResponse($emailResponse);
    }

    /**
     * Updates the requested fields in scope.
     *
     * @param  array   $fields
     *
     * @return LinkedIn
     */
    public function withFields(array $fields)
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * Attempts to extract the email address from a valid email api response.
     *
     * @param  array  $response
     * @return string|null
     */
    protected function extractEmailFromResponse($response = [])
    {
        try {
            $confirmedEmails = array_filter($response['elements'], function ($element) {
                return
                    strtoupper($element['type']) === 'EMAIL'
                    && strtoupper($element['state']) === 'CONFIRMED'
                    && $element['primary'] === true
                    && isset($element['handle~']['emailAddress'])
                ;
            });

            return $confirmedEmails[0]['handle~']['emailAddress'];
        } catch (Exception $e) {
            return null;
        }
    }    
    
    public function getPerson($accessToken)
    {
        $url = "https://api.linkedin.com/v2/me?projection=(id,firstName,lastName,profilePicture(displayImage~:playableStreams))&oauth2_access_token=" . $accessToken;
        $params = [];
        $response = $this->curl($url,http_build_query($params), "application/x-www-form-urlencoded", false);
        $person = json_decode($response);
        return $person;
    }
    
    public function getPersonID($accessToken)
    {
        $url = "https://api.linkedin.com/v2/me?oauth2_access_token=" . $accessToken;
        $params = [];
        $response = $this->curl($url,http_build_query($params), "application/x-www-form-urlencoded", false);
        $personID = json_decode($response)->id;
        return $personID;
    }
    
    public function getCompanyPages($accessToken)
    {
        
        $company_pages = "https://api.linkedin.com/v2/companies?format=json&is-company-admin=true&oauth2_access_token=" . trim($accessToken);
        $pages = $this->curl($company_pages,json_encode([]), "application/json", false);
        return json_decode($pages);
        
    }
    
    public function linkedInTextPost($accessToken , $person_id,  $message, $visibility = "PUBLIC")
    {
        $post_url = "https://api.linkedin.com/v2/ugcPosts?oauth2_access_token=" .$accessToken;
        $request = [
            "author" => "urn:li:person:" . $person_id,
            "lifecycleState" => "PUBLISHED",
            "specificContent" => [
                "com.linkedin.ugc.ShareContent" => [
                    "shareCommentary" => [
                        "text" => $message
                    ],
                    "shareMediaCategory" => "NONE",
                ],
                
            ],
            "visibility" => [
                "com.linkedin.ugc.MemberNetworkVisibility" => $visibility,
            ]
        ];
        $post = $this->curl($post_url,json_encode($request), "application/json", true);
        return $post;
    }
    
    public function linkedInLinkPost($accessToken, $person_id, $message, $link_title, $link_desc, $link_url , $visibility = "PUBLIC")
    {
        $post_url = "https://api.linkedin.com/v2/ugcPosts?oauth2_access_token=" .$accessToken;
        $request = [
            "author" => "urn:li:person:" . $person_id,
            "lifecycleState" => "PUBLISHED",
            "specificContent" => [
                "com.linkedin.ugc.ShareContent" => [
                    "shareCommentary" => [
                        "text" => $message
                    ],
                    "shareMediaCategory" => "ARTICLE",
                    "media"=> [[
                        "status" => "READY",
                        "description"=> [
                            "text" => substr($link_desc, 0, 200),
                        ],
                        "originalUrl" =>  $link_url,
                        
                        "title" => [
                            "text" => $link_title,
                        ],
                    ]],
                ],
                
            ],
            "visibility" => [
                "com.linkedin.ugc.MemberNetworkVisibility" => $visibility,
            ]
        ];
        
        $post = $this->curl($post_url,json_encode($request), "application/json", true);
        return $post;
    }
    
    public function linkedInPhotoPost($accessToken,   $person_id, $message, $image_path,  $image_title, $image_description , $visibility = "PUBLIC")
    {
        
        $prepareUrl = "https://api.linkedin.com/v2/assets?action=registerUpload&oauth2_access_token=" .$accessToken;
        $prepareRequest =  [
            "registerUploadRequest" => [
                "recipes" => [
                    "urn:li:digitalmediaRecipe:feedshare-image"
                ],
                "owner" => "urn:li:person:" . $person_id,
                "serviceRelationships" => [
                    [
                        "relationshipType" => "OWNER",
                        "identifier" => "urn:li:userGeneratedContent"
                    ],
                ],
            ],
        ];
        
        $prepareReponse = $this->curl($prepareUrl,json_encode($prepareRequest), "application/json");
        $uploadURL = json_decode($prepareReponse)->value->uploadMechanism->{"com.linkedin.digitalmedia.uploading.MediaUploadHttpRequest"}->uploadUrl;
        $asset_id = json_decode($prepareReponse)->value->asset;
        ;
        // dump($photo);
        
        
        $client =new Client();
        $response = $client->request('PUT', $uploadURL, [
            'headers' => [ 'Authorization' => 'Bearer ' . $accessToken ],
            'body' => fopen($image_path, 'r'),
            'verify' => $this->ssl
        ]);
        
        // dump($response);
        
        
        $post_url = "https://api.linkedin.com/v2/ugcPosts?oauth2_access_token=" .$accessToken;
        $request = [
            "author" => "urn:li:person:" . $person_id,
            "lifecycleState" => "PUBLISHED",
            "specificContent" => [
                "com.linkedin.ugc.ShareContent" => [
                    "shareCommentary" => [
                        "text" => $message
                    ],
                    "shareMediaCategory" => "IMAGE",
                    "media"=> [[
                        "status" => "READY",
                        "description"=> [
                            "text" => substr($image_description, 0, 200),
                        ],
                        "media" =>  $asset_id,
                        
                        "title" => [
                            "text" => $image_title,
                        ],
                    ]],
                ],
                
            ],
            "visibility" => [
                "com.linkedin.ugc.MemberNetworkVisibility" => $visibility ,
            ]
        ];
        
        $post = $this->curl($post_url,json_encode($request), "application/json");
        // dd($post);
        return $post;
    }
    
    public function curl($url, $parameters, $content_type, $post = true)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->ssl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($post) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
        }
        curl_setopt($ch, CURLOPT_POST, $post);
        $headers = [];
        $headers[] = "Content-Type: {$content_type}";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        return $result;
    }
    
}
