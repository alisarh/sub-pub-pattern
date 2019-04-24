<?php

class Message {

	var $topic;
	var $content;
	
}

class Buffer {

	
	var $buffer = array();
	var $subs = array();

	public function forward(){
	
		$keys = array_keys($this->buffer);
		for($k=0 ; $k<count($keys); $k++){
		for($i=0; $i<count($this->subs);$i++){
			for($j=0; $j<count($this->subs[$i]->topics); $j++){
					if($this->subs[$i]->topics[$j] == $keys[$k]){
						end($this->buffer[$keys[$k]]);
						$in = key($this->buffer[$keys[$k]]);
						if(!in_array($this->buffer[$keys[$k]][$in],$this->subs[$i]->msgs)){
						array_push($this->subs[$i]->msgs , $this->buffer[$keys[$k]][$in]);
						}
					}
				}
			
		}
		
		}
					
				
	}
		
	
}
class Publish {
	
	public function send(Message $msg , Buffer $buf ){		

		if(!in_array($msg->topic , $buf->buffer)){
		     $buf->buffer[$msg->topic][]= $msg->content;
		}
		else
	array_push($buf->buffer[$msg->topic],$msg->content);
		
		
	
}
}
class Subscribe {

	var $topics = array();
	var $msgs = array();
	public function subs(Message $msg){		
		if(!in_array($msg->topic , $this->topics)){
			array_push($this->topics,$msg->topic);
		
	}

	
}

	public function unSubs(Message $msg){
			if(($key = array_search($msg->topic , $this->topics))!== false ){
					array_splice($this->topics,$key,1);
				}
	}	
	
	public function printt(){
		foreach(array_reverse($this->msgs) AS $mesg){
			print_r($mesg);
			echo '<br/>';
		}
	}
}

$pub = new Publish();
$pub1 = new Publish();
$msg = new Message();
$msg1 = new Message();
$msg2 = new Message();
$msg3 = new Message();
$buff = new Buffer();
$sub = new Subscribe();
$sub1 = new Subscribe();
$msg->topic = 'dogs';
$msg->content='first dog';
$msg1->topic = 'dogs';
$msg1->content='second dog';
$msg2->topic = 'cats';
$msg2->content='first cat';
$msg3->topic = 'cats';
$msg3->content='se cat';
/* publish message */

$pub->send($msg2,$buff);
$pub->send($msg,$buff);
/* subscribe topic */
$sub->subs($msg);
$sub->subs($msg2);

$sub1->subs($msg2); 

/* add subscribers to buffer */ 
$buff->subs[0] = $sub;
$buff->subs[1] = $sub1;

$buff->forward();
/* new messages */
$pub->send($msg1,$buff);
$pub->send($msg3,$buff);
/* unsubscribe topic befor buffer forward */
$sub->unSubs($msg2);
$buff->forward();
echo '<br/>';
$sub->printt();


?>