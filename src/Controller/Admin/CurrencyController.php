<?php
namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\Client;

class CurrencyController extends AbstractController
{
    private $apiKey = 'bb48f367bd-023a1538ac-svhah4';

    /**
     * @Route("/admin/currency-list", name="admin_currency_list", methods={"GET"})
     */
    public function currencyList(): JsonResponse
    {
        $client = new Client();
        $response = $client->request('GET', 'https://api.fastforex.io/fetch-all', [
            'query' => ['api_key' => $this->apiKey, 'from' => 'USD'],
            'headers' => ['accept' => 'application/json'],
        ]);
        $data = json_decode($response->getBody(), true);
        $currencies = isset($data['results']) ? array_keys($data['results']) : [];
        return $this->json(['currencies' => $currencies]);
    }

    #[Route('/admin/currency-convert', name: 'admin_currency_convert', methods: ['POST'])]
    public function convert(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        if (!$data || !isset($data['amount']) || !isset($data['from']) || !isset($data['to'])) {
            return new JsonResponse(['error' => 'Invalid request data'], 400);
        }

        $amount = (float) $data['amount'];
        $from = $data['from'];
        $to = $data['to'];

        try {
            $client = new Client();
            $response = $client->request('GET', 'https://api.fastforex.io/fetch-all', [
                'query' => [
                    'api_key' => $this->apiKey,
                    'from' => $from
                ],
                'headers' => ['accept' => 'application/json'],
            ]);

            $ratesData = json_decode($response->getBody(), true);
            
            if (!isset($ratesData['results'])) {
                return new JsonResponse(['error' => 'Invalid API response'], 500);
            }

            $results = [];
            foreach ($to as $currency) {
                if (isset($ratesData['results'][$currency])) {
                    $results[$currency] = round($amount * $ratesData['results'][$currency], 2);
                }
            }

            return new JsonResponse(['results' => $results]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Currency conversion failed: ' . $e->getMessage()], 500);
        }
    }
}