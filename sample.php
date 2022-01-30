<?php
/**
 * Command Pattern Concept
 */
//EXECUTION TIME
ini_set('max_execution_time', '0');

//ERROR REPORTING
//error_reporting(0);
error_reporting(E_ALL);

//LIBS
require_once(dirname(__FILE__).'/src/fsm.php');
use mrferrys\finitestatemachine\finiteStateMachineClass as fsmClass;
use mrferrys\finitestatemachine\stateClass as stateClass;


/**                                                                        
 *  MAIN
 */
try{
	echo "---------------- CASE OF USE \r\n";
	class State_1 extends stateClass
	{
		
		function __construct($id)
		{
		   $this->stateID=$id;
		}
		public function DoBeforeEntering(){
			echo "StateID: $this->stateID DoBeforeEntering <br>";
		}

		public function DoBeforeLeaving(){
			echo "StateID: $this->stateID DoBeforeLeaving <br>";
		}
		
		public function Reason(){
			echo "StateID: $this->stateID Reason <br>";
			if($this->fsm!=null)
			{
				$this->fsm->PerformTransition($this->stateID);
			}
		}
		
		public function Act(){
			echo "StateID: $this->stateID ACTION <br>";
		}
	}

	class stateMachine{
	public $fsm;
	public $states=array(
		"nullState"=>0,
		"state_1"=>1,
		"state_2"=>2,
		"finish"=>3
		);
	public $transitions=array(
		"nullTransition"=>0,
		 "state_1_done" => 1,
		 "state_2_done"=> 2,
		 "finishing"=>3,
		);
		function __construct()
		{
		   
		}

		public function buildFSM(){
			$this->fsm =   new fsmClass();
			$s1         =   new State_1($this->states["state_1"]);
			$s1->fsm    =   $this->fsm;
			$s1->AddTransition($this->transitions["state_1_done"],$this->states["state_2"]);

			$s2         =   new State_1($this->states["state_2"]);
			$s2->fsm    =   $this->fsm;
			$s2->AddTransition($this->transitions["state_2_done"],$this->states["finish"]);

			$s3  =   new State_1($this->states["finish"]);
			$s3->fsm    = $this->fsm;
			$this->fsm->AddState($s1);
			$this->fsm->AddState($s2);
			$this->fsm->AddState($s3);
		}

		public function startProcess(){

			while($this->fsm!=null && $this->fsm->getCurrentStateID() != $this->states["finish"] )
			{
				$this->fsm->getCurrentState()->Reason();
				$this->fsm->getCurrentState()->Act();
				$this->fsm->PerformTransition($this->fsm->getCurrentStateID());
			}
		}

	}
	//MAIN
	$machine= new stateMachine();
	$machine->buildFSM();
	$machine->startProcess();
	
}catch(\Exception $e){
    echo $e->getMessage();
}
?>

