<?php

class Element
{
    private string $name;
    private array $weaknesses = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function addWeakness(Element $element): void
    {
        $this->weaknesses[] = $element;
    }

    public function addWeaknesses(array $elements): void
    {
        foreach ($elements as $element) {
            if (!$element instanceof Element) continue;
            $this->addWeakness($element);
        }
    }

    public function isWeakAgainst(Element $element): bool
    {
        return in_array($element, $this->weaknesses);
    }
}

class player
{
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

class Game
{
    private int $firstPlayerPoints = 0;
    private int $secondPlayerPoints = 0;
    private array $elements = [];
    private const MAX_WINS = 2;
    public function __construct()
    {
        $this->setup();

    }



    public function setup(): void
    {
        $this->elements = [
            $rock = new Element('Rock'),
            $scissors = new Element('Scissors'),
            $paper = new Element('Paper'),
        ];
        $rock->addWeaknesses([$paper]);
        $paper->addWeaknesses([$scissors]);
        $scissors->addWeaknesses([$rock]);
    }

    public function displayElements(): void
    {
        foreach ($this->elements as $key => $element) {
            echo "{$key} -  {$element->getname()}" . PHP_EOL;
        }
    }

    public function start(string $firstPlayer, string $secondPlayer): void
    {
        $player1 = new Player($firstPlayer);
        $player2 = new Player($secondPlayer);
        while ($this->firstPlayerPoints < self::MAX_WINS && $this->secondPlayerPoints<self::MAX_WINS) {
            if ($firstPlayer == 'Max') {
                $this->displayElements();
                $player1Index = (int)readline("{$player1->getName()} choose element from list: ");
                $player1->setSelection($this->elements[$player1Index]);
            } else {
                $pcIndex = array_rand($this->elements);
                $player1->setSelection($this->elements[$pcIndex]);
            }
            $pcIndex = array_rand($this->elements);
            $player2->setSelection($this->elements[$pcIndex]);
            if ($player1->getSelection() === $player2->getSelection()) {
                continue;
            } elseif ($player1->getSelection()->isWeakAgainst($player2->getSelection())) {
                $this->secondPlayerPoints+=1;
            } else {
                $this->firstPlayerPoints+=1;
            }
        }

    }
    /**
     * @return int
     */
    public function getFirstPlayerPoints(): int
    {
        return $this->firstPlayerPoints;
    }

    /**
     * @return int
     */
    public function getSecondPlayerPoints(): int
    {
        return $this->secondPlayerPoints;
    }

    /**
     * @param int $firstPlayerPoints
     */
    public function setFirstPlayerPoints(int $firstPlayerPoints): void
    {
        $this->firstPlayerPoints = $firstPlayerPoints;
    }

    /**
     * @param int $secondPlayerPoints
     */
    public function setSecondPlayerPoints(int $secondPlayerPoints): void
    {
        $this->secondPlayerPoints = $secondPlayerPoints;
    }
}

class Tournament
{
    private const GAMES_PLAYED_FIRST_STAGE = 4;
    private const GAMES_PLAYED_SECOND_STAGE = 2;
    private Game $game;
    private array $playersFirstStage = [
        ['Max', "PC1"],
        ["PC2", "PC3"],
        ["PC4", "PC5"],
        ["PC6", "PC7"]
    ];
    private array $playersSecondStage = [];
    private array $final = [];

    public function __construct(Game $game)
    {
        $this->game = $game;
    }
// alt j to change similar elements in code at once
    public function getGameStage1(): void
    {

        for ($i = 0; count($this->playersSecondStage) < self::GAMES_PLAYED_FIRST_STAGE ; $i++) { // make 4 as const and all numbers +

            $this->game->start($this->playersFirstStage[$i][0],$this->playersFirstStage[$i][1]);
            $countGames = $i+1;
            if ($this->game->getFirstPlayerPoints() === 2) {
                $this->playersSecondStage[$i] = $this->playersFirstStage[$i][0];
                echo "The winner of Game - $countGames is {$this->playersFirstStage[$i][0]}. He played vs {$this->playersFirstStage[$i][1]}. Score ({$this->game->getFirstPlayerPoints()}:{$this->game->getSecondPlayerPoints()})" . PHP_EOL;
            } elseif($this->game->getSecondPlayerPoints() === 2) {
                $this->playersSecondStage[$i] = $this->playersFirstStage[$i][1];
                echo "The winner of Game - $countGames is {$this->playersFirstStage[$i][1]}. He played vs {$this->playersFirstStage[$i][0]}. Score ({$this->game->getSecondPlayerPoints()}:{$this->game->getFirstPlayerPoints()})". PHP_EOL;
            }
            $this->game->setFirstPlayerPoints(0);
            $this->game->setSecondPlayerPoints(0);
        }
    }

    public function getGameStage2(): void
    {
        $secondStage = [
            [], []
        ];
        $secondStage[0][0] = $this->playersSecondStage[0];
        $secondStage[0][1] = $this->playersSecondStage[1];
        $secondStage[1][0] = $this->playersSecondStage[2];
        $secondStage[1][1] = $this->playersSecondStage[3];
        for ($i = 0; count($this->final) < self::GAMES_PLAYED_SECOND_STAGE; $i++) {
            $this->game->start($secondStage[$i][0],$secondStage[$i][1]);
            $countGames = $i+1;
            if ($this->game->getFirstPlayerPoints() === 2) {
                $this->final[$i] = $secondStage[$i][0];
                echo "The winner of Game - $countGames is {$secondStage[$i][0]}. He played vs {$secondStage[$i][1]}. Score ({$this->game->getFirstPlayerPoints()}:{$this->game->getSecondPlayerPoints()})" . PHP_EOL;

            } else {
                $this->final[$i] = $secondStage[$i][1];
                echo "The winner of Game - $countGames is {$secondStage[$i][1]}. He played vs {$secondStage[$i][0]}. Score ({$this->game->getSecondPlayerPoints()}:{$this->game->getFirstPlayerPoints()})". PHP_EOL;

            }
            $this->game->setFirstPlayerPoints(0);
            $this->game->setSecondPlayerPoints(0);
        }
    }

    public function finalStage(): void
    {   $this->game->start($this->final[0],$this->final[1]);
        if ($this->game->getFirstPlayerPoints() === 2) {
            echo $this->final[0] . " has won the tournament. He played vs {$this->final[1]}. The score is ({$this->game->getFirstPlayerPoints()}:{$this->game->getSecondPlayerPoints()}) ";
        } else {
            echo $this->final[1] . " has won the tournament. He played vs {$this->final[0]}. The score is ({$this->game->getSecondPlayerPoints()}:{$this->game->getFirstPlayerPoints()})";
        }
        $this->game->setFirstPlayerPoints(0);
        $this->game->setSecondPlayerPoints(0);
    }
}

$new = new Tournament(new game);
//$newGame = new Game();
//$newGame->start("Max","PC1");
echo "___________________FIRSTSTAGE___________________".PHP_EOL;
$new->getGameStage1().PHP_EOL;
echo "___________________SECONDSTAGE__________________".PHP_EOL;
$new->getGameStage2();
echo "______________________FINAL_____________________".PHP_EOL;
$new->finalStage();
