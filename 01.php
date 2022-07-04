<?php

 class Element{
     private string $name;
    private  array $weaknesses=[];
     public function __construct(string $name)
     {
         $this->name = $name;
     }

     public function getName(): string
     {
         return $this->name;
     }

     public function  addWeakness (Element $element):void{
        $this->weaknesses[] = $element;
     }

     public function  addWeaknesses(array $elements):void{
         foreach ($elements as $element){
             if(!$element instanceof  Element)continue;
        $this->addWeakness($element);
         }
     }

     public function isWeakAgainst(Element $element):bool{
         return in_array($element,$this->weaknesses);
     }
 }
 class player {
     private string $name;
    private ?Element $selection = null;
     public function __construct(string $name)
{
    $this->name = $name;
}
     public function getName(): string
     {
         return $this->name;
     }

     public function getSelection(): Element
     {
         return $this->selection;
     }

     public function setSelection(Element $selection): void
     {
         $this->selection = $selection;
     }


 }
class Game {

private array $elements = [];
    public function __construct()
     {
    $this->setup();

     }
     public function  getName (){
        return $this->getName();
     }
     public function  setup():void{
        $this->elements=[
            $rock = new Element('Rock'),
            $scissors = new Element('Scissors'),
            $paper = new Element('Paper'),
        ];
        $rock->addWeaknesses([$paper]);
         $paper->addWeaknesses([$scissors]);
         $scissors->addWeaknesses([$rock]);
     }
    public function  displayElements():void{
        foreach($this->elements as $key => $element){
            echo "{$key} -  {$element->getname()}".PHP_EOL;
        }
    }

     public function start(string $firstPlayer , string $secondPlayer):array{
        $winner = [];
        $player1 = new Player($firstPlayer);
         $player2 = new Player($secondPlayer);
         for($i = 0 ; count($winner)<3;$i++) {
             $this->displayElements();
             if($firstPlayer == 'Max'){
                 $player1Index = (int)readline("{$player1->getName()} choose element from list: ");
                 $player1->setSelection($this->elements[$player1Index]);
             }
             else{
                 $pcIndex = array_rand($this->elements);
                 $player1->setSelection($this->elements[$pcIndex]);
             }
             $pcIndex = array_rand($this->elements);
             $player2->setSelection($this->elements[$pcIndex]);
             if ($player1->getSelection() === $player2->getSelection()) {
                 echo "The game is Tie. Both got {$player1->getSelection()->getName()}" . PHP_EOL;
             } elseif ($player1->getSelection()->isWeakAgainst($player2->getSelection())) {
                 echo "{$player2->getName()} is the winner. He got {$player2->getSelection()->getName()}. And {$player1->getName()} got {$player1->getSelection()->getName()}" . PHP_EOL;
                   array_push($winner,'second player');
             } else {
                 echo "{$player1->getName()} is the winner. He got {$player1->getSelection()->getName()}. And {$player2->getName()} got {$player2->getSelection()->getName()}" . PHP_EOL;
                 array_push($winner,'first player');
             }
         }
         return $this->countWinningElements($winner);
     }
     public function countWinningElements(array $winner) : array{
         return array_count_values($winner);
     }
}

class Tournament {
     private Game $game;
     private array $playersFirstStage=[
         ['Max',"PC1"],
         ["PC2","PC3"],
         ["PC4","PC5"],
         ["PC6","PC7"]
     ];
     private array $playersSecondStage = [
    [],[]
     ];
     private array $final = [];
     public function __construct(Game $game)
     {
         $this->game = $game;
     }
    public function getGameStage1(): void
    {
        for($i=0;count($this->playersSecondStage)<4;$i++){
       if($this->game->start($this->playersFirstStage[$i][0],$this->playersFirstStage[$i][1] )['first player'] === 2){
       echo "first player won".PHP_EOL;
        $this->playersSecondStage[$i] = $this->playersFirstStage[$i][0];
    }
       else {
           echo "second player won".PHP_EOL;
           $this->playersSecondStage[$i] = $this->playersFirstStage[$i][1];
       }
    }
    var_dump($this->playersSecondStage);
    }
    public function getGameStage2(): void{
         $secondStage = [
                 [],[]
             ];
         $secondStage[0][0] = $this->playersSecondStage[0];
         $secondStage[0][1] = $this->playersSecondStage[1];
         $secondStage[1][0] = $this->playersSecondStage[2];
         $secondStage[1][1] = $this->playersSecondStage[3];
        for($i=0;count($this->final)<2;$i++){
            if($this->game->start($secondStage[$i][0],$secondStage[$i][1] )['first player'] === 2){
                echo "first player won".PHP_EOL;
                $this->final[$i] = $secondStage[$i][0];
            }
            else {
                echo "second player won".PHP_EOL;
                $this->final[$i] = $secondStage[$i][1];
            }
            }
        var_dump($this->final);
        }

    public function finalStage():void{
        if($this->game->start($this->final[0],$this->final[1] )['first player'] >= 2 ){
           echo  $this->final[0]. " has won the tournament" ;
        }
        else {
            echo $this->final[1] . " has won the tournament ";
        }
    }
 }
$new = new Tournament(new game);

$new->getGameStage1();
$new->getGameStage2();
$new->finalStage();
