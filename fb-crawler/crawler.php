<?php
/* PHP SDK v5.0.0 */
/* make the API call */
 
// Include the required dependencies.
require_once( 'vendor/autoload.php' );
require_once __DIR__ . '/vendor/autoload.php';
$fb = new Facebook\Facebook([
  'app_id' => '1744882292444086',
  'app_secret' => 'e25b31c957beab8f53942d5dc1f7f662',
  'default_graph_version' => 'v2.7',
]);
$long_lived_AccessToken = "EAAYy9hE3S7YBACLHGOKSijZADoG2BoYpwVW4FZCzJx7omGJF0zHw5SqWnbIeLttY1DtVOdC00I16KFZCuZCwWpWL9yTx4IKPKt8mXH3DBZAapAIbYnjXZAh5zbdn9OxpxlhZCnEBj4mrdfnzqVYxn8HsoLAqZAtMSo2JKo7ZB9iIDJAZDZD";
// Sets the default fallback access token so we don't have to pass it to each request
$fb->setDefaultAccessToken($long_lived_AccessToken);

$response = $fb->get('/488125214682885?fields=conversations');
//$responseObj = $response->getGraphObject();
//print_r( $response->getDecodedBody());
//echo count($responseObj['conversations']);
//echo $responseObj['conversations'][26]['id'];
$conversations_id_array = array();
$reponseArray = $response->getDecodedBody();
//echo $reponseArray['conversations']['paging']['next'];
foreach ($reponseArray['conversations']['data'] as $value) {
	array_push($conversations_id_array, $value['id']);
	//echo $value['id'];
}
$next_page = $reponseArray['conversations']['paging']['next'];
$dem = 0;
//$conversations_id_array = $reponseArray['conversations']['data'];
while($next_page != null) {
	$dem++; 
	if ($dem == 5)
		break;
	$next_page_url = str_replace('https://graph.facebook.com/v2.7/488125214682885/conversations?access_token=EAAYy9hE3S7YBACLHGOKSijZADoG2BoYpwVW4FZCzJx7omGJF0zHw5SqWnbIeLttY1DtVOdC00I16KFZCuZCwWpWL9yTx4IKPKt8mXH3DBZAapAIbYnjXZAh5zbdn9OxpxlhZCnEBj4mrdfnzqVYxn8HsoLAqZAtMSo2JKo7ZB9iIDJAZDZD', '', $reponseArray['conversations']['paging']['next'] );
	//echo $next_page_url;
	$response = $fb->get('/488125214682885?fields=conversations'.$next_page_url);
	$reponseArray = $response->getDecodedBody();
	$next_page = $reponseArray['conversations']['paging']['next'];
	//echo $reponseArray['conversations']['paging']['next'];
	foreach ($reponseArray['conversations']['data'] as $value) {
		array_push($conversations_id_array, $value['id']);
	//echo $value['id'];
	}
}
//Functional-style!
//print_r($conversations_id_array);
$conversations_content_array = array();
foreach ($conversations_id_array as $value) {
	# code...
	$response = $fb->get('/'.$value.'/messages?fields=from,message');
	$reponseArray = $response->getDecodedBody();
	//print_r($reponseArray);
	foreach ($reponseArray['data'] as $value) {
		echo $value['from']['name']."=>".$value['message'];
		echo '<br>';
		//$conversations_content_array[$value['from']['name']] = $value['message'];
	//echo $value['id'];
	}
	//$next_page = $reponseArray['conversations']['paging']['next'];
}
print_r($conversations_content_array);
function getConversationByConversationId($conversation_id) {

}
?>