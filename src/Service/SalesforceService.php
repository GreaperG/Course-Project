<?php 

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class SalesforceService
{
  private ?string $accessToken = null;
  private ?string $instanceUrl = null;

  public function __construct(
    private HttpClientInterface $httpClient,
    private string $salesforceUsername,
    private string $salesforcePassword,
    private string $salesforceSecurityToken,
    private string $salesforceClientId,
    private string $salesforceClientSecret,
  )
  {}

  public function authenticate(): void
  {
    $response = $this->httpClient->request('POST', 'https://login.salesforce.com/services/oauth2/token', [
        'body' => [
            'grant_type' => 'password',
            'client_id' => $this->salesforceClientId,
            'client_secret' => $this->salesforceClientSecret,
            'username' => $this->salesforceUsername,
            'password' => $this->salesforcePassword . $this->salesforceSecurityToken,
        ],
    ]);

    $data = $response->toArray();
    $this->accessToken = $data['access_token'];
    $this->instanceUrl = $data['instance_url'];
  }

  public function createAccountAndContact(
    string $companyName,
    string $phone,
    string $firstName,
    string $lastName,
    string $email,
    ?string $website = null,
    ?string $industry = null
    ): string
  {
    if(!$this->accessToken){
        $this->authenticate();
    }
    $accountData = [
        'Name' => $companyName,
        'Phone' => $phone,
    ];

    if($website){
        $accountData['Website'] = $website;
    }

    if($industry){
        $accountData['Industry'] = $industry;
    }
    


    $response = $this->httpClient->request('POST', $this->instanceUrl . '/services/data/v58.0/sobjects/Account', [
        'headers' => [
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Content-Type' => 'application/json',
        ],
        'json' => $accountData,
    ]);

    $data = $response->toArray();
    return $data['id'];
  }

  public function createContact(string $accountId, string $firstName, string $lastName, string $email): string
  {

    if(!$this->accessToken){
        $this->authenticate();
    }
  
    $response = $this->httpClient->request('POST', $this->instanceUrl . '/services/data/v58.0/sobjects/Contact', [
        'headers' => [
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Content-Type' => 'application/json',
        ],
        'json' => [
            'AccountId' => $accountId,
            'FirstName' => $firstName,
            'LastName' => $lastName,
            'Email' => $email,
        ],
    ]);

    $data = $response->toArray();
    return $data['id'];
  }
}

?>