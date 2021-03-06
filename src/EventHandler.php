<?php
  namespace elpho\event;

  abstract class EventHandler{
    private $listeners = array();

    public function addEventListener($event,$listener,$method=""){
      $eventName = $event;

      if(is_object($event))
        $eventName = get_class($event);

      $this->setup($eventName);

      $this->listeners[$eventName][] = array($listener,$method);
    }
    public function removeEventListener(Event $event,$listener){
      $eventName = get_class($event);
      $this->setup($eventName);

      for($i = 0; $i < count($this->listeners[$eventName]); $i++){
        $subject = $this->listeners[$eventName][$i];
        if($subject[0] !== $listener)
          continue;

        array_splice($this->listeners[$eventName],$i,1);
      }
    }
    protected function dispatchEvent(Event $event){
      $eventName = get_class($event);
      $this->setup($eventName);
      $event->setTargetOnce($this);

      foreach($this->listeners[$eventName] as $listener){
        $called = $listener[0];
        if($listener[1] != "")
          $called = $listener;

        call_user_func($called,$event);
      }
    }
    private function setup($eventName){
      if(!isset($this->listeners[$eventName]))
        $this->listeners[$eventName] = array();
    }
  }
