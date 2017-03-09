<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class CnpqCrawlerController extends Controller{

	public function show_biddings($page = 1) {
		$url = 'http://www.cnpq.br/web/guest/licitacoes?p_p_id=licitacoescnpqportlet_WAR_licitacoescnpqportlet_INSTANCE_BHfsvMBDwU0V&p_p_lifecycle=0&p_p_state=normal&p_p_mode=view&p_p_col_id=column-2&p_p_col_pos=1&p_p_col_count=2&delta=10&registros=1345&pagina=' . $page;

		$client = new Client();

		$crawler = $client->request('GET', $url);

		$bidding_list = $crawler->filterXPath('//div[@class="licitacoes"]')->each(function (Crawler $node, $i){
			$bidding = array(
				'origin' => 'CNPQ',
				'title' => $node->filterXPath('//h4')->text(),
				'description' => $node->filterXPath('//div[@class="cont_licitacoes"]')->text(),
				'start_date' => $node->filterXPath('//div[@class="data_licitacao"]/span[1]')->text(),
				'publication_date' => $node->filterXPath('//div[@class="data_licitacao"]/span[2]')->text(),
				'attachment_links' => $node->filterXPath('//ul[@class="download-list"]//a')
				                           ->each(function (Crawler $subnode, $i){
					                           return array(trim($subnode->text()), 'http://www.cnpq.br' . $subnode->filterXPath('//a/@href')->text());
				                           })
			);

			return $bidding;
		});

		$response_body = ['description' => 'The biddings scraped from www.cnpq.br',
						  'page' => $page,
		                  'data' => $bidding_list];

		return response()
			->json($response_body,
				   200,
				   ['Content-type'=> 'application/json; charset=utf-8'],
				   JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_LINE_TERMINATORS);
	}
}
