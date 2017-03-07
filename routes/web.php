<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

Route::get('/', function () {
	$client = new Client();
	$url = "http://www.cnpq.br/web/guest/licitacoes?p_p_id=licitacoescnpqportlet_WAR_licitacoescnpqportlet_INSTANCE_BHfsvMBDwU0V&p_p_lifecycle=0&p_p_state=normal&p_p_mode=view&p_p_col_id=column-2&p_p_col_pos=1&p_p_col_count=2&pagina=3&delta=10&registros=1345";

	$crawler = $client->request('GET', $url);

	$bidding_list = $crawler->filterXPath('//div[@class="licitacoes"]')->each(function (Crawler $node, $i){
		$bidding = array(
			'origin' => 'CNPQ',
			'title' => $node->filterXPath('//h4')->text(),
			'description' => $node->filterXPath('//div[@class="cont_licitacoes"]')->text(),
			'start_date' => $node->filterXPath('//div[@class="data_licitacao"]/span[1]')->text(),
			'publication_date' => $node->filterXPath('//div[@class="data_licitacao"]/span[2]')->text(),
			'attachment_links' => $node->filterXPath('//ul[@class="download-list"]//a/@href')->each(function (Crawler $subnode, $i){
				return 'http://www.cnpq.br/' . $subnode->text();
			})
		);

		return $bidding;
	});

	dump($bidding_list);
    return view('welcome');
});
