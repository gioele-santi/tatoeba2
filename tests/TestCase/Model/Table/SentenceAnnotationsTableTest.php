<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SentenceAnnotationsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\Core\Configure;
use Cake\Utility\Hash;

class SentenceAnnotationsTableTest extends TestCase {
    public $fixtures = array(
        'app.sentence_annotations',
        'app.users',
        'app.sentences',
    );

    function setUp() {
        parent::setUp();
        Configure::write('Acl.database', 'test');
        $this->SentenceAnnotation = TableRegistry::getTableLocator()->get('SentenceAnnotations');
    }

    function tearDown() {
        unset($this->SentenceAnnotation);
        parent::tearDown();
    }

    function testGetLatestAnnotations() {
        $result = $this->SentenceAnnotation->getLatestAnnotations(2);
        $this->assertEquals(2, count($result));
    }

    function testGetAnnotationsForSentenceId() {
        $result = $this->SentenceAnnotation->getAnnotationsForSentenceId(10);
        $this->assertEquals('ちょっと待って。', $result->text);
    }

    function testSaveAnnotation_addsAnnotation() {
        $userId = 1;
        $data = array(
            'sentence_id' => 6,
            'meaning_id' => 1,
            'text' => ' Trim me please '
        );
        $sentenceAnnotation = $this->SentenceAnnotation->saveAnnotation(
            $data, $userId
        );
        
        $expected = array(
            'sentence_id' => 6,
            'meaning_id' => 1,
            'text' => 'Trim me please',
            'user_id' => $userId
        );
        $result = array_intersect_key(
            $sentenceAnnotation->toArray(), $expected
        );

        $this->assertEquals($expected, $result);
    }

    function testSaveAnnotation_editsAnnotation() {
        $userId = 4;
        $data = array(
            'id' => 1,
            'sentence_id' => 6,
            'meaning_id' => 1,
            'text' => 'Some new text'
        );
        $sentenceAnnotation = $this->SentenceAnnotation->saveAnnotation(
            $data, $userId
        );
        
        $expected = array(
            'id' => 1,
            'sentence_id' => 6,
            'meaning_id' => 1,
            'text' => 'Some new text',
            'user_id' => $userId
        );
        $result = array_intersect_key(
            $sentenceAnnotation->toArray(), $expected
        );

        $this->assertEquals($expected, $result);
    }

    function testSeach() {
        $result = $this->SentenceAnnotation->search('問題');
        $resultIds = Hash::extract($result, '{n}.id');
        $this->assertEquals([1], $resultIds);
    }
}
