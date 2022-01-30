# finitestatemachine
A Finite State Machine System based on Chapter 3.1 of Game Programming Gems 1 by Eric Dybsand,Written by Roberto Cezar Bianchini, July 2010 ported to php by MrFerrys.

## Description

A Finite State Machine System based on Chapter 3.1 of Game Programming Gems 1 by Eric Dybsand,Written by Roberto Cezar Bianchini, July 2010 ported to php by MrFerrys.

## Getting Started

### Dependencies

* PHP >= 5

### Installation
composer require mrferrys/finitestatemachine

### Usage

* How to use it.
```
	use mrferrys\finitestatemachine\finiteStateMachineClass as fsmClass;
	use mrferrys\finitestatemachine\stateClass as stateClass;
	
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
```
## Authors

MrFerrys  

## Version History

* 1.0.0
    * Initial Release (X.Y.Z MAJOR.MINOR.PATCH)

## License
 Finite State Machine
 A Finite State Machine System based on Chapter 3.1 of Game Programming Gems 1 by Eric Dybsand
 
   Written by Roberto Cezar Bianchini, July 2010
 
 
   How to use:
	1. Place the labels for the transitions and the states of the Finite State System
	    in the corresponding enums.
 
	2. Write new class(es) inheriting from FSMState and fill each one with pairs (transition-state).
	    These pairs represent the state S2 the FSMSystem should be if while being on state S1, a
	    transition T is fired and state S1 has a transition from it to S2. Remember this is a Deterministic FSM. 
	    You can't have one transition leading to two different states.

	   Method Reason is used to determine which transition should be fired.
	   You can write the code to fire transitions in another place, and leave this method empty if you
	   feel it's more appropriate to your project.

	   Method Act has the code to perform the actions the NPC is supposed do if it's on this state.
	   You can write the code for the actions in another place, and leave this method empty if you
	   feel it's more appropriate to your project.

	3. Create an instance of FSMSystem class and add the states to it.
 
	4. Call Reason and Act (or whichever methods you have for firing transitions and making the NPCs
	     behave in your game) from your Update or FixedUpdate methods.
 
	Asynchronous transitions from Unity Engine, like OnTriggerEnter, SendMessage, can also be used, 
	just call the Method PerformTransition from your FSMSystem instance with the correct Transition 
	when the event occurs.
 
 
 
   THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, 
   INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE 
   AND NON-INFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, 
   DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
   OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
   Ported to PHP by MrFerrys
   Author Roberto Cezar Bianchini
   
 See the LICENSE file for details

## Acknowledgments

Finite State Machine:
* [programmerclick.com](https://programmerclick.com/article/73042375109/)
* [wiki.unity3d.com](https://wiki.unity3d.com/index.php?title=Finite_State_Machine)
