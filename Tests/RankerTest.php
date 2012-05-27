<?php
class RankerTest extends PHPUnit_Framework_TestCase {

  private $rankables = array();

  public function setUp() {
    $this->rankables[] = $this->createRankable("bbb", 75);  
    $this->rankables[] = $this->createRankable("ddd", 50);  
    $this->rankables[] = $this->createRankable("ccc", 75);  
    $this->rankables[] = $this->createRankable("aaa", 100);  

    $this->rankables[] = $this->createRankable("eee", 5);  
    $this->rankables[] = $this->createRankable("fff", 5);  
    $this->rankables[] = $this->createRankable("ggg", 5);  
    
    $this->rankables[] = $this->createRankable("hhh", 1);  
    $this->rankables[] = $this->createRankable("iii", 0);  
  }

  private function createRankable($name, $score) {
    return (object) array(
      'name' => $name,
      'score' => $score,
      'ranking' => 0,
    );
  }

  private function assertRanking($expected, $rankables) {
    $actual = "";
    foreach ($rankables as $rankable) {
      $actual .= $rankable->ranking;
    }
    $this->assertEquals($expected, $actual);
  }

  public function testSettingRankingStrategy() {
    $ranker = new Ranker();
    $ranker->setRankingStrategy(RankingStrategy::COMPETITION);
    $this->assertEquals("competition", $ranker->getRankingStrategy());
  }

  public function testSort() {
    $ranker = new Ranker();
    $ranker->sortByOrderByParameter($this->rankables);

    $actual_first = $this->rankables[0]->name;
    $this->assertEquals("aaa", $actual_first);
    $actual_last = $this->rankables[3]->name;
    $this->assertEquals("ddd", $actual_last);
  }
 
  /**
   * Helper to test ranking strategies
   */
  private function applyRankingStrategy($strategy) {
    $ranker = new Ranker();
    $ranker->setRankingStrategy($strategy);
    $ranker->rank($this->rankables);
  }
  
  public function testCompetitionRanking() {
    $this->applyRankingStrategy(RankingStrategy::COMPETITION);
    $this->assertRanking("122455589", $this->rankables);
  }
  
  public function testModifiedCompetitionRanking() {
    $this->applyRankingStrategy(RankingStrategy::MODIFIED_COMPETITION);
    $this->assertRanking("133477789", $this->rankables);
  }

  public function testDenseRanking() {
    $this->applyRankingStrategy(RankingStrategy::DENSE);
    $this->assertRanking("122344456", $this->rankables);
  }
  
  public function testOrdinalRanking() {
    $this->applyRankingStrategy(RankingStrategy::ORDINAL);
    $this->assertRanking("123456789", $this->rankables);
  }
  
}