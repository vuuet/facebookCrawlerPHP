<?php
/* PHP SDK v5.0.0 */
/* make the API call */
 
// Include the required dependencies.
require_once __DIR__ . '/vendor/autoload.php';
$fb = new Facebook\Facebook([
  'app_id' => '1744882292444086',
  'app_secret' => 'e25b31c957beab8f53942d5dc1f7f662',
  'default_graph_version' => 'v2.7',
]);
$long_lived_AccessToken = "EAAYy9hE3S7YBACLHGOKSijZADoG2BoYpwVW4FZCzJx7omGJF0zHw5SqWnbIeLttY1DtVOdC00I16KFZCuZCwWpWL9yTx4IKPKt8mXH3DBZAapAIbYnjXZAh5zbdn9OxpxlhZCnEBj4mrdfnzqVYxn8HsoLAqZAtMSo2JKo7ZB9iIDJAZDZD";
// Sets the default fallback access token so we don't have to pass it to each request
$fb->setDefaultAccessToken($long_lived_AccessToken);

$response = $fb->get('/488125214682885/conversations');
$ConversationsEdge = $response->getGraphEdge();
$conversationsIdArray = array();
$count = 0;
foreach ($ConversationsEdge as $status) {
  array_push($conversationsIdArray, $status->asArray()['id']);
  echo $count++.'<br>';
}

$nextCoversation = $fb->next($ConversationsEdge);
while (isset($nextCoversation)) {
	# code...
	foreach ($nextCoversation as $status) {
  		array_push($conversationsIdArray, $status->asArray()['id']);
  		echo $count++.'<br>';
	}
	$nextCoversation = $fb->next($nextCoversation);
}
//print_r($conversationsIdArray);
$dem = 0;
$Export = array();
foreach ($conversationsIdArray as $id) {
	try {
		//code...
		$dem++;
		// if ($dem==2)
		// 	break;
		echo $dem.".Conversations ID: ".$id.'<br>';
		array_push($Export, "----------Conversations ID: ".$id."----------");
		$response = $fb->get('/'.$id.'/messages?fields=from,message');
		$content_page = $response->getGraphEdge();

		foreach ($content_page as $content) {
			$content = $content->asArray();
			//echo "@".$content['from']['name']." => ".$content['message'];
			//echo '<br>';
			array_push($Export, "[".$content['from']['name']."] => ".$content['message']);
		}
		
		$next_content_page = $fb->next($content_page);
		while (isset($next_content_page)) {
		# code...
			foreach ($next_content_page as $content) {
				//$content = array_reverse($content->asArray());
				$content = $content->asArray();
				//echo "@".$content['from']['name']." => ".$content['message'];
				//echo '<br>';
				array_push($Export, "[".$content['from']['name']."] => ".$content['message']);
			}
			$next_content_page = $fb->next($next_content_page);
		}
	}

	//catch exception
	catch(Exception $e) {
	  echo 'Message: ' .$e->getMessage();
	  continue;
	}
	
}

$fp=fopen("FbConversations.data","w+");
$Export = array_reverse($Export);
foreach($Export as $value){
	fwrite($fp,$value."\n");
}

$fp.close();
?>