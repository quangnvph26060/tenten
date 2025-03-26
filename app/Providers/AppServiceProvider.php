<?php

namespace App\Providers;

use App\Models\Domain;
use App\Models\PriceDomain;
use GuzzleHttp\Client;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Paginator::useBootstrapFive();
        $this->domainPrice();
        $this->domainList();
    }




    public function domainPrice()
    {
        $url = 'https://api-reseller.tenten.vn/v1/Domains/price.json';

        $data = [
            "api_key" => "6dc564c5e650dedd67144761a3f2fcdb",
            "api_user" => "dnse002",
        ];


        $client = new Client();
        $response = $client->post($url, [
            'form_params' => $data,
        ]);

        $responseBody = json_decode($response->getBody(), true);

        if (isset($responseBody['error']) && !empty($responseBody['error'])) {
            throw new \Exception($responseBody['error']);
        }

        // Dữ liệu từ API
        $domains = $responseBody['data'] ?? [];

        foreach ($domains as $key => $domain) {
            $check = PriceDomain::where('name', $key)->get();
            if ($check->isEmpty()) {
                PriceDomain::create([
                    'name' =>  $key,
                    'price' => $domain['total'],
                    'vat' => $domain['vat'],
                ]);
            }
        }
    }

    public function domainList()
    {
        $url = 'https://api-reseller.tenten.vn/v1/Domains/list.json';

        $data = [
            "api_key" => "6dc564c5e650dedd67144761a3f2fcdb",
            "api_user" => "dnse002",
            "page" => 1,
            "limit" => E_ALL,
        ];

        $client = new Client();
        $response = $client->post($url, [
            'form_params' => $data,
        ]);

        $responseBody = json_decode($response->getBody(), true);

        if (isset($responseBody['error']) && !empty($responseBody['error'])) {
            throw new \Exception($responseBody['error']);
        }

        $domains = $responseBody['data'] ?? [];
        foreach ($domains as $domain) {
            $check = Domain::where('name', $domain['domain_name'])->get();
            if ($check->isEmpty()) {
                Domain::create([
                    'name' => $domain['domain_name'],
                    'created_date' => $domain['created_date'],
                    'expiration_date' => $domain['expiration_date'],
                    'status' => $domain['domain_status']
                ]);
            }
        }
    }
}
