<?php
namespace mrferrys\finitestatemachine;
/**
* Finite State Machine
* A Finite State Machine System based on Chapter 3.1 of Game Programming Gems 1 by Eric Dybsand
* 
*   Written by Roberto Cezar Bianchini, July 2010
* 
* 
*   How to use:
*	1. Place the labels for the transitions and the states of the Finite State System
*	    in the corresponding enums.
* 
*	2. Write new class(es) inheriting from FSMState and fill each one with pairs (transition-state).
*	    These pairs represent the state S2 the FSMSystem should be if while being on state S1, a
*	    transition T is fired and state S1 has a transition from it to S2. Remember this is a Deterministic FSM. 
*	    You can't have one transition leading to two different states.
*
*	   Method Reason is used to determine which transition should be fired.
*	   You can write the code to fire transitions in another place, and leave this method empty if you
*	   feel it's more appropriate to your project.
*
*	   Method Act has the code to perform the actions the NPC is supposed do if it's on this state.
*	   You can write the code for the actions in another place, and leave this method empty if you
*	   feel it's more appropriate to your project.
*
*	3. Create an instance of FSMSystem class and add the states to it.
* 
*	4. Call Reason and Act (or whichever methods you have for firing transitions and making the NPCs
*	     behave in your game) from your Update or FixedUpdate methods.
* 
*	Asynchronous transitions from Unity Engine, like OnTriggerEnter, SendMessage, can also be used, 
*	just call the Method PerformTransition from your FSMSystem instance with the correct Transition 
*	when the event occurs.
* 
* 
* 
*   THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, 
*   INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE 
*   AND NON-INFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, 
*   DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
*   OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*   Ported to php by MrFerrys
*   @author Roberto Cezar Bianchini
*  
*/
define('NULL_STATE'         ,0);
define('NULL_TRANSITION'    ,0);

/**
 *   finiteStateMachineClass represents the Finite State Machine class.
 *   It has a List with the States the NPC has and methods to add,
 *   delete a state, and to change the current state the Machine is on.
 */
class finiteStateMachineClass
{
    private $states=array();
    private $currentStateID=NULL_STATE;
    private $currentState;

    function __construct()
    {
        $this->states         =   array();
        $this->currentStateID =   NULL_STATE;
        $this->currentState   =   null;
    }

    public function getCurrentStateID(){
        return $this->currentStateID;
    }

    public function getCurrentState(){
        return $this->currentState;
    }

    public function AddState($state=null)
    {
        if($state==null){return;}//THROW STATE IS NULL
        if(count($this->states)==0)
        {
            $this->states[$state->getID()]=$state;
            $this->currentState   = $state;
            $this->currentStateID = $state->getID();
            return;
        }
        foreach( $this->states as $key=>$kValue )
        {
            if($kValue->getID() == $state->getID()){return;}//THROW ITEM EXISTS
        }
        $this->states[$state->getID()]=$state;
    }

    public function DeleteState($sID=NULL_STATE)
    {
        $keyToRemove = NULL_STATE;
        if($sID==NULL_STATE){return;} //THROW NULL NOT ALLOWED
        foreach( $this->states as $key=>$kValue )
        {
            if($kValue->getID() == $sID){ $keyToRemove=$key;}//THROW ITEM EXISTS
        }

        if($keyToRemove!=NULL_STATE){unset($this->states[$keyToRemove]);}
    }
    
    public function PerformTransition($transition=NULL_TRANSITION)
    {
        if($transition== NULL_TRANSITION){return;}//THROW EXCEPTION
        $id= $this->currentState->GetOutPutState($transition);
        if($id==NULL_STATE){return;}
        $this->currentStateID =   $id;
        foreach($this->states as $key=>$kValue)
        {
            if($kValue->getID()==$this->currentStateID)
            {
                $this->currentState->DoBeforeLeaving();
                $this->currentState=$kValue;
                $this->currentState->DoBeforeEntering();
                return;
            }
        }
    }
   
}
/**
* This class represents the States in the Finite State System.
* Each state has a Dictionary with pairs (transition-state) showing
* which state the FSM should be if a transition is fired while this state
* is the current state.
* Method Reason is used to determine which transition should be fired .
* Method Act has the code to perform the actions the NPC is supposed do if it's on this state.
 */
abstract class stateClass 
{
    public    $fsm;
    protected $map      = array();
    protected $stateID;

    public function getID()
    {
        return $this->stateID;
    }

    public function AddTransition($transition=NULL_TRANSITION,$sID=NULL_STATE)
    {
        //empty state id
            if( $sID==NULL_STATE || !is_numeric($sID) ){return;}//TRHOW NULL NOT ALLOWED
            if( $transition==NULL_TRANSITION ){ return;}//THROW NULL NOT ALLOWED 
            if( array_key_exists($transition,$this->map) ){return;}//THROW EXISTS REPEATED
            $this->map[$transition]=$sID;
    }

    public function DeleteTransition($transition=NULL_TRANSITION)
    {
        if($transition == NULL_TRANSITION ){return;} //TRHOW NULL NOT ALLOWED
        if(array_key_exists($transition,$this->map))
        {
            unset($this->map[$transition]);
        }
        //THROW TRANSITION NOT FOUND
    }

    public function GetOutPutState($transition=NULL_TRANSITION)
    {
            if(array_key_exists($transition,$this->map))
            {
                return $this->map[$transition];
            }
            return NULL_STATE;
    }

    abstract protected function DoBeforeEntering();
    abstract protected function DoBeforeLeaving();
    abstract protected function Reason();
    abstract protected function Act();
}
?>
